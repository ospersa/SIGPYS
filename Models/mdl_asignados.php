<?php
    class Asignados
    {
        public static function onLoadAsignacion($idSol)
        {
            require('../Core/connection.php');
            $idSol = mysqli_real_escape_string($connection, $idSol);
            $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.est
                FROM pys_asignados
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
                WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_personas.est	= '1' AND pys_fases.est	= '1' AND pys_asignados.idAsig = '$idSol';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function asignarPersonaProy($idProy)
        {
            require('../Core/connection.php');
            $idProy = mysqli_real_escape_string($connection, $idProy);
            $consulta = "SELECT pys_frentes.idFrente, pys_proyectos.idProy, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy
                FROM pys_actualizacionproy
                INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
                INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
                INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
                INNER JOIN pys_tiposproy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
                INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
                INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona
                INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
                WHERE pys_frentes.est = '1' AND pys_proyectos.est = '1' AND pys_actualizacionproy.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_personas.est = '1' AND pys_convocatoria.est = '1' AND pys_actualizacionproy.idProy = '$idProy';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            echo '  <div id="content" class="center-align">
                    <h4>ASIGNAR PERSONAS A PROYECTO</h4>
                    <div class="container">
                        <form action="../Controllers/ctrl_asignados.php" method="post" autocomplete="off" class="col l12 m12 s12">
                            <input type="hidden" name="txtIdProy" value="'.$datos['idProy'].'">
                            <div class="row">
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtFrente" id="txtFrente" value="'.$datos['nombreFrente']." - ".$datos['descripcionFrente'].'" readonly>
                                    <label for="txtFrente">Frente</label>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtCodConectate" id="txtCodConectate" value="'.$datos['codProy'].'" readonly>
                                    <label for="txtCodConectate">Código Proyecto en Conecta-TE</label>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtNomProyecto" id="txtNomProyecto" value="'.$datos['nombreProy'].'" readonly>
                                    <label for="txtNomProyecto">Proyecto</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l3 m3 s12">
                                    '.Asignados::selectRol().'
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    '.Asignados::selectPersona().'
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    '.Asignados::selectFase().'
                                </div>
                                <div class="input-field col l1 m1 s12">
                                    <button class="btn waves-effect" type="submit" name="btnAsignar" id="btnAsignar">Asignar</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col l8 m8 s12 offset-l2 offset-m2">
                                '.Asignados::mostrarAsignados("Proyecto", $idProy).'
                            </div>
                        </div>
                    </div>';
            mysqli_close($connection);
        }

        public static function selectRol()
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_roles WHERE est = '1' ORDER BY nombreRol;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $string = '  <select name="sltRol" id="sltRol" class="asignacion">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '  <option value="'.$datos['idRol'].'">'.$datos['nombreRol'].'</option>';
                }
                $string .= '  </select>
                        <label for="sltRol">Rol*</label>';
            } else {
                echo "<script>alert ('No hay categorías creadas')</script>";
            }
            return $string;
            mysqli_close($connection);
        }

        public static function selectPersona()
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_personas WHERE est = '1' ORDER BY apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $string = '  <select name="sltPersona" id="sltPersona" class="asignacion">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '  <option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                }
                $string .= '  </select>
                        <label for="sltPersona">Persona*</label>';
            } else {
                echo "<script>alert ('No hay categorías creadas')</script>";
            }
            return $string;
            mysqli_close($connection);
        }
        
        public static function selectPersonaPys()
        {
            require('../Core/connection.php');
            $consulta = "SELECT idPersona, apellido1, apellido2, nombres
                            FROM pys_personas 
                            WHERE est = '1' 
                            AND ((idCargo = 'CAR012') OR (idCargo = 'CAR013') OR (idCargo = 'CAR014') OR (idCargo = 'CAR015') OR (idCargo = 'CAR016') OR (idCargo = 'CAR017') OR (idCargo = 'CAR019') OR (idCargo = 'CAR027') OR (idCargo = 'CAR033') OR (idCargo = 'CAR035') OR (idCargo = 'CAR036') OR (idCargo = 'CAR0340') OR (idCargo = 'CAR041'))
                            ORDER BY apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $string = '  <select name="sltPersona" id="sltPersona" class="asignacion">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '  <option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                }
                $string .= '  </select>
                        <label for="sltPersona">Persona*</label>';
            } else {
                echo "<script>alert ('No hay categorías creadas')</script>";
            }
            mysqli_close($connection);
            return $string;
        }

        public static function selectFase()
        {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_fases WHERE est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $string = '  <select name="sltFase" id="sltFase" class="asignacion">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '  <option value="'.$datos['idFase'].'">'.$datos['nombreFase'].'</option>';
                }
                $string .= '  </select>
                        <label for="sltFase">Fase*</label>';
            } else {
                echo "<script>alert ('No hay categorías creadas')</script>";
            }
            return $string;
            mysqli_close($connection);
        }

        public static function mostrarAsignados($tipo, $id)
        {
            require('../Core/connection.php');
            $string = "";
            if ($tipo == "Proyecto") {
                $consulta = "SELECT pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_proyectos.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.idAsig
                    FROM pys_asignados
                    INNER JOIN pys_proyectos ON pys_asignados.idProy = pys_proyectos.idProy
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
                    INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
                    INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                    INNER JOIN pys_roles ON pys_asignados.idRol = pys_roles.idRol
                    INNER JOIN pys_fases ON pys_asignados.idFase = pys_fases.idFase
                    INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
                    where pys_asignados.est = '1' AND pys_asignados.idSol = '' AND pys_asignados.idCurso = '' AND pys_fases.est = '1' AND pys_roles.est = '1' AND ((pys_personas.est = '1') OR (pys_personas.est = '0')) AND pys_actualizacionproy.est = '1' AND pys_proyectos.est = '1' AND pys_frentes.est = '1' AND pys_convocatoria.est = '1' AND pys_proyectos.idProy = '$id'
                    ORDER BY pys_roles.nombreRol;";
                $resultado = mysqli_query($connection, $consulta);
                if ($registros = mysqli_num_rows($resultado) > 0) {
                    $string = ' <table class="responsive-table centered">
                                    <thead>
                                        <tr>
                                            <th>Rol</th>
                                            <th>Asignado a</th>
                                            <th>Fase</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                        $string .= '    <tr>
                                            <td>'.$datos['nombreRol'].'</td>
                                            <td>'.strtoupper($nombreCompleto).'</td>
                                            <td>'.$datos['nombreFase'].'</td>
                                            <td><a href="asignados.php?sup='.$datos['idAsig'].'" title="Eliminar Asignación"><i class="material-icons red-text">delete</i></a></td>
                                        </tr>';
                    }
                    $string .= '    </tbody>
                                </table>';
                }
            } elseif ($tipo == "SolIni") {
                $consulta = "SELECT pys_asignados.idAsig, pys_asignados.maxhora, pys_asignados.maxmin, pys_roles.nombreRol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase
                    FROM pys_asignados
                    INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
                    WHERE pys_asignados.idSol = '$id' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_roles.est = '1' AND pys_personas.est = '1' AND pys_fases.est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    $string = ' <table class="responsive-table centered">
                                    <thead>
                                        <tr>
                                            <th>Rol</th>
                                            <th>Asignado a</th>
                                            <th>Fase</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        $nombreCompleto = strtoupper($datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres']);
                        $string .= '    <tr>
                                            <td>'.$datos['nombreRol'].'</td>
                                            <td>'.$nombreCompleto.'</td>
                                            <td>'.$datos['nombreFase'].'</td>
                                            <td><a href="asignados.php?sup='.$datos['idAsig'].'" title="Eliminar Asignación"><i class="material-icons red-text">delete</i></a></td>
                                        </tr>';
                    }
                    $string .= '    </tbody>
                                </table>';
                }
            } elseif ($tipo == "SolEsp") {
                $consulta = "SELECT pys_asignados.idAsig, pys_asignados.maxhora, pys_asignados.maxmin, pys_roles.nombreRol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase, pys_asignados.est
                    FROM pys_asignados
                    INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
                    WHERE pys_asignados.idSol = '$id' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_roles.est = '1' AND pys_personas.est = '1' AND pys_fases.est = '1'
                    ORDER BY pys_asignados.fechAsig DESC;";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    $string = ' <table class="responsive-table centered">
                                    <thead>
                                        <tr>
                                            <th>Rol</th>
                                            <th>Asignado a</th>
                                            <th>Fase</th>
                                            <th>Horas</th>
                                            <th>Minutos</th>
                                            <th>Estado</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        $nombreCompleto = strtoupper($nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres']);
                        $string .= '    <tr>
                                            <td>'.$datos['nombreRol'].'</td>
                                            <td>'.$nombreCompleto.'</td>
                                            <td>'.$datos['nombreFase'].'</td>
                                            <td>'.$datos['maxhora'].'</td>
                                            <td>'.$datos['maxmin'].'</td>';
                        if ($datos['est'] == '1') {
                            $string .= '    <td class="teal-text"><strong>Activo</strong></td>';
                        } else {
                            $string .= '    <td class="grey-text"><strong>Inactivo</strong></td>';
                        }
                        $string .= '        <td><a href="#modalAsignados" class="modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalAsignados.php'".');" title="Editar Asignación"><i class="material-icons teal-text">edit</i></a></td>
                                            <td><a href="asignados.php?sup='.$datos['idAsig'].'" title="Eliminar Asignación"><i class="material-icons red-text">delete</i></a></td>
                                        </tr>';
                    }
                    $string .= '    </tbody>
                                </table>
                                <form action="../Controllers/ctrl_cotizacion.php" method="post" class="col l12 m12 s12" autocomplete="off">
                                <div class="input-field col l6 m12 s12 offset-l3 ">
                                <button href="#modalCorreoAsignacion" class=" modal-trigger btn waves-effect waves-light" onclick="envioData('."'$id'".','."'modalCorreoAsignacion.php'".');">Enviar correo de asignación</button>
                            </div>
                            </form>';
                }
            } elseif ($tipo == "Cot") {
                $totalHoras = 0;
                $totalMinutos = 0;
                $totalRecurso = 0;
                $consulta = "SELECT pys_asignados.fechAsig, pys_personas.idPersona, pys_roles.nombreRol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idAsig, pys_asignados.est
                    FROM pys_asignados 
                    INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2')
                    AND pys_roles.est = '1'
                    AND idSol = '$id';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_num_rows($resultado);
                if ($datos > 0) {
                    $string = '
                        <div class="divider"></div>
                        <h5>Personas Asignadas:</h5>
                        <table class="responsive-table">
                        <thead>
                            <tr class="grey lighten-1">
                                <th class="center-align">Rol</th>
                                <th class="center-align">Asignado a</th>
                                <th class="center-align">Fase</th>
                                <th class="center-align">Horas</th>
                                <th class="center-align">Minutos</th>
                                <th class="center-align">Valor recurso</th>
                                <th>Estado</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($datos2 = mysqli_fetch_array($resultado)) {
                        $fecha = $datos2['fechAsig']; // Tomamos la fecha de asignación al producto para calcular el salario
                        if ($fecha == null) { // Si está en blanco procedemos a tomar la fecha de la Solicitud Específica
                            $consFechSolEsp = "SELECT fechSol FROM pys_solicitudes WHERE idSol = '$id';";
                            $resFechSolEsp = mysqli_query($connection, $consFechSolEsp);
                            $fechSolEsp = mysqli_fetch_array($resFechSolEsp);
                            $fecha = date("Y-m-d", strtotime($fechSolEsp['fechSol']));
                        } else { // De lo contrario traemos el valor almacenado en la tabla de pys_asignados (Este campo es nuevo en la tabla 21/11/2018)
                            $fecha = date("Y-m-d", strtotime($datos2['fechAsig']));
                        }
                        $idPersona = $datos2['idPersona'];
                        $consulta3 = "SELECT * 
                            FROM pys_asignados
                            INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona
                            WHERE pys_salarios.estSal = 1
                            AND pys_asignados.idSol = '$id'
                            AND pys_asignados.idPersona = '$idPersona'
                            AND '$fecha' >= mes 
                            AND '$fecha' <= anio;";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        $datos3 = mysqli_fetch_array($resultado3);
                        $salarioMinuto = $datos3['salario'] / 60;
                        $minutosAsignados = ($datos2['maxhora'] * 60) + $datos2['maxmin'];
                        $recurso = round($salarioMinuto * $minutosAsignados, 2);
                        $totalHoras = $totalHoras + $datos2['maxhora'];
                        $totalMinutos = $totalMinutos + $datos2['maxmin'];
                        $totalRecurso = $totalRecurso + $recurso;
                        if ($datos2['est'] == 1) {
                            $estado = "Activo";
                            $color = "teal lighten-5";
                        } else {
                            $estado = "Inactivo";
                            $color = "grey lighten-2";
                        }
                        $string .= '
                            <tr class="'.$color.'">
                                <td>'.$datos2['nombreRol'].'</td>
                                <td>'.$datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'].'</td>
                                <td>'.$datos2['nombreFase'].'</td>
                                <td class="center-align">'.$datos2['maxhora'].'</td>
                                <td class="center-align">'.$datos2['maxmin'].'</td>
                                <td class="center-align">$ '.number_format($recurso, 2, ",", ".").'</td>
                                <td>'.$estado.'</td>
                                <td><a href="#modalAsignados" class="waves-effect btn modal-trigger" onclick="envioData('."'$datos2[9]'".','."'modalAsignados.php'".')"><i class="material-icons" title="Editar asignación">edit</i></a></td>
                            </tr>';
                    }
                    if ($totalMinutos > 60) {
                        $totalHoras += floor($totalMinutos / 60);
                        $totalMinutos = (($totalMinutos / 60) - floor($totalMinutos / 60)) * 60;
                    }
                    $string .= '
                        </tbody>
                        <tfoot>
                        <tr class="grey lighten-1">
                            <div hidden><input name="txtTotalRecurso" id="txtTotalRecurso" value="'.$totalRecurso.'"></div>
                            <td colspan="3"><strong>Total asignaciones:</strong></td>
                            <td class="center-align"><strong>'.$totalHoras.'</strong></td>
                            <td class="center-align"><strong>'.$totalMinutos.'</strong></td>
                            <td class="center-align"><span id="txtTotalRecurso"><strong>$ '.number_format($totalRecurso, 2, ",", ".").'</strong></span></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                        </table>';
                } else {
                    $string = "<div class='divider'></div>
                    <div hidden><input name='txtTotalRecurso' id='txtTotalRecurso' value='0'></div>
                    <h5>Aún no se han asignado personas a esta solicitud.</h5>";
                }
            }
            return $string;
            mysqli_close($connection);
        }

        public static function eliminarAsignacion($idAsig)
        {
            require('../Core/connection.php');
            $idAsig = mysqli_real_escape_string($connection, $idAsig);
            /* Validación de tiempos registrados :: Si tiene tiempos registrados no se debe permitir la eliminación de la asignación */
            $query = "SELECT * FROM pys_tiempos WHERE idAsig = '$idAsig' AND estTiempo != '0';";
            $result = mysqli_query($connection, $query);
            $registry = mysqli_num_rows($result);
            if ($registry > 0) {
                echo "<script> alert('No se puede eliminar esta asignación porque hay tiempos registrados. Por favor contacte a la mesa de servicio.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                $consulta = "UPDATE pys_asignados SET est = '0' WHERE idAsig = '$idAsig';";
                $resultado = mysqli_query($connection, $consulta);
                if ($resultado) {
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                } else {
                    echo "<script> alert('Se presentó un error al eliminar la asignación. Por favor intente nuevamente');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                }
            }
            mysqli_close($connection);
        }

        public static function registrarAsignacion($rol, $persona, $fase, $idProy, $idCurso, $registra, $maxHora, $maxMin, $idSol)
        {
            require('../Core/connection.php');
            if ($maxHora == "") {
                $maxHora = 0;
            }
            if ($maxMin == "") {
                $maxMin = 0;
            }
            if (substr($idSol, 0, 1) == 'P') {
                $idSol = substr($idSol, 1);
            }
            $consulta = "SELECT idPersona FROM pys_login WHERE usrLogin = '$registra' AND est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $registra = $datos['idPersona'];
            if ($rol == null || $persona == null || $fase == null) {
                echo "<script> alert('Existe algún campo VACÍO. Registro no válido');</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                /** Validación de registros que existen en la tabla, para evitar duplicidad */
                $consulta2 = "SELECT idAsig FROM pys_asignados WHERE idFase = '$fase' AND idRol = '$rol' AND idPersona = '$persona' AND idProy = '$idProy' AND idSol = '$idSol' AND (est = '1' OR est = '2');";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registros2 = mysqli_num_rows($resultado2);
                if ($registros2 > 0) {
                    echo "<script> alert('Ya existe una asignación similar, por favor intente nuevamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                } else {
                    $consulta3 = "SELECT MAX(idAsig) FROM pys_asignados;";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $datos3 = mysqli_fetch_array($resultado3);
                    $idAsig = $datos3[0] + 1;
                    $consulta4 = "INSERT INTO pys_asignados VALUES ('$idAsig', '$idSol', '$idProy', '$idCurso', '$persona', '$rol', '$fase', '$registra', '0', '0', '$maxHora', '$maxMin', NOW(), '1');";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    if ($resultado4) {
                        echo "<script> alert('Se guardó correctamente el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                    } else {
                        echo "<script> alert('Se presentó un error al intentar guardar la información, por favor intente nuevamente.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarAsignacion($idAsig, $maxhora, $maxmin)
        {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_asignados SET maxhora = $maxhora, maxmin = $maxmin WHERE idAsig = '$idAsig';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado) {
                echo "<script> alert ('Se actualizó correctamente la información'); </script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            }
            mysqli_close($connection);
        }

        public static function cambiarEstadoAsignacion($idAsig, $estado)
        {
            require('../Core/connection.php');
            if ($estado == 1) {
                $consulta = "UPDATE pys_asignados SET est = '2' WHERE idAsig = '$idAsig';";
                $resultado = mysqli_query($connection, $consulta);
            } elseif ($estado == 2) {
                $consulta = "UPDATE pys_asignados SET est = '1' WHERE idAsig = '$idAsig';";
                $resultado = mysqli_query($connection, $consulta);
            }
            if ($resultado) {
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            } else {
                echo "<script>alert('Ocurrió un error al intentar inactivar la asignación. Por favor intente nuevamente.')</script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
            }
            mysqli_close($connection);
        }

        public static function asignarPersonaSolIni($idSolIni)
        {
            require('../Core/connection.php');
            $idSolIni = mysqli_real_escape_string($connection, $idSolIni);
            $consulta = "SELECT pys_actsolicitudes.idSol, pys_frentes.descripcionFrente, pys_frentes.nombreFrente, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.ObservacionAct, pys_actualizacionproy.idProy
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_actualizacionproy.idFrente
                WHERE idSol = '$idSolIni'
                AND pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estModulo = '1' AND pys_actualizacionproy.est = '1' AND pys_frentes.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $datos = mysqli_fetch_array($resultado);
                echo '  <div id="content" class="center-align">
                        <h4>ASIGNAR PERSONAS A SOLICITUD INICIAL</h4>
                        <div class="container">
                            <form action="../Controllers/ctrl_asignados.php" method="post" autocomplete="off" class="col l12 m12 s12">
                                <input type="hidden" name="txtIdProy" value="'.$datos['idProy'].'">
                                <div class="row">
                                    <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                        <input type="text" name="txtCodSol" id="txtCodSol" value="'.$datos['idSol'].'" readonly>
                                        <label for="txtCodSol">Código Solicitud Inicial</label>
                                    </div>
                                    <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                        <input type="text" name="txtFrente" id="txtFrente" value="'.$datos['nombreFrente']." - ".$datos['descripcionFrente'].'" readonly>
                                        <label for="txtFrente">Frente</label>
                                    </div>
                                    <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                        <input type="text" name="txtCodConectate" id="txtCodConectate" value="'.$datos['codProy'].'" readonly>
                                        <label for="txtCodConectate">Código Proyecto en Conecta-TE</label>
                                    </div>
                                    <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                        <input type="text" name="txtNomProyecto" id="txtNomProyecto" value="'.$datos['nombreProy'].'" readonly>
                                        <label for="txtNomProyecto">Proyecto</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col l3 m3 s12">
                                        '.Asignados::selectRol().'
                                    </div>
                                    <div class="input-field col l4 m4 s12">
                                        '.Asignados::selectPersona().'
                                    </div>
                                    <div class="input-field col l4 m4 s12">
                                        '.Asignados::selectFase().'
                                    </div>
                                    <div class="input-field col l1 m1 s12">
                                        <button class="btn waves-effect" type="submit" name="btnAsignar" id="btnAsignar">Asignar</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col l12 m12 s12">
                                    '.Asignados::mostrarAsignados("SolIni", $idSolIni).'
                                </div>
                            </div>
                        </div>';
            }
            
            mysqli_close($connection);
        }

        public static function asignarPersonaSolEsp($idSol)
        {
            require('../Core/connection.php');
            $idSol = mysqli_real_escape_string($connection, $idSol);
            $consulta = "SELECT pys_actsolicitudes.idSol, pys_frentes.descripcionFrente, pys_frentes.nombreFrente, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.ObservacionAct, pys_actualizacionproy.idProy, pys_actsolicitudes.registraTiempo
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_actualizacionproy.idFrente
                WHERE idSol = '$idSol'
                AND pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estModulo = '1' AND pys_actualizacionproy.est = '1' AND pys_frentes.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $datos = mysqli_fetch_array($resultado);
                echo '  <div id="content" class="center-align">
                    <h4>ASIGNAR PERSONAS A SOLICITUD ESPECÍFICA</h4>
                    <div class="container">
                        <form action="../Controllers/ctrl_asignados.php" method="post" autocomplete="off" class="col l12 m12 s12">
                            <input type="hidden" name="txtIdProy" value="'.$datos['idProy'].'">
                            <div class="row">
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtCodSol" id="txtCodSol" value="P'.$datos['idSol'].'" readonly>
                                    <label for="txtCodSol">Código Solicitud Específica</label>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <label class ="active" for="txtDesc">Descripcion Solicitud Especifica</label>
                                    <p id="txtDesc" class= "truncate">'.$datos['ObservacionAct'].'</p>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtFrente" id="txtFrente" value="'.$datos['nombreFrente']." - ".$datos['descripcionFrente'].'" readonly>
                                    <label for="txtFrente">Frente</label>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtCodConectate" id="txtCodConectate" value="'.$datos['codProy'].'" readonly>
                                    <label for="txtCodConectate">Código Proyecto en Conecta-TE</label>
                                </div>
                                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                    <input type="text" name="txtNomProyecto" id="txtNomProyecto" value="'.$datos['nombreProy'].'" readonly>
                                    <label for="txtNomProyecto">Proyecto</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l3 m3 s12">
                                    '.Asignados::selectRol().'
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    '.Asignados::selectPersonaPys().'
                                </div>
                                <div class="input-field col l2 m4 s12">
                                    '.Asignados::selectFase().'
                                </div>';
                if ($datos['registraTiempo'] == 1) {
                    echo ' <div class="input-field col l1 m1 s12">
                                        <input type="number" name="txtHoras" id="txtHoras" placeholder="0" class="asignacion">
                                        <label for="txtHoras">Horas</label>
                                    </div>
                                    <div class="input-field col l1 m1 s12">
                                        <input type="number" name="txtMinutos" id="txtMinutos" min="0" max="59" placeholder="0" class="asignacion">
                                        <label for="txtMinutos">Minutos</label>
                                    </div>';
                } else {
                    echo '  <input type="hidden" name="txtHoras" id="txtHoras" placeholder="0" class="asignacion" value="0">
                            <input type="hidden" name="txtMinutos" id="txtMinutos" min="0" max="59" placeholder="0" class="asignacion" value="0">
                    ';
                }
                echo '<div class="input-field col l1 m1 s12">
                                    <button class="btn waves-effect" type="submit" name="btnAsignar" id="btnAsignar">Asignar</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col l12 m12 s12">
                                '.Asignados::mostrarAsignados("SolEsp", $idSol).'
                            </div>
                        </div>
                    </div>';
            }
            mysqli_close($connection);
        }

        public static function infoEnviarCorreoSolIni($idSol)
        {
            require('../Core/connection.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2, pys_personas.correo , pys_proyectos.codProy, pys_proyectos.nombreProy   
                FROM pys_solicitudes 
                INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSolIni = pys_actsolicitudes.idSol
                INNER JOIN pys_personas ON pys_actsolicitudes.idSolicitante = pys_personas.idPersona
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
                INNER JOIN pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
                WHERE pys_solicitudes.est = '1' AND pys_solicitudes.idSol = '$idSol' AND pys_actsolicitudes.est = '1' AND pys_personas.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function infoEnviarCorreoAsig($idSol)
        {
            require('../Core/connection.php');
            $consulta = "SELECT  pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSol, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.fechPrev FROM pys_solicitudes
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_personas ON pys_solicitudes.idPersona = pys_personas.idPersona
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            WHERE pys_solicitudes.est = '1'  AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1'  AND pys_personas.est = '1' AND pys_cursosmodulos.estProy = '1'  AND pys_solicitudes.idSol = '$idSol'";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function infoEnviarCorreoResponsable($idSol)
        {
            require('../Core/connection.php');
            $string ="";
            $consulta = "SELECT  pys_personas.correo, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase
				FROM pys_asignados
				INNER JOIN pys_solicitudes ON pys_asignados.idSol = pys_solicitudes.idSol
				INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
				INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
				INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
				INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
				INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
				INNER JOIN pys_roles ON pys_asignados.idRol = pys_roles.idRol
				INNER JOIN pys_fases ON pys_asignados.idFase = pys_fases.idFase
				WHERE pys_asignados.est = '1' AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_actualizacionproy.est = '1' AND pys_proyectos.est = '1' AND ((pys_personas.est = '1') or (pys_personas.est = '0'))  AND pys_roles.est = '1' AND pys_fases.est = '1' AND pys_actsolicitudes.idSol = '$idSol';";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos = mysqli_fetch_array($resultado)) {
                $correo=$datos['correo'];
                $apellido1=$datos['apellido1'];
                $apellido2=$datos['apellido2'];
                $nombres=$datos['nombres'];
                $nombreRol=$datos['nombreRol'];
                $nombreFase=$datos['nombreFase'];
                $string .= "    <strong>Responsable: </strong>$nombres $apellido1 $apellido2 <br />
                                <strong>Rol: </strong>$nombreRol <br />
                                <strong>Fase: </strong>$nombreFase <br />
                                <strong>Correo: </strong>$correo <br />
                                <br />";
            }
            return $string;
            mysqli_close($connection);
        }
        public static function correosResponsable($idSol)
        {
            require('../Core/connection.php');
            $string ="";
            $consulta = "SELECT  pys_personas.correo
				FROM pys_asignados
				INNER JOIN pys_solicitudes ON pys_asignados.idSol = pys_solicitudes.idSol
				INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
				INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
				INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
				INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
				INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
				INNER JOIN pys_roles ON pys_asignados.idRol = pys_roles.idRol
				INNER JOIN pys_fases ON pys_asignados.idFase = pys_fases.idFase
				WHERE pys_asignados.est = '1' AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_actualizacionproy.est = '1' AND pys_proyectos.est = '1' AND ((pys_personas.est = '1') or (pys_personas.est = '0'))  AND pys_roles.est = '1' AND pys_fases.est = '1' AND pys_actsolicitudes.idSol = '$idSol'";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos = mysqli_fetch_array($resultado)) {
                $count = count($datos);
                $correo=$datos['correo'];
                $string .= $correo.";";
            }
            $string = substr($string, 0, -1);
            mysqli_close($connection);
            return $string;
        }

        public static function correosAsesorGestorRed($idSol)
        {
            require('../Core/connection.php');
            $asesoresGestores = '';
            $query = "SELECT pys_personas.correo
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_asignados ON pys_asignados.idProy = pys_cursosmodulos.idProy AND pys_asignados.est = '1'
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona AND pys_personas.est = '1'
                WHERE pys_actsolicitudes.idSol = '$idSol' AND pys_actsolicitudes.est = '1' AND pys_asignados.idSol = '';";
            $result = mysqli_query($connection, $query);
            $registry = mysqli_num_rows($result);
            if ($registry > 0) {
                while ($data = mysqli_fetch_array($result)) {
                    $correo = $data['correo'];
                    $asesoresGestores .= $correo . ';';
                }
                $asesoresGestores = substr($asesoresGestores, 0, -1);
            }
            mysqli_close($connection);
            return $asesoresGestores;
        }
    }
