<?php
    class Periodo {

        public static function onLoad($idPeriodo) {
            require('../Core/connection.php');
            $consulta="SELECT idPeriodo, inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2
                FROM pys_periodos WHERE idPeriodo = '$idPeriodo';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            mysqli_close($connection);
            return $datos;
        }

        public static function busquedaTotal() {
            require ('../Core/connection.php');
            $consulta = "SELECT idPeriodo, inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2
                FROM pys_periodos WHERE estadoPeriodo = '1' ORDER BY idPeriodo DESC;";
            $resultado = mysqli_query($connection, $consulta);
            echo'   <table class="left responsive-table">
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
                                <td>'.$datos['inicioPeriodo'].'</td>
                                <td>'.$datos['finPeriodo'].'</td>
                                <td>'.$datos['diasSegmento1'].'</td>
                                <td>'.($datos['diasSegmento1'] * 8).'</td>
                                <td>'.$datos['diasSegmento2'].'</td>
                                <td>'.($datos['diasSegmento2'] * 8).'</td>
                                <td><a href="#modalPeriodo" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPeriodo.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>';
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require ('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT idPeriodo, inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2 
                FROM pys_periodos WHERE estadoPeriodo = '1' AND (inicioPeriodo LIKE '%$busqueda%' OR finPeriodo LIKE '%$busqueda%') ORDER BY idPeriodo DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo '  <table class="left responsive-table">
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
                                    <td>'.$datos['inicioPeriodo'].'</td>
                                    <td>'.$datos['finPeriodo'].'</td>
                                    <td>'.$datos['diasSegmento1'].'</td>
                                    <td>'.($datos['diasSegmento1'] * 8).'</td>
                                    <td>'.$datos['diasSegmento2'].'</td>
                                    <td>'.($datos['diasSegmento2'] * 8).'</td>
                                    <td><a href="#modalPeriodo" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPeriodo.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                                </tr>';
                }
                echo '      </tbody>
                        </table>';
            }else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarPeriodo($fechIni, $fechFin, $diasSeg1, $diasSeg2){
            require ('../Core/connection.php');
            $consulta = "SELECT COUNT(idPeriodo) FROM pys_periodos;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $codPeriodo = ($datos[0] == 0) ? 1 : $datos[0] + 1;
            $diasSeg1 = mysqli_real_escape_string($connection, $diasSeg1);
            $diasSeg2 = mysqli_real_escape_string($connection, $diasSeg2);
            $sql = "INSERT INTO pys_periodos VALUES ('$codPeriodo', '$fechIni', '$fechFin', '$diasSeg1', '$diasSeg2', 1);";
            $resultado = mysqli_query($connection, $sql);
            if ($resultado) {
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/dedicacion.php?cod='.$codPeriodo.'">';
            } else {
                echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/periodo.php">';
            }
        }

        public static function actualizarPeriodo ($idPeriodo, $fechaIni, $fechaFin, $diasSeg1, $diasSeg2){
            require ('../Core/connection.php');
            $diasSeg1 = mysqli_real_escape_string($connection, $diasSeg1);
            $diasSeg2 = mysqli_real_escape_string($connection, $diasSeg2);
            $consulta = "UPDATE pys_periodos SET inicioPeriodo = '$fechaIni', finPeriodo = '$fechaFin', diasSegmento1 = '$diasSeg1', diasSegmento2 = '$diasSeg2' WHERE idPeriodo = '$idPeriodo';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se actualizó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/periodo.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/periodo.php">';
            }   
            mysqli_close ($connection);
        }

    }
?>