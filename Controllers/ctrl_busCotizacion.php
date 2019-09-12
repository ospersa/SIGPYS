<?php
/* Inclusión del Modelo */
require('../Models/mdl_cotizacion.php');

/* Inicialización variables*/
$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Procesamiento peticiones al controlador */
if ($busqueda == null) {
    echo "<script>alert('Por favor no deje el campo de busqueda vacío.')</script>";
    echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos a la página anterior
} else {
    $resultado = Cotizacion::busquedaPendientes($busqueda);
}

?>