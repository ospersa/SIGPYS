<?php
    class CentroCosto {

        public static function onLoadCentroCosto ($idCeco) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_centrocostos WHERE idCeco = '$idCeco' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal () {
            require('../Core/connection.php');
            $consulta = "SELECT idCeco, ceco, nombre FROM pys_centrocostos WHERE estado = '1' ORDER BY ceco;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table left">
                            <thead>
                                <tr>
                                    <th>Código Centro de Costos</th>
                                    <th>Nombre Centro de Costos</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['ceco'].'</td>
                                    <td>'.$datos['nombre'].'</td>
                                    <td><a href="#modalCentroCosto" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCentroCosto.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                </table>';
            } else {
                echo "<h5 class='red-text'>No hay centros de costos creados en el sistema.</h5>";
            }
            mysqli_close($connection);
        }

        public static function busqueda ($busqueda) {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT idCeco, ceco, nombre FROM pys_centrocostos WHERE estado = '1' AND (ceco LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%') ORDER BY ceco;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table left">
                            <thead>
                                <tr>
                                    <th>Código Centro de Costos</th>
                                    <th>Nombre Centro de Costos</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['ceco'].'</td>
                                    <td>'.$datos['nombre'].'</td>
                                    <td><a href="#modalCentroCosto" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCentroCosto.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                </table>';
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarCeco ($codigo, $nombre) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            if ($codigo != null) {
                /** Verificación que la información no esté duplicada en la tabla */
                $consulta = "SELECT idCeco FROM pys_centrocostos WHERE ceco = '$codigo';";
                $resultado = mysqli_query($connection, $consulta);
                if (mysqli_num_rows($resultado) == 0) {
                    /** Generación del ID a registrar en la tabla */
                    $consulta2 = "SELECT COUNT(idCeco), MAX(idCeco) FROM pys_centrocostos;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $count = $datos2[0];
                    $max = $datos2[1];
                    if ($count == 0) {
                        $idCeco = "CECO001";
                    } else {
                        $idCeco = "CECO".substr(substr($max, 4) + 1001, 1);
                    }
                    /** Inserción de los datos en la tabla pys_centrocostos */
                    $codigo = mysqli_real_escape_string($connection, $codigo);
                    $nombre = mysqli_real_escape_string($connection, $nombre);
                    $consulta3 = "INSERT INTO pys_centrocostos VALUES ('$idCeco','$codigo', '$nombre', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($resultado3) {
                        mysqli_query($connection, "COMMIT;");
                        echo "<script> alert('Registro guardado correctamente.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
                    } else {
                        mysqli_query($connection, "ROLLBACK;");
                        echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
                    }
                } else {
                    echo "<script> alert('Ya existe un registro con el código ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
                }
            } else {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarCeco ($idCeco, $ceco, $nombre) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            /** Se verifica que la información halla sido modificada */
            $consulta = "SELECT ceco, nombre FROM pys_centrocostos WHERE idCeco = '$idCeco' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($ceco == $datos['ceco'] && $nombre == $datos['nombre']) {
                echo "<script> alert('La información ingresada es igual. Registro no actualizado');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
            } else {                
                $nombre = mysqli_real_escape_string($connection, $nombre);
                echo $consulta2 = "UPDATE pys_centrocostos SET ceco = '$ceco', nombre = '$nombre' WHERE idCeco = '$idCeco' AND estado = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2) {
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('Registro actualizado correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
                } else {
                    mysqli_query($connection, "ROLLBACK");
                    echo "<script> alert('Ocurrió un error al intentar actualizar el registro.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/centroCosto.php">';
                }
            }
            mysqli_close($connection);
        }

    }
?>