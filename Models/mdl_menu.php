<?php

class Menu {
    //Cuando se realice la aasignacion de cargo de" asesor/gestor" asignar el id en la consulta
    public static function validar ($user){
        require('../Core/connection.php');
        $user = mysqli_real_escape_string($connection, $user);
        $consulta = "SELECT * FROM `pys_personas`
        INNER JOIN pys_login ON pys_login.idPersona = pys_personas.idPersona
        WHERE pys_personas.est = 1 AND pys_login.est = 1 AND idCargo='CAR041' AND pys_login.usrLogin='$user'";
        $resultado = mysqli_query($connection, $consulta);
        mysqli_close ($connection);

        if (mysqli_num_rows($resultado) > 0){
            return true;
        } else { 
            return false;
        }

    }


}