<?php

class Password {   

    public static function selectUser($idPersona) {
        require('../Core/connection.php');
        $consulta = "SELECT correo from pys_personas where est = '1' and idPersona = '$idPersona';";
        $resultado = mysqli_query($connection, $consulta);
        $rowcount=mysqli_num_rows($resultado);
        if ($rowcount != 0) {
            echo '
                <div id="" class="input-field col l12 m12 s12">
                    <input disabled id="txtMail" name="txtMail" type="text" class="validate" required   value="';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo $datos[0];
            }
            echo'"/>
                    <label class="active" for="txtMail">e-mail</label>
                </div>
            ';
        } else {
            echo '
            <div id="" class="input-field col l12 m12 s12 ">
                <input name="txtMail" type="text" class="validate" disabled required value="" />
                <label for="txtMail">e-mail</label>
            </div>
            ';
        }
        mysqli_close($connection);
    }

    public static function registroUser($userPerfil, $userName, $userId, $userPass) {
        require('../Core/connection.php');
        //Contador tabla Login
        $consulta = "select count(idLogin),Max(idLogin) from pys_login";
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
        //insert tabla Login
        $sql="INSERT INTO pys_login VALUES ('$codLogin', '$userPerfil', '$userId', '$userName', '$userPass', '1');";
        $resultado = mysqli_query($connection, $sql);
        mysqli_close($connection);
        echo "<script> alert ('Se guardó correctamente la información');</script>";
        echo '<meta http-equiv="Refresh" content="0;url=../Views/password.php">';
    }

}