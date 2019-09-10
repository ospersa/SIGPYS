<?php
/* Inclusión del Modelo */
include_once("../Models/mdl_plataforma.php");

/* Inicialización de Variables */
$nomPlataforma  = (isset($_POST["txtNomPlataforma"])) ? $_POST['txtNomPlataforma'] : null;
$val            = (isset($_POST['val'])) ? $_POST['val'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idPlataforma   = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if($id){
    $info = Plataforma::onLoad($id);
    $nomPlataforma = $info['nombrePlt'];
}

/* Peticiones realizadas al Controlador */
if (isset($_POST['btnGuardarPlataforma'])) {
    Plataforma::registrarPlataforma($nomPlataforma);
} else if ($val == "1") {
    Plataforma::actualizarPlataforma($idPlataforma, $nomPlataforma);
} else if ($val == "2") {
    Plataforma::suprimirPlataforma($idPlataforma);
}

?>