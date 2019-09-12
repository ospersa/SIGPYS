<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_periodo.php";

/* Inicialización de Variables */
$fechaInicial   = (isset($_POST['txtFechaInicial'])) ? $_POST['txtFechaInicial']: null;
$fechaFinal     = (isset($_POST['txtFechaFinal'])) ? $_POST['txtFechaFinal'] : null;
$diasSeg1       = (isset($_POST['txtDiasSeg1'])) ? $_POST['txtDiasSeg1'] : null;
$diasSeg2       = (isset($_POST['txtDiasSeg2'])) ? $_POST['txtDiasSeg2'] : null;
$id             = (isset($_REQUEST["id"])) ? $_REQUEST['id'] : null;
$idPeriodo      = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$val            = (isset($_POST["val"])) ? $_POST['val'] : null;

/* Procesamiento de peticiones al Controlador */
if (isset($_POST['btnGuardarPeriodo'])) {
    if ($fechaInicial != null && $fechaFinal != null && $diasSeg1 != null && $diasSeg2 != null ) {
        Periodo::registrarPeriodo($fechaInicial, $fechaFinal, $diasSeg1, $diasSeg2);
    } else {
        echo "<script>alert ('Existe algún campo vacío. El registro no se pudo guardar');</script>";
        echo '<meta http-equiv="Refresh" content="0;url=../Views/periodo.php">';
    }
} else if ($val == "1") {
    Periodo::actualizarPeriodo($idPeriodo, $fechaInicial, $fechaFinal, $diasSeg1, $diasSeg2);
} 

/* Carga de información en el Modal */
if($id){
    $info = Periodo::onLoad($id);
    $fechaInicial = $info['inicioPeriodo'];
    $fechaFinal = $info['finPeriodo'];
    $diasSeg1 = $info['diasSegmento1'];
    $diasSeg2 = $info['diasSegmento2'];
}

?>