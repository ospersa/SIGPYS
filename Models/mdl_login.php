<?php

class Login {
    
    public static function validar ($id, $user){
        require('../Core/connection.php');
        $json = array();
		$consulta = "SELECT * FROM pys_login 
            INNER JOIN pys_personas ON pys_login.idPersona = pys_personas.idPersona
            WHERE pys_personas.identificacion = $id AND pys_login.usrLogin ='$user';";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0){
            $json[]     = array(
                'estado' => true,
                'datos'     =>' <br>
                <br>
                <div class="col l10 m10 s12 offset-l1 offset-m1 left-align">
                    <h6>Ingrese su nueva contraseña</h6>
                </div>
                <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input id="txtpassPer1" name="txtpassPer" type="password"  class="validate" required  value="" />
                    <label for="txtpassPer1">Password*</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input id="txtpass1Per1" name="txtpass1Per" type="password"  class="validate" onkeyup="confirPassword(\'#txtpassPer1\', \'#txtpass1Per1\', \'#CambiarPassword\' )" required  value="" />
                    <label for="txtpass1Per1">Confirmar Password*</label>
                    <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
                </div>
            
            <button class="btn waves-effect waves-light" type="submit" id="CambiarPassword"  name="CambiarPassword" disabled >Cambiar Password</button>',
            );  
        } else {
            $json[]     = array(
                'estado' => false,
                'datos'     =>' <div class="teal darken-3 col l10 m10 s12 offset-l1 offset-m1">
                <h6 class=" white-text"> El usuario '.$user.' o numero de cedula '.$id.' no fue encontrados </h6>
            </div>',
            );
           
            echo '
                ';
        }
        $jsonString = json_encode($json);
        echo $jsonString;
        mysqli_close ($connection);
    }

    public static function cambiarPassword($id, $pass){
        require('../Core/connection.php');
        $hash = password_hash($pass, PASSWORD_DEFAULT, [15]);
        $consulta = "UPDATE pys_login SET  passwLogin ='$hash'  WHERE usrLogin = '$id';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            echo "<script> alert ('Se actualizó correctamente la contraseña, ingrese con la nueva contraseña.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/login.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar actualizar la contraseña');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/login.php">';
        }   
        mysqli_close ($connection);
    }

}