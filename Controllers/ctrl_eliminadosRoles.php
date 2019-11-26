<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_eliminadosRoles.php');

/* Inicialización variables*/
$cod  = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if ($cod != null){
    EliminadosRoles::activarRol($cod);
} else {
    EliminadosRoles::onLoadEliminados();
}

?>