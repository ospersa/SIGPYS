<?php

class  PlaneacionAse{

    public static function onPeriodoActual() {
        require('../Core/connection.php');
        $fechaAct = $newFecha = date("Y/m/d");
        $consulta ="SELECT idPeriodo FROM pys_periodos WHERE inicioPeriodo <= '$fechaAct' AND '$fechaAct'<= finPeriodo;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos['idPeriodo'];
        mysqli_close($connection);
    }

    public static function onPeriodo($idPer,$usuario) {
        require('../Core/connection.php');
        $consulta ="SELECT * FROM pys_periodos WHERE idPeriodo = $idPer;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $fechaini = strtotime($datos['inicioPeriodo']);
        $fechafin = strtotime($datos['finPeriodo']);
        $diaIni = (date('w',$fechaini)==0)? 7: date('w',$fechaini);
        $diaFin = date('w',$fechafin);
        $diff = $fechafin - $fechaini;
        $diasFalt = (( ( $diff / 60 ) / 60 ) / 24);
        $string = "";
        for($i=1;$i<$diaIni;$i++){
            $string .= '<div>
            <div class="card">
                <div class="card-content grey" type="button">
                    <h6 class="card-stats-number transparent-text">00-00-0000</h6>
                </div>
            </div>
        </div>';
        }
        
        for($i=0;$i<=$diasFalt;$i++){
            $fechaDia = date("d-m-Y", strtotime( '+'.$i.' day', $fechaini ));
            $diafech = date('w', strtotime($fechaDia));
            $conteo = self::ValidacionPlaneacionDia($fechaDia, $usuario);
            $fechaCons = date("Y-m-d", strtotime($fechaDia));
            $consultaTiempo ="SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos  
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                INNER JOIN pys_login  ON pys_login.idPersona = pys_asignados.idPersona
                WHERE usrLogin = '$usuario' AND estTiempo = 1 AND fechTiempo ='$fechaCons'";
            $resultadoTiempo = mysqli_query($connection, $consultaTiempo);
            $datosTiempo = mysqli_fetch_array($resultadoTiempo);
            $horaTiempo =$datosTiempo['SUM(horaTiempo)'];
            $minTiempo = $datosTiempo['SUM(minTiempo)'];
            $tiempoTotal = ($horaTiempo*60)+ $minTiempo;
            if($conteo > 0 && $tiempoTotal < 480){
                $color = 'teal accent-4';
                $letra = "black";
            } else if ($tiempoTotal >= 480) {
                $color = 'teal  darken-3 v';
                $letra = "white";
            } else{ 
                $color = 'teal lighten-5 w';
                $letra = "black";
            }
                $string .= '
                <div>
                    <div class="card">
                        <div class="fechPer card-content '.$color.' '.$letra.'-text" type="button" onclick ="cargarResAgenda(\''.$fechaDia.'\',$(this))">
                        <h6 class="card-stats-number '.$letra.'-text">'.$fechaDia.'</h6>
                        </div>
                    </div>
                </div>';
            
        }
        if($diaFin !=0){
            for($j=$diaFin+1;$j<=7;$j++){
                $string .= '<div>
                <div class="card">
                    <div class="card-content grey " type="button">
                        <h6 class="card-stats-number transparent-text ">00-00-0000</h6>
                    </div>
                </div>
            </div>';
            }
        }
        return $string;
        mysqli_close($connection);
    }
  
    
    public static function crearDivP($long, $usuario){
        echo '
        <div class="row" id="cardPro'.$long.'">
            <div class="col s12 m12 l12">
                <div class="card" >
                <div class="card-content ">
                <div class="row ">
            <a class=" btn btn-floating right waves-effect waves-light red" onclick="eliminarDiv('.$long.')"><i class="material-icons" >delete</i></a>
                    </div>
                    <div class ="conteo row" id=" proy'.$long.'">       
                        <div class="input-field">'.PlaneacionAse::selectProyectoUsuario($usuario,$long).'</div>
                        <div id="div_produc'.$long.'" class="col l12 m12 s12 "></div>
                    </div> 
                </div>
                </div>
            </div>
        </div>';
    } 

