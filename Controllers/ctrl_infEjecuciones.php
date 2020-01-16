<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infEjecuciones.php');

/* Inicialización variables*/
$txtFechIni = (isset($_POST['txtFechIni'])) ? $_POST['txtFechIni'] : null;
$txtFechFin = (isset($_POST['txtFechFin'])) ? $_POST['txtFechFin'] : null;
$diasLab    = (isset($_POST['diasLab'])) ? $_POST['diasLab'] : null;


/* Procesamiento peticiones al controlador */
if (isset($_POST['btnDescargar'])) {
    InformeEjecuciones::descarga($txtFechIni, $txtFechFin, $diasLab);
} else {
    InformeEjecuciones::busqueda($txtFechIni, $txtFechFin, $diasLab);
} 
?>