<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_planeacion.php";

/* Inicialización variables*/
$idDedicacion   = (isset($_POST['txtIdDedicacion'])) ? $_POST['txtIdDedicacion'] : null;
$idAsignado     = (isset($_POST['idAsignado'])) ? $_POST['idAsignado'] : null;
$hrsInvertir    = (isset($_POST['horasInvertir'])) ? $_POST['horasInvertir'] : null;
$mtsInvertir    = (isset($_POST['minutosInvertir'])) ? $_POST['minutosInvertir'] : null;
$observacion    = (isset($_POST['observacion'])) ? $_POST['observacion'] : null;
$idAsignado2    = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

/* Carga de información en el Modal */
if ($idAsignado2 != null) {
    $info = Planeacion::onLoad($idAsignado2);
    $hrsInvertir = $info['horasInvertir'];
    $mtsInvertir = $info['minutosInvertir'];
}


if ($idDedicacion != null && $idAsignado != null && $hrsInvertir != null && $mtsInvertir != null) {
    $hrsDisp = $_POST['txtHorasDisponibles'];
    $resultado = Planeacion::guardarPlaneacion($idDedicacion, $idAsignado, $hrsInvertir, $mtsInvertir, $hrsDisp, $observacion);
} else if (isset($_POST['btnActPlane'])) {
    $idAsignado2 = $_POST['cod'];
    Planeacion::actualizarTiempos($idAsignado2, $hrsInvertir, $mtsInvertir);
}

?>