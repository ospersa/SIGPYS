<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_rol.php";

/* Inicialización variables*/
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
    
/* Inicialización variables*/
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? $resultado = Rol::busquedaTotal() : $resultado = Rol::busqueda($search);
}
 
?>   