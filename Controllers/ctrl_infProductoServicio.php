<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infProductoServicio.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$check       = (empty($_POST['chkHistorico'])) ? null : $_POST['chkHistorico'] ;
$terminados  = (empty($_POST['chkTerminados'])) ? null : $_POST['chkTerminados'] ;
$busqueda    = (empty($_POST['txtBusquedaProy'])) ? null : $_POST['txtBusquedaProy'] ;
$proyecto    = (empty($_POST['sltProy'])) ? null : $_POST['sltProy'] ;

/* Verificación de las peticiones a través de AJAX */
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    /*if (isset($_POST['sltProy'])) {
        $proyecto = $_POST['sltProy'];
        InformeProductoServicio::busquedaProductoServicioConSaldo($proyecto);
    } else {
        InformeProductoServicio::busquedaProductoServicioConSaldo(null);
    }*/
    echo InformeProductoServicio::busqueda($fechIni, $fechFin, $check, $proyecto, $terminados);
}

/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    //InformeProductoServicio::informeConSaldo($idProy);
    InformeProductoServicio::descarga($fechIni, $fechFin, $check, $proyecto, $terminados);
}

?>