<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_inventario.php');
include_once('../Models/mdl_solicitudEspecifica.php');
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
$id        = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$prep      = "";



if ($id != null){
    $prep  = substr($id, 0, 3);
    $id    = substr($id, 3);
    $infoAsig = Inventario::OnLoadAsignados($id); 
    $selectEstado = Inventario::selectEstadoInv($id);
    $info                   = SolicitudEspecifica::formResultado($id);
    $idSol                  = $info['idSol'];
    $desSol                 = $info['descripcionSol'];
    $idProy                 = $info['codProy'];
    $nomProy                = $info['nombreProy'];
    $nomProdOSer            = $info['nombreSer'];
    $equipo                 = $info['nombreEqu'];
    $servicio               = $info['nombreSer'];
    $solEspecifica          = $info['ObservacionAct'];
    $fechaPrev              = $info['fechPrev'];
    $idSer                  = $info['idSer'];
    $complemento = Inventario::formularioInventario($id);
} else if (($persona || $proyecto || $equipo || $idSol || $descrip) != null){
    Inventario::onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip);
} else{ 
    Inventario::onLoadUsuario($usuario);
    
}


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
} 


?>