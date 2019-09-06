<?php 
/** Inclusión del Modelo */
include('../Models/mdl_productos.php');

/** Inicialización variables */
$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/** Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    Producto::busquedaClaseProductos($busqueda);
}

?>