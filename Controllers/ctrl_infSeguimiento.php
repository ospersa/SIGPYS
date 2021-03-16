<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infSeguimiento.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$busqueda    = (empty($_POST['txtBusquedaProy'])) ? null : $_POST['txtBusquedaProy'] ;
$proyecto    = (empty($_POST['sltProy'])) ? null : $_POST['sltProy'] ;
$frenteinf   = (empty($_POST['sltFrenteInf'])) ? null : $_POST['sltFrenteInf'] ;
$frente = InformeSeguimiento::selectFrente();



/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeSeguimiento::descarga ($proyecto, $frenteinf, $fechIni, $fechFin);
} else{
    InformeSeguimiento::busqueda($proyecto, $frenteinf, $fechIni, $fechFin);
}


?>