    public static function selectSolUsuario ($user, $proyecto,$periodo, $long){
        require('../Core/connection.php');
        $string = "";
        $numero = 1;
        $consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_solicitudes.descripcionSol FROM pys_solicitudes 
        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
        INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
        INNER JOIN pys_asignados ON pys_asignados.idSol = pys_actsolicitudes.idSol
        INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
        INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona
        WHERE pys_solicitudes.idTSol = 'TSOL02' AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005') AND pys_solicitudes.est = 1 AND ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_actsolicitudes.est =1 AND pys_actualizacionproy.est = 1 AND pys_personas.est = 1 AND pys_login.usrLogin = '$user' AND pys_actualizacionproy.idProy ='$proyecto' ";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 ) {
            while ($datos = mysqli_fetch_array($resultado)) {
                $idSol = $datos['idSol'];
                $descripcionSol = $datos['ObservacionAct'];
                $hDispo =PlaneacionAse::horasDisponibles($idSol, $user);
                if ($hDispo ==0){
                    $hDispo ='0';
                }
            $string .= '
            <div class ="row">
                <div class ="row">
                    <div class="input-field col l3 m3 s12 ">
                        <p>
                        <label>
                            <input type="checkbox" id="checkidSol'.$long.'--'.$numero.'" class="filled-in" name ="idSol[]" value ='.$idSol.' data-checked="false" onclick ="checkProd(\'#checkidSol'.$long.'--'.$numero.'\','.$numero.','.$long.')">
                            <span>P'.$idSol.'</span>
                        </label>
                        <p>
                    </div>
                    <div class="input-field col l4 m4 s12">
                        <label class="active">'.$descripcionSol.'</label>
                    </div>
                    <div class="input-field col l2 m2 s12">
                        <label class="active">Horas disponibles: '.$hDispo.' h</label>
                    </div>
                    <div class="input-field col l1 m1 s12">
                        <input type="number" class="validate" name ="horas[]" id="horas'.$long.'--'.$numero.'" value="0" min="0" max="12" disabled>
                        <label for="horas" class="active">Horas</label>
                    </div>
                    <div class="input-field col l1 m1 s12">
                        <input type="number" class="validate" name ="min[]" id="min'.$long.'--'.$numero.'" value="0" min="0" max="59" disabled>
                        <label for="min" class="active">Minutos</label>
                    </div>
                    <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                        <textarea name="obser[]" id="obser'.$long.'--'.$numero.'" class="materialize-textarea" disabled></textarea>
                        <label for="obser" class="active">Actividad</label>
                    </div>
                     
                </div>
                <div class="divider"> </div>
            </div>';
            $numero += 1;
            }
        }
        echo $string;
        mysqli_close($connection);
    }
    
    public static function guardarPlaneacion($productos, $horas, $min, $obs, $usuario, $fecha,$periodo){
        require('../Core/connection.php');
        if($productos != [] && $horas != [] && $min != [] && $obs != [] ){
            $newFecha = date("Y/m/d", strtotime($fecha));
            $count = 0;
            $countSi = 0;
            $horasSum= array_sum($horas);
            $minutosSum = array_sum($min);
            $tiempoReg = PlaneacionAse::validarHorasDia($fecha, $usuario, $horasSum, $minutosSum,"''");
            $tiempoReg[0]."horas".$tiempoReg[1];
            $totalTiempo = ($tiempoReg[0]*60) +$tiempoReg[1];
            if ($totalTiempo > 720){
                echo "<script> alert ('Hay mas de 12 horas Planeadas o Registradas para esta fecha');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.$fecha.'">';
            }else{ 
                $cant = count($productos);
                for($i=0;$i<$cant ;$i++){
                    if($productos[$i] != null && $horas[$i] != null  && $min[$i] != null  && $obs[$i] != ''){
                        $consulta = PlaneacionAse::consultaAsig($productos[$i], $usuario);
                        $resultado = mysqli_query($connection, $consulta);
                        if (mysqli_num_rows($resultado) > 0 ) {
                            $count += 1;
                            while ($datos = mysqli_fetch_array($resultado)){
                                $idAsig = $datos['idAsig'];
                                $obser = mysqli_real_escape_string($connection, $obs[$i]);
                                $consultaInsert = 'INSERT INTO pys_agenda  VALUES (null, '.$idAsig.', "'.$newFecha.'", "'.$obser.'", '.$horas[$i].', '.$min[$i].', now(), 1);';
                                $resultadoInsert = mysqli_query($connection, $consultaInsert);
                                $consultaplan = PlaneacionAse::guardarEnPlaneacion($idAsig, $horas[$i], $min[$i], $obs[$i], $usuario, $newFecha, $periodo);
                                if($resultadoInsert && $consultaplan){
                                    $countSi += 1;
                                }
                            }
                        }
                    }
                }
                if ($count == $countSi && $count >0){
                    echo "<script> alert ('Se guardó correctamente la información');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.$fecha.'">';
                } else if($count==0){
                    echo "<script> alert ('Existe algun campo vacio');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.$fecha.'">';
                } else {
                    echo "<script> alert ('No se guardó correctamente la información');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.$fecha.'">';
                }
            } 
        }else {
            echo "<script> alert ('No se ha seleccionado ninguna solicitud');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.$fecha.'">';
        }
        mysqli_close($connection);
    }

    public static function guardarEnPlaneacion($idAsig, $hora, $min, $obser, $usuario, $fecha, $periodo){
        require('../Core/connection.php');
        $horaTotal = 0;
        $minTotal = 0;
        $obs = "";
        $idAsignacionNew = 0;
        $consulta = "SELECT idDedicacion FROM pys_dedicaciones
        INNER JOIN pys_login ON pys_login.idPersona = pys_dedicaciones.persona_IdPersona
        WHERE pys_dedicaciones.estadoDedicacion=1 AND pys_login.est=1 AND periodo_IdPeriodo = ".$periodo." AND pys_login.usrLogin = '".$usuario."'";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $idDedicacion = $datos['idDedicacion'];
        $consultaAsig = "SELECT idAsignacion, horasInvertir, minutosInvertir, observacion FROM pys_asignaciones WHERE idDedicacion = $idDedicacion AND idAsignado = $idAsig AND estadoAsignacion = 1";
        $resultadoAsig = mysqli_query($connection, $consultaAsig);
        $con1 = "SELECT * FROM pys_asignaciones;";
        $registros = mysqli_query($connection, $con1);
        $count = mysqli_num_rows($registros);
        if ($count == 0) {
            $idAsignacionNew = 1;
        } else if ($count > 0) {
            $idAsignacionNew = $count + 1;
        }
        if($resultadoAsig && mysqli_num_rows($resultadoAsig) == 1){
            $datosAsig = mysqli_fetch_array($resultadoAsig);
            $idAsignacion = $datosAsig['idAsignacion'];
            $horasInvertir = $datosAsig['horasInvertir'];
            $minutosInvertir = $datosAsig['minutosInvertir'];
            $observacion = $datosAsig['observacion'];
            $horaTotal = $horasInvertir + $hora;
            $minTotal = $minutosInvertir + $min;
            if ($minTotal >= 60){
                $horaTotal = intval(( $minTotal/60 )+$horaTotal);
                $minTotal = intval( $minTotal%60);
            } 
            $observ = $observacion.'; '.$obser. ' -fecha: '.$fecha;
            $obs = mysqli_real_escape_string($connection, $observ);
            $consultaUpdate = "UPDATE pys_asignaciones SET horasInvertir=".$horaTotal.", minutosInvertir =".$minTotal.",observacion ='".$obs."' WHERE idAsignacion=".$idAsignacion;
            return mysqli_query($connection, $consultaUpdate);
        } else {
            $obse = mysqli_real_escape_string($connection, $obser);
            $consultaInsert = "INSERT INTO pys_asignaciones VALUES($idAsignacionNew, $idDedicacion, $idAsig, $hora, $min, '$obse -fecha: $fecha', 1) ";
            return mysqli_query($connection, $consultaInsert);
        }
        mysqli_close($connection);
        
    }

    public static function consultaAsig($idSol, $usuario){
        return "SELECT pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idAsig
        FROM pys_asignados
        INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona
        WHERE ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_asignados.idSol ='$idSol' AND pys_login.usrLogin='$usuario' ";
         
    }

    public static function horasDisponibles($idSol, $usuario){
        require('../Core/connection.php');
        $horaAge = 0;
        $minutosAge = 0;
        $horaReg = 0;
        $minutosReg = 0;
        $horaTotal = 0;
        $minTotal = 0;
        $consulta = PlaneacionAse::consultaAsig($idSol, $usuario);
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 ) {
            $datos = mysqli_fetch_array($resultado);
            $maxhora = $datos['maxhora'];
            $maxmin = $datos['maxmin'];
            $maxmin = ($maxhora*60)+$maxmin;
            $idAsig = $datos['idAsig'];
            $consultaReg = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos  WHERE idAsig =$idAsig AND estTiempo = 1";
            $resultadoReg = mysqli_query($connection, $consultaReg);
            $datosReg = mysqli_fetch_array($resultadoReg);
            $horaReg = $datosReg['SUM(horaTiempo)'];
            $minutosReg = $datosReg['SUM(minTiempo)'];
            $minutosReg = ($horaReg*60)+$minutosReg;
            $consultaAge = "SELECT SUM(horaAgenda), SUM(minAgenda) FROM pys_agenda  WHERE idAsig =$idAsig AND est = 1";
            $resultadoAge = mysqli_query($connection, $consultaAge);
            if ($resultadoAge) {
                $datosAge = mysqli_fetch_array($resultadoAge);
                $horaAge = $datosAge['SUM(horaAgenda)'];
                $minutosAge = $datosAge['SUM(minAgenda)'];
                $minutosAge = ($horaAge*60)+$minutosAge;
            }
            $minTotal =$maxmin - $minutosReg -$minutosAge;
            $horaTotal = round($minTotal/60, 2);
            return $horaTotal;
        }
        mysqli_close($connection);
    }

    public static function mostrarAgenda ($fecha, $user){
        require('../Core/connection.php');
        include_once('mdl_tiempos.php');
        $fecha = mysqli_real_escape_string($connection, $fecha);
        $json = array();
        $newFecha = date("Y-m-d", strtotime($fecha));
        $cont = 1;
        $agenda = "";
        $consulta ="SELECT pys_agenda.idAsig , pys_agenda.idAgenda, pys_agenda.horaAgenda, pys_agenda.minAgenda, pys_agenda.notaAgenda, pys_agenda.estAgenda, estAgenda 
        FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig =pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE pys_agenda.estAgenda <> 0 AND( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est = 1 AND pys_login.usrLogin = '$user' AND pys_agenda.fechAgenda ='$newFecha'";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos = mysqli_fetch_array($resultado)){    
            $idAgenda = $datos['idAgenda'];
            $idAsig = $datos['idAsig'];
            $notaAgenda = $datos['notaAgenda'];
            $horaAgenda = $datos['horaAgenda'];
            $minAgenda = $datos['minAgenda'];
            $estAgenda = $datos['estAgenda'];
            $consulta2 = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_actualizacionproy.nombreProy, pys_actualizacionproy.codProy FROM pys_asignados
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_asignados.idSol
            WHERE ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_actualizacionproy.est = 1 AND pys_solicitudes.est = 1 AND pys_asignados.idAsig = $idAsig";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $idSol = $datos2['idSol'];
            $descripcionSol = $datos2['descripcionSol'];
            $nombreProy = $datos2['nombreProy'];
            $codProy = $datos2['codProy'];
            if ($estAgenda == 1) {
                $type = "";
                $text = "";
            } else if ($estAgenda == 2) {
                $type = 'disabled';
                $text = '<div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                <p class="left-align teal-text text-darken-1">*Esta actividad ya ha sido registrada en Tiempo*</p>
                </div>';
            } else if ($estAgenda == 3) {
                $type = 'disabled';
                $text = '<div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                <p class="left-align teal-text text-darken-1">*Esta actividad ha sido cancelada*</p>
                </div>';
            }
            if ($estAgenda == 1) {
                $agenda = '
                
                        <div class="row">
                    <div class="input-field col l4 m4 s12  offset-l1 offset-m1">
                        <button type="button" class="btn btn-floating  waves-effect white teal-text tooltipped"
                            name="btnCancelarAgen" data-position="right" onclick="cancelarAgenda('.$cont.')"
                            data-tooltip="Cancelar de Agenda"><i class="material-icons red-text">clear</i></button>
                    </div>
        
                    <div class="input-field col l2 m2 s12  ">
                        <button class="btn btn-floating  waves-effect transparent  tooltipped" type="submit"
                            name="btnActAgenda" data-position="right" data-tooltip="Actualizar en Agenda"><i
                                class="material-icons teal-text">done</i></button>
                    </div>
                    </div>';
            } 
            $fase =Tiempos::selectFase("Sin Label");
                $json[]     = array(
                    'type' => $type,
                    'text'     => $text,
                    'cont'     => $cont,
                    'idAgenda'     => $idAgenda,
                    'idSol'     => $idSol,
                    'fecha'     => $fecha,
                    'codProy'     => $codProy,
                    'nombreProy'     => $nombreProy,
                    'descripcionSol'     => $descripcionSol,
                    'horaAgenda'     => $horaAgenda,
                    'minAgenda'     => $minAgenda,
                    'notaAgenda'     => $notaAgenda,
                    'agenda'     => $agenda,
                    'fase'     => $fase,
                    'estAgenda'     => $estAgenda,
                );
                $cont += 1;
        }
        $jsonString = json_encode($json);
        echo $jsonString;
        mysqli_close($connection);
    }
	
     public static function mostrarAgendaAdmin ($fecha, $user){
        require('../Core/connection.php');
        require('mdl_tiempos.php');
        $json = array();
        $newFecha = date("Y-m-d", strtotime($fecha));
        $cont = 1;
        $consulta ="SELECT pys_agenda.idAsig , pys_agenda.idAgenda, pys_agenda.horaAgenda, pys_agenda.minAgenda, pys_agenda.notaAgenda, pys_agenda.estAgenda 
        FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig =pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE pys_agenda.estAgenda <> 0 AND ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est = 1 AND pys_login.usrLogin = '$user' AND pys_agenda.fechAgenda ='$newFecha'";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos = mysqli_fetch_array($resultado)){
            $idAgenda = $datos['idAgenda'];
            $idAsig = $datos['idAsig'];
            $notaAgenda = $datos['notaAgenda'];
            $horaAgenda = $datos['horaAgenda'];
            $minAgenda = $datos['minAgenda'];
            $estAgenda = $datos['estAgenda'];
            $consulta2 = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_actualizacionproy.nombreProy, pys_actualizacionproy.codProy FROM pys_asignados
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_asignados.idSol
            WHERE ( pys_asignados.est = 1 OR pys_asignados.est = 2)AND pys_actualizacionproy.est = 1 AND pys_solicitudes.est = 1 AND pys_asignados.idAsig = $idAsig";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $idSol = $datos2['idSol'];
            $descripcionSol = $datos2['descripcionSol'];
            $nombreProy = $datos2['nombreProy'];
            $codProy = $datos2['codProy'];
                $type = 'disabled';
            if ($estAgenda == 1) {
                $text = "";
            } else if ($estAgenda == 2) {
                $text = '<div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                <p class="left-align teal-text text-darken-1">*Esta actividad ya ha sido registrada en Tiempo*</p>
                </div>';
            } else if ($estAgenda == 3) {
                $text = '<div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                <p class="left-align teal-text text-darken-1">*Esta actividad ha sido cancelada*</p>
                </div>';
            }
            $json[]     = array(
                'type' => $type,
                'text'     => $text,
                'cont'     => $cont,
                'idAgenda'     => $idAgenda,
                'idSol'     => $idSol,
                'fecha'     => $fecha,
                'codProy'     => $codProy,
                'nombreProy'     => $nombreProy,
                'descripcionSol'     => $descripcionSol,
                'horaAgenda'     => $horaAgenda,
                'minAgenda'     => $minAgenda,
                'notaAgenda'     => $notaAgenda,
                'estAgenda'     => $estAgenda,
                'agenda'     => "",
            );
            $cont += 1;
            
        }
        $jsonString = json_encode($json);
        echo $jsonString;
        mysqli_close($connection);
    }
	
    /* Estados de la Agenda 
    Estado 0 son las eliminadas.
    Estado 1 son las actividades unicamente registradas en la agenda
    Estado 2 son las actividades ya registradas en tiempos
    Estado 3 son las actividades Canceladas y no registradas en tiempos
    */
                    
    public static function cambiarEstadoAgenda($fecha, $usuario, $idSol, $idAgenda, $hora, $min, $obs, $estado, $sltFase, $fechaCambio){
        require('../Core/connection.php');
        $resultadoUpdate = false;
        $regTiempo = [];
        $consulta ="SELECT idAgenda, pys_agenda.idAsig FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE pys_agenda.estAgenda <> 0 AND pys_asignados.est = 1 AND pys_login.est=1 AND pys_agenda.fechAgenda ='$fecha' AND pys_login.usrLogin='$usuario'  AND pys_asignados.idSol ='$idSol' AND idAgenda = $idAgenda";
        $resultado = mysqli_query($connection, $consulta);
        $tiempoReg = PlaneacionAse::validarHorasDia($fecha, $usuario, $hora, $min, $idAgenda);
        $totalTiempo = ($tiempoReg[0]*60) +$tiempoReg[1];
        $obs = mysqli_real_escape_string($connection, $obs);
        if ($totalTiempo > 720 && $estado == 1 ){
            echo "<script> alert ('Hay mas de 12 horas Planeadas o Registradas para esta fecha');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='. date("d-m-Y", strtotime($fecha)).'">';
        }else {
            if($resultado){
                $datos = mysqli_fetch_array($resultado);
                $idAgenda = $datos['idAgenda'];
                if($estado == 2){
                    $regTiempo = Tiempos::registrarTiempos($idSol, $usuario,  date("Y-m-d", strtotime($fecha)), $obs, $hora, $min, $sltFase,1);
                    if ($regTiempo[0] == true){
                        
                        $consultaUpdate = "UPDATE pys_agenda SET estAgenda =$estado, notaAgenda='$obs', horaAgenda = $hora, minAgenda = $min  where idAgenda =$idAgenda";
                        $resultadoUpdate = mysqli_query($connection, $consultaUpdate);  
                    }
                } else if ($estado == 1){
                    if ($fechaCambio != null){
                        $fechCambio = date("Y-m-d", strtotime($fechaCambio));
                        $tiempoCambio = PlaneacionAse::validarHorasDia($fechCambio, $usuario, $hora, $min, $idAgenda);
                        $totalTiempoC = ($tiempoCambio[0]*60) +$tiempoCambio[1];
                        if ($totalTiempoC > 720){
                            echo "<script> alert ('No es posible realizar el cambio de fecha');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='. date("d-m-Y", strtotime($fecha)).'">';
                        } else{
                            $consultaUpdate = "UPDATE pys_agenda SET fechAgenda='$fechCambio', notaAgenda='$obs', horaAgenda = $hora, minAgenda = $min  where idAgenda =$idAgenda";
                            $resultadoUpdate = mysqli_query($connection, $consultaUpdate);
                            $fecha = $fechCambio;
                        }
                    }else{
                        $consultaUpdate = "UPDATE pys_agenda SET notaAgenda='$obs', horaAgenda = $hora, minAgenda = $min  where idAgenda =$idAgenda";
                        $resultadoUpdate = mysqli_query($connection, $consultaUpdate);
                        
                    }
                } else  if($estado == 3){
                    $consultaUpdate = "UPDATE pys_agenda SET estAgenda =$estado, notaAgenda='$obs', horaAgenda = $hora, minAgenda = $min  where idAgenda = $idAgenda";
                    $resultadoUpdate = mysqli_query($connection, $consultaUpdate);
                }
                if ($resultadoUpdate){
                    if($estado == 1){
                        echo ' <script> alert("Se actualizo el registro")</script>
                        <meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.date("d-m-Y", strtotime($fecha)).'">';
                    } else if ($estado == 2) {
                        return $regTiempo[1].' Se ha cambiado el estado en agenda';

                    } else if ($estado == 3) {
                        echo 'Se ha cancelado la asigancion en la agenda';
                    }     
                } else {

                    if($estado == 1){
                        echo ' <script> alert("No es posible actualizar el registro")</script>
                        <meta http-equiv="Refresh" content="0;url=../Views/agenda.php?hoy='.date("d-m-Y", strtotime($fecha)).'">'; 
                    } else if ($estado == 2) {
                        return $regTiempo[1].' No se ha cambiado el estado en agenda';

                    } else if ($estado == 3) {
                        echo 'No se ha cancelado la asigancion en la agenda';
                    }     

                }
            }
        }
        mysqli_close($connection);
    }

    public static function ValidacionPlaneacionDia($fecha, $usuario){
        require('../Core/connection.php');
        $newFecha = date("Y-m-d", strtotime($fecha));
        $consulta ="SELECT idAgenda FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE (pys_agenda.estAgenda = 1 OR pys_agenda.estAgenda = 2) AND ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est=1 AND pys_agenda.fechAgenda ='$newFecha' AND pys_login.usrLogin='$usuario'";
        $resultado = mysqli_query($connection, $consulta);
        return mysqli_num_rows($resultado);
        mysqli_close($connection);
        
    }

    public static function validarHorasDia($fecha, $usuario, $horaAct, $minAct, $idAgenda){
        require('../Core/connection.php');
        $horasA = $horaAct;
        $minA = $minAct;
        $newFecha = date("Y-m-d", strtotime($fecha));
        $consulta ="SELECT horaAgenda, minAgenda FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE pys_agenda.estAgenda = 1 AND ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est=1 AND pys_agenda.fechAgenda ='$newFecha' AND pys_login.usrLogin='$usuario' AND idAgenda <> $idAgenda ";
        $resultado = mysqli_query($connection, $consulta);
        while( $datos = mysqli_fetch_array($resultado)){
            $horaAgenda = $datos['horaAgenda'];
            $minAgenda = $datos['minAgenda'];
            $horasA += $horaAgenda;
            $minA += $minAgenda;
        }
        $consultaT ="SELECT horaTiempo, minTiempo FROM `pys_tiempos` 
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
            WHERE pys_tiempos.estTiempo = 1 AND ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est=1 AND pys_tiempos.fechTiempo ='$newFecha' AND pys_login.usrLogin='$usuario'";
        $resultadoT = mysqli_query($connection, $consultaT);
        while( $datosT = mysqli_fetch_array($resultadoT)){
            $horaTiempo= $datosT['horaTiempo'];
            $minTiempo = $datosT['minTiempo'];
            $horasA += $horaTiempo;
            $minA += $minTiempo;
        }
        if ($minA >= 60){
            $horasA = intval(( $minA/60 )+$horasA);
            $minA = intval( $minA%60);
        } 
        return [$horasA,$minA];
        mysqli_close($connection);
    }

    public static function selectProyectoUsuario ($user,$long){
        require('../Core/connection.php');
        $string = "";
        $consultaPer = "SELECT idPersona FROM pys_login WHERE pys_login.usrLogin = '$user' AND est = 1 ";
        $resultadoPer= mysqli_query($connection, $consultaPer);
        $datosPer = mysqli_fetch_array($resultadoPer);
        $idPer = $datosPer['idPersona'];
        $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
        FROM pys_asignados 
        INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy 
        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
        INNER JOIN pys_solicitudes AS solicitudEspecifica ON solicitudEspecifica.idSol = pys_asignados.idSol 
        INNER JOIN pys_solicitudes AS solicitudInicial ON solicitudInicial.idSol = solicitudEspecifica.idSolIni 
        INNER JOIN pys_personas ON pys_personas.idPersona = solicitudInicial.idSolicitante 
        INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol 
        WHERE pys_asignados.idPersona = '$idPer' AND pys_actsolicitudes.idSolicitante = '' AND pys_asignados.est = '1' AND pys_personas.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND solicitudInicial.est = '1' AND solicitudEspecifica.est = '1' AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005')  GROUP BY pys_actualizacionproy.codProy
        ORDER BY `pys_actualizacionproy`.`codProy` ASC
        ";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 ) {
            $string .= '  <select name="sltProy'.$long.'" id="sltProy'.$long.'" onchange="cargaSolicitudesProy(\'#sltProy'.$long.'\',\'../Controllers/ctrl_agenda.php\',\'#div_produc'.$long.'\','.$long.')" required><option value="" disabled selected>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                $string .= '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
            }
            $string .= '  </select>
                    <label for="sltProy'.$long.'">Seleccione un proyecto</label>';
        } else {
            $string .= '  <select name="sltProy" id="sltProy" >
                        <option value="" disabled>No hay resultados para la busqueda</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        }
        return $string;
        mysqli_close($connection);
    }

    public static function UsuarioPersona($idPersona){
        require('../Core/connection.php');
        $consulta = "SELECT pys_login.usrLogin FROM  pys_personas 
        INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
        WHERE pys_personas.est = 1 AND pys_login.est = 1 AND pys_personas.idPersona ='$idPersona'";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos['usrLogin'];
        mysqli_close($connection);
    }

    
    public static function registrarTiemposAge($idAgenda, $fase, $usuario){
        require('../Core/connection.php');   
        $json = array();   
        if($idAgenda != [] && $fase != [] ){
            $cant = count($idAgenda); 
            $cant2 = count($fase); 
            if($cant == $cant2){
                for($i=0;$i<$cant;$i++){
                    $consulta ="SELECT * FROM pys_agenda
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_agenda.idAsig
                    WHERE idAgenda = $idAgenda[$i]" ;
                    $resultado = mysqli_query($connection, $consulta);
                    $datos = mysqli_fetch_array($resultado);
                    $fechAgenda = $datos['fechAgenda'];
                    $notaAgenda = $datos['notaAgenda'];
                    $horaAgenda = $datos['horaAgenda'];
                    $minAgenda = $datos['minAgenda'];
                    $idSol = $datos['idSol'];
                    $mensaje = PlaneacionAse::cambiarEstadoAgenda($fechAgenda, $usuario, $idSol, $idAgenda[$i], $horaAgenda, $minAgenda, $notaAgenda, 2, $fase[$i], "");
                    $json[]     = array(
                        'mensaje' => "P$idSol : $mensaje",  
                        'fecha' => date("d-m-Y", strtotime($fechAgenda)) ,  
                        
                    );
                }
            } 
        }  else{
            $json[]     = array(
                'mensaje' => "Debe seleccionar al menos un producto/servicio para registrar.",  
            );
        }
        $jsonString = json_encode($json);
        echo $jsonString;
        mysqli_close($connection);
    }

    public static function tiemposRegistrar($fecha, $usuario){
        require('../Core/connection.php');   
        $newFecha = date("Y-m-d", strtotime($fecha));
        $consulta ="SELECT idAgenda FROM pys_agenda 
        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_agenda.idAsig
        INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
        WHERE ( pys_asignados.est = 1 OR pys_asignados.est = 2) AND pys_login.est=1 AND pys_agenda.fechAgenda ='$newFecha' AND pys_login.usrLogin='$usuario 'AND estAgenda='1'";

        $resultado = mysqli_query($connection, $consulta);
        if(mysqli_num_rows($resultado) >=1){
            echo 1;
        } else{
            echo 0;
        }
    }


}
?>