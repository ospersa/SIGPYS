<?php
    class Convocatoria{

        public static function onLoad($idConv){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' AND idConvocatoria ='".$idConv."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos =mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' ORDER BY nombreConvocatoria;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre de la convocatoria</th>
                        <th>Descripción de la convocatoria</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
            ';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreConvocatoria'].'</td>
                        <td>'.$datos['descrConvocatoria'].'</td>
                        <td><a href="#modalConvocatoria" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalConvocatoria.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' AND nombreConvocatoria LIKE '%".$busqueda."%' ORDER BY nombreConvocatoria;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la convocatoria</th>
                            <th>Descripción de la convocatoria</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombreConvocatoria'].'</td>
                            <td>'.$datos['descrConvocatoria'].'</td>
                            <td><a href="#modalConvocatoria" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalConvocatoria.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            }else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';

            }
            mysqli_close($connection);
        }

        public static function registrarConv($nomConv, $descConv){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idConvocatoria),MAX(idConvocatoria) FROM pys_convocatoria;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codConv="CV0001";
            }
            else {
                $codConv='CV'.substr((substr($max,2)+10001),1);	
            }	
            $nomConv2 = mysqli_real_escape_string($connection, $nomConv);
            $descConv2 = mysqli_real_escape_string($connection, $descConv);	
            $sql="INSERT INTO pys_convocatoria VALUES ('$codConv', '$nomConv2',  '$descConv2', '1');";
            $resultado2 = mysqli_query($connection, $sql);
            if ($resultado && $resultado2){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }
            mysqli_close($connection);
            
        }

        public static function actualizarConv($idConv2, $nomConv, $descConv){
            require('../Core/connection.php');            
            $nomConv2 = mysqli_real_escape_string($connection, $nomConv);
            $descConv2 = mysqli_real_escape_string($connection, $descConv);	
            $consulta = "UPDATE pys_convocatoria SET nombreConvocatoria='$nomConv2', descrConvocatoria='$descConv2' WHERE idConvocatoria='$idConv2';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";              
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }
            mysqli_close($connection);
        }

        public static function suprimirConv($idConv2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_convocatoria SET est = '0' WHERE idConvocatoria = '$idConv2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
            }
            mysqli_close($connection);
        }
    }

