<?php

class Proyecto {

    public static function busquedaTotalProyecto () {
        require('../Core/connection.php');
        echo '  <table class="responsive-table left" id="tblProyecto">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Facultad - Departamento</th>
                            <th>Estado</th>
                            <th>Tipo</th>
                            <th>Etapa</th>
                            <th>Cod. Conecta-TE</th>
                            <th>Proyecto</th>
                            <th>Convocatoria</th>
                            <th>Nombre Corto</th>
                            <th>Presupuesto</th>
                            <th>Inicio</th>
                            <th>Cierre</th>
                            <th>*Fecha Colciencias</th>
                            <th>Creación</th>
                            <th>Última Actualización</th>
                            <th>Editar</th>
                            <th>Asignar Personas</th>
                            <th>Agregar Colciencias</th>
                        </tr>
                    </thead>
                    <tbody>';
        $consulta = "SELECT pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_tiposproy.nombreTProy, pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_estadoproy.nombreEstProy, pys_etapaproy.nombreEtaProy, pys_proyectos.idProy, pys_actualizacionproy.proyecto, pys_actualizacionproy.codProy, pys_actualizacionproy.idEstProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy, pys_actualizacionproy.fechaIniProy, pys_actualizacionproy.fechaCierreProy, pys_actualizacionproy.idConvocatoria, pys_proyectos.fechaCreacionProy, pys_actualizacionproy.fechaActualizacionProy, pys_actualizacionproy.idResponRegistro, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.presupuestoProy, pys_actualizacionproy.financia, pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.fechaColciencias
            FROM pys_actualizacionproy
            INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
            INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
            INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_tiposProy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
            INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente
            INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona
            INNER JOIN pys_facdepto ON pys_actualizacionproy.idFacDepto=pys_facdepto.idFacDepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_convocatoria.est = '1'
            ORDER BY pys_proyectos.fechaCreacionProy DESC;";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos = mysqli_fetch_array($resultado)) {
            $presupuesto = ($datos['presupuestoProy'] == '') ? 0 : $datos['presupuestoProy'];
            echo "      <tr>
                            <td>".$datos['nombreEnt']."</td>
                            <td>".$datos['facDeptoFacultad']." - ".$datos['facDeptoDepartamento']."</td>
                            <td>".$datos['nombreEstProy']."</td>
                            <td>".$datos['nombreTProy']."</td>
                            <td>".$datos['nombreEtaProy']."</td>
                            <td>".$datos['codProy']."</td>
                            <td>".$datos['nombreProy']."</td>
                            <td>".$datos['nombreConvocatoria']."</td>
                            <td>".$datos['nombreCortoProy']."</td>
                            <td>$ ".number_format($presupuesto, '0', ',', '.')."</td>
                            <td>".$datos['fechaIniProy']."</td>
                            <td>".$datos['fechaCierreProy']."</td>
                            <td>".$datos['fechaColciencias']."</td>";
            /** Comparación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
            if ($datos['fechaCreacionProy'] != $datos['fechaActualizacionProy']) {
                echo "      <td>".$datos['fechaCreacionProy']."</td>
                            <td class='teal lighten-2'>".$datos['fechaActualizacionProy']."</td>";
            } else {
                echo "      <td>".$datos['fechaCreacionProy']."</td>
                            <td>".$datos['fechaActualizacionProy']."</td>";
            }
            if ($datos['idEstProy'] == 'ESP002') { // ESP002 - Proyecto en Estado Cancelado
                echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>
                            <td></td>
                            <td></td>';    
            } else if ($datos['idEstProy'] == 'ESP004') { // ESP004 - Proyecto en Estado Terminado
                echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>
                            <td></td>';
                $idProy = $datos['idProy'];
                $consulta2 = "SELECT idProy FROM pys_colciencias WHERE est = '1' AND idProy = '$idProy';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $idProyColciencias = $datos2[0];
                if ($idProy == $idProyColciencias) {
                    echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=1" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Proyecto Existente en Colciencias"><i class="material-icons teal-text">library_add</i></a></td>';
                } else {
                    echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=2" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Agregar a Colciencias"><i class="material-icons red-text">library_add</i></a></td>';
                }
            } else {
                echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>';
                /** Consulta para obtener el ID del proyecto en la tabla pys_asignados */
                $consulta2 = "SELECT idProy FROM pys_asignados WHERE idSol = '' AND idCurso = '' AND est = '1' AND idProy = '".$datos['idProy']."';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                    echo '  <td><a href="asignados.php?cod1='.$datos[10].'" onclick="envioData('."'$datos[10]'".','."'asignados.php'".');" title="Ya existen personas asignadas"><i class="material-icons teal-text">person_add</i></a></td>';
                } else {
                    echo '  <td><a href="asignados.php?cod1='.$datos[10].'" onclick="envioData('."'$datos[10]'".','."'asignados.php'".');" title="Asignar personas"><i class="material-icons red-text">person_add</i></a></td>';
                }
                $consulta3 = "SELECT idProy FROM pys_colciencias WHERE est = '1' AND idProy = '".$datos['idProy']."';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $idProyColciencias = $datos3[0];
                if ($datos['idProy'] == $idProyColciencias) {
                    echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=1" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Proyecto Existente en Colciencias"><i class="material-icons teal-text">library_add</i></a></td>';
                } else {
                    echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=2" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Agregar a Colciencias"><i class="material-icons red-text">library_add</i></a></td>';
                }
            }
            echo "      </tr>";
        }
        echo'       </tbody>
                </table>';
        mysqli_close($connection);
    }

    public static function busqueda ($busqueda) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_tiposproy.nombreTProy, pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_estadoproy.nombreEstProy, pys_etapaproy.nombreEtaProy, pys_proyectos.idProy, pys_actualizacionproy.proyecto, pys_actualizacionproy.codProy, pys_actualizacionproy.idEstProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy, pys_actualizacionproy.fechaIniProy, pys_actualizacionproy.fechaCierreProy, pys_actualizacionproy.idConvocatoria, pys_proyectos.fechaCreacionProy, pys_actualizacionproy.fechaActualizacionProy, pys_actualizacionproy.idResponRegistro, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.presupuestoProy, pys_actualizacionproy.financia, pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.fechaColciencias
            FROM pys_actualizacionproy
            INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
            INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
            INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_tiposProy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
            INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente
            INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona
            INNER JOIN pys_facdepto ON pys_actualizacionproy.idFacDepto=pys_facdepto.idFacDepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_convocatoria.est = '1'
            AND (
                (pys_facdepto.facDeptoFacultad LIKE '%".$busqueda."%') OR
                (pys_facdepto.facDeptoDepartamento LIKE '%".$busqueda."%') OR
                (pys_frentes.nombreFrente LIKE '%".$busqueda."%') OR
                (pys_actualizacionproy.codProy LIKE '%".$busqueda."%') OR
                (pys_actualizacionproy.nombreCortoProy LIKE '%".$busqueda."%') OR
                (pys_estadoproy.nombreEstProy LIKE '%".$busqueda."%'))
            ORDER BY pys_proyectos.fechaCreacionProy DESC;";
        $resultado = mysqli_query($connection, $consulta);
        $count=mysqli_num_rows($resultado);
        if($count > 0){
            echo '  <table class="responsive-table left" id="tblProyecto">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Facultad - Departamento</th>
                    <th>Estado</th>
                    <th>Tipo</th>
                    <th>Etapa</th>
                    <th>Cod. Conecta-TE</th>
                    <th>Proyecto</th>
                    <th>Convocatoria</th>
                    <th>Nombre Corto</th>
                    <th>Presupuesto</th>
                    <th>Inicio</th>
                    <th>Cierre</th>
                    <th>*Fecha Colciencias</th>
                    <th>Creación</th>
                    <th>Última Actualización</th>
                    <th>Editar</th>
                    <th>Asignar Personas</th>
                    <th>Agregar Colciencias</th>
                </tr>
            </thead>
            <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $presupuesto = ($datos['presupuestoProy'] == '') ? 0 : $datos['presupuestoProy'];
                echo "      <tr>
                                <td>".$datos['nombreEnt']."</td>
                                <td>".$datos['facDeptoFacultad']." - ".$datos['facDeptoDepartamento']."</td>
                                <td>".$datos['nombreEstProy']."</td>
                                <td>".$datos['nombreTProy']."</td>
                                <td>".$datos['nombreEtaProy']."</td>
                                <td>".$datos['codProy']."</td>
                                <td>".$datos['nombreProy']."</td>
                                <td>".$datos['nombreConvocatoria']."</td>
                                <td>".$datos['nombreCortoProy']."</td>
                                <td>$ ".number_format($presupuesto, '0', '.', ',')."</td>
                                <td>".$datos['fechaIniProy']."</td>
                                <td>".$datos['fechaCierreProy']."</td>
                                <td>".$datos['fechaColciencias']."</td>";
                /** Comparación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
                if ($datos['fechaCreacionProy'] != $datos['fechaActualizacionProy']) {
                    echo "      <td>".$datos['fechaCreacionProy']."</td>
                                <td class='teal lighten-2'>".$datos['fechaActualizacionProy']."</td>";
                } else {
                    echo "      <td>".$datos['fechaCreacionProy']."</td>
                                <td>".$datos['fechaActualizacionProy']."</td>";
                }
                if ($datos['idEstProy'] == 'ESP002') { // ESP002 - Proyecto en Estado Cancelado
                    echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>
                                <td></td>
                                <td></td>';    
                } else if ($datos['idEstProy'] == 'ESP004') { // ESP004 - Proyecto en Estado Terminado
                    echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>
                                <td></td>';
                    $idProy = $datos['idProy'];
                    $consulta2 = "SELECT idProy FROM pys_colciencias WHERE est = '1' AND idProy = '$idProy';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $idProyColciencias = $datos2[0];
                    if ($idProy == $idProyColciencias) {
                        echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=1" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Proyecto Existente en Colciencias"><i class="material-icons teal-text">library_add</i></a></td>';
                    } else {
                        echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=2" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Agregar a Colciencias"><i class="material-icons red-text">library_add</i></a></td>';
                    }
                } else {
                    echo '      <td><a href="#modalProyecto" class="modal-trigger" onclick="envioData('."'$datos[10]'".','."'modalProyecto.php'".');" title="Editar Proyecto"><i class="material-icons teal-text">edit</i></a></td>';
                    /** Consulta para obtener el ID del proyecto en la tabla pys_asignados */
                    $consulta2 = "SELECT idProy FROM pys_asignados WHERE idSol = '' AND idCurso = '' AND est = '1' AND idProy = '".$datos['idProy']."';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                        echo '  <td><a href="asignados.php?cod1='.$datos[10].'" onclick="envioData('."'$datos[10]'".','."'asignados.php'".');" title="Ya existen personas asignadas"><i class="material-icons teal-text">person_add</i></a></td>';
                    } else {
                        echo '  <td><a href="asignados.php?cod1='.$datos[10].'" onclick="envioData('."'$datos[10]'".','."'asignados.php'".');" title="Asignar personas"><i class="material-icons red-text">person_add</i></a></td>';
                    }
                    $consulta3 = "SELECT idProy FROM pys_colciencias WHERE est = '1' AND idProy = '".$datos['idProy']."';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $datos3 = mysqli_fetch_array($resultado3);
                    $idProyColciencias = $datos3[0];
                    if ($datos['idProy'] == $idProyColciencias) {
                        echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=1" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Proyecto Existente en Colciencias"><i class="material-icons teal-text">library_add</i></a></td>';
                    } else {
                        echo '  <td><a href="colcienciasProy.php?cod='.$datos[10].'&cod2=2" onclick="envioData('."'$datos[10]'".','."'colcienciasProy.php'".');" title="Agregar a Colciencias"><i class="material-icons red-text">library_add</i></a></td>';
                    }
                }
                echo "      </tr>"; 
            }   
            echo'       </tbody>
                    </table>';
        }else{
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }        
        mysqli_close($connection);
    }

    public static function onLoadEtapa ($idEtapa) {
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_etapaproy
            INNER JOIN pys_tiposproy ON pys_tiposproy.idTProy = pys_etapaproy.idTProy
            WHERE idEtaProy = '$idEtapa' AND pys_etapaproy.est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function onLoadProyecto ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_proyectos.idProy, pys_tiposproy.idTProy, pys_tiposproy.nombreTProy, pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_estadoproy.idEstProy, pys_estadoproy.nombreEstProy, pys_etapaproy.idEtaProy, pys_etapaproy.nombreEtaProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy, pys_actualizacionproy.fechaCierreProy, pys_actualizacionproy.fechaActualizacionProy, pys_actualizacionproy.fechaIniProy, pys_actualizacionproy.idConvocatoria, pys_proyectos.fechaCreacionProy, pys_actualizacionproy.fechaActualizacionproy, pys_actualizacionproy.idResponRegistro, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.proyecto, pys_entidades.idEnt, pys_entidades.nombreEnt, pys_facdepto.idFac, pys_facdepto.idDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_actualizacionproy.presupuestoProy, pys_actualizacionproy.financia, pys_actualizacionproy.fechaColciencias, pys_convocatoria.nombreConvocatoria, pys_facdepto.idFac, pys_actualizacionproy.proyecto, pys_actualizacionproy.semAcompanamiento, pys_actualizacionproy.idCelula
            FROM pys_actualizacionproy
            INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
            INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
            INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_tiposProy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
            INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente 
            INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona 
            INNER JOIN pys_facdepto ON pys_actualizacionproy.idFacDepto=pys_facdepto.idFacDepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
            WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_convocatoria.est = '1' AND pys_actualizacionproy.idProy = '$idProy';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaTotalEstado () {
        require('../Core/connection.php');
        $consulta = "SELECT nombreEstProy, descripcionEstProy FROM pys_estadoproy WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        echo '  <table class="responsive-table left">
                    <thead>
                        <tr>
                            <th>Estado del proyecto</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>';
        while ($datos = mysqli_fetch_array($resultado)) {
            echo "      <tr>
                            <td>$datos[0]</td>
                            <td>$datos[1]</td>
                        </tr>";
        }
        echo '      </tbody>
                </table>';
        mysqli_close($connection);
    }

    public static function busquedaEstado ($busqueda) {
        require('../Core/connection.php');
        $consulta = "SELECT nombreEstProy, descripcionEstProy FROM pys_estadoproy WHERE est = '1' AND nombreEstProy LIKE '%$busqueda%';";
        $resultado = mysqli_query($connection, $consulta);
        $count=mysqli_num_rows($resultado);
        if($count > 0){
            echo '  <table class="responsive-table left">
                        <thead>
                            <tr>
                                <th>Estado del proyecto</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo "      <tr>
                                <td>$datos[0]</td>
                                <td>$datos[1]</td>
                            </tr>";
            }
            echo '      </tbody>
                    </table>';
        } else{
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
                mysqli_close($connection);
    }

    public static function registrarEstado ($nombre, $descripcion) {
        require('../Core/connection.php');
        $consulta = "SELECT COUNT(idEstProy), MAX(idEstProy) FROM pys_estadoproy;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $count = $datos[0];
        $max = $datos[1];
        $duplicado = 0;
        if ($count == 0) {
            $countEst = "EST001";
        } else {
            $countEst = "EST".substr(substr($max, 3) + 1001, 1);
        }
        if ($nombre == null) {
            echo "<script> alert('El campo Estado se encuentra VACÍO. Registro no válido');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/estProyecto.php">';
        } else {
            $consulta3 = "SELECT nombreEstProy, descripcionEstProy FROM pys_estadoproy WHERE est = '1';";
            $resultado3 = mysqli_query($connection, $consulta3);
            while ($infoDB = mysqli_fetch_array($resultado3)){
                if ($infoDB['nombreEstProy'] == $nombre && $infoDB['descripcionEstProy'] == $descripcion) {
                    $duplicado = 1;
                }
            }
            if ($duplicado > 0) {
                echo "<script> alert('El registro está duplicado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/estProyecto.php">';
            } else {
                // Código para la inserción de los datos en la tabla pys_estadoproy
                $consulta2 = "INSERT INTO pys_estadoproy VALUES ('$countEst', '$nombre', '$descripcion', '1');";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2) {
                    echo "<script> alert('El registro se insertó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/estProyecto.php">';
                } else {
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser guardado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/estProyecto.php">';
                }
            }
        }
        mysqli_close($connection);
    }

    public static function selectTipoProyecto ($idEtapa, $idFrente) {
        require('../Core/connection.php');
        $consulta = "SELECT idTProy, nombreTProy FROM pys_tiposproy WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($idEtapa == null && $idFrente == null) {
            if ($registros = mysqli_num_rows($resultado) > 0) {
                if ($resultado) {
                    echo '  <select name="sltTipoProy" id="sltTipoProy">
                                <option value="" selected disabled>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '  <option value="'.$datos['idTProy'].'">'.$datos['nombreTProy'].'</option>';
                    }
                    echo '  </select>
                            <label for="sltTipoProy">Tipo Proyecto*</label>';
                }
            } else {
                echo "<script> alert('No hay categorías registradas en la base de datos.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/estProyecto.php">';
            }
        } else if ($idEtapa != null && $idFrente == null) {
            $consulta2 = "SELECT pys_etapaproy.idTProy FROM pys_etapaproy
                INNER JOIN pys_tiposproy ON pys_tiposproy.idTProy = pys_etapaproy.idTProy
                WHERE idEtaProy = '$idEtapa' AND pys_etapaproy.est = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                $datos2 = mysqli_fetch_array($resultado2);
                echo '  <select name="sltTipoProy" id="sltTipoProy">
                                <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idTProy'] == $datos2[0]) {
                        echo '  <option value="'.$datos['idTProy'].'" selected>'.$datos['nombreTProy'].'</option>';
                    } else {
                        echo '  <option value="'.$datos['idTProy'].'">'.$datos['nombreTProy'].'</option>';
                    }
                }
                echo '  </select>
                        <label for="sltTipoProy">Tipo Proyecto*</label>';
            }
        } else if ($idEtapa == null && $idFrente != null) {
            $consulta2 = "SELECT * FROM pys_tiposproy WHERE est = '1' AND idFrente = '$idFrente';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                echo '  <div class="input-field col l6 m6 s12 offset-l3 offset-m3 select-plugin">
                            <select name="sltTipoProy3" id="sltTipoProy3" onchange="cargaSelect(\'#sltTipoProy3\',\'../Controllers/ctrl_proyecto.php\',\'#divInfo2\');">
                                <option value="" selected disabled>Seleccione</option>';
                while ($datos2 = mysqli_fetch_array($resultado2)) {
                    echo '  <option value="'.$datos2['idTProy'].'">'.$datos2['nombreTProy'].'</option>';
                }
                echo '      </select>
                            <label for="sltTipoProy3">Tipo Proyecto*</label>
                        </div>';
            } else {
                echo '  <div class="input-field col l6 m6 s12 offset-l3 offset-m3 select-plugin">
                            <select name="sltTipoProy3" id="sltTipoProy3" onchange="cargaSelect(\'#sltTipoProy3\',\'../Controllers/ctrl_proyecto.php\',\'#divInfo2\');">
                                <option value="" selected disabled>No hay tipos de proyectos creados para el frente seleccionado</option>
                            </select>
                            <label for="sltTipoProy3">Tipo Proyecto*</label>
                        </div>';
            }
        }
        mysqli_close($connection);
    }

    public static function selectTipoIntExt ($tipo) {
        echo '  <select name="sltTipoIntExt" id="sltTipoIntExt">';
        if ($tipo = "Interno") {
            echo '  <option value="Interno" selected>Interno</option>
                    <option value="Externo">Externo</option>';
        } else if ($tipo = "Externo") {
            echo '  <option value="Interno">Interno</option>
                    <option value="Externo" selected>Externo</option>';
        }
        echo '  </select>
                <label for="sltTipoIntExt">Proyecto*</label>';
    }

    public static function selectEstado ($idEstado) {
        require('../Core/connection.php');
        $consulta = "SELECT idEstProy, nombreEstProy FROM pys_estadoproy WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            echo '  <select name="sltEstadoProy" id="sltEstadoProy">';
            if ($idEstado == null) {
                echo '      <option value="ESP001">Abierto</option>';
            } else {
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEstProy'] == $idEstado) {
                        echo '<option value="'.$datos['idEstProy'].'" selected>'.$datos['nombreEstProy'].'</option>';
                    } else {
                        echo '<option value="'.$datos['idEstProy'].'">'.$datos['nombreEstProy'].'</option>';
                    }
                }
            }
            echo '  </select>
                    <label for="sltEstadoProy">Estado del Proyecto</label>';
        } else { // No hay información en tabla pys_estadoproy
            echo '  <select name="sltEstadoProy" id="sltEstadoProy">
                        <option value"" selected disabled>No existen estados de proyecto</option>
                    </select>
                    <label for="sltEstadoProy">Estado del Proyecto</label>';
        }
        mysqli_close($connection);
    }

    public static function selectCelula ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idCelula, nombreCelula FROM pys_celulas WHERE estado = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($idProy == null) {
            echo '  <select name="sltCelula">
                        <option value="" disabled selected>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '  <option value="'.$datos['idCelula'].'">'.$datos['nombreCelula'].'</option>';
            }
            echo '  </select>
                    <label for="sltCelula">Célula*</label>';
        } else {
            $string = '  <select name="sltCelula2">
                        <option value="" selected>Seleccione</option>';
            $consulta2 = "SELECT idCelula FROM pys_actualizacionproy WHERE idProy = '$idProy' AND est='1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idCelula'] == $datos2['idCelula']) {
                    $string .= '  <option value="'.$datos['idCelula'].'" selected>'.$datos['nombreCelula'].'</option>';
                } else {
                    $string .= '  <option value="'.$datos['idCelula'].'">'.$datos['nombreCelula'].'</option>';
                }
            }
            $string .= '    </select>
                            <label for="sltCelula2">Célula</label>';
            return $string;
        }
        mysqli_close($connection);
    }

    public static function selectEtapa ($idEtapa, $tipoProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idEtaProy, nombreEtaProy FROM pys_etapaproy WHERE idTProy = '$tipoProy' AND est ='1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($idEtapa != null && $tipoProy != null) {
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <select name="sltEtapaProy" id="sltEtapaProy">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEtaProy'] == $idEtapa) {
                        echo '  <option value="'.$datos['idEtaProy'].'" selected>'.$datos['nombreEtaProy'].'</option>';
                    } else {
                        echo '  <option value="'.$datos['idEtaProy'].'">'.$datos['nombreEtaProy'].'</option>';
                    }
                }
                echo '  </select>
                        <label for="sltEtapaProy">Etapa del Proyecto*</label>';
            } else {
                echo "<script> alert('No hay categorías registradas en la base de datos.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
            }    
        }
        mysqli_close($connection);
    }
    
    public static function busquedaTotalEtapa () {
        require('../Core/connection.php');
        $consulta = "SELECT pys_etapaproy.idEtaProy, pys_tiposproy.idTProy, pys_tiposproy.nombreTProy, pys_etapaproy.nombreEtaProy, pys_etapaproy.descripcionEtaProy FROM pys_etapaproy
            INNER JOIN pys_tiposproy ON pys_tiposproy.idTProy = pys_etapaproy.idTProy WHERE pys_etapaproy.est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        echo '  <table class="responsive-table left">
                    <thead>
                        <tr>
                            <th>Tipo proyecto</th>
                            <th>Etapa</th>
                            <th>Descripción</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
        while ($datos = mysqli_fetch_array($resultado)) {
            echo '      <tr>
                            <td>'.$datos[2].'</td>
                            <td>'.$datos[3].'</td>
                            <td>'.$datos[4].'</td>
                            <td><a href="#modalEtapaProy" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEtapaProy.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                        </tr>';
        }
        echo '      </tbody>
                </table>';
        mysqli_close($connection);
    }

    public static function busquedaEtapa ($busqueda) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_etapaproy.idEtaProy, pys_tiposproy.idTProy, pys_tiposproy.nombreTProy, pys_etapaproy.nombreEtaProy, pys_etapaproy.descripcionEtaProy FROM pys_etapaproy
            INNER JOIN pys_tiposproy ON pys_tiposproy.idTProy = pys_etapaproy.idTProy WHERE pys_etapaproy.est = '1' AND (pys_etapaproy.nombreEtaProy LIKE '%$busqueda%' OR pys_tiposproy.nombreTProy LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table left">
                        <thead>
                            <tr>
                                <th>Tipo proyecto</th>
                                <th>Etapa</th>
                                <th>Descripción</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos[2].'</td>
                                <td>'.$datos[3].'</td>
                                <td>'.$datos[4].'</td>
                                <td><a href="#modalEtapaProy" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEtapaProy.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        
        mysqli_close($connection);
    }

    public static function registrarEtapa ($tipo, $nombre, $descripcion) {
        require('../Core/connection.php');
        /** Contador para la tabla pys_etapaproy */
        $consulta = "SELECT COUNT(idEtaProy), MAX(idEtaProy) FROM pys_etapaproy;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $count = $datos[0];
        $max = $datos[1];
        $duplicado = 0;
        if ($count == 0) {
            $countEta = "ETP001";
        } else {
            $countEta = "ETP".substr(substr($max, 3) + 1001, 1);
        }
        if ($tipo == null || $nombre == null) {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';
        } else {
            /** Verificamos si la etapa a guardar ya está creada */
            $consulta2 = "SELECT idTProy, nombreEtaProy FROM pys_etapaproy WHERE est = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($infoDB = mysqli_fetch_array($resultado2)) {
                if ($infoDB['idTProy'] == $tipo && $infoDB['nombreEtaProy'] == $nombre) {
                    $duplicado = 1;
                }
            }
            if ($duplicado > 0) {
                echo "<script> alert('El registro está duplicado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';
            } else {
                $consulta3 = "INSERT INTO pys_etapaproy VALUES ('$countEta', '$tipo', '$nombre', '$descripcion', '1');";
                $resultado3 = mysqli_query($connection, $consulta3);
                if ($resultado3) {
                    echo "<script> alert('El registro se insertó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';
                } else {
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser guardado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';                  
                }
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarEtapa ($idEtapa, $tipoProy, $nombre, $descripcion) {
        require('../Core/connection.php');
        /** Consulta para saber si la información ingresada es la misma de la tabla */
        $consulta = "SELECT * FROM pys_etapaproy WHERE pys_etapaproy.est = '1' AND idEtaProy = '$idEtapa';";
        $resultado = mysqli_query($connection, $consulta);
        $infoDB = mysqli_fetch_array($resultado);
        if ($infoDB['idTProy'] == $tipoProy && $infoDB['nombreEtaProy'] == $nombre && $infoDB['descripcionEtaProy'] == $descripcion) {
            echo "<script> alert('Registro NO actualizado.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';
        } else {
            $consulta2 = "UPDATE pys_etapaproy SET idTProy = '$tipoProy', nombreEtaProy = '$nombre', descripcionEtaProy = '$descripcion' WHERE (idEtaProy = '$idEtapa');";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado2) {
                echo "<script> alert('El registro se actualizó correctamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';
            } else {
                echo "<script> alert('Ocurrió un error y el registro NO pudo ser actualizado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/etaProyecto.php">';          
            }
        }
        mysqli_close($connection);
    }

    public static function selectEntidad ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idEnt, nombreEnt FROM pys_entidades WHERE est = '1' ORDER BY nombreEnt;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $consulta2 = "SELECT pys_entidades.idEnt, pys_entidades.nombreEnt FROM pys_actualizacionproy
            INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_actualizacionproy.idFacDepto
            INNER JOIN pys_entidades ON pys_entidades.idEnt = pys_facdepto.idEnt
            WHERE pys_actualizacionproy.idProy = '$idProy'
            AND pys_entidades.est = '1' AND pys_actualizacionproy.est = '1'
            ORDER BY pys_entidades.nombreEnt ASC;";
            $resultado2 = mysqli_query($connection, $consulta2);
            echo '  <select name="sltEntidad" id="sltEntidadProy" onchange="cargaSelect(\'#sltEntidadProy\',\'../Controllers/ctrl_proyecto.php\',\'#sltFacultadProy\');">';
            if ((mysqli_num_rows($resultado2)) > 0) {
                $datos2 = mysqli_fetch_array($resultado2);
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEnt'] == $datos2['idEnt']) {
                        echo '<option value="'.$datos['idEnt'].'" selected>'.$datos['nombreEnt'].'</option>';
                    } else {
                        echo '<option value="'.$datos['idEnt'].'">'.$datos['nombreEnt'].'</option>';
                    }
                }
            }
            echo '  </select>
                    <label for="sltEntidad">Empresa</label>';
        }
        mysqli_close($connection);
    }

    public static function selectFacultad ($idEnt, $idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idFac, facDeptoFacultad FROM pys_facdepto WHERE estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idEnt = '$idEnt' ORDER BY facDeptoFacultad;";
        $resultado = mysqli_query($connection, $consulta);
        if ($idProy == null) {
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <select name="sltFacul" id="sltFacul2" onchange="cargaSelect(\'#sltFacul2\',\'../Controllers/ctrl_proyecto.php\',\'#sltDeptoProy\');">';
                echo '  <option value="" selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '  <option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                }
                echo '  </select>
                        <label for="sltFacul">Facultad</label>';
            }
        } else {
            if (mysqli_num_rows($resultado) > 0) {
                $consulta2 = "SELECT idFac, facDeptoFacultad
                    FROM pys_actualizacionproy
                    INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_actualizacionproy.idFacDepto 
                    WHERE pys_actualizacionproy.est = '1' AND pys_actualizacionproy.idProy = '$idProy';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                    echo '  <select name="sltFacul" id="sltFacul" onchange="cargaSelect(\'#sltFacul\',\'../Controllers/ctrl_proyecto.php\',\'#sltDeptoProy\');">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idFac'] == $datos2['idFac']) {
                            echo '  <option value="'.$datos['idFac'].'" selected>'.$datos['facDeptoFacultad'].'</option>';
                        } else {
                            echo '  <option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                        }
                    }
                    echo '  </select>
                            <label for="sltFacul">Facultad</label>';    
                
            }
        }
        mysqli_close($connection);
    }
    public static function selectDepartamento ($idFacul, $idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idFacDepto, facDeptoDepartamento, idDepto FROM pys_facdepto WHERE estFacdeptoDepto = '1' AND  idFac = '$idFacul';";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($idProy == null && $idFacul != null) {
            if ($registros > 0) {
                echo '  <select id="sltDepto" name="sltDepto">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '  <option value="'.$datos['idDepto'].'">'.$datos['facDeptoDepartamento'].'</option>';
                }
                echo '  </select>
                        <label for="sltDepto">Departamento</label>';
            }
        } else if ($idProy != null && $idFacul != null) {
            $consulta2 = "SELECT idDepto, facDeptoFacultad FROM pys_actualizacionproy 
                INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_actualizacionproy.idFacDepto
                WHERE idProy = '$idProy' AND pys_actualizacionproy.est = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
                echo '  <select id="sltDepto" name="sltDepto">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idDepto'] == $datos2['idDepto']) {
                        echo '  <option value="'.$datos['idDepto'].'" selected>'.$datos['facDeptoDepartamento'].'</option>';
                    } else {
                        echo '  <option value="'.$datos['idDepto'].'">'.$datos['facDeptoDepartamento'].'</option>';
                    }
                }
                echo '  </select>
                <label for="sltDepto">Departamento</label>';
            
        }
        mysqli_close($connection);
    }

    public static function selectFrente ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT descripcionFrente, idFrente, nombreFrente FROM pys_frentes WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($idProy == null) {
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <select name="sltFrente" id="sltFrente" onchange="cargaSelect(\'#sltFrente\', \'../Controllers/ctrl_proyecto.php\',\'#divInfo\');">
                            <option value="" disabled selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '  <option value="'.$datos['idFrente'].'">'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                }
                echo '  </select>
                        <label for="sltFrente">Frente*</label>';
            }
        } else {
            $pos = substr($idProy, 4); // Id del proyecto
            $consulta2 = "SELECT idFrente FROM pys_tiposproy WHERE est = '1' AND idTProy = '$pos';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <select name="sltFrente" id="sltFrente">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idFrente'] == $datos2['idFrente']) {
                        echo '  <option value="'.$datos['idFrente'].'" selected>'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                    } else {
                        echo '  <option value="'.$datos['idFrente'].'">'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                    }
                }
                echo '  </select>
                        <label for="sltFrente">Frente*</label>';
            }
        }
        return $datos['idFrente'];
        mysqli_close($connection);
    }

    public static function selectElementoPep ($idCeco) {
        require('../Core/connection.php');
        $consulta = "SELECT idElemento, nombreElemento FROM pys_elementospep WHERE idCeco = '$idCeco' AND estado = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($registro = mysqli_num_rows($resultado) > 0) {
            echo '  <select name="sltElementoPep" id="sltElementoPep">
                        <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '  <option value="'.$datos['idElemento'].'">'.$datos['nombreElemento'].'</option>';
            }
            echo '  </select>
                    <label for="sltElementoPep">Elemento PEP</select>';
        } else if (substr($idCeco, 0, 3) == "PRY") {
            $consulta2 = "SELECT pys_elementospep.idElemento, pys_elementospep.idCeco 
                FROM pys_cruceproypep 
                INNER JOIN pys_elementospep ON pys_elementospep.idElemento = pys_cruceproypep.idElemento 
                WHERE idProy = '$idCeco' AND pys_cruceproypep.estado = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $consulta3 = "SELECT idElemento, nombreElemento FROM pys_elementospep WHERE idCeco = '".$datos2['idCeco']."' AND estado = '1';";
            $resultado3 = mysqli_query($connection, $consulta3);
            echo '  <select name="sltElementoPep" id="sltElementoPep">
                        <option value="" selected>Seleccione</option>';
            while ($datos3 = mysqli_fetch_array($resultado3)) {
                if ($datos3['idElemento'] == $datos2['idElemento']) {
                    echo '  <option value="'.$datos3['idElemento'].'" selected>'.$datos3['nombreElemento'].'</option>';
                } else {
                    echo '  <option value="'.$datos3['idElemento'].'">'.$datos3['nombreElemento'].'</option>';
                }
            }
            echo '  </select>
                    <label for="sltElementoPep2">Elemento PEP</select>';
        }
        mysqli_close($connection);
    }

    public static function registrarTipo ($idFrente, $nombreTipo) {
        require('../Core/connection.php');
        /** Contador para la tabla pys_tiposproy */
        $duplicado = 0;
        $consulta = "SELECT COUNT(idTProy), MAX(idTProy) FROM pys_tiposproy;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $count = $datos[0];
        $max = $datos[1];
        if ($count == 0) {
            $idTip = "TPRY01";
        } else {
            $idTip = "TPRY".substr(substr($max, 4) + 101, 1);
        }
        if ($idFrente == null || $nombreTipo == null) {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';
        } else {
            /** Verificamos si la información a guardar ya está en la tabla */
            $consulta2 = "SELECT idFrente, nombreTProy FROM pys_tiposproy WHERE est = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($infoDB = mysqli_fetch_array($resultado2)) {
                if ($infoDB['idFrente'] == $idFrente && $infoDB['nombreTProy'] == $nombreTipo) {
                    $duplicado = 1;
                }
            }
            if ($duplicado > 0) {
                echo "<script> alert('El registro está duplicado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';
            } else {
                $consulta3 = "INSERT INTO pys_tiposproy (idTProy, idFrente, nombreTProy, est) VALUES ('$idTip', '$idFrente', '$nombreTipo', '1');";
                $resultado3 = mysqli_query($connection, $consulta3);
                if ($resultado3) {
                    echo "<script> alert('El registro se insertó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';
                } else {
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser guardado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';                  
                }
            }
        }
        mysqli_close($connection);
    }

    public static function onLoadTipo ($id) {
        require('../Core/connection.php');
        $codigo = substr($id,4);
        $consulta = "SELECT idTProy, nombreTProy, nombreFrente, descripcionFrente FROM pys_tiposproy
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_tiposproy.idFrente
            WHERE pys_tiposproy.est = '1' AND pys_tiposproy.idTProy = '$codigo';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaTotalTipo () {
        require('../Core/connection.php');
        $consulta = "SELECT idTProy, nombreTProy, nombreFrente, descripcionFrente FROM pys_tiposproy
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_tiposproy.idFrente
            ORDER BY nombreFrente;";
        $resultado = mysqli_query($connection, $consulta);
        echo '  <table class="responsive-table left">
                    <thead>
                        <tr>
                            <th>Frente</th>
                            <th>Tipo de proyecto</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
        while ($datos = mysqli_fetch_array($resultado)) {
            echo '      <tr>
                            <td>'.$datos[2].' '.$datos[3].'</td>
                            <td>'.$datos[1].'</td>
                            <td><a href="#modalTipoProy" class="waves-effect waves-light btn modal-trigger" onclick="envioData(\'TIP-'.$datos[0].'\','."'modalTipoProy.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                        </tr>';
        }
        echo '      </tbody>
                </table>';
        mysqli_close($connection);
    }

    public static function busquedaTipo ($busqueda) {
        require('../Core/connection.php');
        $consulta = "SELECT idTProy, nombreTProy, nombreFrente, descripcionFrente FROM pys_tiposproy
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_tiposproy.idFrente
            WHERE (nombreTProy LIKE '%$busqueda%' OR nombreFrente LIKE '%$busqueda%' OR descripcionFrente LIKE '%$busqueda%')
            ORDER BY nombreFrente;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table left">
                        <thead>
                            <tr>
                                <th>Frente</th>
                                <th>Tipo de proyecto</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos[2].' '.$datos[3].'</td>
                                <td>'.$datos[1].'</td>
                                <td><a href="#modalTipoProy" class="waves-effect waves-light btn modal-trigger" onclick="envioData(\'TIP-'.$datos[0].'\','."'modalTipoProy.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        
        mysqli_close($connection);
    }

    public static function actualizarTipo ($id, $frente, $nombre) {
        require('../Core/connection.php');
        $id = substr($id, 4);
        $consulta = "SELECT idFrente, nombreTProy FROM pys_tiposproy WHERE idTProy = '$id' AND est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $infoDB = mysqli_fetch_array($resultado);
        if ($infoDB['idFrente'] == $frente && $infoDB['nombreTProy'] == $nombre) {
            echo "<script> alert('Registro NO actualizado.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';
        } else {
            $consulta2 = "UPDATE pys_tiposproy SET idFrente = '$frente', nombreTProy = '$nombre' WHERE (idTProy = '$id');";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado2) {
                echo "<script> alert('El registro se actualizó correctamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';
            } else {
                echo "<script> alert('Ocurrió un error y el registro NO pudo ser actualizado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProyecto.php">';          
            }
        }
        mysqli_close($connection);
    }

    public static function selectFinancia ($financia) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_entidades.idEnt, pys_entidades.nombreEnt, pys_facdepto.idEnt, pys_facdepto.idFacDepto
            FROM pys_entidades
            INNER JOIN pys_facdepto ON pys_facdepto.idEnt = pys_entidades.idEnt
            WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.idFac = 'FAC014' AND pys_facdepto.idDepto = 'DP0027'
            ORDER BY nombreEnt;";
        $resultado = mysqli_query($connection, $consulta);
        $consulta2 = "SELECT idFacDepto, idFac, facDeptoFacultad, idDepto, facDeptoDepartamento FROM pys_facdepto
            WHERE estFacdeptoFac = '1' AND estFacdeptoDepto = '1' AND idFac != 'FAC014'
            ORDER BY facDeptoFacultad";
        $resultado2 = mysqli_query($connection, $consulta2);
        if (($registros = mysqli_num_rows($resultado) > 0) || ($registros2 = mysqli_num_rows($resultado2) > 0)) {
            if ($financia == null) {
                echo '  <select name="sltFinancia" id="sltFinancia">
                            <option value="" selected disabled>Seleccione</option>
                            <optgroup label="Empresa">';
            } else {
                echo '  <select name="sltFinancia" id="sltFinancia">
                            <optgroup label="Empresa">';
            }            
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idFacDepto'] == $financia || $datos['idEnt'] == $financia) {
                    echo '  <option value="'.$datos['idFacDepto'].'" selected>'.$datos['nombreEnt'].'</option>';
                } else {
                    echo '  <option value="'.$datos['idFacDepto'].'">'.$datos['nombreEnt'].'</option>';
                }
            }
            echo '      </optgroup>
                        <optgroup label="Facultad - Departamento">';
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                if ($datos2['idFacDepto'] == $financia) {
                    echo '  <option value="'.$datos2['idFacDepto'].'" selected>'.$datos2["facDeptoFacultad"].' - '.$datos2["facDeptoDepartamento"].'</option>';
                } else {
                    echo '  <option value="'.$datos2['idFacDepto'].'">'.$datos2["facDeptoFacultad"].' - '.$datos2["facDeptoDepartamento"].'</option>';
                }
            }
            echo '      </optgroup>
                    </select>
                    <label for="sltFinancia">Financia*</label>';
        }
        mysqli_close($connection);
    }

    public static function selectCeco ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idCeco, ceco, nombre FROM pys_centrocostos WHERE estado = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            if ($idProy == null) {
                echo '  <div class="input-field col l3 m3 s12 offset-l3 offset-m3 select-plugin">
                            <select name="sltCeco" id="sltCeco" onchange=cargaSelect(\'#sltCeco\',\'../Controllers/ctrl_proyecto.php\',\'#divInfo3\');>
                                <option value="" disabled selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <option value="'.$datos['idCeco'].'">'.$datos['ceco'].' - '.$datos['nombre'].'</option>';
                }
                echo '      </select>
                            <label for="sltCeco">Centro de Costos*</label>
                        </div>';
            } else {
                $consulta2 = "SELECT pys_elementospep.idCeco 
                    FROM pys_cruceproypep 
                    INNER JOIN pys_elementospep ON pys_elementospep.idElemento = pys_cruceproypep.idElemento 
                    WHERE pys_elementospep.estado = '1' AND pys_cruceproypep.estado = '1' AND pys_cruceproypep.idProy = '$idProy';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $string = '         <select name="sltCeco2" id="sltCeco2" onchange=cargaSelect(\'#sltCeco2\',\'../Controllers/ctrl_proyecto.php\',\'#divInfo4\');>
                                        <option value="" selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idCeco'] == $datos2['idCeco']) {
                        $string .= '    <option value="'.$datos['idCeco'].'" selected>'.$datos['ceco'].' - '.$datos['nombre'].'</option>';
                    } else {
                        $string .= '    <option value="'.$datos['idCeco'].'">'.$datos['ceco'].' - '.$datos['nombre'].'</option>';
                    }
                }
                $string .= '        </select>
                                    <label for="sltCeco2">Centro de Costos*</label>';
                return $string;
            }
        }
        echo '  <div id="divInfo3" class="input-field col l3 m3 s12 select-plugin"></div>';
        mysqli_close($connection);
    }

    public static function selectConvocatoria ($idConvocatoria) {
        require('../Core/connection.php');
        $consulta = "SELECT idConvocatoria, nombreConvocatoria FROM pys_convocatoria WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <select name="sltConvocatoria" id="sltConvocatoria">';
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idConvocatoria'] == $idConvocatoria) {
                    echo '  <option value="'.$datos['idConvocatoria'].'" selected>'.$datos['nombreConvocatoria'].'</option>';
                } else {
                    echo '  <option value="'.$datos['idConvocatoria'].'">'.$datos['nombreConvocatoria'].'</option>';
                }
            }
            echo '  </select>
                    <label for="sltConvocatoria">Convocatoria</label>';
        }
        mysqli_close($connection);
    }

    public static function selectFuenteFinanciacion ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idFteFin, nombre, sigla FROM pys_fuentesfinanciamiento WHERE estado ='1';";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            if ($idProy == null) {
                echo '  <div class="input-field col l2 m2 s12 select-plugin">
                            <select name="sltFuenteFinanciamiento" id="sltFuenteFinanciamiento">
                                <option value="" disabled selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <option value="'.$datos['idFteFin'].'">'.$datos['sigla'].'</option>';
                }
                echo '      </select>
                            <label for="sltFuenteFinanciamiento">Fuente de Financiación</label>
                        </div>';
            } else {
                $consulta2 = "SELECT idFteFin FROM pys_actualizacionproy WHERE est = '1' AND idProy = '$idProy';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $string = '     <select name="sltFuenteFinanciamiento">
                                    <option value="" selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idFteFin'] == $datos2['idFteFin']) {
                        $string .= '<option value="'.$datos['idFteFin'].'" selected>'.$datos['sigla'].'</option>';
                    } else {
                        $string .= '<option value="'.$datos['idFteFin'].'">'.$datos['sigla'].'</option>';
                    }
                }
                $string .= '    </select>
                                <label for="sltFuenteFinanciamiento">Fuente de Financiación*</label>';
                return $string;
            }
        } else {
            echo '<h5>No hay Fuentes de Financiamiento, creadas en el sistema</h5>'.$registros;
        }
        mysqli_close($connection);
    }

    public static function cargaNombreProyecto ($frente) {
        require('../Core/connection.php');
        $consulta = "SELECT descripcionFrente FROM pys_frentes WHERE idFrente = '$frente' AND est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        echo '  <div class="caja col l6 m6 s12 offset-l3 offset-m3 teal lighten-5">
                <p class="col l12 m12 s12 center-align teal-text"><strong>Código Proyecto en Conecta-TE:</strong></p>
                <div class="row">
                    <div class="input-field col l4 m6 s12">
                        <input readonly name="txtSiglaFr" id="txtSiglaFr" type="text" value="'.$datos[0].'">
                        <label for="txtSiglaFr" class="active">Frente</label>
                    </div>
                    <div class="input-field col l4 m6 s12">
                        <input name="txtAnio" id="txtAnio" type="number" value="'.date('y').'">
                        <label for="txtAnio" class="active">Año</label>
                    </div>
                    <div class="input-field col l4 m12 s12">
                        <input required name="txtSiglaProy" id="txtSiglaProy" type="text" value="">
                        <label for="txtSiglaProy" class="active">Proyecto*</label>
                    </div>
                </div>
                </div>';
        echo Proyecto::selectTipoProyecto(null, $frente);
        mysqli_close($connection);
    }

    public static function cargaInfoProyecto ($idProy) {
        require('../Core/connection.php');
        $consulta = "SELECT idEtaProy, nombreEtaProy FROM pys_etapaproy where est = '1' and idTProy = '$idProy' ORDER BY idEtaProy;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            echo '  <div class="input-field col l3 m3 s12 offset-l3 offset-m3 select-plugin">
                        <select name="sltEtapaProy" id="sltEtapaProy">
                            <option value="" disabled selected>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <option value="'.$datos['idEtaProy'].'">'.$datos['nombreEtaProy'].'</option>';
            }
            echo '      </select>
                        <label for="sltEtapaProy">Etapa del Proyecto*</label>
                    </div>';
        } else {
            echo '  <div class="input-field col l3 m3 s12 offset-l3 offset-m3 select-plugin">
                        <select name="sltEtapaProy" id="sltEtapaProy" class="validate">
                            <option value="" selected disabled>No hay etapas creadas</option>
                        </select>
                        <label for="sltEtapaProy">Etapa del Proyecto*</label>
                    </div>';
        }
        echo '  <div class="input-field col l3 m3 s12">
                    <textarea required name="txtNomProy" id="txtNomProy" class="materialize-textarea" placeholder="Campo obligatorio"></textarea>
                    <label for="txtNomProy" class="active">Nombre del Proyecto</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input name="txtNomCorProy" id="txtNomCorProy" value="">
                    <label for="txtNomCorProy" class="active">Nombre Corto</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <textarea name="txtContProy" id="txtContProy" class="materialize-textarea"></textarea>
                    <label for="txtContProy" class="active">Contexto del Proyecto</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtFechIni" name="txtFechIni" type="text" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtFechIni" class="active">Fecha de Inicio del Proyecto</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input id="txtFechFin" name="txtFechFin" type="text" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtFechFin" class="active">Fecha de Cierre del Proyecto</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input name="txtPresupuesto" id="txtPresupuesto" type="text" onkeyup="format(\'#txtPresupuesto\');">
                    <label for="txtPresupuesto" class="active">Presupuesto asignado $</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input name="txtFechColciencias" id="txtFechColciencias" type="text" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtFechColciencias" class="active">Fecha Colciencias</label>
                </div>
                ';
        $consulta2 = "SELECT idConvocatoria, nombreConvocatoria FROM pys_convocatoria WHERE est = '1' ORDER BY nombreConvocatoria;";
        $resultado2 = mysqli_query($connection, $consulta2);
        if (mysqli_num_rows($resultado2) > 0) {
            echo '  <div class="input-field col l2 m2 s12 offset-l3 offset-m3 select-plugin">
                        <select name="sltConvocatoria" id="sltConvocatoria">
                            <option value="" disabled selected>Seleccione</option>';
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                echo '      <option value="'.$datos2['idConvocatoria'].'">'.$datos2['nombreConvocatoria'].'</option>';
            }
            echo '      </select>
                        <label for="sltConvocatoria">Convocatoria*</label>
                    </div>';
        } else {
            echo "<script> alert('No hay categorías registradas en la base de datos.');</script>";
            //echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
        }
        echo '  <div class="input-field col l2 m2 s12">
                    <input id="txtSemAcom" name="txtSemAcom" type="number"></input>
                    <label for="txtSemAcom" class="active">Semanas de Acompañamiento</label>
                </div>
                ';
        /** Se muestra el select para la fuente de financiación */
        Proyecto::selectFuenteFinanciacion(null);
        /** Se muestra el select para el centro de costos */
        Proyecto::selectCeco(null);
        mysqli_close($connection);
    }

    public static function registrarProyecto ($siglaFrente, $anio, $siglaCodProyecto, $tipoProy, $proyectoIntExt, $frente, $estadoPry, $etapaPry, $nombrePry, $financia, $convocatoria, $departamento, $facultad, $entidad, $nombreCortoPry, $contextoPry, $fechIni, $fechFin, $usuario, $presupuesto, $fechaColciencias, $semanas, $fteFinancia, $celula, $centroCosto, $pep) {
        require('../Core/connection.php');
        mysqli_query($connection, "BEGIN;");
        $codConectate = $siglaFrente.$anio.$siglaCodProyecto;
        if ($fechIni != "") {
            $fechIni = "'$fechIni'";
        } else {
            $fechIni = "NULL";
        }
        
        if ($fechFin != "") {
            $fechFin = "'$fechFin'";
        } else {
            $fechFin = "NULL";
        }
        
        if ($fechaColciencias != "") {
            $fechaColciencias = "'$fechaColciencias'";
        } else {
            $fechaColciencias = "NULL";
        }
        /** Limpieza de la variable presupuesto para almacenarla en BD */
        $presupuesto = str_replace(".","",$presupuesto);
        
        /** Verificación del código de estado del Proyecto */
        if ($estadoPry == "Abierto") {
            $estadoPry = "ESP001";
        }

        /** Obtención del ID de la persona logueada y que está realizando el registro del proyecto */
        $consultaIdPersona = "SELECT idPersona FROM pys_login WHERE est = '1' AND usrLogin = '$usuario';";
        $resultadoIdPersona = mysqli_query($connection, $consultaIdPersona);
        $datosIdPersona = mysqli_fetch_array($resultadoIdPersona);
        $idPersona = $datosIdPersona[0];
        
        /** Contador para ID proyecto, tabla Proyectos */
        $consulta = "SELECT COUNT(idProy), MAX(idProy) FROM pys_proyectos;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $count = $datos[0];
        $max = $datos[1];
        if ($count == 0) {
            $codProy = "PRY001";
        } else {
            $codProy = "PRY".substr(substr($max, 3) + 1001, 1);
        }
        
        /** Contador para ID Curso Modulo */
        $consulta2 = "SELECT COUNT(idCM), MAX(idCM) FROM pys_cursosmodulos;";
        $resultado2 = mysqli_query($connection, $consulta2);
        $datos2 = mysqli_fetch_array($resultado2);
        $count2 = $datos2[0];
        $max2 = $datos2[1];
        if ($count2 == 0) {
            $codCM = "CM0001";
        } else {
            $codCM = "CM".substr(substr($max2, 3) + 10001, 1);
        }
        
        /** Verificación si el código de proyecto ya está creado */
        $consulta3 = "SELECT codProy FROM pys_proyectos WHERE codProy = '$codConectate';";
        $resultado3 = mysqli_query($connection, $consulta3);
        if (mysqli_num_rows($resultado3) > 0){
            echo "<script> alert ('El proyecto ya se encuentra registrado en el sistema.');</script>";
        } else {
            /** Validación de Campos Vacíos */
            if ($codProy == null || $tipoProy == null || $proyectoIntExt == null || $frente == null || $siglaCodProyecto == null || $estadoPry == null || $etapaPry == null || $nombrePry == null || $financia == null || $convocatoria == null || $semanas == null || $fteFinancia == null || $celula == null || $centroCosto == null || $pep == null) {
                echo "<script> alert ('Existe algún campo VACÍO. Registro no válido');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                /** Validación del código de proyecto */
                $consulta4= "SELECT codProy FROM pys_actualizacionproy WHERE ((est = '0') OR (est = '1')) AND codProy = '$codConectate';";
                $resultado4 = mysqli_query($connection, $consulta4);
                if ($registros4 = mysqli_num_rows($resultado4) > 0) {
                    echo "<script> alert ('El Código Proyecto en Conecta-TE ya existe. Registro no válido');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                } else {
                    /** Código para encontrar el ID de la tabla pys_facdepto con respecto a la facultad y el departamento */
                    if ($departamento == null) {
                        $consulta5 = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idFac = '$facultad' AND idEnt = '$entidad';";
                        $resultado5 = mysqli_query($connection, $consulta5);
                        $datos5 = mysqli_fetch_array($resultado5);
                        $facDepto = $datos5[0];
                    } else {
                        $facDepto = $departamento;
                    }
                    /** Código Insert tabla pys_proyectos*/
                    $consulta6 = "INSERT INTO pys_proyectos VALUES ('$codProy', '$facDepto', '$tipoProy', '$frente', '$estadoPry', '$etapaPry', '$codConectate', '$nombrePry', '$nombreCortoPry', '$contextoPry', $fechIni, $fechFin, NOW(), '$convocatoria', '$idPersona', '', '$presupuesto', '$financia', $fechaColciencias, '1');";
                    $resultado6 = mysqli_query($connection, $consulta6);
                                        
                    /** Obtenemos el ID actual de la tabla pys_actualizacionproy */
                    $consulta7 = "SELECT MAX(idActProy) FROM pys_actualizacionproy;";
                    $resultado7 = mysqli_query($connection, $consulta7);
                    $datos7 = mysqli_fetch_array($resultado7);
                    $idActProy = $datos7[0]+1;
                    
                    /** Código Insert tabla pys_actualizacionproy */
                     $consulta8 = "INSERT INTO pys_actualizacionproy VALUES ('$idActProy', '$facDepto', '$codProy', '$tipoProy', '$proyectoIntExt', '$frente', '$estadoPry', '$etapaPry', '$codConectate', '$nombrePry', '$nombreCortoPry', '$contextoPry', $fechIni, $fechFin, NOW(), '$convocatoria', '$idPersona', '', '$presupuesto', '$financia', $fechaColciencias, '$semanas', '$fteFinancia', '$celula', '1');";
                    $resultado8 = mysqli_query($connection, $consulta8);

                    /** Código Insert tabla pys_cursosmodulos*/
                    $consulta9 = "INSERT INTO pys_cursosmodulos VALUES ('$codCM', '$frente', '$codProy', 'CR0051', 'MD0001', '', '', '', '', '1', '1', '1');";
                    $resultado9 = mysqli_query($connection, $consulta9);

                    /** Código Insert tabla pys_cruceproypep */
                    $consulta10 = "INSERT INTO pys_cruceproypep VALUES (NULL, '$pep', '$codProy', NOW(), NULL, '1');";
                    $resultado10 = mysqli_query($connection, $consulta10);

                    if ($resultado6 && $resultado8 && $resultado9 && $resultado10) {
                        mysqli_query($connection, "COMMIT;");
                        echo "<script> alert ('El registro se INSERTÓ correctamente');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
                    } else {
                        mysqli_query($connection, "ROLLBACK;");
                        echo "<script> alert ('Se presentó un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
                    }
                }

            }
        }
        mysqli_close($connection);
    }

    public static function suprimirProyecto ($idProy, $nota, $persona) {
        require('../Core/connection.php');
        mysqli_query($connection, "BEGIN;");
        /** Consulta del ID de la persona que realiza la modificación del registro */
        $consulta = "SELECT idPersona FROM pys_login WHERE usrLogin = '$persona' AND est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $persona = $datos['idPersona'];
        if ($nota == null || $nota == "") {
            echo "<script> alert ('No puede suprimir el registro porque el campo MOTIVO DE ANULACIÓN se encuentra VACÍO. Proyecto NO eliminado.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
        } else {
            /** Verificación de los estados de las solicitudes relacionadas al proyecto que se encuentren abiertas. ESS006 = Terminado ESS007 = Cancelado */
            $consulta2 = "SELECT pys_cursosmodulos.idCM, pys_actsolicitudes.idSol, pys_actsolicitudes.idEstSol, pys_actsolicitudes.est 
                FROM pys_proyectos
                INNER JOIN pys_cursosmodulos ON pys_proyectos.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                WHERE ((pys_actsolicitudes.idEstSol != 'ESS006') AND (pys_actsolicitudes.idEstSol != 'ESS007'))
                AND pys_proyectos.est = '1' AND pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estProy = '1'
                AND pys_cursosmodulos.estCurso = '1' AND pys_actsolicitudes.idSer = '' AND pys_proyectos.idProy = '$idProy';";
            $resultado2 = mysqli_query($connection, $consulta2);
            /** Si hay solicitudes pendientes por cerrar no se permite la eliminación del proyecto */
            if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                echo "<script> alert ('El proyecto a eliminar tiene solicitudes pendientes por cerrar. Proyecto NO eliminado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">'; // Redireccionar al estado de solicitudes x proyecto
            } else {
                /** Código para inactivación en la tabla pys_proyectos */
                $consulta3 = "UPDATE pys_proyectos SET idResponRegistro = '$persona', motivoAnulacion = '$nota', est = '0' WHERE idProy = '$idProy';";
                $resultado3 = mysqli_query($connection, $consulta3);
                /** Código para inactivación en la tabla pys_actualizacionproy */
                $consulta4 = "UPDATE pys_actualizacionproy SET idResponRegistro = '$persona', motivoAnulacion = '$nota', est = '0' WHERE idProy = '$idProy' AND est = '1';";
                $resultado4 = mysqli_query($connection, $consulta4);
                /** Código para desactivación en la tabla pys_cursosmodulos */
                $consulta5 = "UPDATE pys_cursosmodulos SET estProy = '0' WHERE idProy = '$idProy';";
                $resultado5 = mysqli_query($connection, $consulta5);
                if ($resultado3 && $resultado4 && $resultado5) {
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert ('El proyecto se SUPRIMIÓ correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
                } else {
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert ('Ocurrió un error al intentar eliminar el Proyecto. Por favor intente nuevamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/proyecto.php">';
                }
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarProyecto ($idProy, $entidad, $facultad, $departamento, $tipoProy, $tipoIntExt, $frente, $estProy, $etpProy, $codProy, $nomProy, $nomCortoProy, $descProy, $conv, $presupuesto, $financia, $fechaIni, $fechaFin, $persona, $fechaColciencias, $semanas, $celula, $fteFinancia, $centroCosto, $pep) {
        require('../Core/connection.php');
        mysqli_query($connection, "BEGIN;");
        /** Obtenemos ID de la tabla pys_facdepto respecto al departamento */
        if ($departamento != null) {
            $consulta = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoDepto = '1' AND idDepto = '$departamento' AND idFac = '$facultad' AND idEnt = '$entidad';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $facDepto = $datos[0];
        } else if ($facultad != null) {
            /** Obtenemos ID de la tabla pys_facdepto respecto a la facultad */
            $consulta = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idFac = '$facultad' AND idEnt = '$entidad';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $facDepto = $datos[0];
        } else if ($entidad != null) {
            /** Obtenemos ID de la tabla pys_facdepto respecto a la entidad */
            $consulta = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND idDepto = 'DP0027' AND idFac = 'FAC014' AND idEnt = '$entidad';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $facDepto = $datos[0];
        }
        /** Obtención del ID de la persona logueada y que está realizando el registro del proyecto */
        $consultaIdPersona = "SELECT idPersona FROM pys_login WHERE est = '1' AND usrLogin = '$persona';";
        $resultadoIdPersona = mysqli_query($connection, $consultaIdPersona);
        $datosIdPersona = mysqli_fetch_array($resultadoIdPersona);
        $idPersona = $datosIdPersona[0];
        if ($idProy == null || $entidad == null || $tipoIntExt == null || $estProy == null || $etpProy == null || $codProy == null || $nomProy == null || $conv == null || $financia == null || $semanas == null || $fteFinancia == null || $celula == null || $centroCosto == null || $pep == null) {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la página anterior
        } else {
            $consInfoDB = "SELECT * FROM pys_actualizacionproy WHERE idProy = '$idProy' AND est = '1';";
            $resultadoInfoDB = mysqli_query($connection, $consInfoDB);
            $datosDB = mysqli_fetch_array($resultadoInfoDB);
            $consInfoDB2 = "SELECT * FROM pys_cruceproypep WHERE idProy = '$idProy' AND estado = '1';";
            $resultadoInfoDB2 = mysqli_query($connection, $consInfoDB2);
            $datosDB2 = mysqli_fetch_array($resultadoInfoDB2);
            if ($datosDB['idFacDepto'] == $facDepto &&
                    $datosDB['idTProy'] == $tipoProy &&
                        $datosDB['proyecto'] == $tipoIntExt &&
                            $datosDB['idFrente'] == $frente &&
                                $datosDB['idEstProy'] == $estProy &&
                                    $datosDB['idEtaProy'] == $etpProy &&
                                        $datosDB['codProy'] == $codProy &&
                                            $datosDB['nombreProy'] == $nomProy &&
                                                $datosDB['nombreCortoProy'] == $nomCortoProy &&
                                                    $datosDB['descripcionProy']  == $descProy &&
                                                        $datosDB['fechaIniProy'] == $fechaIni &&
                                                            $datosDB['fechaCierreProy'] == $fechaFin &&
                                                                $datosDB['idConvocatoria'] == $conv &&
                                                                    $datosDB['presupuestoProy'] == $presupuesto &&
                                                                        $datosDB['financia'] == $financia &&
                                                                            $datosDB['fechaColciencias'] == $fechaColciencias &&
                                                                                $datosDB['semAcompanamiento'] == $semanas &&
                                                                                    $datosDB['idFteFin'] == $fteFinancia &&
                                                                                        $datosDB['idCelula'] == $celula &&
                                                                                            $datosDB2['idElemento'] == $pep) {
                    echo "<script> alert('La información ingresada es la misma. El registro NO fue actualizado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la página anterior
            } else {
                if ($fechaIni != "") {
                    $fechaIni = "'$fechaIni'";
                } else {
                    $fechaIni = "NULL";
                }
                
                if ($fechaFin != "") {
                    $fechaFin = "'$fechaFin'";
                } else {
                    $fechaFin = "NULL";
                }
                
                if ($fechaColciencias != "") {
                    if ($fechaColciencias == "0000-00-00") {
                        $fechaColciencias = "NULL";
                    } else {
                        $fechaColciencias = "'$fechaColciencias'";
                    }
                } else {
                    $fechaColciencias = "NULL";
                }
                
                /** Obtenemos el ID actual de la tabla pys_actualizacionproy */
                $consulta6 = "SELECT MAX(idActProy) FROM pys_actualizacionproy;";
                $resultado6 = mysqli_query($connection, $consulta6);
                $datos6 = mysqli_fetch_array($resultado6);
                $idActProy = $datos6[0]+1;
                
                if ($estProy == 'ESP002' || $estProy == 'ESP004') {
                    /** ESP002 = Cancelado; EPS004 = Terminado (PROYECTO) 
                     *  Verificamos que el Proyecto a Cancelar o Terminar no tenga solicitudes pendientes por cerrar.
                     * */
                    $consulta2 = "SELECT pys_cursosmodulos.idCM, pys_actsolicitudes.idSol, pys_actsolicitudes.idEstSol, pys_actsolicitudes.est 
                        FROM pys_proyectos
                        INNER JOIN pys_cursosmodulos ON pys_proyectos.idProy = pys_cursosmodulos.idProy
                        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        WHERE ((pys_actsolicitudes.idEstSol != 'ESS006') and (pys_actsolicitudes.idEstSol != 'ESS007'))
                        AND pys_proyectos.est = '1' AND pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estProy = '1'
                        AND pys_cursosmodulos.estCurso = '1' AND pys_actsolicitudes.idSer = '' AND pys_proyectos.idProy = '$idProy';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                        echo "<script> alert('Tiene SOLICITUDES INICIALES en Cola.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la página anterior
                    } else {
                        /** Insert de la información en la tabla pys_actualizacionproy */
                        $consulta3 = "INSERT INTO pys_actualizacionproy VALUES
                            ('$idActProy', '$facDepto', '$idProy', '$tipoProy', '$tipoIntExt', '$frente', '$estProy', '$etpProy', '$codProy', '$nomProy', '$nomCortoProy', '$descProy', $fechaIni, $fechaFin, NOW(), '$conv', '$idPersona', '', '$presupuesto', '$financia', $fechaColciencias, '$semanas', '$fteFinancia', '$celula', '1');";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        
                        /** Consulta de la fecha mínima de las actualizaciones con estado 1 en la tabla pys_actualizacionproyecto */
                        $consulta4 = "SELECT MIN(pys_actualizacionproy.fechaActualizacionProy) FROM pys_actualizacionproy WHERE pys_actualizacionproy.est= '1' AND pys_actualizacionproy.idProy = '$idProy';";
                        $resultado4 = mysqli_query($connection, $consulta4);
                        $datos4 = mysqli_fetch_array($resultado4);
                        $fecha = $datos4[0];

                        /** Cambio de estado a la actualización inmediatamente anterior */
                        $consulta5 = "UPDATE pys_actualizacionproy SET est = '2' WHERE pys_actualizacionproy.idProy = '$idProy' AND pys_actualizacionproy.fechaActualizacionProy = '$fecha';";
                        $resultado5 = mysqli_query($connection, $consulta5);

                        /** Comprobación de la información registrada en la tabla pys_cruceproypep */
                        $consulta6 = "SELECT idCruce FROM pys_cruceproypep WHERE idProy = '$idProy' AND estado = '1';";
                        $resultado6 = mysqli_query($connection, $consulta6);
                        if ($registros6 = mysqli_num_rows($resultado6) > 0) {
                            $consulta7 = "INSERT INTO pys_cruceproypep VALUES (NULL, '$pep', '$idProy', NOW(), NULL, '1');";
                            $resultado7 = mysqli_query($connection, $consulta7);
                            /** Cambio de estado de la anterior asignación del elemento PEP en la tabala pys_cruceproypep */
                            $consulta8 = "SELECT MIN(fechaAsignacion) FROM pys_cruceproypep WHERE idProy = '$idProy' AND estado = '1';";
                            $resultado8 = mysqli_query($connection, $consulta8);
                            $datos8 = mysqli_fetch_array($resultado8);
                            $consulta9 = "UPDATE pys_cruceproypep SET estado = '2' WHERE idProy = '$idProy' AND fechaAsignacion = '$datos8[0]';";
                            $resultado9 = mysqli_query($connection, $consulta9);
                        } else {
                            /** Insert de la información en la tabla pys_cruceproypep */
                            $consulta9 = "INSERT INTO pys_cruceproypep VALUES (NULL, '$pep', '$idProy', NOW(), NULL, '1');";
                            $resultado9 = mysqli_query($connection, $consulta9);
                        }

                        if ($resultado3 && $resultado5 && $resultado6 && $resultado9) {
                            if (mysqli_query($connection, "COMMIT;")) {
                                echo "<script> alert('El registro se ACTUALIZÓ correctamente.');</script>";
                                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la p�gina anterior
                            }                            
                        } else {
                            if (mysqli_query($connection, "ROLLBACK;")) {
                                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la p�gina anterior
                            }
                        }
                    }
                } else {
                    /** Actualización de la información en la tabla pys_actualizacionproy */
                    $consulta3 = "INSERT INTO pys_actualizacionproy VALUES
                            ('$idActProy', '$facDepto', '$idProy', '$tipoProy', '$tipoIntExt', '$frente', '$estProy', '$etpProy', '$codProy', '$nomProy', '$nomCortoProy', '$descProy', $fechaIni, $fechaFin, NOW(), '$conv', '$idPersona', '', '$presupuesto', '$financia', $fechaColciencias, '$semanas', '$fteFinancia', '$celula', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    /** Consulta de la fecha mínima de las actualizaciones con estado 1 en la tabla pys_actualizacionproyecto */
                    $consulta4 = "SELECT MIN(pys_actualizacionproy.fechaActualizacionProy) FROM pys_actualizacionproy WHERE pys_actualizacionproy.est= '1' AND pys_actualizacionproy.idProy = '$idProy';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    $datos4 = mysqli_fetch_array($resultado4);
                    $fecha = $datos4[0];
                    /** Cambio de estado a la actualización inmediatamente anterior */
                    $consulta5 = "UPDATE pys_actualizacionproy SET est = '2' WHERE pys_actualizacionproy.idProy = '$idProy' AND pys_actualizacionproy.fechaActualizacionProy = '$fecha';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    /** Comprobación de la información registrada en la tabla pys_cruceproypep */
                    $consulta6 = "SELECT idCruce FROM pys_cruceproypep WHERE idProy = '$idProy' AND estado = '1';";
                    $resultado6 = mysqli_query($connection, $consulta6);
                    if ($registros6 = mysqli_num_rows($resultado6) > 0) {
                        $consulta7 = "INSERT INTO pys_cruceproypep VALUES (NULL, '$pep', '$idProy', NOW(), NULL, '1');";
                        $resultado7 = mysqli_query($connection, $consulta7);
                        /** Cambio de estado de la anterior asignación del elemento PEP en la tabla pys_cruceproypep */
                        $consulta8 = "SELECT MIN(fechaAsignacion) FROM pys_cruceproypep WHERE idProy = '$idProy' AND estado = '1';";
                        $resultado8 = mysqli_query($connection, $consulta8);
                        $datos8 = mysqli_fetch_array($resultado8);
                        $consulta9 = "UPDATE pys_cruceproypep SET estado = '2' WHERE idProy = '$idProy' AND fechaAsignacion = '$datos8[0]';";
                        $resultado9 = mysqli_query($connection, $consulta9);
                    } else {
                        /** Insert de la información en la tabla pys_cruceproypep */
                        $consulta9 = "INSERT INTO pys_cruceproypep VALUES (NULL, '$pep', '$idProy', NOW(), NULL, '1');";
                        $resultado9 = mysqli_query($connection, $consulta9);
                    }

                    if ($resultado3 && $resultado5 && $resultado6 && $resultado9) {
                        if (mysqli_query($connection, "COMMIT;")) {
                            echo "<script> alert('El registro se ACTUALIZÓ correctamente.');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la página anterior
                        }
                    } else {
                        if (mysqli_query($connection, "ROLLBACK;")) {
                            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos al usuario a la página anterior
                        }
                    }
                }   
            }
        }
        mysqli_close($connection);
    }

}


?>