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
                $string = ' <table class="responsive-table centered">
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
       
            inner join pys_tipossolicitud on pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            inner join pys_actsolicitudes on pys_solicitudes.idSol = pys_actsolicitudes.idSol
            inner join pys_estadosol on pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            inner join pys_cursosmodulos on pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            inner join pys_proyectos on pys_cursosmodulos.idProy = pys_proyectos.idProy
            inner join pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
            inner join pys_frentes on pys_proyectos.idFrente = pys_frentes.idFrente
            inner join pys_servicios on pys_actsolicitudes.idSer = pys_servicios.idSer
            inner join pys_equipos on pys_servicios.idEqu = pys_equipos.idEqu
            inner join pys_asignados on pys_asignados.idsol = pys_actsolicitudes.idSol
            inner join pys_personas on pys_asignados.idResponRegistro = pys_personas.idPersona
            inner join pys_convocatoria on pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
	   	   
	        where pys_solicitudes.est = '1' and pys_actsolicitudes.est = '1' and pys_actualizacionproy.est = '1' and pys_tipossolicitud.est = '1' and pys_estadosol.est = '1' and pys_personas.est = '1' and pys_frentes.est = '1' and pys_cursosmodulos.estProy = '1' and pys_cursosmodulos.estCurso = '1' and pys_convocatoria.est = '1' and pys_equipos.est = '1' and pys_asignados.est = '1' and   pys_asignados.idPersona = '".$idUsuario."' and pys_solicitudes.idTSol = 'TSOL02' and ((pys_estadosol.idEstSol != 'ESS001') and (pys_estadosol.idEstSol != 'ESS006') and (pys_estadosol.idEstSol != 'ESS007')) and ((pys_actualizacionproy.codProy like '%".$buscar."%') or (pys_actualizacionproy.nombreProy like '%$buscar%') or(pys_solicitudes.idSol like '%$buscar%') or (pys_estadosol.nombreEstSol like '%$buscar%')) 
            ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table centered">
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
                                        <th>Marcar como terminado</th>
                                        <th>Cerrar producto/servicio</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $registra = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>';
                                        $idSol = $datos['idSol'];
                    /** Validación sí se ha registrado tiempo para el producto/servicio */
                    $consulta1 = "SELECT pys_tiempos.idTiempo, pys_asignados.idAsig
                    FROM pys_tiempos 
                    INNER JOIN pys_asignados ON pys_tiempos.idAsig = pys_asignados.idAsig
                    WHERE pys_tiempos.estTiempo = 1 AND pys_asignados.est = 1 AND pys_asignados.idPersona='".$idUsuario."' AND pys_asignados.idSol='".$idSol."';";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    $registros1 = mysqli_num_rows($resultado1);
                    while ($datos2 = mysqli_fetch_array($resultado1)) {
                        $idAsig = $datos2["idAsig"];
                    }
                    if( $registros1 > 0){
                        $string .= '<td><a href="#modalTiempos" class="modal-trigger" onclick="envioData(\''.$datos['idSol'].'\',\'modalTiempos.php\');" title="Ya ha registrado tiempos"><i class="material-icons teal-text">timer</i></a></td>';
                    } else {
                        $string .= '<td><a href="#modalTiempos" class="modal-trigger" onclick="envioData(\''.$datos['idSol'].'\',\'modalTiempos.php\');" title="Aun no ha registrado tiempos"><i class="material-icons red-text">timer</i></a></td>';
                    }

                    /** Sí ha registrado tiempos y el estado de asignacion es diferente a 0 o 2 se habilita para marcar como terminada la labor*/
                    if( $registros1 > 0){
                        $consulta2 = "SELECT est FROM pys_asignados WHERE idPersona = '".$idUsuario."' AND idSol = '".$datos['idSol']."' AND idAsig = '".$idAsig."' AND est = 1;";
                        $resultado2 = mysqli_query($connection, $consulta2);
                        $registros2 = mysqli_num_rows($resultado2);
                        if ($registros2 > 0) {
                            $string .= '<td><a href="#modalTerminar" class="modal-trigger" onclick="envioData(\''.$idAsig.'\',\'modalTerminar.php\');" title="Marcar como terminado"><i class="material-icons red-text">done</i></a></td>';
                        }
                    } else {
                        $string .= '<td></td>';
                    }

                    /** Sí ha registrado tiempos se activa la opción de cerrar producto/servicio*/
                    if( $registros1 > 0){
                        $consulta3 = "SELECT est FROM pys_asignados WHERE idPersona = '".$idUsuario."' AND idSol = '".$datos['idSol']."' AND idAsig = '".$idAsig."' AND est = 1;";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        $registros3 = mysqli_num_rows($resultado3);
                        if ($registros3 > 0) {
                            $string .= '<td><a href="#modalTerminar" class="modal-trigger" onclick="envioData(\''.$idAsig.'\',\'modalTerminar.php\');" title="Marcar como terminado"><i class="material-icons red-text">done</i></a></td>
                            </tr>';
                        }
                    } else {
                        $string .= '<td></td></tr>';
                    }
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

    }

?>