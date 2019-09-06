<?php 

    Class Costos {

        public static function generarIdCosto () {
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idCosto), MAX(idCosto) FROM pys_costos;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($datos[0] == 0) {
                $idCosto = "C00001";
            } else {
                $idCosto = 'C'.substr((substr($datos[1], 1) + 100001), 1);
            }
            return $idCosto;
            mysqli_close($connection);
        }

    }

?>