<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
}

/* Inclusión del Modelo */
require('../Models/mdl_solicitudInicial.php');

/* Inicialización variables*/
$search     = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$userName   = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? SolicitudInicial::busquedaTotalInicialesGest($userName) : SolicitudInicial::busquedaInicialesGest($search, $userName);
}
