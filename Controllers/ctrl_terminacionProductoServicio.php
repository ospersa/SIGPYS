<?php
include_once('../Models/mdl_terminacionProductoServicio.php');


$busProy  = (isset($_POST['sltProy'])) ? $_POST['sltProy'] : null;
$fechIni  = (isset($_POST['txtFechIni'])) ? $_POST['txtFechIni']: null; 
$fechFin  = (isset($_POST['txtFechFin'])) ? $_POST['txtFechFin']: null;
if(!isset($_SESSION)) { 
    session_start();
}
$cod = 0;
$usuario = $_SESSION['usuario'];

if ($busProy != ''){
    $cod = 1;
} else if ($fechIni != null && $fechFin != null){
    $cod = 2;
} else{
    $cod = 3;
}
if (isset($_POST['b'])) {
    $busqueda = $_POST['b'];
    $resultado = Terminar::selectProyectoUsuario($busqueda, $usuario);
} else {
echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechIni, $fechFin);
}

?>
