<?php

    Class Celula {

        public static function onLoadCelula ($idCelula) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_celulas WHERE idCelula = '$idCelula' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal () {
            require('../Core/connection.php');
            $personas = "";
            $consulta = "SELECT * FROM pys_celulas WHERE estado = '1' ORDER BY nombreCelula;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Nombre Célula</th>
                                    <th>Coordinador(es)</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $consulta2 = "SELECT apellido1, apellido2, nombres FROM pys_celulascoordinador
                        INNER JOIN pys_personas ON pys_personas.idPersona = pys_celulascoordinador.idPersona
                        WHERE idCelula = '".$datos['idCelula']."'
                        AND pys_celulascoordinador.estado = '1';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                        while ($datos2 = mysqli_fetch_array($resultado2)) {
                            $nombreCompleto = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                            $personas .= "<div class='chip'>".strtoupper($nombreCompleto)."</div>";
                        }
                    }
                    echo '      <tr>
                                    <td>'.$datos['nombreCelula'].'</td>
                                    <td>'.$personas.'</td>
                                    <td><a href="#modalCelula" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCelula.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                    $personas = "";
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo "<h5 class='red-text'>No hay células creadas en el sistema.</h5>";
            }
            mysqli_close($connection);
        }

        public static function busqueda ($busqueda) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_celulas WHERE estado = '1' AND nombreCelula LIKE '%$busqueda%' ORDER BY nombreCelula;";
            $resultado = mysqli_query($connection, $consulta);
            $personas = "";
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Nombre Célula</th>
                                    <th>Coordinador(es)</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $consulta2 = "SELECT apellido1, apellido2, nombres FROM pys_celulascoordinador
                        INNER JOIN pys_personas ON pys_personas.idPersona = pys_celulascoordinador.idPersona
                        WHERE idCelula = '".$datos['idCelula']."'
                        AND pys_celulascoordinador.estado = '1';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                        while ($datos2 = mysqli_fetch_array($resultado2)) {
                            $nombreCompleto = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                            $personas .= "<div class='chip'>".strtoupper($nombreCompleto)."</div>";
                        }
                    }
                    echo '      <tr>
                                    <td>'.$datos['nombreCelula'].'</td>
                                    <td>'.$personas.'</td>
                                    <td><a href="#modalCelula" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCelula.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                    $personas = "";
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo "<h5 class='red-text'>No hay resultados para la busqueda: <span class='teal-text'>$busqueda</span>.</h5>";
            }
            mysqli_close($connection);
        }

        public static function registrarCelula ($nombreCelula, $coordinacion) {
            if ($nombreCelula != null) {
                $begin = mysqli_query($connection, "BEGIN;");
                require('../Core/connection.php');
                /** Generación del ID para la tabla pys_celulas */
                $consulta = "SELECT COUNT(idCelula), MAX(idCelula) FROM pys_celulas;";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $count = $datos[0];
                $max = $datos[1];
                if ($count == 0) {
                    $idCelula = "CEL001";
                } else {
                    $idCelula = "CEL".substr(substr($max, 3) + 1001, 1);
                }
                /** Verificación para que no se creen registros duplicados */
                $consulta2 = "SELECT * FROM pys_celulas WHERE nombreCelula = '$nombreCelula';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros = mysqli_num_rows($resultado2) == 0) {
                    $consulta3 = "INSERT INTO pys_celulas VALUES ('$idCelula', '$nombreCelula', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $guardarCoor = Celula::guardarCoordinador($idCelula, $coordinacion);
                    if ($resultado3 && $guardarCoor) {
                        if  ($commit = mysqli_query($connection, "COMMIT;")) {
                            echo "<script> alert('Registro guardado correctamente.');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
                        }
                    } else {
                        if ($rollback = mysqli_query($connection, "ROLLBACK;")) {
                            echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
                        }
                    }
                } else {
                    echo "<script> alert('Ya existe un registro con el nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
                }
                mysqli_close($connection);
            } else {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/celulas.php">';
            }
        }

        public static function guardarCoordinador($celula, $coordinador) {
            require '../Core/connection.php';
            mysqli_query($connection, "BEGIN;");
            for ($i=0; $i < count($coordinador); $i++) {
                $cons = "   SELECT pys_celulascoordinador.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                                FROM pys_celulascoordinador 
                                INNER JOIN pys_personas ON pys_personas.idPersona = pys_celulascoordinador.idPersona
                                WHERE pys_celulascoordinador.idCelula = '$celula' AND pys_celulascoordinador.idPersona = '$coordinador[$i]' AND pys_celulascoordinador.estado = '1';";
                $res = mysqli_query($connection, $cons);
                $infoDB = mysqli_fetch_array($res);
                $nombre = $infoDB['nombres']." ".$infoDB['apellido1']." ".$infoDB['apellido2'];
                if ($infoDB['idPersona'] == $coordinador[$i]) {
                    echo "<script> alert('".strtoupper($nombre)." ya está asignado como coordinador.');</script>";
                } else {
                    $consulta = "INSERT INTO pys_celulascoordinador (idCelula, idPersona, estado) VALUES ('$celula', '$coordinador[$i]', '1');";
                    $resultado = mysqli_query($connection, $consulta);
                    if ($resultado) {
                        $guardado += 1;
                    }
                }
            }
            if ($guardado > 0) {
                mysqli_query($connection, "COMMIT;");
                return true;
            } else {
                mysqli_query($connection, "ROLLBACK;");
                return false;
            }
            mysqli_close($connection);
        }

        public static function eliminarCoordinador($celula, $coordinador) {
            require '../Core/connection.php';
            mysqli_query($connection, "BEGIN;");
            for ($i=0; $i < count($coordinador) ; $i++) { 
                $consulta = "UPDATE pys_celulascoordinador SET estado = '2' WHERE idPersona = '$coordinador[$i]' AND idCelula = '$celula';";
                $resultado = mysqli_query($connection, $consulta);
                if ($resultado) {
                    $eliminado += 1;
                }
            }
            if ($eliminado > 0) {
                mysqli_query($connection, "COMMIT;");
                return true;
            } else {
                mysqli_query($connection, "ROLLBACK;");                
                return false;
            }
            mysqli_close($connection);
        }

        public static function actualizarCelula ($idCelula, $nombreCelula, $eliminar, $agregar) {
            require '../Core/connection.php';
            mysqli_query($connection, "BEGIN;");
            $consulta = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($nombreCelula == $datos['nombreCelula'] && $agregar == null && $eliminar == null) {
                echo "<script> alert('La información ingresada es igual. Registro no actualizado');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
            } else {
                if ($nombreCelula != $datos['nombreCelula'] ) {
                    $consulta2 = "UPDATE pys_celulas SET nombreCelula = '$nombreCelula' WHERE idCelula = '$idCelula';";
                    $resultado2 = mysqli_query($connection, $consulta2);   
                } 
                if ($agregar != null) {
                    $resultado3 = Celula::guardarCoordinador($idCelula, $agregar);
                }
                if ($eliminar != null) {
                    $resultado4 = Celula::eliminarCoordinador($idCelula, $eliminar);
                }
                if ($resultado2 || $resultado3 || $resultado4) {
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('Registro actualizado correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
                } else {
                    mysqli_query($connection, "ROLLBACK;");
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/celula.php">';
                }
            }
            mysqli_close($connection);
        }

        public static function selectCoordinador ($required) {
            require '../Core/connection.php';
            $consulta = "SELECT idPersona, nombres, apellido1, apellido2 FROM pys_personas WHERE est = '1' AND idFacDepto = 'FD0034' ORDER BY apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $string = ' <select name="sltCoordinador[]" id="sltCoordinador" multiple '.$required.'>
                                <option value="" disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '<option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                }
                $string .= '</select>
                            <label for="sltCoordinador">Coordinador(es)*</label>';
                return $string;
            }
            mysqli_close($connection);
        }

        public static function tablaCoordinadores ($celula) {
            require '../Core/connection.php';
            $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_personas.idPersona FROM pys_celulascoordinador
                            INNER JOIN pys_personas ON pys_personas.idPersona = pys_celulascoordinador.idPersona
                            WHERE idCelula = '".$celula."'
                            AND pys_celulascoordinador.estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                $tabla = '  <table class="table striped centered" id="tbl-coordinadores">
                                <thead>
                                    <tr>
                                        <th>Persona</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $tabla .= '     <tr>
                                        <td>'.strtoupper($nombreCompleto).'</td>
                                        <td>
                                            <label>
                                                <input type="checkbox" value="'.$datos['idPersona'].'" name="chkDeleteCoor[]"> <span></span>
                                            </label>
                                        </td>
                                    </tr>';
                }
                $tabla .= '     </tbody>
                            </table>
                            <label for="tbl-coordinadores" class="active"><strong>Coordinadores Asignados:</strong></label>
                            <div class="input-field">
                                '.Celula::selectCoordinador("").'
                            </div>';
                return $tabla;
            } else {
                echo Celula::selectCoordinador("");
            }
            mysqli_close($connection);
        }

    }

?>