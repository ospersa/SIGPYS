<?php 
/** Inclusión del modelo */
include('../Models/mdl_productos.php');

/** Inicialización de variables */
$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/** Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    Producto::busquedaTipoProductos($busqueda);
}

?>