<?php
if(!isset($_SESSION)) { 
    session_start();
}
include_once('../Models/mdl_terminacionProductoServicio.php');
include_once('../Models/mdl_tiempos.php');
$busProy    = (isset($_POST['sltProy'])) ? $_POST['sltProy'] : null;
$fechFin    = (isset($_POST['txtFechPre'])) ? $_POST['txtFechPre']: null;
$cod        = 0;
$usuario    = $_SESSION['usuario'];
$id         = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$prep ="";
if (isset($_POST['b']) ) {
    $busqueda = $_POST['b'];
    Terminar::selectProyectoUsuario($busqueda, $usuario);
} else if($id != null) {
    $prep = substr($id, 0, 3);
    $id = substr($id, 3);
    echo $prep;
    $tiempoInvertido = Tiempos::OnloadTiempoInvertido($id);
    
}else {
    if ($busProy != '' && $fechFin == null){
        $cod = 1;
        echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechFin);
    } else if ($fechFin != null && $busProy == ''){
        $cod = 2;
        echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechFin);
    } else if ($fechFin != null && $busProy != ''){
        $cod = 3;
        echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechFin);
    } else {
        $cod = 4;
        echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechFin);
    }
}



?>
