<?php
    class Rol{

        public static function onLoad($idRol){
            require('../Core/connection.php');
            $consulta = "SELECT pys_tiproles.idTipRol, pys_tiproles.nombreTipRol, pys_roles.idRol, pys_roles.nombreRol, pys_roles.descripcionRol
                FROM pys_tiproles 
                INNER JOIN pys_roles ON pys_tiproles.idTipRol = pys_roles.tiproles_idTipRol WHERE pys_roles.idRol= '".$idRol."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos =mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }


        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta = "SELECT pys_roles.idRol, pys_roles.nombreRol, pys_roles.descripcionRol, pys_tiproles.nombreTipRol FROM pys_tiproles 
                INNER JOIN pys_roles ON pys_tiproles.idTipRol = pys_roles.tiproles_idTipRol
                WHERE pys_roles.est='1' ORDER BY pys_roles.nombreRol";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th>Descripción del Rol</th>
                        <th>Tipo de Rol</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[1].'</td>
                        <td>'.$datos[2].'</td>
                        <td>'.$datos[3].'</td>
                        <td><a href="#modalRol" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalRol.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta = "SELECT pys_roles.idRol, pys_roles.nombreRol, pys_roles.descripcionRol, pys_tiproles.nombreTipRol FROM pys_tiproles 
                INNER JOIN pys_roles ON pys_tiproles.idTipRol = pys_roles.tiproles_idTipRol WHERE pys_roles.est='1' AND pys_roles.nombreRol LIKE '%".$busqueda."%' ORDER BY pys_roles.nombreRol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if($registros){
                echo'
                <table class="centered responsive-table">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th>Descripción del Rol</th>
                            <th>Tipo de Rol</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos[1].'</td>
                            <td>'.$datos[2].'</td>
                            <td>'.$datos[3].'</td>
                            <td><a href="#modalRol" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalRol.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
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

        public static function registrarRol($tipRol, $nomRol, $descRol){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idRol),MAX(idRol) FROM pys_roles;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codRol="ROL001";
            }
            else {
                $codRol='ROL'.substr((substr($max,3)+1001),1);	
            }		
            $sql="INSERT INTO pys_roles VALUES ('$codRol', '$nomRol', '$descRol', '$tipRol', '1');";
            $resultado = mysqli_query($connection, $sql);
            if($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }
            mysqli_close($connection);
        }

        public static function actualizarRol($idRol2, $tipRol, $nomRol, $descRol){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_roles SET nombreRol = '$nomRol', descripcionRol = '$descRol', tiproles_idTipRol = '$tipRol' WHERE idRol = '$idRol2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }
            mysqli_close($connection);
            }

        public static function suprimirRol($idRol2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_roles SET est = '0' WHERE idRol = '$idRol2';";
            echo $consulta;
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/rol.php">';
            }
            mysqli_close($connection);
            }

        public static function selectTipoRol ($id) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_tiproles WHERE est = '1' ORDER BY nombreTipRol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $select = '         <select name="selTipRol" id="selTipRol">
                                        <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idTipRol'] == $id) {
                        $select .= '    <option value="'.$datos['idTipRol'].'" selected>'.$datos['nombreTipRol'].'</option>';
                    } else {
                        $select .= '    <option value="'.$datos['idTipRol'].'">'.$datos['nombreTipRol'].'</option>';
                    }
                    
                }
                $select .= '        </select>
                                    <label for="selTipRol">Rol para:</label>';
            } else {
                $select = ' <select name="selTipRol" id="selTipRol">
                                <option value="" selected disabled>No hay tipos de roles creados</option>
                            </select>
                            <label for="selTipRol">Rol para:</label>';
            }
            return $select;
            mysqli_close($connection);
        }

    }

?>