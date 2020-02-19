<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infMisTiempos.php');
include_once('../Models/mdl_tiempos.php');
if(!isset($_SESSION)){ 
    session_start();
}
/* Inicialización variables*/
$user    = $_SESSION['usuario'];
$fechIni = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$id      = (empty($_GET['id'])) ? null : $_GET['id'];

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
                        

} 

?>