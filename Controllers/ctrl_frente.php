<?php
/* Inclusión del Modelo */
include_once("../Models/mdl_frente.php");

/* Inicialización de Variables */
$nomFrente      = (isset($_POST["txtNomFrente"])) ? $_POST["txtNomFrente"] : null;
$descFrente     = (isset($_POST["txtDescFrente"])) ? $_POST["txtDescFrente"] : null;
$coorFrente     = (isset($_POST["selCoorFrente"])) ? $_POST["selCoorFrente"] : null;
$val            = (isset($_POST['val'])) ? $_POST['val'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idFrente       = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$idPersona      = null;
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Variables que cargan select en otros formularios */
$selectCoorFrente = Frente::selectCoordinadorFrente(null);

/* Carga de información en el Modal */
if ($id) {
    $info = Frente::onLoad($id);
    $nomFrente = $info['nombreFrente'];
    $descFrente = $info['descripcionFrente'];
    $idPersona = $info['idPersona'];
}
      
/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Frente::busquedaTotal() : Frente::busqueda($search);
} else if (isset($_POST['btnGuardarFrente'])) {
    Frente::registrarFrente($nomFrente, $descFrente, $coorFrente);
} else if ($val == "1") {
    Frente::actualizarFrente($idFrente, $nomFrente, $descFrente, $coorFrente);
} else if ($val == "2") {
    Frente::suprimirFrente($idFrente);
}

?>