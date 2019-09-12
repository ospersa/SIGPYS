<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_fase.php";

/* Inicialización variables*/
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
  
/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Fase::busquedaTotal() : Fase::busqueda($search);
}
    
?>