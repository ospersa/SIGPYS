<?php
/** Inclusión del Modelo */
include_once("../Models/mdl_solicitud.php");

if (isset($_POST['txt-search'])) {
    $busqueda = $_POST['txt-search'];
    if ($busqueda == null) {
        Solicitud::busquedaTotalEstados();
    } else {
        Solicitud::busquedaEstados($busqueda);
    }
}
?>