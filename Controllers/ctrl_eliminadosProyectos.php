<?php
if(!isset($_SESSION)) { 
    session_start();
}

/* Inclusión del Modelo */
include_once('../Models/mdl_eliminadosProyectos.php');

/* Inicialización variables*/
$cod         = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$usuario     = $_SESSION['usuario'];

/* Carga de información en el Modal */
if ($cod != null){
    EliminadosProyectos::activarProyecto($cod, $usuario);
} else {
    EliminadosProyectos::onLoadEliminados();
}

?>