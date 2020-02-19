<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infProductosCelulas.php');

/* Inicialización de variables */
$idCelula = (isset($_POST['sltCelula'])) ? $_POST['sltCelula'] : null;
$idProy   = (isset($_POST['sltProyecto'])) ? $_POST['sltProyecto'] : null;
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];

/* Variables que cargan Select en formularios*/
$selectCelula = InformeProductosCelulas::selectCelula();
$selectProyecto = InformeProductosCelulas::selectProyecto("all");

/* Procesamiento peticiones al controlador */
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['sltCelula']) && !isset($_POST['sltProyecto'])) {
        echo $selectProyecto = InformeProductosCelulas::selectProyecto($idCelula);
    }
    if (isset($_POST['sltCelula']) && isset($_POST['sltProyecto'])) {
        echo InformeProductosCelulas::busqueda($idCelula, $idProy, $fechIni, $fechFin);
    }
}

/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeProductosCelulas::descarga($idCelula, $idProy, $fechIni, $fechFin);
}

?>