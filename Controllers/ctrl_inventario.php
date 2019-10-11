<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_inventario.php');
if(!isset($_SESSION)){ 
    session_start();
}
/* Inicialización variables*/
$usuario    = $_SESSION['usuario'];
$perfil = $_SESSION['perfil'];

$persona   =(isset($_POST['sltPersona'])) ? $_POST['sltPersona'] : null;
$proyecto  =(isset($_POST['sltProyecto'])) ? $_POST['sltProyecto'] : null;
$equipo    =(isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$idSol     =(isset($_POST['sltProducto'])) ? $_POST['sltProducto'] : null; 
$descrip   =(isset($_POST['txtDescrip'])) ? $_POST['txtDescrip'] : null;




if (isset($_POST['persona'])) {
    $busqueda = $_POST['persona'];
    Inventario::selectPersona($busqueda);
} else if (isset($_POST['equipo'])) {
    $busqueda = $_POST['equipo'];
    Inventario::selectEquipo($busqueda);
} else if (isset($_POST['producto'])) {
    $busqueda = $_POST['producto'];
    Inventario::selectProducto($busqueda);
} else if (isset($_POST['proyecto'])) {
    $busqueda = $_POST['proyecto'];
    Inventario::selectProyecto($busqueda);
} else if (($persona || $proyecto || $equipo || $idSol || $descrip) != null){
    Inventario::onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip);
} else{ 
    Inventario::onLoadUsuario($usuario);
    
}


?>