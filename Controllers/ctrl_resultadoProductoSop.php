<?php 
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
/* Inclusión del Modelo */
include('../Models/mdl_resultadoProducto.php');
include_once('../Models/mdl_terminacionProductoServicio.php');

$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$usuario  = $_SESSION['usuario'];
$id       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$info = "";
if ($id != null){
    $info = Terminar::informacionProdSer($id); 
} else if ($busqueda != null){
    ResulProducto::cargaBusqueda($usuario, $busqueda, 3); 
} else {
    ResulProducto::cargaUsuario($usuario, 3); 

}

?>