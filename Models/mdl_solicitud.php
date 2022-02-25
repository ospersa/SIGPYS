<?php

    class Solicitud
    {
        public static function onLoadEstadoSolicitud($idEstado)
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_estadosol WHERE est = '1' AND idEstSol = '$idEstado';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }
        
        public static function onLoadTipoSolicitud($idTipo)
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_tipossolicitud WHERE est = '1' AND idTSol = '$idTipo';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotalTipos()
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_tipossolicitud WHERE est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table  left">
                            <thead>
                                <tr>
                                    <th>Tipo de Solicitud</th>
                                    <th>Descripción Tipo de Solicitud</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['nombreTSol'].'</td>
                                    <td>'.$datos['descripcionTSol'].'</td>
                                    <td><a href="#modalTipSol" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalTipSol.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function busquedaTipos($busqueda)
        {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT * FROM pys_tipossolicitud WHERE est = '1' AND (nombreTSol LIKE '%$busqueda%' OR descripcionTSol LIKE '%$busqueda%');";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table left">
                            <thead>
                                <tr>
                                    <th>Tipo de Solicitud</th>
                                    <th>Descripción Tipo de Solicitud</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['nombreTSol'].'</td>
                                    <td>'.$datos['descripcionTSol'].'</td>
                                    <td><a href="#modalTipSol" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalTipSol.php'".');" title="Editar"><i class="material-icons teal-text teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function busquedaTotalEstados()
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_estadosol 
            WHERE pys_estadosol.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table left">
                            <thead>
                                <tr>
                                    <th>Estado de Solicitud</th>
                                    <th>Equipo</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $equipo= $datos['descripcionEstSol'];
                    $consulta1 = "SELECT nombreEqu  FROM pys_equipos
                        WHERE est = '1' and idEqu='$equipo';";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    if (mysqli_num_rows($resultado1) > 0) {
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $equipo =$datos1['nombreEqu'];
                        }
                    }
                    echo '      <tr>
                                    <td>'.$datos['nombreEstSol'].'</td>
                                    <td>'.$equipo.'</td>
                                    <td><a href="#modalEstSol" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEstSol.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            }
            mysqli_close($connection);
        }

        public static function busquedaEstados($busqueda)
        {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_equipos.nombreEqu
                FROM pys_estadosol 
                LEFT JOIN pys_equipos ON pys_equipos.idEqu = pys_estadosol.descripcionEstSol AND pys_equipos.est = '1'
                WHERE pys_estadosol.est = '1' AND (nombreEstSol LIKE '%$busqueda%' OR descripcionEstSol LIKE '%$busqueda%' OR nombreEqu LIKE '%$busqueda%')
                ORDER BY pys_estadosol.nombreEstSol ASC;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table left">
                            <thead>
                                <tr>
                                    <th>Estado de Solicitud</th>
                                    <th>Equipo</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['nombreEstSol'].'</td>
                                    <td>'.$datos['nombreEqu'].'</td>
                                    <td><a href="#modalEstSol" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEstSol.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarEstadoSolicitud($nombre, $descripcion)
        {
            require('../Core/connection.php');
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            if ($nombre == null || $nombre == " ") {
                echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
            } else {
                /** Obtenemos el ID de la tabla pys_estadosol */
                $consulta = "SELECT COUNT(idEstSol), MAX(idEstSol) FROM pys_estadosol;";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $count = $datos[0];
                $max = $datos[1];
                if ($count == '0') {
                    $idEstado = "ESS001";
                } else {
                    $idEstado = 'ESS'.substr((substr($max, 3)+1001), 1);
                }
                /** Validación para evitar que se creen registros duplicados */
                $consulta2 = "SELECT * FROM pys_estadosol WHERE nombreEstSol = '$nombre' AND  descripcionEstSol = '$descripcion' AND est = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros = mysqli_num_rows($resultado2) > 0) {
                    echo '<script>alert("Ya existe un registro igual o similar a este. No se guardó la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                } else {
                    /** Insert del nuevo estado en la tabla */
                    $consulta3 = "INSERT INTO pys_estadosol VALUES ('$idEstado', '$nombre', '$descripcion', '1')";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($resultado3) {
                        echo '<script>alert("Se guardó correctamente la información.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                    } else {
                        echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarEstadoSolicitud($cod, $nombre, $descripcion)
        {
            require('../Core/connection.php');
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            if ($nombre == null || $nombre == "" || $nombre == " ") { //Verificación de que no venga el campo de nombre vacío
                echo '<script>alert("Existe algún campo vacío, el registro no fue modificado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
            } else {
                /** Verificación de datos en la tabla */
                $consulta = "SELECT nombreEstSol, descripcionEstSol FROM pys_estadosol WHERE est = '1' AND idEstSol = '$cod';";
                $resultado = mysqli_query($connection, $consulta);
                $infoDB = mysqli_fetch_array($resultado);
                if ($infoDB['nombreEstSol'] == $nombre && $infoDB['descripcionEstSol'] == $descripcion) {
                    echo '<script>alert("La información ingresada es la misma. No se guardó la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                } else {
                    $consulta2 = "UPDATE pys_estadosol SET nombreEstSol = '$nombre', descripcionEstSol = '$descripcion' WHERE est = '1' AND idEstSol = '$cod';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($resultado2) {
                        echo '<script>alert("Registro actualizado correctamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                    } else {
                        echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function registrarTipoSolicitud($nombreTip, $descripcionTip)
        {
            require('../Core/connection.php');
            $nombreTip = mysqli_real_escape_string($connection, $nombreTip);
            $descripcionTip = mysqli_real_escape_string($connection, $descripcionTip);
            if ($nombreTip == null || $nombreTip == " ") {
                echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
            } else {
                /** Obtenemos el ID de la tabla pys_estadosol */
                $consulta = "SELECT COUNT(idTSol), MAX(idTSol) FROM pys_tipossolicitud;";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $count = $datos[0];
                $max = $datos[1];
                if ($count == '0') {
                    $idTipo = "TSOL01";
                } else {
                    $idTipo = 'TSOL'.substr((substr($max, 4)+101), 1);
                }
                /** Validación para evitar que se creen registros duplicados */
                $consulta2 = "SELECT * FROM pys_tipossolicitud WHERE nombreTSol = '$nombreTip' AND est = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros = mysqli_num_rows($resultado2) > 0) {
                    echo '<script>alert("Ya existe un registro igual o similar a este. No se guardó la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                } else {
                    /** Insert del nuevo tipo de solicitud en la tabla */
                    $consulta3 = "INSERT INTO pys_tipossolicitud VALUES ('$idTipo', '$nombreTip', '$descripcionTip', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($resultado3) {
                        echo '<script>alert("Se guardó correctamente la información.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                    } else {
                        echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarSolicitudInicial($idSol, $estSol, $obs, $solicita, $registra, $idCM, $fechaPrevista, $estProy)
        {
            if ($fechaPrevista == null || $fechaPrevista == '') {
                $fechaPrevista = "NULL";
            } else {
                $fechaPrevista = "'$fechaPrevista'";
            }
            require('../Core/connection.php');
            $obs = mysqli_real_escape_string($connection, $obs);
            /** Se obtiene ID de la persona que está realizando el registro */
            $cons = "SELECT idPersona FROM pys_login WHERE usrLogin = '$registra' AND est = '1';";
            $res = mysqli_query($connection, $cons);
            $datosPersona = mysqli_fetch_array($res);
            $registra = $datosPersona['idPersona'];
            $begin = mysqli_query($connection, "BEGIN;");
            if ($estSol == "ESS006" || $estSol == "ESS007") {
                $consulta1 = "SELECT pys_solicitudes.idSol, pys_actsolicitudes.idEstSol, pys_solicitudes.idSolIni, pys_actsolicitudes.observacionAct, pys_actsolicitudes.est FROM pys_actsolicitudes INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol WHERE (pys_actsolicitudes.idEstSol != 'ESS006' AND pys_actsolicitudes.idEstSol != 'ESS007') AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' AND pys_solicitudes.idSolIni = '$idSol';";
                $resultado1 = mysqli_query($connection, $consulta1);
                $registros1 = mysqli_num_rows($resultado1);
                if ($registros1 > 0) {
                    echo '<script>alert("No se puede cambiar estado a Cancelado/Terminado porque tiene '.$registros1.' solicitudes específicas pendientes por cerrar, por favor verifique.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                } else {
                    /** Insert de la información en la tabla pys_actsolicitudes */
                    $consulta2 = "INSERT INTO pys_actsolicitudes (idEstSol, idSol, idCM, idSer, idPersona, idSolicitante, fechPrev, fechAct, ObservacionAct, presupuesto, horas, est) VALUES ('$estSol', '$idSol', '$idCM', 'SER047', '$registra', '$solicita', $fechaPrevista, NOW(), '$obs', '0', '0', '1');";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    /** Obtención de la última actualización en la tabla pys_actsolicitudes para realizar el cambio de estado a 2 */
                    $consulta3 = "SELECT MIN(pys_actsolicitudes.fechAct) FROM pys_actsolicitudes WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$idSol';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $datos3 = mysqli_fetch_array($resultado3);
                    $ultActualizacion = $datos3[0];
                    /** Actualización de estado a 2 en la última actualización de la solicitud */
                    $consulta4 = "UPDATE pys_actsolicitudes SET est = '2' WHERE pys_actsolicitudes.idSol = '$idSol' AND pys_actsolicitudes.fechAct = '$ultActualizacion' AND pys_actsolicitudes.est = '1';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    if ($resultado2 && $resultado4) {
                        $commit = mysqli_query($connection, "COMMIT;");
                        echo '<script>alert("Registro actualizado correctamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    } else {
                        $rollback = mysqli_query($connection, "ROLLBACK;");
                        echo '<script>alert("El registro no pudo ser actualizado. Por favor intente nuevamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    }
                }
            } else {
                /** Insert de la información en la tabla pys_actsolicitudes */
                $consulta2 = "INSERT INTO pys_actsolicitudes (idEstSol, idSol, idCM, idSer, idPersona, idSolicitante, fechPrev, fechAct, ObservacionAct, presupuesto, horas, est) VALUES ('$estSol', '$idSol', '$idCM', 'SER047', '$registra', '$solicita', $fechaPrevista, NOW(), '$obs', '0', '0', '1');";
                $resultado2 = mysqli_query($connection, $consulta2);
                /** Obtención de la última actualización en la tabla pys_actsolicitudes para realizar el cambio de estado a 2 */
                $consulta3 = "SELECT MIN(pys_actsolicitudes.fechAct) FROM pys_actsolicitudes WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$idSol';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $ultActualizacion = $datos3[0];
                /** Actualización de estado a 2 en la última actualización de la solicitud */
                $consulta4 = "UPDATE pys_actsolicitudes SET est = '2' WHERE pys_actsolicitudes.idSol = '$idSol' AND pys_actsolicitudes.fechAct = '$ultActualizacion' AND pys_actsolicitudes.est = '1';";
                $resultado4 = mysqli_query($connection, $consulta4);
                if ($resultado2 && $resultado4) {
                    $commit = mysqli_query($connection, "COMMIT;");
                    echo '<script>alert("Registro actualizado correctamente.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                } else {
                    $rollback = mysqli_query($connection, "ROLLBACK;");
                    echo '<script>alert("El registro no pudo ser actualizado. Por favor intente nuevamente.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarTipoSolicitud($cod, $nombre, $descripcion)
        {
            require('../Core/connection.php');
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            if ($nombre == null || $nombre == "" || $nombre == " ") { //Verificación de que no venga el campo de nombre vacío
                echo '<script>alert("Existe algún campo vacío, el registro no fue modificado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/estSolicitud.php">';
            } else {
                /** Verificación de datos en la tabla */
                $consulta = "SELECT nombreTSol, descripcionTSol FROM pys_tipossolicitud WHERE est = '1' AND idTSol = '$cod';";
                $resultado = mysqli_query($connection, $consulta);
                $infoDB = mysqli_fetch_array($resultado);
                if ($infoDB['nombreTSol'] == $nombre && $infoDB['descripcionTSol'] == $descripcion) {
                    echo '<script>alert("La información ingresada es la misma. No se guardó la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                } else {
                    $consulta2 = "UPDATE pys_tipossolicitud SET nombreTSol = '$nombre', descripcionTSol = '$descripcion' WHERE idTSol = '$cod';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($resultado2) {
                        echo '<script>alert("Registro actualizado correctamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                    } else {
                        echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/tipSolicitud.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function validarDatosSolIni($idSol, $estSol, $obs, $solicita, $fechaPrevista)
        {
            if ($estSol == null || $obs == "" || $obs == " " || $obs == null || $solicita == null) {
                echo '<script>alert("No se pudo actualizar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                return "Cancelar";
            } else {
                $observacionForm = "";
                $observacionDB = "";
                require('../Core/connection.php');
                $split1 = str_split($obs);
                foreach ($split1 as $sp) {
                    if (ctype_space($sp)) {
                        unset($sp);
                    } else {
                        $observacionForm .= $sp;
                    }
                }
                $obs = mysqli_real_escape_string($connection, $obs);
                $consulta = "SELECT idEstSol, idSolicitante, fechPrev, ObservacionAct FROM pys_actsolicitudes WHERE idSol = '$idSol' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $split2 = str_split($datos['ObservacionAct']); // Se realiza conversión del string a un arreglo
                foreach ($split2 as $sp2) { // Ciclo para recorrer el nuevo arreglo e identificar espacios en blanco
                    if (ctype_space($sp2)) { // Si en la posición recorrida encuentra un espacio procede a eliminarlo del arreglo
                        unset($sp2);
                    } else { // Se crea un nuevo string con todo el texto recibido sin espacios en blanco, esto para poder comparar más adelante el texto introducido
                        $observacionDB .= $sp2;
                    }
                }
                if ($estSol == $datos['idEstSol'] && $observacionForm == $observacionDB && $solicita == $datos['idSolicitante'] && $fechaPrevista == $datos['fechPrev']) {
                    echo '<script>alert("La información ingresada es la misma. El registro no fue actualizado.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    return "Cancelar";
                } else {
                    return "Actualizar";
                }
                mysqli_close($connection);
            }
        }

        public static function selectEstadoSolicitud($idEstado, $equipo)
        {
            require('../Core/connection.php');
            $string = "";
            if ($equipo == 'EQU001' || $equipo == 'EQU002') {
                $consulta = "SELECT idEstSol, nombreEstSol FROM pys_estadosol WHERE est = '1' AND descripcionEstSol = '$equipo' ORDER BY nombreEstSol;";
            } else {
                $consulta = "SELECT idEstSol, nombreEstSol FROM pys_estadosol WHERE est = '1' AND descripcionEstSol = '' ORDER BY nombreEstSol;";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                if ($idEstado != null) {
                    $string = " <select name='sltEstadoSolicitud' id='sltEstadoSolicitud'>
                                        <option value=''>Seleccione</option>";
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idEstSol'] == $idEstado) {
                            $string.= " <option value='".$datos['idEstSol']."' selected>".$datos['nombreEstSol']."</option>";
                        } else {
                            $string.= " <option value='".$datos['idEstSol']."'>".$datos['nombreEstSol']."</option>";
                        }
                    }
                    $string .= "</select>
                                <label for='sltEstadoSolicitud'>Estado Producto/Servicio</label>";
                }
            }
            return $string;
            mysqli_close($connection);
        }

        public static function selectEstadoSolicitudInicial($idEstado, $equipo)
        {
            require('../Core/connection.php');
            $string = "";
            if ($equipo == 'EQU001' || $equipo == 'EQU002') {
                $consulta = "SELECT idEstSol, nombreEstSol FROM pys_estadosol WHERE est = '1' AND descripcionEstSol = '$equipo' ORDER BY nombreEstSol;";
            } else {
                $consulta = "SELECT idEstSol, nombreEstSol FROM pys_estadosol WHERE (idEstSol = 'ESS002' OR idEstSol = 'ESS004' OR idEstSol = 'ESS006' ) AND est = '1' AND descripcionEstSol = '' ORDER BY nombreEstSol;";
            }
            
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                if ($idEstado != null) {
                    $string = " <select name='sltEstadoSolicitud' id='sltEstadoSolicitud'>
                                        <option value=''>Seleccione</option>";
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idEstSol'] == $idEstado) {
                            $string.= " <option value='".$datos['idEstSol']."' selected>".$datos['nombreEstSol']."</option>";
                        } else {
                            $string.= " <option value='".$datos['idEstSol']."'>".$datos['nombreEstSol']."</option>";
                        }
                    }
                    $string .= "</select>
                                <label class='teal-text' for='sltEstadoSolicitud'>Estado Proyecto de Contenido digital</label>";
                }
            }
            return $string;
            mysqli_close($connection);
        }
    }
