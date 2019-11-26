<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_eliminadosFrentes.php');

/* Inicialización variables*/
$cod  = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if ($cod != null){
    EliminadosFrentes::activarFrente($cod);
} else {
    EliminadosFrentes::onLoadEliminados();
}

?>