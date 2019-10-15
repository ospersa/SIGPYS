<?php
    Class ResulServicio {

        public static function cargaUsuario ($user) {
            require('../Core/connection.php');
            $sql = "SELECT idPersona FROM pys_login where usrLogin = '$user';";
            $result = mysqli_query($connection, $sql);
            while($data = mysqli_fetch_array($result)){
                $idUsuario = $data[0];
            };
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.descripcionSol, pys_equipos.nombreEqu,  pys_servicios.nombreSer,  pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol
            FROM pys_solicitudes
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_asignados ON pys_asignados.idsol = pys_actsolicitudes.idSol
            INNER JOIN pys_personas ON pys_asignados.idResponRegistro = pys_personas.idPersona
	        WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_equipos.est = '1' AND pys_asignados.est = '2' AND   pys_asignados.idPersona = '".$idUsuario."' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'NO' AND  pys_solicitudes.idTSol = 'TSOL02' AND ((pys_actsolicitudes.idEstSol != 'ESS001') AND (pys_actsolicitudes.idEstSol != 'ESS006') AND (pys_actsolicitudes.idEstSol != 'ESS007'))
            ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left">
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
                                        <th>Información Servicio</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>
                                        <td><a href="#modalResulSerTer" data-position="right" class="modal-trigger tooltipped" data-tooltip="Mas información del Servicio" onclick="envioData(\''.$idSol.'\',\'modalResulSerTer.php\')"><i class="material-icons teal-text">info_outline</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            } else {
                if ($cod == '1') {
                    $string = "<div class='card-panel teal darken-1'><h6 class='white-text'>No hay solicitudes específicas creadas.</h6></div>";
                } else {
                    $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$buscar.'</strong></h6></div>';
                }
            }
            echo $string;
        
        }

        public static function cargaBusqueda($user, $buscar) {
            require('../Core/connection.php');
            $sql = "SELECT idPersona FROM pys_login where usrLogin = '$user';";
            $result = mysqli_query($connection, $sql);
            while($data = mysqli_fetch_array($result)){
                $idUsuario = $data[0];
            };
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.descripcionSol, pys_equipos.nombreEqu,  pys_servicios.nombreSer,  pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol
            FROM pys_solicitudes
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_asignados ON pys_asignados.idsol = pys_actsolicitudes.idSol
            INNER JOIN pys_personas ON pys_asignados.idResponRegistro = pys_personas.idPersona
	        WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_equipos.est = '1' AND pys_asignados.est = '2' AND   pys_asignados.idPersona = '".$idUsuario."' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'NO' AND  pys_solicitudes.idTSol = 'TSOL02' AND ((pys_actsolicitudes.idEstSol != 'ESS001') AND (pys_actsolicitudes.idEstSol != 'ESS006') AND (pys_actsolicitudes.idEstSol != 'ESS007')) AND ((pys_actualizacionproy.codProy LIKE '%".$buscar."%') OR (pys_actualizacionproy.nombreProy LIKE '%$buscar%') OR (pys_actsolicitudes.idSol LIKE '%$buscar%')) 
            ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left">
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
                                        <th>Información Servicio</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>
                                        <td><a href="#modalResulSerTer" data-position="right" class="modal-trigger tooltipped" data-tooltip="Mas información del Servicio" onclick="envioData(\''.$idSol.'\',\'modalResulSerTer.php\')"><i class="material-icons teal-text">info_outline</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            } else {
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$buscar.'</strong></h6></div>';
            }
            echo $string;
        
        }

    }  
?>  