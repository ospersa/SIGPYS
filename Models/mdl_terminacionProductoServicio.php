<?php

    Class Terminar {

        public static function selectProyectoUsuario ($busqueda, $user){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy,pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy  FROM pys_asignados 
            INNER JOIN pys_proyectos on pys_proyectos.idProy = pys_asignados.idProy 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy =pys_proyectos.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND pys_actualizacionproy.est=1 AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_proyectos.est=1 AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
            echo '  <select name="sltProy" id="sltProy" required>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                echo '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
            }
            echo '  </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        } else {
            echo '  <select name="sltProy" id="sltProy" required>
                        <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        }
        mysqli_close($connection);
        }
        public static function cargarProyectosUser ($user){
            require('../Core/connection.php');
            //valida los proyectos en los cuales es agigando como gestor o asesor RED.
            $string = ' <table class="responsive-table left" id="terminar">
            <thead>
                <tr>
                    <th>Código solicitud</th>
                    <th>Producto/Servicio</th>
                    <th>Cód. proyecto en Conecta-TE</th>
                    <th>Proyecto</th>
                    <th>Equipo -- Servicio</th>
                    <th>Descripción Producto/Servicio</th>
                    <th>Fecha prevista entrega</th>
                    <th>Fecha creación</th>
                    <th>Terminar y enviar correo</th>
                </tr>
            </thead>
            <tbody>';
            $consulta = "SELECT pys_proyectos.idProy,pys_proyectos.codProy, pys_proyectos.nombreProy  FROM pys_asignados 
            INNER JOIN pys_proyectos on pys_proyectos.idProy = pys_asignados.idProy 
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_proyectos.est=1 ";
            $resultado = mysqli_query($connection, $consulta);
            while($data = mysqli_fetch_array($resultado)){
                $idProy = $data['idProy'];
                $codProy = $data['codProy'];
                $nombreProy = $data['nombreProy'];
                // consulta listado de solicitudes del proyecto
                $consulta1 = "SELECT pys_solicitudes.idSol, pys_solicitudes.idSolIni, pys_actsolicitudes.idSer, pys_solicitudes.fechSol FROM pys_proyectos 
                INNER JOIN pys_cursosmodulos ON pys_proyectos.idProy = pys_cursosmodulos.idProy 
                INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                WHERE pys_proyectos.idProy = '$idProy' AND pys_actsolicitudes.est=1 AND pys_solicitudes.idTSol= 'TSOL02' AND pys_actsolicitudes.est=1 AND pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS007' AND pys_actsolicitudes.idEstSol !='ESS006' ";
                $resultado1 = mysqli_query($connection, $consulta1);
               
                while ($data1 = mysqli_fetch_array($resultado1)){
                    $idSol = $data1['idSol'];
                    $idSolIni = $data1['idSolIni'];
                    $idSer = $data1['idSer'];
                    $fechSol = $data1['fechSol'];
                    
                    $consulta2 = "SELECT idPersona FROM pys_asignados WHERE idSol= '$idSol' and est !=2";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $registros2 = mysqli_num_rows($resultado2);
                    /*cambiar por == */
                    if ($registros2 != 0){
                        $consulta3 = "SELECT pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev FROM pys_actsolicitudes 
                        INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
                        INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
                        WHERE pys_actsolicitudes.idSer = '$idSer'  AND pys_actsolicitudes.idSol= '$idSol' AND pys_equipos.est = 1 AND pys_servicios.est = 1";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        while ($data3 = mysqli_fetch_array($resultado3)){
                            $nombreEqu = $data3['nombreEqu'];
                            $nombreSer = $data3['nombreSer'];
                            $ObservacionAct = $data3['ObservacionAct'];
                            $fechPrev = $data3['fechPrev'];
                            $string .= '<tr>
                                        <td>'.$idSolIni.'</td>
                                        <td>P'.$idSol.'</td>
                                        <td>'.$codProy.'</td>
                                        <td>'.$nombreProy.'</td>
                                        <td>'.$nombreEqu.' -- '.$nombreSer.'</td>
                                        <td><p class="truncate">'.$ObservacionAct.'</p></td>
                                        <td>'.$fechPrev.'</td>
                                        <td>'.$fechSol.'</td>
                                        <td><a href="#!" data-position="right" class="modal-trigger tooltipped" data-tooltip="Terminar Producto o Servicio"><i class="material-icons red-text">done_all</i></a></td>
                                        </tr>';
                        }
                    }
                }
            }
            $string .= '    </tbody>
                            </table>';
            echo $string;
        }
    }
?>
