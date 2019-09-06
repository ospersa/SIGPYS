<?php
/** Inclusión del Modelo */
include_once("../Models/mdl_rol.php");

/** Inicialización de Variables */
$tipRol = (isset($_POST["selTipRol"])) ? $_POST["selTipRol"] : null;
$nomRol = (isset($_POST["txtNomRol"])) ? $_POST["txtNomRol"] : null;
$descRol = (isset($_POST["txtDescRol"])) ? $_POST["txtDescRol"] : null;
$val = (isset($_POST['val'])) ? $_POST['val'] : null;
$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idRol = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/** Variables que cargan select en otros formularios */
$selectTipRol = Rol::selectTipoRol(null);

/** Procesamiento de peticiones al Controlador */
if (isset($_POST['btnGuardarRol'])) {
    Rol::registrarRol($tipRol, $nomRol, $descRol);
} else if ($val == "1") {
    Rol::actualizarRol($idRol, $tipRol, $nomRol, $descRol);
} else if ($val == "2") {
    Rol::suprimirRol($idRol);
}

/** Carga de información en el Modal */
if ($id) {
    $info = Rol::onLoad($id);
    $tipRol = $info['idTipRol'];
    $nomTipRol = $info['nombreTipRol'];
    $idRol = $info['idRol'];
    $nomRol = $info['nombreRol'];
    $descRol = $info['descripcionRol'];
}

?>