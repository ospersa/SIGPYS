<?php
//Iniciamos la sesión
session_start();

//Pedimos el archivo que controla la duración de las sesiones
require('../Core/sessions.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIGPYS</title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css"> -->
    <link rel="stylesheet" href="../Assets/Css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Css/master.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <main class="valign-wrapper teal darken-1">
        <div class="container ">
            <div class="row">
                <form id="login" action=""  method="post" class="login white col l6 m6 s12 offset-l3 offset-m3">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="usserName" name="usserName" type="text" required class="validate">
                            <label for="usserName">User Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" name="password" type="password" required class="validate">
                            <label for="password">Password</label>
                        </div>
                    </div>
                    <button id="btn-login" class="btn waves-effect waves-light right" type="submit" name="action">Log In</button>
                    <a href="visitante.php" class="waves-effect waves-light btn cyan darken-2 left">Invited</a>
                </form>
            </div>
            <div class="row">
                <a class=" white-text modal-trigger col l6 m6 s12 offset-l3 offset-m3" href="#modalLogin">¿Olvido su contraseña?</a>
            </div>
            <div class="row">
                <div id="message" class="red darken-3 white-text center-align col l6 m6 s12 offset-l3 offset-m3"></div>
            </div>
        </div>
        <!-- Modal Structure -->
<div id="modalLogin" class="modal">
    <?php
    require('modalLogin.php');
    ?>
</div>
  <!-- Compiled and minified JavaScript -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
    <script src="../Assets/Js/jquery-3.1.0.min.js"></script>
    <script src="../Assets/Js/materialize.js"></script>
    <script src="../Assets/Js/login.js"></script>
</body>

</html>