<?php 
/* Inicializar variables de sesión */
if(!isset($_SESSION['usuario'])) { 
    session_start();
}

/* Inclusión del Modelo */
include_once('../Models/mdl_resultadoProducto.php');
include_once('../Models/mdl_terminacionProductoServicio.php');

$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$usuario  = $_SESSION['usuario'];
$id       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$info = "";
if ($busqueda != null){
    ResultadoProductoTer::cargaBusqueda($usuario, $busqueda, 1); 
} else {
    ResultadoProductoTer::cargaUsuario($usuario, 1); 
}

?>