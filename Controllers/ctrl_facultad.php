<?php

/* Carga del Modelo */
include_once('../Models/mdl_facultad.php');

/* Inicialización de Variables */
$entidad        = (isset($_POST["selEntidad"])) ? $_POST["selEntidad"] : null;
$nomFacultad    = (isset($_POST["txtNomFacultad"])) ? $_POST["txtNomFacultad"] : null;
$val            = (isset($_POST['val'])) ? $_POST['val'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idFacultad2    = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$search         = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Variables que cargan select en otros formularios */
$selectEntidad = Facultad::selectEntidad(null);

/* Carga de información en el Modal */
if ($id) {
    $info = Facultad::onLoad($id);
    $entidad = $info['idEnt'];
    $nomFacultad = $info['nombreFac'];
}

/** Procesamiento de peticiones realizadas al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Facultad::busquedaTotal() : Facultad::busqueda($search);
}

if (isset($_POST['btnGuardarFacultad'])) {
    $resultado = Facultad::registrarFacultad($entidad, $nomFacultad);
} else if (isset($_POST['btnActFac'])) {
    Facultad::actualizarFacultad($idFacultad2, $entidad, $nomFacultad);
} else if (isset($_POST['btnEliFac'])) {
    Facultad::suprimirFacultad($idFacultad2);
}

?>