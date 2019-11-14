<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}

/* Inclusión del Modelo */
include_once('../Models/mdl_menu.php');

/* Inicialización variables*/
$usuario  = $_SESSION['usuario'];

/* Procesamiento peticiones al controlador */
$validar = Menu::validar ($usuario);

?>