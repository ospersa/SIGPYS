<?php

$persona = $_POST['idPersona'];
$periodo = $_POST['sltPeriodo'];
$porcenDedica1 = $_POST['dedicacion1Seg'];
$porcenDedica2 = $_POST['dedicacion2Seg'];
$idDedica = $_REQUEST['id'];
$val = $_POST['val'];

include_once"../Models/mdl_dedicacion.php";

if ($persona != null){
    $resultado = Dedicacion::guardarDedicacion($periodo, $persona, $porcenDedica1, $porcenDedica2);
}

if($idDedica != null){
    $info = new Dedicacion();
    $detail = $info->onLoad($idDedica);
    if (is_array($detail)) {
        foreach ($detail as $valor) {
            $dedicacionSeg1 = $valor['porcentajeDedicacion1'];
            $dedicacionSeg2 = $valor['porcentajeDedicacion2'];
            $horasSeg1 = (($valor['diasSegmento1'] * 8) * $dedicacionSeg1) / 100;
            $horasSeg2 = (($valor['diasSegmento2'] * 8) * $dedicacionSeg2) / 100;
            $diasSeg1 = $valor['diasSegmento1'];
            $diasSeg2 = $valor['diasSegmento2'];
            $dedicacion = $valor[3];
            $horasReales = $valor[4];
            $nombreCompleto = $valor['apellido1']." ".$valor['apellido2']." ".$valor['nombres'];
        }
    }
}

if ($val=="1") {
    $porcenDedica1 = $_POST['txtDedicacion1'];
    $porcenDedica2 = $_POST['txtDedicacion2'];
    $horasDedica = $_POST['txtHorasSeg1'] + $_POST['txtHorasSeg2'];
    $idDedica2 = $_POST['cod'];
    Dedicacion::actualizarDedicacion($idDedica2, $porcenDedica1, $porcenDedica2, $horasDedica);
} else if ($val=="2") {
    $idDedica2 = $_POST['cod'];
    Dedicacion::suprimirDedicacion($idDedica2);
}


?>