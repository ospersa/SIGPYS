<?php 

    include('mdl_costos.php');

    Class Servicios {

        public static function onLoad($idSer){
            require('../Core/connection.php');
            $consulta = "SELECT pys_servicios.idSer, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_servicios.nombreCorto, pys_servicios.descripcionSer, pys_costos.costo, pys_servicios.productoOservicio 
            FROM pys_servicios
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
			INNER JOIN pys_costos ON pys_costos.idSer = pys_servicios.idSer
			WHERE pys_servicios.est = '1' 
            AND pys_equipos.est = '1' 
            AND pys_costos.est = '1' 
            AND pys_costos.idClProd = '' 
            AND pys_costos.idTProd = '' 
            AND pys_servicios.idSer= '$idSer';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }
        public static function generarIdServicio () {
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idSer), MAX(idSer) FROM pys_servicios;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($datos[0] == 0) {
                $idServicio = "SER001";
            } else {
                $idServicio = 'SER'.substr((substr($datos[1], 3) + 1001), 1);
            }
            return $idServicio;
            mysqli_close($connection);
        }

        public static function busquedaServicios ($busqueda) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_servicios.idSer, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_servicios.nombreCorto, pys_servicios.descripcionSer, pys_servicios.productoOservicio, pys_costos.costo FROM pys_servicios
                INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
                INNER JOIN pys_costos ON pys_costos.idSer = pys_servicios.idSer
                WHERE pys_servicios.est = '1' AND pys_equipos.est = '1' AND pys_costos.est = '1' AND pys_costos.idClProd = '' AND pys_costos.idTProd = '' 
                AND ((nombreSer LIKE '%$busqueda%') OR (nombreEqu LIKE '%$busqueda%') OR (nombreCorto LIKE '%$busqueda%'))
                GROUP BY pys_servicios.idSer
                ORDER BY pys_equipos.nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0){    
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Servicio</th>
                            <th>Nombre corto</th>
                            <th>Descripción de servicio</th>
                            <th>¿Genera producto?</th>
                            <th>Costo $</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    $costo = number_format((integer)$datos['costo'],1,",","."); 
                    echo'
                        <tr>
                            <td>'.$datos['nombreEqu'].'</td>
                            <td>'.$datos['nombreSer'].'</td>
                            <td>'.$datos['nombreCorto'].'</td>
                            <td>'.$datos['descripcionSer'].'</td>
                            <td>'.$datos['productoOservicio'].'</td>
                            <td>$'.$costo.'</td>
                            <td><a href="#modalServicio" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalServicio.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
                
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarServicio ($equipo, $nombre, $nombreCorto, $descripcion, $producto, $costo) {
            require('../Core/connection.php');
            /** Verificación campos vacíos */
            if ($equipo == null || $nombre == null || $producto == null) {
                echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
            } else {
                /** Verificación información existente en la tabla, para evitar duplicidad */
                $consulta = "SELECT idSer FROM pys_servicios WHERE nombreSer = '$nombre' AND idEqu = '$equipo' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    echo '<script>alert("Ya existe un registro igual o similar a este. No se guardó la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
                } else {
                    /** Insert de la información */
                    $idCosto = Costos::generarIdCosto();
                    $idServicio = self::generarIdServicio();
                    if ($idCosto != null && $idServicio != null) {
                        $consulta2 = "INSERT INTO pys_servicios VALUES ('$idServicio', '$equipo', '$nombre', '$nombreCorto', '$descripcion', '$producto', '1');";
                        $resultado2 = mysqli_query($connection, $consulta2);
                        $consulta3 = "INSERT INTO pys_costos VALUES ('$idCosto', '$idServicio', '', '', '$costo', '1');";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        if ($resultado2 && $resultado3) {
                            echo '<script>alert("Se guardó correctamente la información.")</script>';
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
                        } else {
                            echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                            echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
                        }
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function selectGeProd ($id){
            require('../Core/connection.php');
            $select = "";
            $consulta = "SELECT idSer, productoOservicio FROM pys_servicios WHERE idSer='$id';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                if ($id != null ) {
                    $select = ' <select name="sltProducto" id="sltProducto" disabled>
                                    <option value="" selected disabled>Seleccione</option>';
                } else {
                    $select = ' <select name="sltProducto" id="sltProducto" onchange="cargaSelect(\'#sltProducto\',\'../Controllers/ctrl_servicios.php\',\'#sltServicioLoad\')" required>
                                    <option value="" selected disabled>Seleccione</option>';
                }
                
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idSer'] == $id) {
                        $select .= '<option value="'.$datos['productoOservicio'].'" selected>'.$datos['productoOservicio'].'</option>';
                    } else {
                        $select .= '<option value="'.$datos['productoOservicio'].'">'.$datos['productoOservicio'].'</option>';
                    }
                }
                $select .= '    </select>
                                <label for="sltProducto">Genera producto?*</label>';
            } else {
                $select = ' <select name="sltProducto" id="sltProducto" required>
                                <option value=""></option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                            <label for="sltProducto">Genera producto?*</label>';
            }
            return $select;
            mysqli_close($connection);
        }

        public static function actualizarServicio ($idSer, $idEqu, $nombreSer, $nombreCorto, $descripcion, $costo){
            require('../Core/connection.php');
            if($idSer != null || $idEqu != null || $nombreSer != null ||$producto != null){
                $consulta = "UPDATE pys_servicios SET idEqu='$idEqu', nombreSer='$nombreSer', nombreCorto ='$nombreCorto', descripcionSer='$descripcion' WHERE idSer='$idSer'";
                $resultado = mysqli_query($connection, $consulta);
                /*Codigo de actualizacion en la tabla pys_costos*/
                $consulta2 = "UPDATE pys_costos SET costo='$costo' WHERE idSer='$idSer'";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado && $resultado2) {                    
                    echo "<script> alert ('Se guardó correctamente la información');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
                } else { 
                    echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
                }
            } else {
                echo "<script> alert ('Existen campos vacios. Registro no valido');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
            }
            mysqli_close($connection);
        }

        public static function suprimirServicio ($id){
            require('../Core/connection.php');
            echo $consulta = "UPDATE pys_servicios SET est = '0' WHERE idSer = '$id' ";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
            } else {
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/servicios.php">';
            }

        }
    }

?>