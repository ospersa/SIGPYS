<?php

use PhpOffice\PhpSpreadsheet\Calculation\DateTime;

class  PlaneacionAse{

    public static function onPeriodoActual() {
        require('../Core/connection.php');
        $consulta ="SELECT MAX(idPeriodo) FROM pys_periodos;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos['MAX(idPeriodo)'];

    }

    public static function onPeriodo($idPer) {
        require('../Core/connection.php');
        $consulta ="SELECT * FROM pys_periodos WHERE idPeriodo = $idPer;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $fechaini = strtotime($datos['inicioPeriodo']);
        $fechafin = strtotime($datos['finPeriodo']);
        $diaIni = date('w',$fechaini);
        $diaFin = date('w',$fechafin);
        $diff = $fechafin - $fechaini;
        $diasFalt = (( ( $diff / 60 ) / 60 ) / 24);
        $string = "";
        for($i=1;$i<$diaIni;$i++){
            $string .= '<div class="col s2 m2 l2">
            <div class="card">
                <div class="card-content grey" type="button">
                    <h6 class="card-stats-number transparent-text">--00</h6>
                </div>
            </div>
        </div>';
        }
        
        for($i=0;$i<=$diasFalt;$i++){
            $fechaDia = date("d-m-Y", strtotime( '+'.$i.' day', $fechaini ));
            $diafech = date('w', strtotime($fechaDia));
            if($diafech != 0 && $diafech != 6){
            $string .= '
            <div class="col s2 m2 l2">
                <div class="card">
                    <div class="fechPer card-content teal white-text" type="button" >
                    <h6 class="card-stats-number black-text">'.$fechaDia.'</h6>
                    </div>
                </div>
            </div>';
            } else if($diafech == 6){
                $string .= '
            <div class="col s2 m2 l2">
                <div class="card">
                    <div class="card-content grey white-text" type="button">
                    <h6 class="card-stats-number black-text">'.$fechaDia.'</h6>
                    </div>
                </div>
            </div>';
            }
        }
        if($diaFin !=0){
            for($j=$diaFin+1;$j<7;$j++){
                $string .= '<div class="col s2 m2 l2">
                <div class="card">
                    <div class="card-content grey " type="button">
                        <h6 class="card-stats-number transparent-text ">--00</h6>
                    </div>
                </div>
            </div>';
            }
        }
        return $string;
        mysqli_close($connection);
    }


    public static function planeacionDiaPer ($fecha, $persona, $periodo) {
        require('../Core/connection.php');
        $string = "";
        $consulta = "SELECT * FROM pys_periodos 
        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo
        INNER JOIN pys_asignaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
        WHERE pys_periodos.estadoPeriodo = 1 AND pys_dedicaciones.estadoDedicacion = 1 AND pys_asignaciones.estadoAsignacion = 1 AND pys_periodos.idPeriodo = $periodo AND pys_dedicaciones.persona_IdPersona = '$persona'";
        $resultado = mysqli_query($connection, $consulta); 
        while($datos = mysqli_fetch_array($resultado)){
            $idAsignacion = strtotime($datos['idAsignacion']);
            $observacion = strtotime($datos['observacion']);
            $obs=explode(';',$observacion);
            if ($obs[0] <$fecha && $fecha < $obs[1]){
                $consulta1 ="SELECT * FROM pys_asignados 
                INNER JOIN pys_actsolicitudes ON pys_asignados.idSol = pys_actsolicitudes.idSol
                INNER JOIN pys_actualizacionproy ON pys_asignados.idProy = pys_actualizacionproy.idProy
                WHERE pys_asignados.est =1 AND pys_actsolicitudes.est = 1 AND pys_actualizacionproy.est=1 AND pys_asignados.idAsig ='$idAsignacion'";
                $resultado2 = mysqli_query($connection, $consulta2); 
                while($datos2 = mysqli_fetch_array($resultado2)){
                    $string .='<div class ="row">
                    <h6>Nombre del Proyecto:</h6></div>
                    <hr>';

                }

            }

        }

       
        mysqli_close($connection);
    }

