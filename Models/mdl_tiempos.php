<?php

    Class Tiempos {

        public static function Provisional () {//Cambiar nombre, antes no estaba definido 
            require('../Core/connection.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_solicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_equipos.idEqu, pys_servicios.nombreSer, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct, pys_tipossolicitud.nombreTSol, pys_tipossolicitud.idTSol, pys_actsolicitudes.idEstSol, pys_actsolicitudes.idCM, pys_actsolicitudes.presupuesto, pys_actsolicitudes.horas, pys_solicitudes.idSer
                FROM pys_solicitudes
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idPersona
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_solicitudes.idSer
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_tipossolicitud ON pys_tipossolicitud.idTSol = pys_solicitudes.idTSol
                WHERE pys_solicitudes.est = '1' AND pys_solicitudes.idSol = '$id' AND pys_personas.est = '1' AND pys_equipos.est = '1' AND pys_servicios.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function OnloadTiempoInvertido($codsol){
            require('../Core/connection.php');
            $consulta = "SELECT  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.idAsig, pys_asignados.hora, pys_asignados.minuto, pys_asignados.maxhora, pys_asignados.maxmin
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

            where pys_asignados.est = '1' and pys_actsolicitudes.est = '1' and pys_solicitudes.est = '1' and pys_cursosmodulos.estProy = '1' and pys_cursosmodulos.estCurso = '1' and pys_actualizacionproy.est = '1' and pys_proyectos.est = '1' and pys_frentes.est = '1' and ((pys_personas.est = '1') or (pys_personas.est = '0')) and pys_convocatoria.est = '1' and pys_roles.est = '1' and pys_fases.est = '1' and pys_actsolicitudes.idSol = '$codsol'";
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
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                $idAsig = $datos['idAsig'];
                $consulta2 = "SELECT SUM(horaTiempo) as totHora, SUM(minTiempo) as totMinu FROM pys_tiempos WHERE idAsig = $idAsig";
                $resultado2 = mysqli_query($connection, $consulta2);
                $info = mysqli_fetch_array($resultado2);
                $hora = ($info['totHora'] == null) ? 0 : $info['totHora'];
                $minutos = ($info['totMinu']== null) ? 0 : $info['totMinu']; 

                if ($minutos >= 60){
                    $hora = intval(( $minutos/60 )+$hora);
                    $minutos = intval( $minutos%60);
                } 
                $string .= '
                    <tr>
                        <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                        <td>'.$datos['nombreRol'].'</td>
                        <td>'.$datos['nombreFase'].'</td>
                        <td>'.$datos['maxhora'].'h '.$datos['maxmin'].'m </td>
                        <td>'.$hora.'h '.$minutos.'m </td>
                </tr>';
            }    
            $string .= "
                </tbody>
            </table>";
            mysqli_close($connection);               
            return $string;    
        }

        public static function OnloadTiempoRegistrado($codsol,$idPer){
            require('../Core/connection.php');
            $consulta = "SELECT pys_tiempos.fechTiempo, pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_tiempos.notaTiempo, pys_fases.nombreFase 
                FROM `pys_asignados` 
                INNER JOIN pys_personas on pys_asignados.idPersona=pys_personas.idPersona 
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
                INNER JOIN pys_fases ON pys_asignados.idFase=pys_fases.idFase 
                INNER JOIN pys_tiempos on pys_asignados.idAsig=pys_tiempos.idAsig 
                WHERE pys_asignados.idSol= '$codsol' AND pys_login.usrLogin ='$idPer' AND pys_tiempos.estTiempo=1 AND pys_asignados.est=1";
            $resultado = mysqli_query($connection, $consulta);
            if($dat= mysqli_num_rows($resultado) > 0){
                echo $dat;
                $string = '
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tiempo</th>
                            <th>Fase</th>
                            <th>Nota</th>
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
                            <td>'.$datos['notaTiempo'].'</td>
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
    
    }
?>
