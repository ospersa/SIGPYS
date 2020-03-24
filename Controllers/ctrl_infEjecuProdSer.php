<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infEjecuProdSer.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$busqueda    = (empty($_POST['txtBusquedaProy'])) ? null : $_POST['txtBusquedaProy'] ;
$proyecto    = (empty($_POST['sltProy'])) ? null : $_POST['sltProy'] ;
$frenteinf   = (empty($_POST['sltFrenteInf'])) ? null : $_POST['sltFrenteInf'] ;
$diasLab     = (isset($_POST['diasLab'])) ? $_POST['diasLab'] : null;
$frente = InformeEjecucion::selectFrente();



/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeEjecucion::descarga ($proyecto, $frenteinf, $fechIni, $fechFin, $diasLab);
} else{
    InformeEjecucion::busqueda($proyecto, $frenteinf, $fechIni, $fechFin, $diasLab);
}


?>