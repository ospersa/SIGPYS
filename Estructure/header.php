<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIGPYS</title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
   <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css"> -->
   <link rel="stylesheet" href="../Assets/Css/materialize.min.css">
   <link rel="stylesheet" href="../Assets/Css/Chart.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Css/master.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
//Reanudamos la sesión
session_start();

//Comprobamos si el usario está logueado
//Si no lo está, se le redirecciona al index
//Si lo está, definimos el botón de cerrar sesión y la duración de la sesión
$path = $_SERVER['REQUEST_URI'];
$path = explode('/', $path);
$path = end($path);
if( $path != "visitante.php"){
    if ((!isset($_SESSION['usuario'])) and ($_SESSION['estado'] != 'Autenticado')) {
        header('Location: ../index.php');
    } else {
        require('../Core/sessions.php');
        $usserName = $_SESSION['usuario'];
        $salir = '<a href="logout.php" target="_self" class="white-text">Cerrar sesión</a>';
    };
}
?>

    <body>
        <header>
            <?php
            if( $path != "visitante.php"){
                require_once('../Controllers/ctrl_menu.php');
            }
            ?>
        </header>
        <main>
            <div class="loader">
                <div id="page-loader" class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-teal-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
            