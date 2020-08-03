<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infNotasTiempos.php');

require_once('../Models/mdl_infEjecuProdSer.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];
$busqueda    = (empty($_POST['txtBusquedaProyInf'])) ? null : $_POST['txtBusquedaProyInf'] ;
$proyecto    = (empty($_POST['stlProyecto'])) ? null : $_POST['stlProyecto'] ;
$solicitud   = (empty($_POST['stlSolicitud'])) ? null : $_POST['stlSolicitud'] ;
$diasLab     = (isset($_POST['diasLab'])) ? $_POST['diasLab'] : null;
$sltProy     = (isset($_POST['dato1'])) ? $_POST['dato1'] : null;
$idProy      = (isset($_POST['dato2'])) ? $_POST['dato2'] : null;



/* Variables que cargan Select en formularios*/
if (isset($_POST['b'])) {
    $busqueda = $_POST['b'];
    InformeNotasTiempo::selectProyectoSol($busqueda);
}
/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeNotasTiempo::descarga ($proyecto, $solicitud, $fechIni, $fechFin, $diasLab); 
}
else if (isset($_POST['dato1']) && isset($_POST['dato2']) ){
    echo InformeNotasTiempo::selectSolicitudes($sltProy);
}
else if (!empty($_POST['txtFechIni']) && !empty($_POST['txtFechFin']) && !empty($_POST['diasLab']) && !empty($_POST['stlProyecto']) ) {
    InformeNotasTiempo::busqueda($proyecto, $solicitud, $fechIni, $fechFin, $diasLab); 
}



?>