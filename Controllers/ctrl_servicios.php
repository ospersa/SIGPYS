<?php
/* Inclusión del Modelo */
include('../Models/mdl_servicios.php');

/* Inicialización Variables */
$equipo         = (isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$nombre         = (isset($_POST['txtNombreServicio'])) ? $_POST['txtNombreServicio'] : null;
$nombreCorto    = (isset($_POST['txtNombreCortoServicio'])) ? $_POST['txtNombreCortoServicio'] : null;
$descripcion    = (isset($_POST['txtDescripcionServicio'])) ? $_POST['txtDescripcionServicio'] : null;
$producto       = (isset($_POST['sltProducto'])) ? $_POST['sltProducto'] : null;
$costo          = (isset($_POST['txtCostoServicio'])) ? $_POST['txtCostoServicio'] : null;
$busqueda       = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnRegistrar'])) {
    Servicios::registrarServicio($equipo, $nombre, $nombreCorto, $descripcion, $producto, $costo);
} else if (isset($_POST['txt-search'])) {
    Servicios::busquedaServicios($busqueda);
}

?>