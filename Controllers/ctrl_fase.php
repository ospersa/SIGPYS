<?php

/* Carga del Modelo */
include_once("../Models/mdl_fase.php");

/* Inicialización de Variables */
$nomFase    = (isset($_POST["txtNomFase"])) ? $_POST["txtNomFase"] : null;
$descFase   = (isset($_POST["txtDescFase"])) ? $_POST["txtDescFase"] : null;
$val        = (isset($_POST['val'])) ? $_POST['val'] : null;
$id         = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idFase     = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if ($id) {
    $info = Fase::onLoad($id);
    $nomFase = $info['nombreFase'];
    $descFase = $info['descripcionFase'];
}

/* Procesamiento de peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Fase::busquedaTotal() : Fase::busqueda($search);
} else if (isset($_POST['btnGuardarFase'])) {
    Fase::registrarFase($nomFase, $descFase);
} else if (isset($_POST['btnActFase'])) {
    Fase::actualizarFase($idFase, $nomFase, $descFase);
} elseif (isset($_POST['btnEliFase'])) {
    Fase::suprimirFase($idFase);
}

?>