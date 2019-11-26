<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_eliminadosFases.php');

/* Inicialización variables*/
$cod  = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if ($cod != null){
    EliminadosFases::activarFase($cod);
} else {
    EliminadosFases::onLoadEliminados();
}

?>