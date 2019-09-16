<?php

    class Perfiles {

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_perfil WHERE est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre del perfil</th>
                        <th>Descripción del perfil</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos =mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombrePerfil'].'</td>
                        <td>'.$datos['descripcionPerfil'].'</td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_perfil WHERE est = '1' AND nombrePerfil LIKE '%".$busqueda."%';";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del perfil</th>
                            <th>Descripción del perfil</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombrePerfil'].'</td>
                            <td>'.$datos['descripcionPerfil'].'</td>
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

        public static function registrarPerfil($nomPerf, $descPerf){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idPerfil),MAX(idPerfil) FROM pys_perfil;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codPerf="PERF01";	
            }
            else {
                $codPerf='PERF'.substr((substr($max,4)+101),1);
            }		
            $sql="INSERT INTO pys_perfil VALUES ('$codPerf', '$nomPerf',  '$descPerf', '1');";
            $resultado = mysqli_query($connection, $sql);
            if ($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/perfil.php">';
            }else{
                echo '<meta http-equiv="Refresh" content="0;url=../Views/perfil.php">';
                echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
            }
            mysqli_close($connection);    
        }

    }
?>