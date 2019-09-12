<?php

    Class FuenteFinanciamiento {

        public static function onLoadFuenteFinanciamiento ($id) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_fuentesfinanciamiento WHERE idFteFin = '$id' AND estado = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal () {
            require('../Core/connection.php');
            $consulta = "SELECT idFteFin, nombre, sigla FROM pys_fuentesfinanciamiento WHERE estado = '1' ORDER BY sigla;";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Sigla</th>
                                    <th>Nombre</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['sigla'].'</td>
                                    <td>'.$datos['nombre'].'</td>
                                    <td><a href="#modalFuenteFinanciamiento" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFuenteFinanciamiento.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo "<h5 class='red-text'>No hay Fuentes de Financiaci�n creadas en el sistema.</h5>";
            }
            mysqli_close($connection);
        }

        public static function busqueda ($busqueda) {
            require('../Core/connection.php');
            $consulta = "SELECT idFteFin, nombre, sigla FROM pys_fuentesfinanciamiento WHERE estado = '1' AND (nombre LIKE '%$busqueda%' OR sigla LIKE '%$busqueda%') ORDER BY sigla;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                echo '  <table class="responsive-table centered">
                            <thead>
                                <tr>
                                    <th>Sigla</th>
                                    <th>Nombre</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <tr>
                                    <td>'.$datos['sigla'].'</td>
                                    <td>'.$datos['nombre'].'</td>
                                    <td><a href="#modalFuenteFinanciamiento" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFuenteFinanciamiento.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarFuenteFinanciamiento ($sigla, $nombre) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            if ($sigla != null && $sigla != " ") {
                /** Verificación que la información no esté duplicada en la tabla */
                $consulta = "SELECT idFteFin FROM pys_fuentesfinanciamiento WHERE sigla = '$sigla';";
                $resultado = mysqli_query($connection, $consulta);
                if ($registros = mysqli_num_rows($resultado) == 0) {
                    /** Generación del ID a registrar en la tabla */
                    $consulta2 = "SELECT COUNT(idFteFin), MAX(idFteFin) FROM pys_fuentesfinanciamiento;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $count = $datos2[0];
                    $max = $datos2[1];
                    if ($count == 0) {
                        $idFuente = "FF0001";
                    } else {
                        $idFuente = "FF".substr(substr($max, 2) + 10001, 1);
                    }
                    /** Inserción de los datos en la tabla pys_fuentesfinanciamiento */
                    $consulta3 = "INSERT INTO pys_fuentesfinanciamiento VALUES ('$idFuente', '$nombre', '$sigla', '1');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    if ($resultado3) {
                        if (mysqli_query($connection, "COMMIT;")){
                            echo "<script> alert('Registro guardado correctamente.');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
                        }
                    } else {
                        if (mysqli_query($connection, "ROLLBACK;")) {
                            echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
                        }
                    }
                } else {
                    echo "<script> alert('Ya existe un registro con el código ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
                }
            } else {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarFuenteFinanciamiento ($id, $sigla, $nombre) {
            require('../Core/connection.php');
            mysqli_query($connection, "BEGIN;");
            /** Se verifica que la información halla sido modificada respecto a lo que se encuentra en la tabla */
            $consulta = "SELECT nombre, sigla FROM pys_fuentesfinanciamiento WHERE idFteFin = '$id';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($nombre == $datos['nombre'] && $sigla == $datos['sigla']) {
                echo "<script> alert('La información ingresada es igual. Registro no actualizado');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
            } else {
                $consulta2 = "UPDATE pys_fuentesfinanciamiento SET nombre = '$nombre', sigla = '$sigla' WHERE idFteFin = '$id' AND estado = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2) {
                    if (mysqli_query($connection, "COMMIT;")) {
                        echo "<script> alert('Registro actualizado correctamente.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
                    }
                } else {
                    if (mysqli_query($connection, "ROLLBACK;")) {
                        echo "<script> alert('Ocurrió un error al intentar actualizar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/fuenteFinanciamiento.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

    }

?>