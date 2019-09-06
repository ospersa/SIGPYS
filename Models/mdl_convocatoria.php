<?php
    class Convocatoria{

        public static function onLoad($idConv){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' AND idConvocatoria ='".$idConv."';";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $fetch[] = $datos;
            }
            return $fetch;
            mysqli_close($connection);
        }


        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' ORDER BY nombreConvocatoria;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Nombre de la convocatoria</th>
                        <th>Descripción de la convocatoria</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            ';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[1].'</td>
                        <td>'.$datos[2].'</td>
                        <td><a href="#modalConvocatoria" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalConvocatoria.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_convocatoria WHERE est='1' AND nombreConvocatoria LIKE '%".$busqueda."%' ORDER BY nombreConvocatoria;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Nombre de la convocatoria</th>
                        <th>Descripción de la convocatoria</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos =mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[1].'</td>
                        <td>'.$datos[2].'</td>
                        <td><a href="#modalConvocatoria" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalConvocatoria.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
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
            $sql="INSERT INTO pys_convocatoria VALUES ('$codConv', '$nomConv',  '$descConv', '1');";
            $resultado = mysqli_query($connection, $sql);
            mysqli_close($connection);
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
        }

        public static function actualizarConv($idConv2, $nomConv, $descConv){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_convocatoria SET nombreConvocatoria='$nomConv', descrConvocatoria='$descConv' WHERE idConvocatoria='$idConv2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
        }

        public static function suprimirConv($idConv2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_convocatoria SET est = '0' WHERE idConvocatoria = '$idConv2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            echo "<script> alert ('Se eliminó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/convocatoria.php">';
        }
    }

