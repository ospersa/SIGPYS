<?php
/* Inclusión del Modelo */
require('../Models/mdl_cotizacion.php');

/* Inicialización variables*/
$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$cotizaciones = null;
/* Procesamiento peticiones al controlador */
if ($busqueda == null) {
    $cotizaciones = Cotizacion::listarCotizaciones();
} else {
    $resultado = Cotizacion::busquedaPendientes($busqueda);
}

?>