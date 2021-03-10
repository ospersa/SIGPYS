<?php 
/* Inicializar variables de sesión */
if(!isset($_SESSION['usuario'])) { 
    session_start();
}

/* Inclusión del Modelo */
include_once('../Models/mdl_productosInfo.php');
include_once('../Models/mdl_terminacionProductoServicio.php');

$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$id       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$info = "";
if ($busqueda != null){
    ProductoInfo::cargaBusqueda($busqueda, 1); 
} else {
    ProductoInfo::carga(); 
}

?>