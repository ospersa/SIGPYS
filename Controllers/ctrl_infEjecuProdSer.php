<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infEjecuProdSer.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$busqueda    = (empty($_POST['txtBusquedaProy'])) ? null : $_POST['txtBusquedaProy'] ;
$proyecto    = (empty($_POST['sltProy'])) ? null : $_POST['sltProy'] ;


//echo InformeProductoServicio::busqueda($fechIni, $fechFin, $check, $proyecto);
/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeEjecucion::descarga ($proyecto, $fechIni, $fechFin);
} else if (isset($_POST['sltProyecto'])){
    InformeEjecucion::busqueda($proyecto, $fechIni, $fechFin);
}

?>