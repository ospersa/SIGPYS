<?php 
/* Inclusión del Modelo */
include_once("../Models/mdl_cargaExcel.php");

$operacion  = isset($_POST['operacion']) ? $_POST['operacion'] : null;
$archivo    = isset($_POST['file']) ? $_POST['file'] : null;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($operacion != null) {
        CargaExcel::cargaArchivo($operacion, $archivo);
    }
}
?>