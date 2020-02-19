<?php

    Class Tiempos {

        public static function OnloadTiempoInvertido($codsol){
            require('../Core/connection.php');
            $horasTotal1 = 0;
            $minTotal1 = 0;
            $horasTotal = 0;
            $minTotal = 0;
            $consulta = "SELECT  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.idAsig, pys_asignados.hora, pys_asignados.minuto, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.est
            FROM pys_asignados
            inner join pys_solicitudes on pys_asignados.idSol = pys_solicitudes.idSol
            inner join pys_actsolicitudes on pys_actsolicitudes.idSol = pys_solicitudes.idSol
            inner join pys_cursosmodulos on pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            inner join pys_proyectos on pys_cursosmodulos.idProy = pys_proyectos.idProy
            inner join pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
            inner join pys_frentes on pys_proyectos.idFrente = pys_frentes.idFrente
            inner join pys_personas on pys_asignados.idPersona = pys_personas.idPersona
            inner join pys_roles on pys_asignados.idRol = pys_roles.idRol
            inner join pys_fases on pys_asignados.idFase = pys_fases.idFase
            inner join pys_convocatoria on pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria

            where pys_asignados.est != '0' and pys_actsolicitudes.est = '1' and pys_solicitudes.est = '1' and pys_cursosmodulos.estProy = '1' and pys_cursosmodulos.estCurso = '1' and pys_actualizacionproy.est = '1' and pys_proyectos.est = '1' and pys_frentes.est = '1' and ((pys_personas.est = '1') or (pys_personas.est = '0')) and pys_convocatoria.est = '1' and pys_roles.est = '1' and pys_fases.est = '1' and pys_actsolicitudes.idSol = '$codsol'";
            $resultado = mysqli_query($connection, $consulta);
            $string = '
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Responsable</th>
                        <th>Rol</th>
                        <th>Fase</th>
                        <th>Tiempo maximo a invertir</th>
                        <th>Tiempo invertirdo</th>
                        <th>Estado de tarea</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                $idAsig = $datos['idAsig'];
                $est = $datos['est'];
                $consulta2 = "SELECT SUM(horaTiempo) as totHora, SUM(minTiempo) as totMinu FROM pys_tiempos WHERE idAsig = $idAsig  AND estTiempo = 1";
                $resultado2 = mysqli_query($connection, $consulta2);
                $info = mysqli_fetch_array($resultado2);
                $hora = ($info['totHora'] == null) ? 0 : $info['totHora'];
                $minutos = ($info['totMinu']== null) ? 0 : $info['totMinu']; 
                if ($est == 1 ){
                    $msjTool = "Tarea no terminada";
                    $color = "red";
                } else {
                    $msjTool = "Tarea terminada";
                    $color = "teal";
                }
                if ($minutos >= 60){
                    $hora = intval(( $minutos/60 )+$hora);
                    $minutos = intval( $minutos%60);
                } 
                $horasTotal1 += $hora;
                $minTotal1 += $minutos;
                $horasTotal += $datos['maxhora'];
                $minTotal += $datos['maxmin'];
                $string .= '
                <tr>
                <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                <td>'.$datos['nombreRol'].'</td>
                <td>'.$datos['nombreFase'].'</td>
                <td>'.$datos['maxhora'].'h '.$datos['maxmin'].'m </td>
                <td>'.$hora.'h '.$minutos.'m </td>
                <td><a class=" tooltipped" data-tooltip="'.$msjTool.'" ><i class="material-icons '.$color.'-text">done</i></a></td>
                </tr>';
            }    
            if ($minTotal1 >= 60){
                $horasTotal1 = intval(( $minTotal1/60 )+$horasTotal1);
                $minTotal1 = intval( $minTotal1%60);
            } 
            if ($minTotal >= 60){
                $horasTotal = intval(( $minTotal/60 )+$horasTotal);
                $minTotal = intval( $minTotal%60);
            } 
            $string .= "
            <tr>
                <td></td>
                <td></td>
                <td><b>Tiempo Total</b></td>
                <td>".$horasTotal."h".$minTotal."m</td>
                <td>".$horasTotal1."h".$minTotal1."m</td>
            </tr>
            </tbody>
            </table>";
            mysqli_close($connection);               
            return $string;    
        }

        public static function OnloadTiempoRegistrado($codsol,$idPer){
            require('../Core/connection.php');
            $consulta = "SELECT pys_tiempos.idTiempo, pys_tiempos.fechTiempo, pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_tiempos.notaTiempo, pys_fases.nombreFase 
                FROM `pys_asignados` 
                INNER JOIN pys_personas on pys_asignados.idPersona=pys_personas.idPersona 
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
                INNER JOIN pys_tiempos on pys_asignados.idAsig=pys_tiempos.idAsig 
                INNER JOIN pys_fases ON pys_tiempos.idFase=pys_fases.idFase 
                WHERE pys_asignados.idSol= '$codsol' AND pys_login.usrLogin ='$idPer' AND pys_tiempos.estTiempo=1 AND pys_asignados.est=1 ORDER BY pys_tiempos.fechTiempo DESC";
            $resultado = mysqli_query($connection, $consulta);
            $dat = mysqli_num_rows($resultado);
            if( $dat > 0){                
                $string = '
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tiempo</th>
                            <th>Fase</th>
                            <th>Nota</th>
                            <th>Editar</th>
                            <th>Suprimir</th>
                            
                        </tr>
                    </thead>
                    <tbody>';

                while ($datos = mysqli_fetch_array($resultado)){
                    $hora = ($datos['horaTiempo'] == null) ? 0 : $datos['horaTiempo'];
                    $minutos = ($datos['minTiempo']== null) ? 0 : $datos['minTiempo']; 

                    if ($minutos >= 60){
                        $hora = intval(( $minutos/60 )+$hora);
                        $minutos = intval( $minutos%60);
                    } 
                    $string .= '
                        <tr>
                            <td>'.$datos['fechTiempo'].'</td>
                            <td>'.$hora.'h '.$minutos.'m </td>
                            <td>'.$datos['nombreFase'].'</td>
                            <td><p class = "truncate">'.$datos['notaTiempo'].'</p></td>
                            <td><a href="#!" class="waves-effect waves-light" onclick ="editarRegistro(\''.$datos[0].'\')"title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            <td><a href="#!" class="waves-effect waves-light" onclick ="ocultarEditar(\''.$datos[0].'\')"title="Suprimir"><i class="material-icons red-text">delete</i></a></td>
                    </tr>';
                }    
                $string .= "
                    </tbody>
                </table>";            
            }else{
                $string = "<h6> No hay tiempos registrados</h6>";
            }
            return $string;
            mysqli_close($connection);   

        }

        public static function registrarTiempos($idsol, $user, $fecha, $nota, $horas, $minutos, $fase, $cod){
            require('../Core/connection.php');
            $string = "";
            $stringF = "";
            $nota = mysqli_real_escape_string($connection, $nota);
            $minutos = mysqli_real_escape_string($connection, $minutos);
            $horas = mysqli_real_escape_string($connection, $horas);
            $fecha = mysqli_real_escape_string($connection, $fecha);
            $resultado2 = false;
            if ($fecha != null && $nota != null && $horas != null && $minutos != null && $fase != null){
                $fechaAct = date("Y-m-d");
                $consulta = "SELECT `inicioPeriodo`, `finPeriodo` FROM pys_periodos WHERE `estadoPeriodo` = 1 AND (`inicioPeriodo` <= '$fechaAct') AND (`finPeriodo` >= '$fechaAct'); "; 
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $inicioPer = $datos['inicioPeriodo'];
                $finPer = $datos['finPeriodo'];
                $consulta3 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo) FROM pys_tiempos
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                INNER JOIN pys_personas ON pys_asignados.idPersona =pys_personas.idPersona
                INNER JOIN pys_login on pys_personas.idPersona=pys_login.idPersona
                WHERE pys_tiempos.fechTiempo = '$fecha' AND pys_login.usrLogin = '$user' AND pys_tiempos.estTiempo = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $horasDB = $datos3[0];
                $minutosDB = $datos3[1];
                $tiempoRegistrado = (($horasDB * 60) + $minutosDB) / 60;
                $tiempoARegistrar = (($horas * 60) + $minutos) / 60;
                $totalDia = $tiempoRegistrado + $tiempoARegistrar;
                if($cod != 1){
                    $string ='<script> alert("';
                    $stringF ='")</script> <meta http-equiv="Refresh" content="0;url='.$_SERVER["HTTP_REFERER"].'">';
                }
                if ($inicioPer <= $fecha && $finPer >= $fecha){
                    if ($totalDia <= 12){
                        $consulta1 = "SELECT idAsig FROM pys_asignados
                        INNER JOIN pys_personas ON pys_asignados.idPersona =pys_personas.idPersona
                        INNER JOIN pys_login on pys_personas.idPersona=pys_login.idPersona
                        WHERE pys_login.usrLogin= '$user' AND pys_asignados.idSol ='$idsol' AND pys_asignados.est = 1 AND pys_personas.est = 1 AND pys_login.est = 1 ";
                        $resultado1 = mysqli_query($connection, $consulta1);
                        $datos = mysqli_fetch_array($resultado1);
                        $idAsig = $datos['idAsig']; 
                        $consulta2 = "INSERT INTO pys_tiempos VALUES (DEFAULT, '$idAsig', '$fecha', '$nota', '$horas', '$minutos', now() , '$fase', '1')";
                        $resultado2 = mysqli_query($connection, $consulta2);
                        
                        if ($resultado1 && $resultado2) {                    
                            $string .= 'Se guardó correctamente la información. ';
                        } else { 
                            $string .= "Ocurrió un error al intentar guardar el registro. ";
                        }
                    } else{
                        $string .="El tiempo no puede ser guardado. Verifique que no esté excediendo 12 horas diarias. ";
                    }
                } else {
                $string .='El tiempo no puede ser guardado. Verifique que la fecha se encuentra dentro del periodo vigente. ';
                } 
                
            } else {
                if($cod != 1){
                    $string ='<script> alert("';
                    $stringF ='")</script> <meta http-equiv="Refresh" content="0;url='.$_SERVER["HTTP_REFERER"].'">';
                }
                $string .='Existe algún campo vacio. ';
            }
            if($cod == 1){
                return [$resultado2, $string];
            }
            echo $string.$stringF;   
            mysqli_close($connection);   
        }

        public static function editarTiemposRe($id, $user, $fecha, $tiempoH, $tiempoM, $fase, $nota){
            require('../Core/connection.php');
            $nota = mysqli_real_escape_string($connection, $nota);
            $tiempoM = mysqli_real_escape_string($connection, $tiempoM);
            $tiempoH = mysqli_real_escape_string($connection, $tiempoH);
            $fecha = mysqli_real_escape_string($connection, $fecha);
            $consultaTiempos = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo) FROM pys_tiempos
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_personas ON pys_asignados.idPersona =pys_personas.idPersona
            INNER JOIN pys_login on pys_personas.idPersona=pys_login.idPersona
            WHERE pys_tiempos.fechTiempo = '$fecha' AND pys_login.usrLogin = '$user' AND pys_tiempos.estTiempo = '1' AND idTiempo <> $id;";
            $resultadoTiempos = mysqli_query($connection, $consultaTiempos);
            $datos = mysqli_fetch_array($resultadoTiempos);
            $hora = $datos['SUM(pys_tiempos.horaTiempo)'];
            $min = $datos['SUM(pys_tiempos.minTiempo)'];
            $total = (($hora + $tiempoH)*60) + $min + $tiempoM;
            if ($total <= 720){
                $consulta = "UPDATE pys_tiempos SET fechTiempo = '$fecha', notaTiempo ='$nota', horaTiempo = '$tiempoH', minTiempo ='$tiempoM', idFase = '$fase' WHERE idTiempo = '$id'";
                $resultado = mysqli_query($connection, $consulta);
                if ($resultado) {                    
                    echo "<script> alert ('Se guardó correctamente la información');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER["HTTP_REFERER"].'">';
                } else { 
                    echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER["HTTP_REFERER"].'">';
                }
            } else{
                echo "<script> alert ('No se realizo el registro. Hay mas de 12 horas registradas');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER["HTTP_REFERER"].'">';
            }
            mysqli_close($connection);   
        }

        public static function SuprimirTiempoRe($id){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_tiempos SET estTiempo = 0 WHERE idTiempo = '$id'";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado) {                    
                echo "Se guardó correctamente la información";

            } else { 
                echo "Ocurrió un error al intentar actualizar el registro";
            }
            mysqli_close($connection);   
        }
        public static function llenarFormEditar($id){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_tiempos WHERE idTiempo = $id;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $string= '
            <a class="botonSalir btn-floating waves-effect waves-light  transparent " onclick="cerrar()"><i class="material-icons red-text">clear</i></a>
            <div class="row">
            <h4>Editar tiempo resgistrado</h4>
            </div>
            <div class="row">
            <form id="edit" action="../Controllers/ctrl_regtime.php" method="post">
                    <input id="idTiempo" name="idTiempo" value="'.$id.'"type="hidden">
                    <div class="input-field col s12 m2 l2">
                        <input id="dateEdit" name="dateEdit" type="text" value='.$datos["fechTiempo"].' class="datepicker">
                        <label class="active" for="dateEdit">Fecha</label>
                    </div>
                    <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                        <input id="horasEdit" name="horasEdit" type="number" value="'.$datos["horaTiempo"].'" min="0" max="12">
                        <label class="active" for="horasEdit">Horas</label>
                    </div>
                    <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                        <input id="minutosEdit" name="minutosEdit" type="number" value="'.$datos["minTiempo"].'" min="0" max="59">
                        <label class="active" for="minutosEdit">Minutos</label>
                    </div>
                    <div class="input-field col s12 m5 l5 offset-m1 offset-l1">'.
                        Tiempos::selectFase($datos["idFase"]).'
                        
                    </div>
                    <div class="input-field col s12">
                        <textarea id="notaTEdit" name ="notaTEdit" class="materialize-textarea textarea">'.$datos["notaTiempo"].'</textarea>
                        <label class="active" for="notaTEdit">Nota:</label>
                    </div>
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light" type="submit" name="btnActRegTiempo">Actualizar</button>
                    </div>
                </form>
                </div>
                ';
                echo $string;
        }

        public static function selectFase ($cod) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_fases WHERE est = '1' ;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                if($cod == null){
                    $string = '  <select name="sltFase" id="sltFase" class="asignacion"  >
                                <option value="" selected disabled>Seleccione</option>';

                } else  {
                    $cons = ($cod != "Sin Label") ? "" : "[]" ;
                    $disabled = ($cod != "Sin Label") ? "" : "disabled" ;
                    $string = '  <select name="sltFaseEdit'.$cons.'" id="sltFaseEdit'.$cons.'" class="asignacion" '.$disabled.'>
                                <option value="" selected disabled>Seleccione</option>';
                }
                while ($datos = mysqli_fetch_array($resultado)) {
                    if( $datos['idFase'] == $cod){
                        $string .= '  <option selected value="'.$datos['idFase'].'">'.$datos['nombreFase'].'</option>';
                    }
                    $string .= '  <option value="'.$datos['idFase'].'">'.$datos['nombreFase'].'</option>';
                }
                if($cod == null){
                    $string .= '  </select>
                            <label for="sltFase">Fase*</label>';
                } else if ($cod != "Sin Label"){           
                    $string .= '  </select>
                            <label for="sltFaseEdit">Fase*</label>';
                }
            } else {
                echo "<script>alert ('No hay categorías creadas')</script>";
            }
            return $string;
            mysqli_close($connection);
        }
    
    }
?>
