<?php
include_once('../Models/mdl_terminacionProductoServicio.php');
if(!isset($_SESSION)) { 
    session_start();
}
$usuario = $_SESSION['usuario'];
if (isset($_POST['b'])) {
    $busqueda = $_POST['b'];
    Terminar::selectProyectoUsuario($busqueda, $usuario);
}else{
Terminar::cargarProyectosUser($usuario);
}
?>