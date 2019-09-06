<?php

    class ElementoPep {

        public static function onLoadElementoPep ($idElemento) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_elementospep WHERE idElemento = '$idElemento' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal () {
            require('../Core/connection.php');
            $consulta = "SELECT idElemento, nombreElemento, codigoElemento, ceco 
                FROM pys_elementospep
                INNER JOIN pys_centrocostos ON pys_centrocostos.idCeco = pys_elementospep.idCeco
                WHERE pys_elementospep.estado = '1' ORDER BY nombreElemento;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Código Elemento PEP</th>
                                    <th>Nombre Elemento PEP</th>
                                    <th>Centro de Costos</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['codigoElemento'].'</td>
                                    <td>'.$datos['nombreElemento'].'</td>
                                    <td>'.$datos['ceco'].'</td>
                                    <td><a href="#modalElementosPep" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalElementosPep.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                </table>';
            } else {
                echo "<h5 class='red-text'>No hay elementos PEP creados en el sistema.</h5>";
            }
            mysqli_close($connection);
        }

        public static function busqueda ($busqueda) {
            require('../Core/connection.php');
            $consulta = "SELECT idElemento, nombreElemento, codigoElemento, ceco
                FROM pys_elementospep
                INNER JOIN pys_centrocostos ON pys_centrocostos.idCeco = pys_elementospep.idCeco
                WHERE pys_elementospep.estado = '1' AND (pys_elementospep.nombreElemento LIKE '%$busqueda%' OR pys_elementospep.codigoElemento LIKE '%$busqueda%' OR pys_centrocostos.ceco LIKE '%$busqueda%') ORDER BY nombreElemento;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Código Elemento PEP</th>
                                    <th>Nombre Elemento PEP</th>
                                    <th>Centro de Costos</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['codigoElemento'].'</td>
                                    <td>'.$datos['nombreElemento'].'</td>
                                    <td>'.$datos['ceco'].'</td>
                                    <td><a href="#modalElementosPep" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalElementosPep.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                </table>';
            } else {
                echo "<h5 class='red-text'>No hay resultados para la busqueda: $busqueda.</h5>";
            }
            mysqli_close($connection);
        }

        public static function registrarElementoPep ($nombrePep, $codigoPep, $idCeco) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            if ($nombrePep != null && $nombrePep != " " && $codigoPep != null && $codigoPep != " " && $idCeco != null) {
                /** Verificación que la información no esté duplicada en la tabla */
                $consulta = "SELECT nombreElemento, codigoElemento FROM pys_elementospep WHERE nombreElemento = '$nombrePep' OR codigoElemento = '$codigoPep';";
                $resultado = mysqli_query($connection, $consulta);
                if ($registros = mysqli_num_rows($resultado) == 0) {
                    /** Generación del ID a registrar en la tabla */
                    $consulta2 = "SELECT COUNT(idElemento), MAX(idElemento) FROM pys_elementospep;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $count = $datos2[0];
                    $max = $datos2[1];
                    if ($count == 0) {
                        $idElemento = "PEP001";
                    } else {
                        $idElemento = "PEP".substr(substr($max, 4) + 1001, 1);
                    }
                    /** Inserción de los datos en la tabla pys_elementospep */
                    $consulta3 = "INSERT INTO pys_elementospep VALUES ('$idElemento', '$nombrePep', '$codigoPep', '$idCeco', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($resultado3) {
                        mysqli_query($connection, "COMMIT;");
                        echo "<script> alert('Registro guardado correctamente.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
                    } else {
                        mysqli_query($connection, "ROLLBACK;");
                        echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
                    }
                } else {
                    echo "<script> alert('Ya existe un registro con el código o nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
                }
            } else {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarElementoPep ($idElemento, $nombrePep, $codigoPep, $idCeco) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            /** Se verifica que la información halla sido modificada respecto a lo que se encuentra en la tabla */
            $consulta = "SELECT nombreElemento, codigoElemento, idCeco FROM pys_elementospep WHERE idElemento = '$idElemento' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($nombrePep == $datos['nombreElemento'] && $codigoPep == $datos['codigoElemento'] && $idCeco == $datos['idCeco']) {
                echo "<script> alert('La información ingresada es igual. Registro no actualizado');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
            } else {
                $consulta2 = "UPDATE pys_elementospep SET nombreElemento = '$nombrePep', codigoElemento = '$codigoPep', idCeco = '$idCeco' WHERE idElemento = '$idElemento' AND estado = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2) {
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('Registro actualizado correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
                } else {
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert('Ocurrió un error al intentar actualizar el registro.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/elementosPep.php">';
                }
            }
            mysqli_close($connection);
        }

        public static function selectCeco ($idElemento) {
            require('../Core/connection.php');
            $consulta = "SELECT idCeco, ceco FROM pys_centrocostos WHERE estado = '1' ORDER BY ceco;";
            $resultado = mysqli_query($connection, $consulta);
            if ($idElemento == null) {
                if ($registros = mysqli_num_rows($resultado) > 0) {
                    echo '  <select name="sltCeco" id="sltCeco" required class="validate">
                                <option value="" disabled selected>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '  <option value="'.$datos['idCeco'].'">'.$datos['ceco'].'</option>';
                    }
                    echo '  </select>
                            <label for="sltCeco">Centro de Costos *</label>';
                } else {
                    echo "<h5>No hay Centros de Costos creados en el sistema.</h5>";
                }
            } else {
                $consulta2 = "SELECT idCeco FROM pys_elementospep WHERE estado = '1' AND idElemento = '$idElemento';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                if ($registros = mysqli_num_rows($resultado) > 0) {
                    $string = '     <select name="sltCeco2" required class="validate">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idCeco'] == $datos2['idCeco']) {
                            $string .= '    <option value="'.$datos['idCeco'].'" selected>'.$datos['ceco'].'</option>';
                        } else {
                            $string .= '    <option value="'.$datos['idCeco'].'">'.$datos['ceco'].'</option>';
                        }
                    }
                    $string .= '    </select>
                                    <label for="sltCeco2">Centro de Costos *</label>';
                    return $string;
                } else {
                    echo "<h5>No hay Centros de Costos creados en el sistema.</h5>";
                }
            }
            mysqli_close($connection);
        }
        
    }

?>