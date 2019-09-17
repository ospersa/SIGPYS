<?php

class Login {
    
    public static function validar ($id, $user){
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_login 
            INNER JOIN pys_personas ON pys_login.idPersona = pys_personas.idPersona
            WHERE pys_personas.identificacion = $id AND pys_login.usrLogin ='$user';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            $resul = '<div id="passwords">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input id="txtpassPer1" name="txtpassPer" type="password"  class="validate" required  value="" />
                <label for="txtpassPer1">Password*</label>
            </div>
            <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                <input id="txtpass1Per1" name="txtpass1Per" type="password"  class="validate" onkeyup="confirPassword(\'#txtpassPer1\', \'#txtpass1Per1\', \'#btnActPassword\' )" required  value="" />
                <label for="txtpass1Per1">Confirmar Password*</label>
                <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
            </div>
        </div>';
        return $resul;
        } else {
            echo "<script> alert ('No es valida la informacion');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/login.php">';
        }
        mysqli_close ($connection);
    }

    public static function cambiarPassword($id, $pass){
        require('../Core/connection.php');
        $consulta = "UPDATE pys_login SET  passwLogin ='$pass'  WHERE idLogin = '$id';";
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

}