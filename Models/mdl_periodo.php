<?php
    class Periodo {

        public static function onLoad($idPeriodo) {
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_periodos WHERE idPeriodo ='$idPeriodo';";
            $resultado = mysqli_query($connection, $consulta);
            $datos =mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal() {
            require ('../Core/connection.php');
            $consulta = "SELECT * FROM pys_periodos WHERE estadoPeriodo='1' ORDER BY idPeriodo DESC;";
            $resultado = mysqli_query($connection, $consulta);
            echo'   <table class="centered responsive-table">
                        <thead>
                            <tr>
                                <th>Fecha Inicio Periodo</th>
                                <th>Fecha Fin Periodo</th>
                                <th>Días Segmento 1</th>
                                <th>Horas Segmento 1</th>
                                <th>Días Segmento 2</th>
                                <th>Horas Segmento 2</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'       <tr>
                                <td>'.$datos[1].'</td>
                                <td>'.$datos[2].'</td>
                                <td>'.$datos[3].'</td>
                                <td>'.($datos[3] * 8).'</td>
                                <td>'.$datos[4].'</td>
                                <td>'.($datos[4] * 8).'</td>
                                <td><a href="#modalPeriodo" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPeriodo.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require ('../Core/connection.php');
            $consulta = "SELECT * FROM pys_periodos WHERE estadoPeriodo = '1' AND (inicioPeriodo LIKE '%$busqueda%' OR finPeriodo LIKE '%$busqueda%') ORDER BY idPeriodo DESC;";
            $resultado = mysqli_query($connection, $consulta);
            echo '  <table class="centered responsive-table">
                        <thead>
                            <tr>
                                <th>Fecha Inicio Periodo</th>
                                <th>Fecha Fin Periodo</th>
                                <th>Días Segmento 1</th>
                                <th>Horas Segmento 1</th>
                                <th>Días Segmento 2</th>
                                <th>Horas Segmento 2</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'       <tr>
                                <td>'.$datos[1].'</td>
                                <td>'.$datos[2].'</td>
                                <td>'.$datos[3].'</td>
                                <td>'.($datos[3] * 8).'</td>
                                <td>'.$datos[4].'</td>
                                <td>'.($datos[4] * 8).'</td>
                                <td><a href="#modalPeriodo" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPeriodo.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
            mysqli_close($connection);
        }

        public static function registrarPeriodo($fechIni, $fechFin, $diasSeg1, $diasSeg2){
            require ('../Core/connection.php');
            $consulta = "SELECT COUNT(idPeriodo) FROM pys_periodos;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
            }
            if ($count==0){
                $codPeriodo=1;
            }
            else if ($count > 0) {
                $codPeriodo = $count + 1;
            }
            $sql="INSERT INTO pys_periodos VALUES ('$codPeriodo', '$fechIni',  '$fechFin', '$diasSeg1','$diasSeg2',1);";
            $resultado = mysqli_query($connection, $sql);
            mysqli_close($connection);
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/dedicacion.php?cod='.$codPeriodo.'">';
        }

        public static function actualizarPeriodo ($idPeriodo, $fechaIni, $fechaFin, $diasSeg1, $diasSeg2){
            require ('../Core/connection.php');
            $consulta = "UPDATE pys_periodos SET inicioPeriodo = '$fechaIni', finPeriodo = '$fechaFin', diasSegmento1 = '$diasSeg1', diasSegmento2 = '$diasSeg2' WHERE idPeriodo = '$idPeriodo';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close ($connection);
            echo "<script> alert ('Se actualizó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/periodo.php">';
        }

    }
?>