    public static function selectProyectoUsuario ($user,$long){
        require('../Core/connection.php');
        $string = "";
        $consulta = "SELECT * FROM pys_actualizacionproy 
        INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
        
        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
        INNER JOIN pys_solicitudes  ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
        INNER JOIN pys_asignados ON pys_asignados.idSol = pys_actsolicitudes.idSol
        INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
        INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
        WHERE pys_solicitudes.idTSol = 'TSOL02' AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005') AND pys_solicitudes.est = 1 AND pys_actsolicitudes.est =1 AND pys_actualizacionproy.est = 1 AND pys_personas.est = 1 AND pys_login.usrLogin = '$user' GROUP BY pys_actualizacionproy.codProy ORDER BY pys_actualizacionproy.codProy ASC ";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 ) {
            $string .= '  <select name="sltProy'.$long.'" id="sltProy'.$long.'" onchange="cargaSolicitudesProy(\'#sltProy'.$long.'\',\'../Controllers/ctrl_agenda.php\',\'#div_produc'.$long.'\')" required><option value="" disabled selected>Seleccione</option>';
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

    public static function selectSolUsuario ($user, $proyecto,$periodo){
        require('../Core/connection.php');
        $string = "";
        $consulta = "SELECT pys_actsolicitudes.idSol, pys_solicitudes.descripcionSol FROM pys_solicitudes 
        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
        INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
        INNER JOIN pys_asignados ON pys_asignados.idSol = pys_actsolicitudes.idSol
        INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
        INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona
        WHERE pys_solicitudes.idTSol = 'TSOL02' AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005') AND pys_solicitudes.est = 1 AND pys_asignados.est = 1 AND pys_actsolicitudes.est =1 AND pys_actualizacionproy.est = 1 AND pys_personas.est = 1 AND pys_login.usrLogin = '$user' AND pys_actualizacionproy.idProy ='$proyecto' ";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 ) {
            while ($datos = mysqli_fetch_array($resultado)) {
                $idSol = $datos['idSol'];
                $descripcionSol = $datos['descripcionSol'];
                $hDispo =PlaneacionAse::horasDisponibles($idSol, $user, $periodo);
                if ($hDispo ==0){
                    $hDispo ='0';
                }
            $string .= '
            <div class="input-field col l2 m2 s12 ">
                <p>
                <label>
                    <input type="checkbox" class="filled-in" name ="idSol[]" value ='.$idSol.'>
                    <span>P'.$idSol.'</span>
                </label>
                <p>
            </div>
            <div class="input-field col l1 m1 s12">
                <label class="active">Horas disponibles: '.$hDispo.' h</label>
            </div>
            <div class="input-field col l1 m1 s12 offset-l1 offset-m1">
                <input type="number" class="validate" name ="horas[]">
                <label for="horas" class="active">Horas</label>
            </div>
            </div>
            <div class="input-field col l1 m1 s12">
                <input type="number" class="validate" name ="min[]">
                <label for="min" class="active">Minutos</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1">
                <textarea name="obser[]" class="materialize-textarea"></textarea>
                <label for="obser" class="active">Actividad</label>
            </div>
            </div>
            <div class="input-field col l1 m1 s12">
                <a class="teal-text text-accent-4 tooltipped" data-position="left" data-tooltip="'.$descripcionSol.'"><i class="material-icons small">info_outline</i></a>
            </div>';
            }
        }
        echo $string;
    }

    public static function crearDiv ($long, $usuario, $fecha){
        $div =PlaneacionAse::crearDivP($long, $usuario);
        echo '
        <ul class="collapsible">
            <li>
                <div class="collapsible-header">Registrar Planeación '.$fecha.'</div>
                <div class="collapsible-body">
                    <div class="row">
                    <form id="proyAgend" action="../Controllers/ctrl_agenda.php" method="post">
                    <div class="row btnmas ">
                    <input type="text" class="validate" name ="fecha" hidden value="'.$fecha.'">
                        
                    <a class="sumarDiv btn btn-floating waves-effect waves-light teal tooltipped" data-position="top" data-tooltip="Añadir Actividad" onclick="duplicarDiv()"><i class="material-icons" >add</i></a>
                    <button id="btn-pass" class="btn waves-effect waves-light" type="submit"
                    name="btnGuardar">Guardar</button>
                        </div>
                                            '.$div.'
                    </form>
                </div>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Planeación Registrada</div>
                <div class="collapsible-body">
                <div class="col l10 m10 s12 ">
                    <h6><strong> Nombre del Proyecto:</strong></h6>
                    <hr>
                </div>
                </div>
            </li>
            </ul>
        
        
        ';
    }
    
