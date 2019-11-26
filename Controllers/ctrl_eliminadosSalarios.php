<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_eliminadosSalarios.php');

/* Inicialización variables*/
$cod  = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if ($cod != null){
    EliminadosSalarios::activarSalario($cod);
} else {
    EliminadosSalarios::onLoadEliminados();
}

?>