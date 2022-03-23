<?php

Class Producto {

    public static function onLoadTipoProducto ($id) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_equipos.nombreEqu, pys_servicios.idSer, pys_servicios.nombreSer, pys_tiposproductos.idTProd, pys_tiposproductos.nombreTProd, pys_tiposproductos.descripcionTProd, pys_tiposproductos.costoSin, pys_tiposproductos.costoCon, pys_costos.costo, pys_costos.idClProd, pys_claseproductos.nombreClProd, pys_equipos.idEqu FROM pys_tiposproductos
            INNER JOIN pys_servicios ON pys_tiposproductos.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_costos ON pys_costos.idTProd = pys_tiposproductos.idTProd
            INNER JOIN pys_claseproductos ON pys_costos.idClProd = pys_claseproductos.idClProd
            WHERE pys_equipos.est = '1' AND pys_servicios.est = '1' AND pys_tiposproductos.est = '1' AND pys_costos.est = '1' AND pys_claseproductos.est = '1' AND pys_tiposproductos.idTProd='$id';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function onLoadClaseProducto ($id) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_equipos.nombreEqu, pys_servicios.idSer, pys_servicios.nombreSer, pys_claseproductos.idClProd, pys_claseproductos.nombreClProd, pys_claseproductos.nombreCortoClProd, pys_claseproductos.descripcionClProd, pys_costos.costo, pys_equipos.idEqu FROM pys_claseproductos
            INNER JOIN pys_servicios ON pys_claseproductos.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_costos ON pys_costos.idClProd = pys_claseproductos.idClProd
            WHERE pys_equipos.est = '1' AND pys_servicios.est = '1' AND pys_claseproductos.est = '1' AND pys_costos.est = '1' AND pys_costos.idTProd = '' AND pys_claseproductos.idClProd = '$id';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaTipoProductos ($busqueda) {
        require('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_tiposproductos.idTProd, pys_tiposproductos.nombreTProd, pys_tiposproductos.descripcionTProd, pys_tiposproductos.costoSin, pys_tiposproductos.costoCon, pys_costos.costo, pys_costos.idClProd, pys_claseproductos.nombreClProd 
            FROM pys_tiposproductos
            INNER JOIN pys_servicios ON pys_tiposproductos.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_costos ON pys_costos.idTProd = pys_tiposproductos.idTProd
            INNER JOIN pys_claseproductos ON pys_costos.idClProd = pys_claseproductos.idClProd
            WHERE pys_equipos.est = '1' AND pys_servicios.est = '1' AND pys_tiposproductos.est = '1' AND pys_costos.est = '1' AND pys_claseproductos.est = '1' AND ((pys_equipos.nombreEqu LIKE '%$busqueda%') OR (pys_servicios.nombreSer LIKE '%$busqueda%') OR (pys_claseproductos.nombreClProd LIKE '%$busqueda%') OR (pys_tiposproductos.nombreTProd LIKE '%$busqueda%'))
            ORDER BY pys_tiposproductos.nombreTProd;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            echo '  <table class=" left responsive-table">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Servicio</th>
                                <th>Clase de Producto</th>
                                <th>Tipo de Producto</th>
                                <th>Descripción</th>
                                <th>Costo sin $</th>
                                <th>Costo con $</th>
                                <th>Costo tipo $</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos['nombreEqu'].'</td>
                                <td>'.$datos['nombreSer'].'</td>
                                <td>'.$datos['nombreClProd'].'</td>
                                <td>'.$datos['nombreTProd'].'</td>
                                <td>'.$datos['descripcionTProd'].'</td>
                                <td>'.$datos['costoSin'].'</td>
                                <td>'.$datos['costoCon'].'</td>
                                <td>'.$datos['costo'].'</td>
                                <td><a href="#modalTipProducto" class="waves-effect waves-light modal-trigger" onclick="envioData(\''.$datos['idTProd'].'\',\'modalTipProducto.php\');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        mysqli_close($connection);
    }

    public static function busquedaClaseProductos ($busqueda) {
        require('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_claseproductos.idClProd, pys_claseproductos.nombreClProd, pys_claseproductos.nombreCortoClProd, pys_claseproductos.descripcionClProd
            FROM pys_claseproductos 
            INNER JOIN pys_servicios ON pys_claseproductos.idSer = pys_servicios.idSer AND pys_claseproductos.est = '1' 
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu AND pys_equipos.est = '1'
            WHERE pys_servicios.est = '1' AND ((pys_equipos.nombreEqu LIKE '%$busqueda%') OR (pys_servicios.nombreSer LIKE '%$busqueda%') OR (pys_claseproductos.nombreClProd LIKE '%$busqueda%') OR (pys_claseproductos.nombreCortoClProd LIKE '%$busqueda%')) 
            ORDER BY pys_equipos.nombreEqu;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            echo '  <table class="left responsive-table">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Servicio</th>
                                <th>Clase de Producto</th>
                                <th>Nombre Corto</th>
                                <th>Descripción</th>
                                <th>Costo</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '      <tr>
                                <td>'.$datos['nombreEqu'].'</td>
                                <td>'.$datos['nombreSer'].'</td>
                                <td>'.$datos['nombreClProd'].'</td>
                                <td>'.$datos['nombreCortoClProd'].'</td>
                                <td>'.$datos['descripcionClProd'].'</td>
                                <td></td>
                                <td><a href="#modalClaProducto" class="waves-effect waves-light modal-trigger" onclick="envioData(\''.$datos['idClProd'].'\',\'modalClaProducto.php\');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        mysqli_close($connection);
    }

    public static function registrarTipoProducto ($equipo, $servicio, $clase, $nombre, $descripcion, $costoSin, $costoCon, $costoTipo) {
        /** Método para realizar el registro de tipos de productos nuevos */
        require('../Core/connection.php');
        /** Verificación de campos vacíos */
        if ($equipo == null || $equipo == " " || $servicio == null || $servicio == " " || $clase == null || $clase == " " || $nombre == null || $nombre == " ") {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
        } else {
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            /** Verificación de información existente en la tabla, para evitar duplicidad */
            $consulta = "SELECT idCosto FROM pys_costos 
                INNER JOIN pys_tiposproductos ON pys_tiposproductos.idTProd = pys_costos.idTProd AND pys_tiposproductos.est = '1'
                WHERE pys_costos.idSer = '$servicio' AND pys_costos.idClProd = '$clase' AND pys_costos.est = '1' AND pys_tiposproductos.nombreTProd = '$nombre';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                echo "<script> alert('Ya existe un tipo de producto con la información ingresada. Por favor intente nuevamente');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
            } else {
                /** Obtención del ID a registrar en la tabla pys_tiposproductos */
                $consulta2 = "SELECT COUNT(idTProd), MAX(idTProd) FROM pys_tiposproductos;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                if ($datos2[0] == 0) {
                    $idTProd = "TPR001";
                } else {
                    $idTProd = "TPR".substr((substr($datos2[1], 3) + 1001), 1);
                }
                /** Obtención del ID a registrar en la tabla pys_costos */
                $consulta3 = "SELECT COUNT(idCosto), MAX(idCosto) FROM pys_costos;";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                if ($datos3[0] == "0") {
                    $idCosto = "C00001";
                } else {
                    $idCosto = "C".substr((substr($datos3[1], 1) + 100001), 1);
                }
                /** Preparación de los datos en la base de datos */
                mysqli_query($connection, "BEGIN;");
                /** Insert de la información en las tablas correspondientes */
                $consulta4 = "INSERT INTO pys_tiposproductos VALUES ('$idTProd', '$servicio', '$nombre', '$descripcion', '$costoSin', '$costoCon', '1');";
                $resultado4 = mysqli_query($connection, $consulta4);
                $consulta5 = "INSERT INTO pys_costos VALUES ('$idCosto', '$servicio', '$clase', '$idTProd', '$costoTipo', '1');";
                $resultado5 = mysqli_query($connection, $consulta5);
                if ($resultado4 && $resultado5) {
                    /** Se realiza COMMIT de la información en la base de datos */
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('El registro se insertó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
                } else {
                    /** Se realiza ROLLBACK de los posibles cambios realizados en la base de datos */
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser guardado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">'; 
                }
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarTipoProducto ($id, $servicio, $clase, $nombre, $descripcion, $costoSin, $costoCon, $costoTipo) {
        require('../Core/connection.php');
        /** Verificación de campos vacíos */
        if ($id == null || $servicio == null || $clase == null || $nombre == null) {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
        } else {
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);            
            /** Verificación información existente en la tabla para evitar duplicidad */
            $consulta = "SELECT * FROM pys_tiposproductos WHERE idTProd = '$id';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $consulta2 = "SELECT * FROM pys_costos WHERE idTProd = '$id';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            if ($servicio == $datos['idSer'] && $clase == $datos2['idClProd'] && $descripcion == $datos['descripcionTProd'] && $costoSin == $datos['costoSin'] && $costoCon == $datos['costoCon'] && $costoTipo == $datos2['costo']) {
                echo "<script> alert('La información ingresada es la misma. El registro no fue actualizado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
            } else {
                /** Preparación de la información en la base de datos */
                mysqli_query($connection, "BEGIN;");
                /** Se procede a realizar el update de la información */
                $consulta3 = "UPDATE pys_tiposproductos SET idSer = '$servicio', nombreTProd = '$nombre', descripcionTProd = '$descripcion', costoSin = '$costoSin', costoCon = '$costoCon' WHERE idTProd = '$id';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $consulta4 = "UPDATE pys_costos SET idClProd = '$clase', costo = '$costoTipo' WHERE idTProd = '$id';";
                $resultado4 = mysqli_query($connection, $consulta4);
                if ($resultado3 && $resultado4) {
                    /** Se realiza COMMIT de la información en la base de datos */
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('El registro se actualizó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">';
                } else {
                    /** Se realiza ROLLBACK de los posibles cambios realizados en la base de datos */
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser actualizado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/tipProductos.php">'; 
                }
            }
        }
        mysqli_close($connection);
    }

    public static function registrarClaseProducto ($equipo, $servicio, $nombre, $nombreCorto, $descripcion, $costo) {
        require('../Core/connection.php');
        /** Verificación campos vacíos */
        if ($equipo == null || $servicio == null || $nombre == null || $nombre == " ") {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
        } else {
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $nombreCorto = mysqli_real_escape_string($connection, $nombreCorto);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            /** Verificación información registrada en el tabla */
            $consulta = "SELECT idClProd FROM pys_claseproductos WHERE idSer = '$servicio' AND nombreClProd = '$nombre';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                echo "<script> alert('Ya existe una clase de producto con la información ingresada. Por favor intente nuevamente');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
            } else {
                /** Preparación de los datos de la base de datos */
                mysqli_query($connection, "BEGIN;");
                /** Obtención ID tabla clases */
                $consulta2 = "SELECT COUNT(idClProd), MAX(idClProd) FROM pys_claseproductos;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $idClase = ($datos2[0] == 0) ? "CLA001" : "CLA".substr((substr($datos2[1], 3) + 1001), 1);
                /** Obtención ID tabla costos */
                $consulta3 = "SELECT COUNT(idCosto), MAX(idCosto) FROM pys_costos;";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $idCosto = ($datos3[0] == 0) ? "C00001" : "C".substr((substr($datos3[1], 1) + 100001), 1);
                /** Insert de la información en las tablas */
                $consulta4 = "INSERT INTO pys_claseproductos VALUES ('$idClase', '$servicio', '$nombre', '$nombreCorto', '$descripcion', '1');";
                $resultado4 = mysqli_query($connection, $consulta4);
                $consulta5 = "INSERT INTO pys_costos VALUES ('$idCosto', '$servicio', '$idClase', '', '$costo', '1');";
                $resultado5 = mysqli_query($connection, $consulta5);
                if ($resultado4 && $resultado5) {
                    /** Se realiza COMMIT de la información en la base de datos */
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('El registro se insertó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
                } else {
                    /** Se realiza ROLLBACK de los posibles cambios realizados en la base de datos */
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser guardado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">'; 
                }
                
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarClaseProducto ($id, $servicio, $nombre, $nombreCorto, $descripcion, $costo) {
        require('../Core/connection.php');
        /** Verificación campos vacíos */
        if ($id == null || $servicio == null || $nombre == null || $nombre == " ") {
            echo "<script> alert('Existe algún campo vacío. Registro no válido.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
        } else {
            /** Verificación información existente en la base de datos */
            $nombre = mysqli_real_escape_string($connection, $nombre);
            $nombreCorto = mysqli_real_escape_string($connection, $nombreCorto);
            $descripcion = mysqli_real_escape_string($connection, $descripcion);
            $consulta = "SELECT pys_claseproductos.idSer, pys_claseproductos.nombreClProd, pys_claseproductos.nombreCortoClProd, pys_claseproductos.descripcionClProd, pys_costos.costo FROM pys_claseproductos 
                INNER JOIN pys_costos ON pys_costos.idClProd = pys_claseproductos.idClProd
                WHERE pys_claseproductos.idClProd = '$id' AND pys_costos.idTProd = '';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($datos['idSer'] == $servicio && $datos['nombreClProd'] == $nombre && $datos['nombreCortoClProd'] == $nombreCorto && $datos['descripcionClProd'] == $descripcion && $datos['costo'] == $costo) {
                echo "<script> alert('La información ingresada es la misma. El registro no fue actualizado.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
            } else {
                /** Preparación de la información existente en la base de datos */
                mysqli_query($connection, "BEGIN;");
                /** Update de los datos en las respectivas tablas */
                $consulta2 = "UPDATE pys_claseproductos SET idSer = '$servicio', nombreClProd = '$nombre', nombreCortoClProd = '$nombreCorto', descripcionClProd = '$descripcion' WHERE idClProd = '$id';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $consulta3 = "UPDATE pys_costos SET costo = '$costo' WHERE idClProd = '$id' AND idTProd = '';";
                $resultado3 = mysqli_query($connection, $consulta3);
                if ($resultado2 && $resultado3) {
                    /** Se realiza COMMIT de la información en la base de datos */
                    mysqli_query($connection, "COMMIT;");
                    echo "<script> alert('El registro se actualizó correctamente.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">';
                } else {
                    /** Se realiza ROLLBACK de los posibles cambios realizados en la base de datos */
                    mysqli_query($connection, "ROLLBACK;");
                    echo "<script> alert('Ocurrió un error y el registro NO pudo ser actualizado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/claProductos.php">'; 
                }
            }
            echo mysqli_error($connection);
        }
        mysqli_close($connection);
    }
    
    public static function selectServicio ($idEquipo, $idServicio) {
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_servicios WHERE idEqu = '$idEquipo' AND est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            if ($idServicio != null) {
                $select = ' <select name="sltServicio" id="sltServicio" disabled>
                                <option value="" selected disabled>Seleccione</option>';
            } else {
                $select = ' <select name="sltServicio" id="sltServicio" onchange="cargaSelect(\'#sltServicio\',\'../Controllers/ctrl_productos.php\',\'#sltClaseLoad\');" required>
                                <option value="" selected disabled>Seleccione</option>';
            }
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idSer'] == $idServicio) {
                    $select .= '<option value="'.$datos['idSer'].'" selected>'.$datos['nombreSer'].'</option>';
                } else {
                    $select .= '<option value="'.$datos['idSer'].'">'.$datos['nombreSer'].'</option>';
                }
            }
            $select .= '    </select>
                            <label for="sltServicio">Servicio*</label>';
        } else {
            $select = ' <select name="sltServicio" id="sltServicio">
                            <option value="" selected disabled>No aplica</option>
                        </select>
                        <label for="sltServicio">Servicio*</label>';
        }
        return $select;
        mysqli_close($connection);
    }

    public static function selectClase ($idServicio, $idClase) {
        require ('../Core/connection.php');
        $consulta = "SELECT idClProd, nombreClProd FROM pys_claseproductos WHERE est = '1' AND idSer = '$idServicio';";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $select = '     <select name="sltClase" id="sltClase" required>
                                <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idClProd'] == $idClase) {
                    $select .= '<option value="'.$datos['idClProd'].'" selected>'.$datos['nombreClProd'].'</option>';
                } else {
                    $select .= '<option value="'.$datos['idClProd'].'">'.$datos['nombreClProd'].'</option>';
                }
            }
            $select .= '    </select>
                            <label for="sltClase">Clase de producto*</label>';
        } else {
            $select = '     <select name="sltClase" id="sltClase">
                                <option value="" selected disabled>No aplica</option>
                            </select>
                            <label for="sltClase">Clase de producto*</label>';
        }
        return $select;
        mysqli_close($connection);
    }

    public static function selectClaseConTipo ($idServicio, $idClase) {
        require ('../Core/connection.php');
        $consulta = "SELECT idClProd, nombreClProd FROM pys_claseproductos WHERE est = '1' AND idSer = '$idServicio';";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $select = '     <select name="sltClaseM" id="sltClaseM" onchange="cargaSelectTipProduc(\'#sltClaseM\',\''.$idServicio.'\',\'../Controllers/ctrl_missolicitudes.php\',\'#sltModalTipo\')">
                                <option value="">Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idClProd'] == $idClase) {
                    $select .= '<option value="'.$datos['idClProd'].'" selected>'.$datos['nombreClProd'].'</option>';
                } else {
                    $select .= '<option value="'.$datos['idClProd'].'">'.$datos['nombreClProd'].'</option>';
                }
            }
            $select .= '    </select>
                            <label for="sltClaseM">Clase de producto*</label>';
        } else {
            $select = '     <select name="sltClaseM" id="sltClaseM">
                                <option value="">No aplica</option>
                            </select>
                            <label for="sltClaseM">Clase de producto*</label>';
        }
        mysqli_close($connection);
        return $select;
    }

    public static function selectTipoProducto($idClase, $idServicio, $codTipo){
        require ('../Core/connection.php');
        $select = '     <select name="sltTipo" id="sltTipo" required>
                            <option value="" selected disabled>Seleccione</option>';
        $consulta = "SELECT idTProd FROM `pys_costos` WHERE idSer = '$idServicio' AND idClProd ='$idClase'";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado)){
            
        }
        while ($datos = mysqli_fetch_array($resultado)) {
            if ($datos['idTProd'] != null){
                $idTipo = $datos['idTProd'];
                $consulta1= "SELECT * FROM pys_tiposproductos WHERE idTProd ='$idTipo'";
                $resultado1 = mysqli_query($connection, $consulta1);
                $registros = mysqli_num_rows($resultado1);
                if ($registros > 0) {
                    while ($datos1 = mysqli_fetch_array($resultado1)) {
                        if ($datos1['idTProd'] == $codTipo) {
                            $select .= '<option value="'.$datos1['idTProd'].'" selected>'.$datos1['nombreTProd'].' - '.$datos1['descripcionTProd'].' </option>';
                        } else {
                            $select .= '<option value="'.$datos1['idTProd'].'">'.$datos1['nombreTProd'].' - '.$datos1['descripcionTProd'].'</option>';
                        }
                    }
                } else {
                    $select .= '<option value="" selected >No registra tipo</option>';
                }       
            }     
        }
        $select .= '    </select>
                        <label for="sltTipo">Tipo de producto*</label>';
        return $select;
        mysqli_close($connection);

    }

}

?>