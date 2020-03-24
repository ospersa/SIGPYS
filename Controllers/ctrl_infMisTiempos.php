<?php
if(!isset($_SESSION['usuario'])){ 
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_infMisTiempos.php');
include_once('../Models/mdl_tiempos.php');
/* Inicialización variables*/
$user           = $_SESSION['usuario'];
$fechIni        = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin        = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$id             = (empty($_GET['id'])) ? null : $_GET['id'];
$user           = $_SESSION['usuario'];
$idTiempo       = (isset($_POST['idTiempo'])) ? $_POST['idTiempo'] : null;
$nota           = (isset($_POST['notaTEdit'])) ? $_POST['notaTEdit'] : null;
$date           = (isset($_POST['dateEdit'])) ? $_POST['dateEdit'] : null;
$horas          = (isset($_POST['horasEdit'])) ? $_POST['horasEdit'] : null;
$minutos        = (isset($_POST['minutosEdit'])) ? $_POST['minutosEdit'] : null; 
$idFaseEdit     = (isset($_POST['sltFaseEdit'])) ? $_POST['sltFaseEdit'] : null;
/* Procesamiento peticiones al controlador */
if (!empty($_POST['txtFechIni']) && !empty($_POST['txtFechFin']) && !isset($_POST['btnDescargar'])) {
    $tabla = InformeMisTiempos::busqueda($fechIni, $fechFin, $user);
    echo $tabla;
} else if (isset($_POST['btnDescargar'])) {
    InformeMisTiempos::descarga($fechIni, $fechFin);
} else if($id){
    $info =InformeMisTiempos::llenarFormEditar($id);
    $idTiempo   = $info["idTiempo"];
    $fechTiempo = $info["fechTiempo"];
    $horaTiempo = $info["horaTiempo"];
    $minTiempo  = $info["minTiempo"];
    $notaTiempo = $info["notaTiempo"];
    $fase       = Tiempos::selectFase($info["idFase"]);
} else {
   echo InformeMisTiempos::editarTiemposInfo($idTiempo, $user, $date, $horas, $minutos, $idFaseEdit, $nota, $fechIni, $fechFin);
}



?>