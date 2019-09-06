<?php

$idDedicacion = $_POST['txtIdDedicacion'];
$idAsignado = $_POST['idAsignado'];
$hrsInvertir = $_POST['horasInvertir'];
$mtsInvertir = $_POST['minutosInvertir'];
$observacion = $_POST['observacion'];
$idAsignado2 = $_REQUEST['id'];
$val = $_POST['val'];

$val;

include_once "../Models/mdl_planeacion.php";

if ($idDedicacion != null && $idAsignado != null && $hrsInvertir != null && $mtsInvertir != null) {
    $hrsDisp = $_POST['txtHorasDisponibles'];
    $resultado = Planeacion::guardarPlaneacion($idDedicacion, $idAsignado, $hrsInvertir, $mtsInvertir, $hrsDisp, $observacion);
} 

if ($idAsignado2 != null) {
    $info = new Planeacion();
    $detail = $info->onLoad($idAsignado2);
    if (is_array($detail)){
        foreach ($detail as $valor) {
            $hrsInvertir = $valor['horasInvertir'];
            $mtsInvertir = $valor['minutosInvertir'];
        }
    }
}

if ($val=="1") {
    $hrsInvertir = $_POST['txtHrsInvertir'];
    $mtsInvertir = $_POST['txtMtsInvertir'];
    $idAsignado2 = $_POST['cod'];
    Planeacion::actualizarTiempos($idAsignado2, $hrsInvertir, $mtsInvertir);
}

?>