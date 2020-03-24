<?php

Class SolicitudInicial {

    public static function onLoadSolicitudInicial ($idSolicitud) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_solicitudes.idTSol, pys_tipossolicitud.idTSol, pys_tipossolicitud.nombreTSol, pys_actsolicitudes.idSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.fechAct, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idPersona, pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_Cursosmodulos.idCM, pys_Cursosmodulos.nombreCursoCM, pys_Cursosmodulos.nombreModuloCM, pys_proyectos.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_actualizacionproy.codProy, pys_cursosmodulos.codigoCursoCM, pys_actsolicitudes.idSolicitante, pys_actualizacionproy.idEstProy
            FROM pys_solicitudes
            INNER JOIN pys_tipossolicitud ON pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            INNER JOIN pys_personas ON pys_actsolicitudes.idPersona = pys_personas.idPersona
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1' AND pys_estadosol.est = '1' AND pys_personas.est = '1' AND pys_frentes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_convocatoria.est = '1' AND pys_solicitudes.idTSol = 'TSOL01' AND pys_solicitudes.idSol = '$idSolicitud';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaIniciales ($busqueda) {
        require('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_solicitudes.idTSol, pys_tipossolicitud.idTSol, pys_tipossolicitud.nombreTSol, pys_actsolicitudes.idSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.fechAct, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idPersona, pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_proyectos.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_actualizacionproy.codProy, pys_cursosmodulos.codigoCursoCM, pys_actsolicitudes.idSolicitante
            FROM pys_solicitudes
            INNER JOIN pys_tipossolicitud ON pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            INNER JOIN pys_personas ON pys_actsolicitudes.idPersona = pys_personas.idPersona
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1' AND pys_estadosol.est = '1' AND pys_personas.est = '1' AND pys_frentes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_convocatoria.est = '1' AND pys_solicitudes.idTSol = 'TSOL01' AND (pys_solicitudes.idSol LIKE '%$busqueda' OR pys_estadosol.nombreEstSol LIKE '%$busqueda%' OR pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%')
            ORDER BY pys_solicitudes.fechSol DESC;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table  left" id="tblProyecto">
                        <thead>
                            <tr>
                                <th>CSI</th>
                                <th>Estado Sol.Inicial</th>
                                <th>Código Proyecto Conecta-TE</th>
                                <th>Proyecto</th>
                                <th>Registró</th>
                                <th>Solicitante</th>
                                <th>Descripción Sol. Inicial</th>
                                <th>Fecha Prevista para Entrega</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
                                <th>Editar</th>
                                <th>Crear PS</th>
                                <th>Asignar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos['idSol'].'</td>
                                <td>'.$datos['nombreEstSol'].'</td>
                                <td>'.$datos['codProy'].'</td>
                                <td>'.$datos['nombreProy'].'</td>
                                <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>';
                /** Validación del nombre del solicitante */
                $consulta2 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres 
                    FROM pys_personas
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSolicitante = pys_personas.idPersona
                    WHERE pys_personas.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '".$datos['idSolicitante']."';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $nombreSolicitante = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                echo '          <td>'.$nombreSolicitante.'</td>
                                <td>'.$datos['ObservacionAct'].'</td>
                                <td>'.$datos['fechPrev'].'</td>';
                /** Validación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
                if ($datos['fechSol'] != $datos['fechAct']) {
                    echo '      <td>'.$datos['fechSol'].'</td>
                                <td class="teal lighten-2">'.$datos['fechAct'].'</td>';
                } else {
                    echo '      <td>'.$datos['fechSol'].'</td>
                                <td>'.$datos['fechAct'].'</td>';
                }
                echo '          <td><a href="#modalSolicitudInicial" class="modal-trigger" onclick="envioData(\'SI'.$datos['idSol'].'\',\'modalSolicitudInicial.php\');" title="Editar Solicitud"><i class="material-icons teal-text">edit</i></a></td>';
                /** Validación del estado de la solicitud ESS006=Terminado; ESS007=Cancelado, si pertenece a esos código no se permitirá la asignación de personas ni la creación de nuevos PS*/
                if ($datos['idEstSol'] == 'ESS006' || $datos['idEstSol'] == 'ESS007') {
                    echo '      <td></td>
                                <td></td>';
                } else {
                    /** Comprobación para identificar si ya hay productos/servicios creados a esta solicitud y pintar de color diferente */
                    $consulta3 = "SELECT idSolIni FROM pys_solicitudes WHERE est = '1' AND idSolIni = '".$datos['idSol']."';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($registros = mysqli_num_rows($resultado3)) {
                        echo '  <td><a href="solicitudEspecifica.php?cod='.$datos['idSol'].'" title="Ya existen Productos/Servicios Creados"><i class="material-icons teal-text">add_box</i></a></td>';
                    } else {
                        echo '  <td><a href="solicitudEspecifica.php?cod='.$datos['idSol'].'" title="Crear Producto/Servicio"><i class="material-icons red-text">add_box</i></a></td>';
                    }
                    /** Comprobación para identificar si ya hay personas asignadas */
                    $consulta4 = "SELECT idSol FROM pys_asignados WHERE est = '1' AND idSol = '".$datos['idSol']."';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    if ($registros4 = mysqli_num_rows($resultado4)) {
                        echo '  <td><a href="asignados.php?cod2='.$datos['idSol'].'" title="Ya existen personas asignadas"><i class="material-icons teal-text">person_add</i></a></td>';
                    } else {
                        echo '  <td><a href="asignados.php?cod2='.$datos['idSol'].'" title="Asignar personas"><i class="material-icons red-text">person_add</i></a></td>';
                    }
                }
                echo '      </tr>';
            }
            echo '      </tbody>
                    </table>';
        }else{
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        mysqli_close($connection);
    }

    public static function busquedaTotalIniciales () {
        require('../Core/connection.php');
        $consulta = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_solicitudes.idTSol, pys_tipossolicitud.idTSol, pys_tipossolicitud.nombreTSol, pys_actsolicitudes.idSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.fechAct, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idPersona, pys_estadosol.idEstSol, pys_estadosol.nombreEstSol, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_proyectos.idProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_actualizacionproy.codProy, pys_cursosmodulos.codigoCursoCM, pys_actsolicitudes.idSolicitante
            FROM pys_solicitudes
            INNER JOIN pys_tipossolicitud ON pys_solicitudes.idTSol = pys_tipossolicitud.idTSol
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            INNER JOIN pys_personas ON pys_actsolicitudes.idPersona = pys_personas.idPersona
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1' AND pys_estadosol.est = '1' AND pys_personas.est = '1' AND pys_frentes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_convocatoria.est = '1' AND pys_solicitudes.idTSol = 'TSOL01'
            ORDER BY pys_solicitudes.fechSol DESC;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table  left" id="tblProyecto">
                        <thead>
                            <tr>
                                <th>CSI</th>
                                <th>Estado Sol.Inicial</th>
                                <th>Código Proyecto Conecta-TE</th>
                                <th>Proyecto</th>
                                <th>Registró</th>
                                <th>Solicitante</th>
                                <th>Descripción Sol. Inicial</th>
                                <th>Fecha Prevista para Entrega</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
                                <th>Editar</th>
                                <th>Crear PS</th>
                                <th>Asignar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos['idSol'].'</td>
                                <td>'.$datos['nombreEstSol'].'</td>
                                <td>'.$datos['codProy'].'</td>
                                <td>'.$datos['nombreProy'].'</td>
                                <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>';
                /** Validación del nombre del solicitante */
                $consulta2 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres 
                    FROM pys_personas
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSolicitante = pys_personas.idPersona
                    WHERE pys_personas.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '".$datos['idSolicitante']."';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $nombreSolicitante = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                echo '          <td>'.$nombreSolicitante.'</td>
                                <td>'.$datos['ObservacionAct'].'</td>
                                <td>'.$datos['fechPrev'].'</td>';
                /** Validación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
                if ($datos['fechSol'] != $datos['fechAct']) {
                    echo '      <td>'.$datos['fechSol'].'</td>
                                <td class="teal lighten-2">'.$datos['fechAct'].'</td>';
                } else {
                    echo '      <td>'.$datos['fechSol'].'</td>
                                <td>'.$datos['fechAct'].'</td>';
                }
                echo '          <td><a href="#modalSolicitudInicial" class="modal-trigger" onclick="envioData(\'SI'.$datos['idSol'].'\',\'modalSolicitudInicial.php\');" title="Editar Solicitud"><i class="material-icons teal-text">edit</i></a></td>';
                /** Validación del estado de la solicitud ESS006=Terminado; ESS007=Cancelado, si pertenece a esos código no se permitirá la asignación de personas ni la creación de nuevos PS*/
                if ($datos['idEstSol'] == 'ESS006' || $datos['idEstSol'] == 'ESS007') {
                    echo '      <td></td>
                                <td></td>';
                } else {
                    /** Comprobación para identificar si ya hay productos/servicios creados a esta solicitud y pintar de color diferente */
                    $consulta3 = "SELECT idSolIni FROM pys_solicitudes WHERE est = '1' AND idSolIni = '".$datos['idSol']."';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($registros = mysqli_num_rows($resultado3)) {
                        echo '  <td><a href="solicitudEspecifica.php?cod='.$datos['idSol'].'" title="Ya existen Productos/Servicios Creados"><i class="material-icons teal-text">add_box</i></a></td>';
                    } else {
                        echo '  <td><a href="solicitudEspecifica.php?cod='.$datos['idSol'].'" title="Crear Producto/Servicio"><i class="material-icons red-text">add_box</i></a></td>';
                    }
                    /** Comprobación para identificar si ya hay personas asignadas */
                    $consulta4 = "SELECT idSol FROM pys_asignados WHERE est = '1' AND idSol = '".$datos['idSol']."';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    if ($registros4 = mysqli_num_rows($resultado4)) {
                        echo '  <td><a href="asignados.php?cod2='.$datos['idSol'].'" title="Ya existen personas asignadas"><i class="material-icons teal-text">person_add</i></a></td>';
                    } else {
                        echo '  <td><a href="asignados.php?cod2='.$datos['idSol'].'" title="Asignar personas"><i class="material-icons red-text">person_add</i></a></td>';
                    }
                }
                echo '      </tr>';
            }
            echo '      </tbody>
                    </table>';
        }else{
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda.</h6></div>';
        }
        mysqli_close($connection);
    }

    public static function selectProyecto($busqueda) {
        require('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.idFrente, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_actualizacionproy.descripcionProy
            FROM pys_actualizacionproy
            WHERE pys_actualizacionproy.est = '1' AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0 && $busqueda != null) {
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
    
    public static function selectSolicitante ($idPersona) {
        require('../Core/connection.php');
        $consulta = "SELECT idPersona, apellido1, apellido2, nombres FROM pys_personas WHERE est = '1' ORDER BY apellido1;";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            $string = '     <select name="sltSolicitante" id="sltSolicitante">';
            $string .= '        <option value="" disabled selected>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idPersona'] == $idPersona) {
                    $string .= '<option value="'.$datos['idPersona'].'" selected>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</option>';
                } else {
                    $string .= '<option value="'.$datos['idPersona'].'">'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</option>';
                }
            }
            $string .= '</select>
                        <label for="sltSolicitante">Solicitante</label>';
        }
        return $string;
        mysqli_close($connection);
    }

    public static function preLoadSolicitudInicial () {
        require('../Core/connection.php');
        $consulta = "SELECT nombreTSol, idTSol FROM pys_tipossolicitud WHERE est = '1' AND nombreTSol = 'Inicial';";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos = mysqli_fetch_array($resultado)) {
            $array1 = ['nombreTipo' => $datos['nombreTSol'], 'idTipo' => $datos['idTSol']];
        }
        $consulta2 = "SELECT nombreEstSol, idEstSol FROM pys_estadosol WHERE est = '1' AND idEstSol = 'ESS003';";
        $resultado2 = mysqli_query($connection, $consulta2);
        while ($datos2 = mysqli_fetch_array($resultado2)){
            $array2 = ['nombreEstado' => $datos2['nombreEstSol'], 'idEstado' => $datos2['idEstSol']];
        }
        $consulta3 = "SELECT COUNT(idSol), MAX(idSol) FROM pys_solicitudes;";
        $resultado3 = mysqli_query($connection, $consulta3);
        $datos3 = mysqli_fetch_array($resultado3);
        $count = $datos3[0];
        $max = $datos3[1];
        if ($count == 0) {
            $idSol = "S00001";
        } else {
            $idSol = 'S'.substr((substr($max,1)+100001),1);
        }
        $array3 = ['idSolicitud' => $idSol];
        $array = array_merge($array1, $array2, $array3);
        return $array;
        mysqli_close($connection);
    }

    public static function registrarSolicitudInicial($idSolicitud, $idTipo, $idEstado, $proyecto, $fecha, $descripcion, $solicita, $registra) {
        require('../Core/connection.php');
        $descripcion = mysqli_real_escape_string($connection, $descripcion);
        $proyecto = mysqli_real_escape_string($connection, $proyecto);
        if ($idSolicitud == null || $idTipo == null || $idEstado == null || $proyecto == null || $descripcion == null || $solicita == null || $registra == null) {
            echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
            echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
        } else {
            /** Se obtiene ID de la persona que está realizando el registro */
            $cons = "SELECT idPersona FROM pys_login WHERE usrLogin = '$registra' AND est = '1';";
            $res = mysqli_query($connection, $cons);
            $datosPersona = mysqli_fetch_array($res);
            $registra = $datosPersona['idPersona'];
            /** Consulta para evitar que se creen registros duplicados en la tabla de solicitudes */
            $consulta = "SELECT idSol FROM pys_solicitudes WHERE idSol = '$idSolicitud';";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '<script>alert("Ya existe una solicitud creada con el identificador: '.$idSolicitud.', por favor actualice la página.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
            } else {
                /** Obtención del id de la tabla cursosModulos con respecto al proyecto seleccionado */
                $begin = mysqli_query($connection, "BEGIN;"); // Preparamos los datos en caso de que falle la conexión con el servidor o se presente algún error en el proceso de guardado de los datos, para no afectar la información existente en la DB
                $consulta2 = "SELECT idCM FROM pys_cursosmodulos WHERE estProy = '1' AND idCurso = 'CR0051' AND nombreCursoCM = '' AND idProy = '$proyecto';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $idCM = $datos2['idCM'];
                /** Código para inserción de los datos en la tabla pys_solicitudes */
                $consulta3 = "INSERT INTO pys_solicitudes VALUES ('$idSolicitud', '$idTipo', '', '', '$registra', '$solicita', '$descripcion', NOW(), '1');";
                $resultado3 = mysqli_query($connection, $consulta3);
                /** Código para inserción de los datos en la tabla pys_actsolicitudes */
                $consulta4 = "INSERT INTO pys_actsolicitudes (idEstSol, idSol, idCM, idSer, idPersona, idSolicitante, fechPrev, fechAct, ObservacionAct, presupuesto, horas, est) VALUES ('$idEstado', '$idSolicitud','$idCM', 'SER047', '$registra', '$solicita', '$fecha', NOW(), '$descripcion', '0', '0', '1');";
                $resultado4 = mysqli_query($connection, $consulta4);
                if ($resultado3 && $resultado4) {
                    /** Si ambas consultas son exitosas hacemos commit en la base de datos y guardamos los cambios */
                    $commit = mysqli_query($connection, "COMMIT;");
                    if ($commit) {
                        echo '<script>alert("Se guardó correctamente la información.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    } else {
                        echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    }
                } else {
                    $rollback = mysqli_query($connection, "ROLLBACK;");
                    if ($rollback) {
                        echo '<script>alert("Ocurrió un error al intentar actualizar la información. Por favor intente nuevamente.")</script>';
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudInicial.php">';
                    }
                }
            }
        }
        mysqli_close($connection);
    }

}

?>