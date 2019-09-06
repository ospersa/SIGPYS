<?php 

    include('mdl_costos.php');

    Class Servicios {

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
            if ($registros > 0) {
                
            } else {
                echo "<h4>No hay resultados para la busqueda.</h4>";
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

    }

?>