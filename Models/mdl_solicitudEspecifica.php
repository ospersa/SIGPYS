<?php

    Class SolicitudEspecifica {
        
        public static function onLoadSolicitudEspecifica ($id) {
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

        public static function preLoadSolicitudEspecifica ($id) {
            require('../Core/connection.php');
            $consulta = "SELECT nombreTSol, idTSol FROM pys_tipossolicitud WHERE est = '1' AND nombreTSol = 'Especifica';";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos = mysqli_fetch_array($resultado)){
                $array1 = ['nombreTipo' => $datos['nombreTSol'], 'idTipo' => $datos['idTSol']];
            }
            $consulta2 = "SELECT nombreEstSol, idEstSol FROM pys_estadosol WHERE est = '1' AND idEstSol = 'ESS003';";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 = mysqli_fetch_array($resultado2)){
                $array2 = ['nombreEstado' => $datos2['nombreEstSol'], 'idEstado' => $datos2['idEstSol']];
            }
            /** Consulta para traer información de la solicitud inicial */
            $consulta3 = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.ObservacionAct, pys_actualizacionproy.idProy
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                WHERE idSol = '$id' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1';";
            $resultado3 = mysqli_query($connection, $consulta3);
            while ($datos3 = mysqli_fetch_array($resultado3)) {
                $array3 = ['codProy' => $datos3['codProy'], 'nombreProy' => $datos3['nombreProy'], 'observacion' => $datos3['ObservacionAct'], 'idProy' => $datos3['idProy']];
            }
            $array = array_merge($array1, $array2, $array3);
            return $array;
            mysqli_close($connection);
        }

        public static function registrarSolicitudEspecifica ($idSolIni, $tipoSol, $estadoSol, $idProy, $presupuesto, $horas, $equipo, $servicio, $fechaPrev, $descripcion, $registra) {
            require('../Core/connection.php');
            if ($fechaPrev == null) {
                $fechaPrev = "'NULL'";
            } else {
                $fechaPrev = "'$fechaPrev'";
            }
            /** Verificación de información recibida */
            if ($idSolIni == null || $tipoSol == null || $estadoSol == null || $idProy == null || $equipo == null || $descripcion == null) {
                echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$idSolIni.'">';
            } else {
                /** Se obtiene ID de la persona que registra */
                $cons = "SELECT idPersona FROM pys_login WHERE usrLogin = '$registra' AND est = '1';";
                $res = mysqli_query($connection, $cons);
                $datosPersona = mysqli_fetch_array($res);
                $registra = $datosPersona['idPersona'];
                /** Consulta para traer el id de la solicitud a registrar */
                $consulta = "SELECT COUNT(idSol), MAX(idSol) FROM pys_solicitudes;";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $count = $datos[0];
                $max = $datos[1];
                if ($count == 0) {
                    $idSolEsp = "S00001";
                } else {
                    $idSolEsp = 'S'.substr((substr($max,1)+100001),1);
                }
                /** Consulta para traer el id de la tabla cursos_modulos con respecto al id del proyecto */
                $consulta2 = "SELECT idCM FROM pys_cursosmodulos WHERE estProy = '1' AND idCurso = '' AND nombreCursoCM = '' AND idProy = '$idProy';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $idCM = $datos2['idCM'];
                /** Preparación de los datos para que en caso de falla poder realizar ROLLBACK */
                mysqli_query($connection, "BEGIN;");
                /** Insert de los datos en la tabla pys_solicitudes */
                $consulta3 = "INSERT INTO pys_solicitudes VALUES ('$idSolEsp', '$tipoSol', '$idSolIni', '$servicio', '$registra', '', '$descripcion', NOW(), '1');";
                $resultado3 = mysqli_query($connection, $consulta3);
                /** Insert de los datos en la tabla pys_actsolicitudes */
                $consulta4 = "INSERT INTO pys_actsolicitudes VALUES (DEFAULT, '$estadoSol', '$idSolEsp', '$idCM', '$servicio', '$registra', '', $fechaPrev, NOW(), '$descripcion', '$presupuesto', '$horas', '1');";
                $resultado4 = mysqli_query($connection, $consulta4);
                if ($resultado3 && $resultado4) {
                    mysqli_query($connection, "COMMIT;");
                    echo '<script>alert("Se guardó correctamente la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$idSolIni.'">';
                } else {
                    mysqli_query($connection, "ROLLBACK;");
                    echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$idSolIni.'">';
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarSolicitudEspecifica ($solIni, $solEsp, $tipoSol, $idCM, $estSol, $presupuesto, $horas, $servicio, $fechaPrev, $descripcion, $persona) {
            require('../Core/connection.php');
            /** Validación de datos vacíos */
            if ($solEsp == null || $tipoSol == null || $estSol == null || $persona == null || $idCM == null) {
                echo '<script>alert("No se pudo actualizar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
            } else {
                $solEsp = substr($solEsp, 1);
                /** Obtención del ID de la persona que realiza la actualización */
                $consulta = "SELECT idPersona FROM pys_login WHERE usrLogin = '$persona' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $persona = $datos['idPersona'];
                /** Verificación del estado de la solicitud inicial, para permitir o no la actualización de la específica */
                $consulta2 = "SELECT idEstSol FROM pys_actsolicitudes WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$solIni';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $estSolIni = $datos2['idEstSol'];
                /** Verificación de la información registrada anteriormente, para evitar crear un registro innecesario */
                $consulta3 = "SELECT * FROM pys_actsolicitudes WHERE idSol = '$solEsp' AND est = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                if ($datos3['ObservacionAct'] == $descripcion && $datos3['idSer'] == $servicio && $datos3['idEstSol'] == $estSol && $datos3['presupuesto'] == $presupuesto && $datos3['fechPrev'] == $fechaPrev) {
                    echo '<script>alert("La información ingresada es la misma. El registro no fue modificado.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                } else {
                    /** Verificación del cambio de estado de la solicitud específica; ESS006 = Terminado; ESS007 = Cancelado */
                    if ($estSol != "ESS006" && $estSol != "ESS007") {
                        /** Verificación del estado de la solicitud inicial */
                        if ($estSolIni == "ESS006" || $estSolIni == "ESS007") {
                            echo '<script>alert("La SOLICITUD INICIAL '.$solIni.' se encuentra en estado: TERMINADO o CANCELADO. No se puede actualizar este P/S.")</script>';
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                        } else {
                            mysqli_query($connection, "BEGIN;");
                            /** Insert de los datos en la tabla pys_actsolicitudes */
                            $consulta4 = "INSERT INTO pys_actsolicitudes VALUES (DEFAULT, '$estSol', '$solEsp', '$idCM', '$servicio', '$persona', '', '$fechaPrev', NOW(), '$descripcion', '$presupuesto', '$horas', '1');";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            /** Busqueda del registro anterior, para cambiar el estado */
                            $consulta5 = "SELECT MIN(pys_actsolicitudes.fechAct) FROM pys_actsolicitudes WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$solEsp';";
                            $resultado5 = mysqli_query($connection, $consulta5);
                            $datos5 = mysqli_fetch_array($resultado5);
                            /** Actualización del estado del registro anterior */
                            $consulta6 = "UPDATE pys_actsolicitudes SET est = '2' WHERE fechAct = '$datos5[0]' AND idSol = '$solEsp';";
                            $resultado6 = mysqli_query($connection, $consulta6);
                            if ($resultado4 && $resultado6) {
                                mysqli_query($connection, "COMMIT;");
                                echo '<script>alert("Solicitud específica P'.$solEsp.', actualizada correctamente.")</script>';
                                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                            } else {
                                mysqli_query($connection, "ROLLBACK;");
                                echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                            }
                        }
                    } else {
                        if ($estSolIni == "ESS006" || $estSolIni == "ESS007") {
                            echo '<script>alert("La SOLICITUD INICIAL '.$solIni.' se encuentra en estado: TERMINADO o CANCELADO. No se puede actualizar este P/S.")</script>';
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                        } else {
                            mysqli_query($connection, "BEGIN;");
                            /** Insert de los datos en la tabla pys_actsolicitudes */
                            $consulta4 = "INSERT INTO pys_actsolicitudes VALUES (DEFAULT, '$estSol', '$solEsp', '$idCM', '$servicio', '$persona', '', '$fechaPrev', NOW(), '$descripcion', '$presupuesto', '$horas', '1');";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            /** Busqueda del registro anterior, para cambiar el estado */
                            $consulta5 = "SELECT MIN(pys_actsolicitudes.fechAct) FROM pys_actsolicitudes WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$solEsp';";
                            $resultado5 = mysqli_query($connection, $consulta5);
                            $datos5 = mysqli_fetch_array($resultado5);
                            /** Actualización del estado del registro anterior */
                            $consulta6 = "UPDATE pys_actsolicitudes SET est = '2' WHERE fechAct = '$datos5[0]' AND idSol = '$solEsp';";
                            $resultado6 = mysqli_query($connection, $consulta6);
                            if ($resultado4 && $resultado6) {
                                mysqli_query($connection, "COMMIT;");
                                echo '<script>alert("Solicitud específica P'.$solEsp.', actualizada correctamente.")</script>';
                                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                            } else {
                                mysqli_query($connection, "ROLLBACK;");
                                echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php?cod='.$solIni.'">';
                            }
                        }
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function selectEquipo ($idEquipo) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_equipos WHERE est = '1' ORDER BY nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            $select = ($idEquipo == null) ? '<select name="sltEquipo" id="sltEquipo" required onchange="cargaSelect(\'#sltEquipo\',\'../Controllers/ctrl_solicitudEspecifica.php\',\'#sltServicio\');">' : '<select name="sltEquipo" id="sltEquipo2" required onchange="cargaSelect(\'#sltEquipo2\',\'../Controllers/ctrl_solicitudEspecifica.php\',\'#sltServicio2\');">' ;
            if ($registros > 0) {
                $select .= '        <option value="">Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEqu'] == $idEquipo) {
                        $select .= '<option value="'.$datos['idEqu'].'" selected>'.$datos['nombreEqu'].'</option>';
                    } else {
                        $select .= '<option value="'.$datos['idEqu'].'">'.$datos['nombreEqu'].'</option>';
                    }
                }
                $select .= '    </select>
                                <label for="sltEquipo">Equipo asignado al Producto/Servicio</label>';
            }
            return $select;
            mysqli_close($connection);
        }

        public static function selectServicio ($equipo, $servicio) {
            require('../Core/connection.php');
            $consulta = "SELECT idSer, nombreSer FROM pys_servicios WHERE idEqu = '$equipo' AND est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                echo '  <select name="sltServicio" required>
                            <option value="" selected disabled>Seleccione un servicio</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idSer'] == $servicio) {
                        echo '  <option value="'.$datos['idSer'].'" selected>'.$datos['nombreSer'].'</option>';
                    } else {
                        echo '  <option value="'.$datos['idSer'].'">'.$datos['nombreSer'].'</option>';
                    }
                }
                echo '  </select>
                        <label for="sltServicio">Servicio *</label>';
            } else {
                echo '  <select name="sltServicio" disabled>
                            <option>No hay servicios para el equipo seleccionado</option>
                        </select>
                        <label for="sltServicio">Servicio</label>';
            }
            mysqli_close($connection);
        }

        public static function cargaEspecificas ($inicial, $cod) {
            require('../Core/connection.php');
            $string = "";
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_solicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct, pys_estadosol.nombreEstSol
                FROM pys_solicitudes 
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idPersona 
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_solicitudes.idSer 
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu 
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol 
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                WHERE pys_solicitudes.est = '1' AND pys_personas.est = '1' AND pys_equipos.est = '1' AND pys_servicios.est = '1' 
                AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' 
                AND (pys_solicitudes.idSolIni LIKE '%$inicial%' OR pys_actsolicitudes.idSol LIKE '%$inicial%' 
                OR pys_actualizacionproy.codProy LIKE '%$inicial%' OR pys_estadosol.nombreEstSol LIKE '%$inicial%')
                ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left">
                                <thead>
                                    <tr>
                                        <th>CSI</th>
                                        <th>Producto/Servicio</th>
                                        <th>Estado</th>
                                        <th>Cód. proyecto en Conecta-TE</th>
                                        <th>Proyecto</th>
                                        <th>Equipo -- Servicio</th>
                                        <th>Registró</th>
                                        <th>Descripción Producto/Servicio</th>
                                        <th>Fecha prevista entrega</th>
                                        <th>Fecha creación</th>
                                        <th>Fecha actualización</th>
                                        <th>Editar</th>
                                        <th>Asignar</th>
                                    </tr>
                                </thead>
                                <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $registra = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['nombreEstSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td>'.$registra.'</td>
                                        <td>'.$datos['ObservacionAct'].'</td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>';
                    /** Validación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
                    if ($datos['fechSol'] != $datos['fechAct']) {
                        $string .= '    <td class="teal lighten-2">'.$datos['fechAct'].'</td>';
                    } else {
                        $string .= '    <td>'.$datos['fechAct'].'</td>';
                    }
                    $string .= '        <td><a href="#modalSolicitudEspecifica" class="modal-trigger" onclick="envioData(\''.$datos['idSol'].'\',\'modalSolicitudEspecifica.php\');" title="Editar Solicitud"><i class="material-icons teal-text">edit</i></a></td>';
                    /** Validación de personas asignadas al producto/servicio */
                    $consulta2 = "SELECT idSol FROM pys_asignados WHERE (est = '1' OR est = '2') AND idSol = '".$datos['idSol']."';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $registros2 = mysqli_num_rows($resultado2);
                    if ($datos['nombreEstSol'] == "Terminado" || $datos['nombreEstSol'] == "Terminado Parcial" || $datos['nombreEstSol'] == "Cancelado") {
                        $string .= '<td></td>';
                    } else {
                        if ($registros2 > 0) {
                            $string .= '    <td><a href="asignados.php?cod3='.$datos['idSol'].'" title="Ya existen personas asignadas"><i class="material-icons teal-text">person_add</i></a></td>
                                        </tr>';
                        } else {
                            $string .= '    <td><a href="asignados.php?cod3='.$datos['idSol'].'" title="Asignar personas"><i class="material-icons red-text">person_add</i></a></td>
                                        </tr>';
                        }
                    }
                }
                $string .= '    </tbody>
                            </table>';
            } else {
                if ($cod == '1') {
                    $string = "<div class='card-panel teal darken-1'> <h4 class='white-text'>No hay solicitudes específicas creadas.</h4></div>";
                } else {
                    echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$inicial.'</strong></h6></div>';
                }
            }
            if ($cod == '1') {
                return $string;
            } else if ($cod == '2') {
                echo $string;
            }
            mysqli_close($connection);
        }

        public static function cargaEspecificasUsuario ($buscar, $cod, $usuario) {
            require('../Core/connection.php');
            $string = "";
            $sql = "SELECT idPersona FROM pys_login where usrLogin = '$usuario';";
            $result = mysqli_query($connection, $sql);
            while($data = mysqli_fetch_array($result)){
                $idUsuario = $data[0];
            };

            $consulta = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_solicitudes.idTSol, pys_tipossolicitud.idTSol, pys_tipossolicitud.nombreTSol, pys_actsolicitudes.idSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.fechAct, pys_actsolicitudes.ObservacionAct, pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_Cursosmodulos.idCM, pys_Cursosmodulos.nombreCursoCM, pys_Cursosmodulos.nombreModuloCM, pys_Cursosmodulos.codigoCursoCM, pys_proyectos.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_servicios.idSer, pys_servicios.nombreSer, pys_servicios.productoOservicio, pys_equipos.nombreEqu, pys_asignados.idPersona, pys_asignados.idRol, pys_asignados.idFase, pys_asignados.idResponRegistro, pys_solicitudes.idSolIni
       
            FROM pys_solicitudes
       
            INNER JOIN pys_tipossolicitud ON pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            inner JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            inner JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            inner JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            inner JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            inner JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            inner JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
            inner JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            inner JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            inner JOIN pys_asignados ON pys_asignados.idsol = pys_actsolicitudes.idSol
            inner JOIN pys_personas ON pys_asignados.idResponRegistro = pys_personas.idPersona
            inner JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
	   	   
	        WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1' AND pys_estadosol.est = '1' AND pys_personas.est = '1' AND pys_frentes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_convocatoria.est = '1' AND pys_equipos.est = '1' AND pys_asignados.est = '1' AND   pys_asignados.idPersona = '".$idUsuario."' AND pys_solicitudes.idTSol = 'TSOL02' AND ((pys_estadosol.idEstSol != 'ESS001') AND (pys_estadosol.idEstSol != 'ESS006') AND (pys_estadosol.idEstSol != 'ESS007')) AND ((pys_actualizacionproy.codProy like '%".$buscar."%') or (pys_actualizacionproy.nombreProy like '%$buscar%') or(pys_solicitudes.idSol like '%$buscar%') or (pys_estadosol.nombreEstSol like '%$buscar%')) 
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
                                        <th>Registrar tiempos</th>
                                        <th>Información Producto/Servicio</th>
                                        <th>Marcar como terminado</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $registra = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
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
                                        <td>'.SolicitudEspecifica::registrarTiempo($idSol,$idUsuario).'</td>
                                        <td>'.SolicitudEspecifica::registrarInfoPyS($idSol, $idUsuario).'</td>
                                        <td>'.SolicitudEspecifica::marcarTerminado($idSol, $idUsuario).'</td>
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
            if ($cod == '1') {
                return $string;
            } else if ($cod == '2') {
                echo $string;
            }
            mysqli_close($connection);
        }

        public static function registrarTiempo($idSol, $idUsuario){
            require('../Core/connection.php');
            $consulta1 = "SELECT pys_tiempos.idTiempo, pys_asignados.idAsig
            FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_tiempos.idAsig = pys_asignados.idAsig
            WHERE pys_tiempos.estTiempo = 1 AND pys_asignados.est = 1 AND pys_asignados.idPersona='".$idUsuario."' AND pys_asignados.idSol='".$idSol."';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $registros1 = mysqli_num_rows($resultado1);
            if ( $registros1 > 0){
                $string = '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" data-position="right" data-tooltip="Ya ha registrado tiempos" onclick="envioData(\'TIE'.$idSol.'\',\'modalResultadoServicio.php\');"><i class="material-icons teal-text">timer</i></a>';
            } else {
                $string = '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" data-position="right" data-tooltip="Aun no ha registrado tiempos"  onclick="envioData(\'TIE'.$idSol.'\',\'modalResultadoServicio.php\');"><i class="material-icons red-text">timer</i></a>';
            }
            mysqli_close($connection);
            return $string;
        }

        public static function registrarInfoPyS($idSol, $idUsuario){
            require('../Core/connection.php');
            $string = "";
            $consulta1 = "SELECT pys_tiempos.idTiempo, pys_asignados.idAsig
            FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_tiempos.idAsig = pys_asignados.idAsig
            WHERE pys_tiempos.estTiempo = 1 AND pys_asignados.est = 1 AND pys_asignados.idPersona='".$idUsuario."' AND pys_asignados.idSol='".$idSol."';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $registros1 = mysqli_num_rows($resultado1);
            $consulta2 = "SELECT * FROM pys_actsolicitudes INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $validarLLenoSer = SolicitudEspecifica::validarResultadoServicio($idSol); 
            if ($validarLLenoSer == true){
                $color = "teal";
                $tooltips = 'data-position="right" data-tooltip="Editar resultado de servicio"';
            } else {
                $color = "red";
                $tooltips = 'data-position="right" data-tooltip="Agregar resultado de servicio"';
            }
            if ( $registros1 > 0){
                if ($datos2['productoOservicio'] == 'SI') {
                    if ($datos2['idEqu'] == 'EQU001') {
                        /**Realizacion */
                        $validarLLenoPro = SolicitudEspecifica::validarResultadoProducto($idSol,$datos2['idEqu']);
                        if ($validarLLenoPro == 1){
                            $color = "teal";
                            $tooltips = 'data-position="right" data-tooltip="Editar resultado de Producto"';
                        } else {
                            $color = "red";
                            $tooltips = 'data-position="right" data-tooltip="Agregar resultado de Producto"';
                        }
                        $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" '.$tooltips.' onclick="envioData(\'REA'.$idSol.'\',\'modalResultadoServicio.php\');" ><i class="material-icons '.$color.'-text">assignment</i></a>';
                    } else if ($datos2['idEqu'] == 'EQU002') {
                        /**Diseño grafico */
                        $validarLLenoPro = SolicitudEspecifica::validarResultadoProducto($idSol,$datos2['idEqu']);
                        if ($validarLLenoPro == 1){
                            $color = "teal";
                            $tooltips = 'data-position="right" data-tooltip="Editar resultado de Producto"';
                        } else {
                            $color = "red";
                            $tooltips = 'data-position="right" data-tooltip="Agregar resultado de Producto"';
                        }
                        $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" '.$tooltips.' onclick="envioData(\'DIS'.$idSol.'\',\'modalResultadoServicio.php\');" ><i class="material-icons '.$color.'-text">assignment</i></a>';
                    } else if ($datos2['idEqu'] == 'EQU003') {
                        /**Soporte */
                        $validarLLenoPro = SolicitudEspecifica::validarResultadoProducto($idSol,$datos2['idEqu']);
                        if ($validarLLenoPro == true){
                            $color = "teal";
                            $tooltips = 'data-position="right" data-tooltip="Editar resultado de Producto"';
                        } else {
                            $color = "red";
                            $tooltips = 'data-position="right" data-tooltip="Agregar resultado de Producto"';
                        }
                        $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" '.$tooltips.' onclick="envioData(\'SOP'.$idSol.'\',\'modalResultadoServicio.php\');" ><i class="material-icons '.$color.'-text">assignment</i></a>';
                    }
                } if ($datos2['productoOservicio'] == 'NO' && $datos2['idEqu'] != 'EQU004' ) {
                    $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" '.$tooltips.' onclick="envioData(\'GEN'.$idSol.'\',\'modalResultadoServicio.php\');" ><i class="material-icons '.$color.'-text">assignment</i></a>';

                }
            }else{
                $string = "";
            }
            return $string;
            mysqli_close($connection);
        }

        public static function marcarTerminado($idSol, $idUsuario){
            require('../Core/connection.php');
            $string = "";
            $consulta = "SELECT pys_tiempos.idTiempo, pys_asignados.idAsig
            FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_tiempos.idAsig = pys_asignados.idAsig
            WHERE pys_tiempos.estTiempo = 1 AND pys_asignados.est = 1 AND pys_asignados.idPersona='".$idUsuario."' AND pys_asignados.idSol='".$idSol."';";
            $resultado = mysqli_query($connection, $consulta);
            $tiempos = mysqli_num_rows($resultado);
            while ($datos = mysqli_fetch_array($resultado)) {
                $idAsig = $datos["idAsig"];
            }
            $consulta1 = "SELECT * FROM pys_asignados WHERE idSol = '$idSol' AND est = 1;";
            $resultado1 = mysqli_query($connection, $consulta1);
            $asignados = mysqli_num_rows($resultado1);
            $consulta2 = "SELECT * FROM pys_actsolicitudes INNER JOIN pys_servicios ON pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $validarLLenoPro = SolicitudEspecifica::validarResultadoProducto($idSol, $datos2['idEqu']);
            $validarLLeno = SolicitudEspecifica::validarResultadoServicio($idSol); 
            if ($validarLLeno == 1 || $validarLLenoPro == 1){
                $color = "teal";
            } else {
                $color = "red";
            }
            if( $tiempos > 0 && $asignados > 1){
                $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" data-position="right" data-tooltip="Marcar como terminado" onclick="envioData(\'TER'.$idAsig.'\',\'modalResultadoServicio.php\');"><i class="material-icons '.$color.'-text">done</i></a>';
            } else if ($tiempos > 0 && $asignados == 1 && ($validarLLeno==1 || $validarLLenoPro == 1) ){
                $string .= '<a href="#modalResultadoServicio" class="modal-trigger tooltipped" data-position="right" data-tooltip="Marcar como terminado" onclick="envioData(\'TER'.$idAsig.'\',\'modalResultadoServicio.php\');" ><i class="material-icons '.$color.'-text">done</i></a>';
            } else {
                $string .= '';
            }
            return $string;
            mysqli_close($connection);
        }

        public static function validarResultadoServicio($idSol){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_resultservicio WHERE idSol = '$idSol' AND est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado == TRUE ){
                $datos = mysqli_fetch_array($resultado);
                $idPlat = $datos['idPlat'];
                $idClProd = $datos['idClProd'];
                $idTProd = $datos['idTProd'];
                $observacion = $datos['observacion'];
                $estudiantesImpac = $datos['estudiantesImpac'];
                $docentesImpac = $datos['docentesImpac'];
                $urlResultado  = $datos['urlResultado'];
                if (($idPlat && $idClProd && $idTProd && $observacion && $estudiantesImpac && $docentesImpac && $urlResultado) != null){
                    return 1;
                } else {
                    return 0;
                } 
            }else {
                return 0;
            }
            mysqli_close($connection);
        }

        public static function validarResultadoProducto($idSol,$equipo){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_productos
            INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
            WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado == TRUE ){
                $datos = mysqli_fetch_array($resultado);
                $nomProduc = $datos['nombreProd'];
                $fechaEntre = $datos['fechEntregaProd'];
                $RED = $datos['descripcionProd'];
                $plat = $datos['idPlat']; 
                $clase = $datos['idClProd']; 
                $tipo = $datos ['idTProd'];
                $url = $datos['urlservidor'];  
                $labor = $datos['observacionesProd']; 
                if ($equipo == "EQU003" || $equipo == "EQU002" ){
                    if (($plat && $clase && $tipo && $labor  && $nomProduc && $RED  && $url  && $fechaEntre) != null){
                        return 1;
                    } else {
                        return 0;
                    }                      
                } else if ($equipo == "EQU001" ){
                    $urlY = $datos['urlyoutubeOficial'];     
                    $urlVimeo = $datos['urlVimeo']; 
                    $minDura = $datos['duracionmin'];  
                    $segDura = $datos['duracionseg'];  
                    $sinopsis = $datos['sinopsis'];
                    $autores = $datos['autorExterno'];
                    if (($plat && $clase && $tipo && $labor  && $nomProduc && $RED  && $url  && $urlY  && $urlVimeo &&  $minDura && $segDura && $sinopsis && $autores && $fechaEntre) != null){
                        return 1;
                    } else {
                        return 0;
                    }   
                }
            } else {
                return 0;
            }
            mysqli_close($connection);
        }

        public static function formResultado($idSol){
            require('../Core/connection.php');
            $consulta= "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_solicitudes.idTSol, pys_tipossolicitud.idTSol, pys_tipossolicitud.nombreTSol, pys_actsolicitudes.idSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.fechAct, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idPersona, pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_Cursosmodulos.idCM, pys_Cursosmodulos.nombreCursoCM, pys_Cursosmodulos.nombreModuloCM, pys_proyectos.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_servicios.idSer, pys_servicios.nombreSer, pys_cursosmodulos.codigoCursoCM, pys_equipos.nombreEqu
            FROM pys_solicitudes
            INNER JOIN pys_tipossolicitud ON pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            INNER JOIN pys_personas ON pys_actsolicitudes.idPersona = pys_personas.idPersona
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_solicitudes.est = '1'  AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1' AND pys_estadosol.est = '1' AND pys_personas.est = '1' AND pys_frentes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_equipos.est = '1' AND pys_solicitudes.idTSol = 'TSOL02' AND pys_solicitudes.idSolIni != '' AND pys_convocatoria.est = '1' AND pys_solicitudes.idSol = '$idSol' 
            ORDER BY pys_proyectos.nombreProy";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function cargarInformacionServicio($idSol){
            require('../Core/connection.php');
            $consulta= "SELECT * FROM pys_resultservicio WHERE idSol = '$idSol' AND est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function cargarInformacionProducto($idSol){
            require('../Core/connection.php');
            $consulta= "SELECT * FROM pys_productos
            INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
            WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function totalTiempo($idSol){
            require('../Core/connection.php');
            $consulta1 = "SELECT pys_tiempos.horaTiempo, pys_tiempos.minTiempo
            FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_tiempos.idAsig = pys_asignados.idAsig
            WHERE pys_tiempos.estTiempo = 1 AND pys_asignados.est = 1 AND pys_asignados.idSol='".$idSol."';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $horas = 0;
            $min = 0;
            while($datos = mysqli_fetch_array($resultado1)){
                $horas += $datos['horaTiempo'];
                $min += $datos['minTiempo'];
            }
            if ($min > 60){
                $horas = intval(( $min/60 )+$horas);
                $min = intval( $min%60);
            } 
            $tiempos;
            $tiempo[0] = $horas;
            $tiempo[1] = $min;
            
            mysqli_close($connection);
            return $tiempo;
        }

        public static function selectRED ($id){
            $string = '<select name="SMRed" id="SMRed" class="asignacion" required>';
            if ($id == null){
                $string .= '<option value="" selected disabled>Seleccione</option>
			        <option value="Si">Si</option>
			        <option value="No">No</option>';
            }else if ($id == 'Si'){
                $string .= '<option value="'.$id.'" selected>Si</option>
                <option value="No">No</option>';
            }else if ($id == 'No'){
                $string .= '<option value="'.$id.'" selected>No</option>
                <option value="Si">Si</option>';
            }
		    $string .= ' </select>
            <label for="SMRed">¿Es una RED?*</label>';
            return $string;
        }
    
        public static function guardarResultadoServicio ($idSol, $idPlat, $idSer, $idClProd, $idTipoPro, $observacion, $estudiantesImpac, $docentesImpac, $otrosImpac, $urlResultado, $motivoAnulacion, $usuario, $duracionhora, $duracionmin){
            require('../Core/connection.php');
            $consulta = "SELECT count(idResultServ), Max(idResultServ) FROM pys_resultservicio";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos=mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
            $countResServ="R00001";	
            }
            else {
            $countResServ='R'.substr((substr($max,3)+100001),1);		
            }
            if ($estudiantesImpac == ""){
                $estudiantesImpac = 0;
            } 
            
            if ($docentesImpac == ""){
                $docentesImpac = 0;
            } 
            
            if ($otrosImpac == ""){
                $otrosImpac = 0;
            } 
            $idResponRegistro =  SolicitudEspecifica::generarIdPersona($usuario);
            $consulta2 = "INSERT INTO pys_resultservicio VALUES ('$countResServ', '$idSol', '$idPlat', '$idSer', '$idClProd', '$idTipoPro', '$observacion', '$estudiantesImpac', '$docentesImpac', '$otrosImpac', '$urlResultado', '$motivoAnulacion', '$idResponRegistro', '$duracionhora', '$duracionmin', now(), '1')";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado && $resultado2){
                echo '<script>alert("Se guardó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {
                echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
            mysqli_close($connection);
        }

        public static function guardarResultadoSoporte($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor){
            require('../Core/connection.php');
            $countProd = SolicitudEspecifica::generarCodigoProducto();
            $idPersona = SolicitudEspecifica::generarIdPersona($usuario);
            if ($fechaEntre != null){
                $fechaEntre ="'".$fechaEntre."'";   
           } else {
                $fechaEntre = "null";
           }
            ECHO $consulta="INSERT INTO pys_productos VALUES ('$countProd', '$idSol', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro','$nomProduc','$red', '', $fechaEntre, now(), '', '', '','$url', '$labor', '', '$idPersona', '', '0', '0', DEFAULT, '1')";
            $resultado = mysqli_query($connection, $consulta);
            ECHO $consulta1="INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro', '$nomProduc','$red', '', $fechaEntre, now(),'', '', '', '$url', '$labor', '', '$idPersona', '', '0', '0', DEFAULT, '', '', '1')";
            $resultado1 = mysqli_query($connection, $consulta1);
            if($resultado && $resultado1){
                echo '<script>alert("Se guardó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {
                echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
            mysqli_close($connection);
        }

        public static function guardarResultadoRealizacion($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor, $sinopsis, $autores, $urlY, $urlVimeo, $min, $seg){
            if ($min == ""){
                $min =0;
            }
            if ($seg == ""){
                $seg =0;
            } 
            if ($fechaEntre != null){
                $fechaEntre ="'".$fechaEntre."'";   
           } else {
                $fechaEntre = "null";
           }
            require('../Core/connection.php');
            $countProd = SolicitudEspecifica::generarCodigoProducto();
            $idPersona = SolicitudEspecifica::generarIdPersona($usuario);
            ECHO $consulta="INSERT INTO pys_productos VALUES ('$countProd', '$idSol', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro','$nomProduc','$red', '', $fechaEntre, now(), '$urlY', '', '$urlVimeo','$url', '$labor', '', '$idPersona', '', $min, $seg, DEFAULT, '1')";
            $resultado = mysqli_query($connection, $consulta);
            echo $consulta1 ="INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro', '$nomProduc','$red', '', $fechaEntre, now(),'$urlY', '', '$urlVimeo', '$url', '$labor', '', '$idPersona', '', $min, $seg, DEFAULT, '$sinopsis', '$autores', '1')";
            $resultado1 = mysqli_query($connection, $consulta1);
            if($resultado && $resultado1){
                echo '<script>alert("Se guardó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {
                echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarResultadoServicio ($idSol, $idPlat, $idSer, $idClProd, $idTipoPro, $observacion, $estudiantesImpac, $docentesImpac, $urlResultado, $usuario){
            require('../Core/connection.php');
            $idPersona = SolicitudEspecifica::generarIdPersona($usuario);
            $consulta = "UPDATE pys_resultservicio SET idPlat = '$idPlat',  idSer ='$idSer', idClProd= '$idClProd', idTProd ='$idTipoPro', observacion= '$observacion', estudiantesImpac = '$estudiantesImpac', docentesImpac = '$docentesImpac', urlResultado = '$urlResultado', idResponRegistro = '$idPersona', fechaCreacion = now() WHERE idSol = '$idSol'";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo '<script>alert("Se actualizó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {         
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
            mysqli_close($connection);

        }

        public static function actualizarResultadoSoporte($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor){
            require('../Core/connection.php');
            $consulta = "SELECT idProd FROM pys_productos WHERE idSol = '$idSol' AND est = 1";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $countProd = $datos['idProd'];
            $consulta1 = "UPDATE pys_actproductos SET est= 2 WHERE idProd = '$countProd' AND est = 1";
            $resultado1 = mysqli_query($connection, $consulta1);
            $idPersona = SolicitudEspecifica::generarIdPersona($usuario);
            $consulta2 ="INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro', '$nomProduc','$red', '', '$fechaEntre', now(),'', '', '', '$url', '$labor', '', '$idPersona', '', '0', '0',DEFAULT , '', '', '1')";
            $resultado2 = mysqli_query($connection, $consulta2);
            if($resultado && $resultado1 && $resultado2){
                echo '<script>alert("Se actualizó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
        }

        public static function actualizarResultadoRealizacion($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor, $sinopsis, $autores, $urlY, $urlVimeo, $min, $seg){
            require('../Core/connection.php');
            $consulta1 = "SELECT idProd FROM pys_productos WHERE idSol = '$idSol' AND est = 1";
            $resultado1 = mysqli_query($connection, $consulta1);
            $datos = mysqli_fetch_array($resultado1);
            $countProd = $datos['idProd'];
            $consulta = "UPDATE pys_actproductos SET est= 2 WHERE idProd = '$countProd' AND est = 1";
            $resultado = mysqli_query($connection, $consulta);
            $idPersona = SolicitudEspecifica::generarIdPersona($usuario);
            $consulta2 ="INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$idPlat', '$idClProd', '$idTipoPro', '$nomProduc','$red', '', '$fechaEntre', now(),'$urlY', '', '$urlVimeo', '$url', '$labor', '', '$idPersona', '', $min, $seg, DEFAULT, '$sinopsis', '$autores', '1')";
            $resultado2 = mysqli_query($connection, $consulta2);
            if($resultado && $resultado1 && $resultado2){
                echo '<script>alert("Se actualizó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            } else {
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/misproductosservicios.php">';
            }
        }
        public static function generarCodigoProducto(){
            require('../Core/connection.php');
            $consulta="SELECT COUNT(idProd),MAX(idProd) FROM pys_productos";
			$resultado = mysqli_query($connection, $consulta);
            while ($result=mysqli_fetch_array($resultado)){
                $count=$result[0];
                $max=$result[1];
            }
            if ($count==0){
                $countProd="P00001";	
            }else {
                $countProd='P'.substr((substr($max,1)+100001),1);		
            }
            return $countProd;
            mysqli_close($connection);
        }

        public static function generarIdPersona($usuario){
            require('../Core/connection.php');
            $consulta1 = "SELECT idPersona FROM pys_login where usrLogin = '$usuario';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $data = mysqli_fetch_array($resultado1);
            $idUsuario = $data[0];
            return $idUsuario;
            mysqli_close($connection);
        }

        public static function comprobraExisResultadoServicio($idSol){
            require('../Core/connection.php');
            $consulta1 = "SELECT idSol FROM pys_resultservicio WHERE idSol = '$idSol' AND est = 1;";
            $resultado1 = mysqli_query($connection, $consulta1);
            $registros1 = mysqli_num_rows($resultado1);
            if ($registros1 > 0){
                return True;
            }else{
                return False;
            }
            mysqli_close($connection);
        }

        public static function comprobraExisResultadoProductos($idSol){
            require('../Core/connection.php');
            $consulta1 = "SELECT idSol FROM pys_productos WHERE idSol = '$idSol' AND est = 1;";
            $resultado1 = mysqli_query($connection, $consulta1);
            $registros1 = mysqli_num_rows($resultado1);
            if ($registros1 > 0){
                return True;
            }else{
                return False;
            }
            mysqli_close($connection);
        }

    }

?>
