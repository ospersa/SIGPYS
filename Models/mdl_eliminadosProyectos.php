<?php

    class EliminadosProyectos {

        public static function onLoadEliminados () {
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
            WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '0' AND pys_actualizacionproy.est = '0' AND pys_personas.est = '1' AND pys_convocatoria.est = '1'
            ORDER BY pys_proyectos.fechaCreacionProy DESC;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado and mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Facultad - Departamento</th>
                            <th>Estado</th>
                            <th>Tipo</th>
                            <th>Cod. Conecta-TE</th>
                            <th>Proyecto</th>
                            <th>Convocatoria</th>
                            <th>Creación</th>
                            <th>Última Actualización</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idProyecto = $datos['idProy'];
                    $presupuesto = ($datos['presupuestoProy'] == '') ? 0 : $datos['presupuestoProy'];
                            echo "      <tr>
                                    <td>".$datos['nombreEnt']."</td>
                                    <td>".$datos['facDeptoFacultad']." - ".$datos['facDeptoDepartamento']."</td>
                                    <td>".$datos['nombreEstProy']."</td>
                                    <td>".$datos['nombreTProy']."</td>
                                    <td>".$datos['nombreEtaProy']."</td>
                                    <td>".$datos['codProy']."</td>
                                    <td>".$datos['nombreProy']."</td>
                                    <td>".$datos['nombreConvocatoria']."</td>";
                    /** Comparación de fecha_creación vs fecha_actualización para dejar una clase de color diferente en la celda */
                    if ($datos['fechaCreacionProy'] != $datos['fechaActualizacionProy']) {
                        echo "      <td>".$datos['fechaCreacionProy']."</td>
                                    <td class='teal lighten-2'>".$datos['fechaActualizacionProy']."</td>";
                    } else {
                        echo "      <td>".$datos['fechaCreacionProy']."</td>
                                    <td>".$datos['fechaActualizacionProy']."</td>";
                    }
                    echo'
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idProyecto.'\', \'../Controllers/ctrl_eliminadosProyectos.php\', \'el Proyecto\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Proyectos Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarProyecto($id, $usuario) {
            require('../Core/connection.php');
            $consulta = "SELECT idPersona FROM pys_login where usrLogin = '$usuario' AND est = 1";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $persona = $datos['idPersona'];
            $consulta1 = "UPDATE pys_proyectos SET idResponRegistro = '$persona', motivoAnulacion = '', est = '1' WHERE idProy = '$id';";
            $resultado1 = mysqli_query($connection, $consulta1);
            /** Código para inactivación en la tabla pys_actualizacionproy */
            $consulta2 = "UPDATE pys_actualizacionproy SET idResponRegistro = '$persona', motivoAnulacion = '', est = '1' WHERE idProy = '$id' AND est = '0';";
            $resultado2 = mysqli_query($connection, $consulta2);
            /** Código para desactivación en la tabla pys_cursosmodulos */
            $consulta3 = "UPDATE pys_cursosmodulos SET estProy = '1' WHERE idProy = '$id';";
            $resultado3 = mysqli_query($connection, $consulta3);
            if ($resultado1 && $resultado2 && $resultado3){
                $string = 'Se actualizó correctamente la información';
            }else{
               $string = 'Ocurrió un error al intentar actualizar el registro';              
            }
            echo $string;
            mysqli_close($connection);
        }

       
        
    }

?>