<?php
/* Inclusión del Modelo */
require('../Models/mdl_solicitudInicial.php');

/* Inicialización variables*/
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? SolicitudInicial::busquedaTotalIniciales() : SolicitudInicial::busquedaIniciales($search);
}

?>