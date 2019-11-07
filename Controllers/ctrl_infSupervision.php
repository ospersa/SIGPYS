<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infSupervision.php');

/* Inicialización variables*/
$fechIni     = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin     = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];


if (isset($_POST['txtFechIni']) && isset($_POST['txtFechFin'])  && !isset($_POST['btnDescargar'])) {
    InformeSupervision::busqueda($fechIni, $fechFin);
} else if (isset($_POST['btnDescargar'])) {
    InformeSupervision::descarga($fechIni, $fechFin);
}

?>