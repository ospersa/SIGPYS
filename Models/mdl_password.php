<?php

class Password {   

    public static function onLoad($id){
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_personas 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_perfil ON pys_login.idPerfil = pys_perfil.idPerfil
            WHERE pys_personas.est = 1 
            AND  pys_login.idLogin = '$id' 
            ORDER BY nombres";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
    }

    public static function busqueda($buscar){
        require('../Core/connection.php');
        $buscar = mysqli_real_escape_string($connection, $buscar);
        $consulta = "SELECT * FROM pys_personas 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_perfil ON pys_login.idPerfil = pys_perfil.idPerfil
            WHERE pys_personas.est = 1 AND pys_login.est = 1
            AND (apellido1 LIKE '%$buscar%' OR apellido2 LIKE '%$buscar%' OR nombres LIKE '%$buscar%' ) 
            ORDER BY nombres";
        $resultado = mysqli_query($connection, $consulta);
        $count=mysqli_num_rows($resultado);
        if($count > 0){
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Perfil</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos =mysqli_fetch_array($resultado)){
                $idLogin= $datos['idLogin'];
                
                echo'
                    <tr>
                        <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                        <td>'.$datos['usrLogin'].'</td>
                        <td>'.$datos['nombrePerfil'].'</td>
                        <td><a href="#modalPassword" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$idLogin'".','."'modalPassword.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                </tr>';
            }    
            echo "
                </tbody>
            </table>";
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$buscar.'</strong></h6></div>';

        } 
        mysqli_close($connection);               
    }

    public static function busquedaTotal(){
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_personas 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_perfil ON pys_login.idPerfil = pys_perfil.idPerfil
            WHERE pys_personas.est = 1 AND pys_login.est = 1
            ORDER BY nombres";
        $resultado = mysqli_query($connection, $consulta);
        echo'
        <table class="left responsive-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Perfil</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>';
        while ($datos =mysqli_fetch_array($resultado)){
            $idLogin= $datos['idLogin'];
            echo'
                <tr>
                    <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                    <td>'.$datos['usrLogin'].'</td>
                    <td>'.$datos['nombrePerfil'].'</td>
                    <td><a href="#modalPassword" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$idLogin'".','."'modalPassword.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
            </tr>';
        }    
        echo "
            </tbody>
        </table>";
        mysqli_close($connection);               
    }

    public static function buscarUserCorreo ($idPersona, $tipo) {
        require('../Core/connection.php');
        $consulta = "SELECT correo from pys_personas where est = '1' AND idPersona = '$idPersona';";
        $resultado = mysqli_query($connection, $consulta);
        $rowcount=mysqli_num_rows($resultado);
        $datos = mysqli_fetch_array($resultado);
        $user = explode('@',$datos[0]);
        if($tipo == 'modal'){
            if ($rowcount != 0) {
                echo '
                    <div id="" class="input-field col l3 m3 s12 offset-l3 offset-m3">
                        <input disabled id="txtMail" name="txtMail" type="text" class="validate" required value="'.$datos[0].'"/>
                        <label class="active" for="txtMail">e-mail</label>
                    </div>
                    <div id="" class="input-field col l2 m2 s12 offset-l1 offset-m1">
                        <input id="txtloginPer" name="txtloginPer" type="text" class="validate" required value="'.$user[0].'"/>
                        <label class="active" for="txtloginPer">Usuario*</label>
                    </div>
                ';
            } else {
                echo '
                <div id="" class="input-field col l2 m2 s12 offset-l3 offset-m3 ">
                    <input name="txtMail" type="text" class="validate" disabled required value="" />
                    <label for="txtMail">e-mail</label>
                </div>
                <div id="" class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input id="txtloginPer" name="txtloginPer" type="text" class="validate" required value=""/>
                    <label class="active" for="txtloginPer">Usuario*</label>
                </div>
                ';
            }
        }else{
            if ($rowcount != 0) {
                echo '
                    <div id="" class="input-field col l6 m6 s12">
                        <input disabled id="txtMail" name="txtMail" type="text" class="validate" required value="'.$datos[0].'"/>
                        <label class="active" for="txtMail">e-mail</label>
                    </div>
                    <div id="" class="input-field col l6 m6 s12">
                        <input id="txtloginPer" name="txtloginPer" type="text" class="validate" required value="'.$user[0].'"/>
                        <label class="active" for="txtloginPer">Usuario*</label>
                    </div>
                ';
            } else {
                echo '
                <div id="" class="input-field col l6 m6 s12 ">
                    <input name="txtMail" type="text" class="validate" disabled required value="" />
                    <label for="txtMail">e-mail</label>
                </div>
                <div id="" class="input-field col l6 m6 s12">
                    <input id="txtloginPer" name="txtloginPer" type="text" class="validate" required value=""/>
                    <label class="active" for="txtloginPer">Usuario*</label>
                </div>
                ';
            }
        }
        mysqli_close($connection);
    }

    public static function registroUser($userPerfil, $userName, $userId, $userPass) {
        require('../Core/connection.php');
        //Contador tabla Login
        $consulta = "SELECT count(idLogin),Max(idLogin) FROM pys_login";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos =mysqli_fetch_array($resultado)) {
            $count=$datos[0];
            $max=$datos[1];
        }
        if ($count == "0") {
            $codLogin="L00001";
        } else {
            $codLogin='L'.substr((substr($max, 1)+100001), 1);
        }
        $hash = password_hash($userPass, PASSWORD_DEFAULT, [15]);
        $userName = mysqli_real_escape_string($connection, $userName);
        //insert tabla Login
        $sql="INSERT INTO pys_login VALUES ('$codLogin', '$userPerfil', '$userId', '$userName', '$hash', '1');";
        $resultado2 = mysqli_query($connection, $sql);
        if ($resultado && $resultado2 ){
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }
        mysqli_close($connection);    
    }

    public static function selectBuscarUsuario($buscar){
        require('../Core/connection.php');
        $select ="";
        $buscar = mysqli_real_escape_string($connection, $buscar);
        $consulta = "SELECT * FROM pys_personas LEFT OUTER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona
            WHERE pys_login.idPersona IS NULL AND pys_personas.est = 1 
            AND (apellido1 LIKE '%$buscar%' OR apellido2 LIKE '%$buscar%' OR nombres LIKE '%$buscar%' ) 
            ORDER BY apellido1";
        $resultado = mysqli_query($connection, $consulta);
        $numero_registro = mysqli_num_rows($resultado);
        if ($numero_registro == 0){
            $select = "<script> alert ('No hay categorias registradas en la base de datos');</script>";
        } else {
            $select = '
            <select name="SMPersona" id="SMPersona" onchange="cargaSelect(\'#SMPersona\',\'../Controllers/ctrl_password.php\',\'#sltDinamico\')">  
            <option >Seleccione</option>';
            while ($fila1 = mysqli_fetch_array($resultado)){
                $select .=  '  <option value= "'.$fila1[0].'">'.$fila1['apellido1'].' '.$fila1['apellido2'].' '.$fila1['nombres'].'</option>';
            }
            $select .= '</select>';
        }
        return $select;
        mysqli_close($connection);
    }

    public static function selectPerfil($id){
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_perfil WHERE est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            if ($id != null) {
                $select = ' <select name="sltPerfil2" id="sltPerfil2">
                                <option value="" selected>Seleccione</option>';
            } else {
                $select = ' <select name="sltPerfil" id="sltPerfil" onchange="cargaSelect(\'#sltPerfil2\',\'../Controllers/ctrl_password.php\',\'#sltServicioLoad\')" required>
                                <option value="" selected>Seleccione</option>';
            }
            
            while ($datos = mysqli_fetch_array($resultado)) {
                if ($datos['idPerfil'] == $id) {
                    $select .= '<option value="'.$datos['idPerfil'].'" selected>'.$datos['nombrePerfil'].'</option>';
                } else {
                    $select .= '<option value="'.$datos['idPerfil'].'">'.$datos['nombrePerfil'].'</option>';
                }
            }
            $select .= '    </select>
                            <label for="sltPerfil">Perfil</label>';
        } else {
            $select = ' <select name="sltPerfil" id="sltPerfil" required>
                            <option value="">No hay perfiles creados</option>
                        </select>
                        <label for="sltPerfil">Perfil</label>';
        }
        return $select;
        mysqli_close($connection);
    }


    public static function ActualizarConPassword($id, $perfil, $user,$pass){
        require('../Core/connection.php');
        $hash = password_hash($pass, PASSWORD_DEFAULT, [15]);
        $consulta = "UPDATE pys_login SET  usrLogin = '$user', idPerfil = '$perfil', passwLogin ='$hash'  WHERE idLogin = '$id';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            echo "<script> alert ('Se actualizó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }   
        mysqli_close ($connection);
    }
    public static function actualizar($id, $perfil, $user){
        require('../Core/connection.php');
        $user = mysqli_real_escape_string($connection, $user);
        $consulta = "UPDATE pys_login SET  usrLogin = '$user', idPerfil = '$perfil' WHERE idLogin = '$id';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            echo "<script> alert ('Se actualizó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }   
        mysqli_close ($connection);
    }
    public static function inactivar($id){
        require('../Core/connection.php');
        $consulta = "UPDATE pys_login SET  est = '0' WHERE idLogin = '$id';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            echo "<script> alert ('Se inactivo correctamente el usuario');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar inactivar el usuario');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
        }   
        mysqli_close ($connection);
    }
}