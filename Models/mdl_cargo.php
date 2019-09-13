<?php
    class Cargo{

        public static function onLoad($idCargo){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_cargos WHERE est='1' AND idCargo ='".$idCargo."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos =mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_cargos WHERE est='1' ORDER BY nombreCargo;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Nombre del cargo</th>
                        <th>Descripción del cargo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[1].'</td>
                        <td>'.$datos[2].'</td>
                        <td><a href="#modalCargo" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCargo.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_cargos WHERE est='1' AND nombreCargo LIKE '%".$busqueda."%' ORDER BY nombreCargo;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="centered responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del cargo</th>
                            <th>Descripción del cargo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos[1].'</td>
                            <td>'.$datos[2].'</td>
                            <td><a href="#modalCargo" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCargo.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
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

        public static function registrarcargo($nomCargo, $descCargo){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idCargo),MAX(idCargo) FROM pys_cargos;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codCargo="CAR001";
            }
            else {
                $codCargo='CAR'.substr((substr($max,3)+1001),1);	
            }		
            $sql="INSERT INTO pys_cargos VALUES ('$codCargo', '$nomCargo',  '$descCargo', '1');";
            $resultado = mysqli_query($connection, $sql);
            mysqli_close($connection);
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
        }

        public static function actualizarCargo($idCargo2, $nomCargo, $descCargo){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_cargos SET nombreCargo='$nomCargo', descripcionCargo='$descCargo' WHERE idCargo='$idCargo2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
        }

        public static function suprimirCargo($idCargo2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_cargos SET est = '0' WHERE idCargo='$idCargo2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            echo "<script> alert ('Se eliminó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
        }
    }

