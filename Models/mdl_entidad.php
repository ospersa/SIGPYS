<?php
    class Entidad{

        public static function onLoad($idEnti){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_entidades WHERE est='1' AND idEnt='".$idEnti."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_entidades WHERE est='1' ORDER BY nombreEnt;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre de la Entidad/Empresa</th>
                        <th>Nombre corto de la Entidad/Empresa</th>
                        <th>Descripción de la Entidad/Empresa</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreEnt'].'</td>
                        <td>'.$datos['nombreCortoEnt'].'</td>
                        <td>'.$datos['descripcionEnt'].'</td>
                        <td><a href="#modalEntidad" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEntidad.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_entidades WHERE est='1' AND (nombreEnt LIKE  '%".$busqueda."%' OR nombreCortoEnt LIKE  '%".$busqueda."%') ORDER BY nombreEnt;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la Entidad/Empresa</th>
                            <th>Nombre corto de la Entidad/Empresa</th>
                            <th>Descripción de la Entidad/Empresa</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombreEnt'].'</td>
                            <td>'.$datos['nombreCortoEnt'].'</td>
                            <td>'.$datos['descripcionEnt'].'</td>
                            <td><a href="#modalEntidad" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEntidad.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
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

        public static function registrarEntidad($nomEnti, $nomCortoEnti, $descEnti){
            require('../Core/connection.php');
            $countFacDepto = "";
            //Contador tabla Entidades 
            $consulta = "SELECT COUNT(idEnt), MAX(idEnt) FROM pys_entidades;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codEntidad="ENT001";
            }
            else {
                $codEntidad='ENT'.substr((substr($max,3)+1001),1);		
            }	
            //contador tabla FacDEpto
            $consulta2 = "SELECT COUNT(idFacDepto),MAX(idFacDepto) FROM pys_facdepto;";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 =mysqli_fetch_array($resultado2)){
                $count2=$datos2[0];
                $max2=$datos2[1];
            }
            if ($count2 == 0){
                $countFacDepto="FD0001";
            }
            else {
                $countFacDepto='FD'.substr((substr($max,3)+10001),1);	
            }	
            //insert tabla Entidades 
            $sql="INSERT INTO pys_entidades VALUES ('$codEntidad', '$nomEnti', '$nomCortoEnti', '$descEnti', 'PR0042', 'CAR032', '1');";
            $resultado = mysqli_query($connection, $sql);
            //insert tabla FacDEpto
            $sql2="INSERT INTO pys_facdepto VALUES ('$countFacDepto', '$codEntidad', '', '', '', '', '1', '1', '1')";
            $resultado2 = mysqli_query($connection, $sql2);
            if ($resultado1 && $resultado2){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarEntidad($idEnti2, $nomEnti, $nomCortoEnti, $descEnti){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_entidades SET nombreEnt='$nomEnti', nombreCortoEnt='$nomCortoEnti', descripcionEnt='$descEnti' WHERE idEnt='$idEnti2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';

            }
            mysqli_close($connection);
        }
        public static function suprimirEntidad($idEnti2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_entidades SET est='0' WHERE idEnt='$idEnti2';";
            $resultado = mysqli_query($connection, $consulta);

            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/entidad.php">';

            }
            mysqli_close($connection);
        }

    }

 ?>   