    public static function crearDivP($long, $usuario){
        $divProy = PlaneacionAse::selectProyectoUsuario($usuario,$long);
        return '
        <div class="row" id="cardPro'.$long.'">
            <div class="col s12 m12 l12">
                <div class="card" >
                <div class="card-content ">
                <div class="row ">
            <a class=" btn btn-floating right waves-effect waves-light red" onclick="eliminarDiv('.$long.')"><i class="material-icons" >close</i></a>
                    </div>
                    <div class ="conteo row" id=" proy'.$long.'">       
                        <div class="input-field">'.$divProy.'</div>
                        <div id="div_produc'.$long.'" class="col l10 m10 s12 "></div>
                    </div> 
                </div>
                </div>
            </div>
        </div>';
    } 


    public static function guardarPlaneacion($productos, $horas, $min, $obs, $usuario, $fecha,$periodo){
        require('../Core/connection.php');
        $newFecha = date("Y/m/d", strtotime($fecha));
        var_dump($productos);
        $cant = count($productos);
        for($i=0;$i<$cant ;$i++){
            if($productos[$i] != null){
                echo $consulta = PlaneacionAse::consultaAsig($productos[$i], $usuario);
                $resultado = mysqli_query($connection, $consulta);
                if (mysqli_num_rows($resultado) > 0 ) {
                    echo 'producto'.$productos[$i].'horas'.$horas[$i].'obs'.$obs[$i] ;

                    while ($datos = mysqli_fetch_array($resultado)){
                        $idAsig = $datos['idAsig'];
                        echo $consultaInsert = 'INSERT INTO pys_agenda  VALUES (null, '.$idAsig.', "'.$newFecha.'", "'.$obs[$i].'", '.$horas[$i].', '.$min[$i].', now(), 1);';
                        $resultadoInsert = mysqli_query($connection, $consultaInsert);
                        PlaneacionAse::guardarEnPlaneacion($idAsig, $horas[$i], $min[$i], $obs[$i], $usuario, $fecha, $periodo);
                    }
                }
            }    
        }
        
    }

    public static function guardarEnPlaneacion($idAsig, $hora, $min, $obser, $usuario, $fecha, $periodo){
        require('../Core/connection.php');
        $horaTotal = 0;
        $minTotal = 0;
        $obs = "";
        echo$consulta = "SELECT idDedicacion FROM pys_dedicaciones
        INNER JOIN pys_login ON pys_login.idPersona = pys_dedicaciones.persona_IdPersona
        WHERE pys_dedicaciones.estadoDedicacion=1 AND pys_login.est=1 AND periodo_IdPeriodo = ".$periodo." AND pys_login.usrLogin = '".$usuario."'";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $idDedicacion = $datos['idDedicacion'];
        echo $consultaAsig = "SELECT idAsignacion, horasInvertir, minutosInvertir, observacion FROM pys_asignaciones WHERE idDedicacion = $idDedicacion AND idAsignado = $idAsig AND estadoAsignacion = 1";
        $resultadoAsig = mysqli_query($connection, $consultaAsig);
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
            $obs = $observacion.'; '.$obser. 'fecha: '.$fecha;
            echo $consultaUpdate = "UPDATE pys_asignaciones SET horasInvertir=".$horaTotal.", minutosInvertir =".$minTotal.",observacion ='".$obs."' WHERE idAsignacion=".$idAsignacion;
            $resultadoUpdate = mysqli_query($connection, $consultaUpdate);

        } else {
            echo $consultaInsert = "INSERT INTO pys_asignaciones VALUES(null, '".$idDedicacion."','.$idAsig.', '.$hora.', '.$min.', '".$obser." fecha: ".$fecha."', 1) ";
            $resultadoInsert = mysqli_query($connection, $consultaInsert);
        }
    }

    public static function consultaAsig($idSol, $usuario){
        return $consulta ="SELECT pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idAsig
        FROM pys_asignados
        INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona
        WHERE pys_asignados.est = 1 AND pys_asignados.idSol ='$idSol' AND pys_login.usrLogin='$usuario' ";
         
    }
    public static function horasDisponibles($idSol, $usuario, $periodo){
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
    }
}
?>