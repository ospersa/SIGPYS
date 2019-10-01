<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_cargo.php";

/* Inicialización variables*/
$nomCargo    = (isset($_POST["txtnomCargo"])) ? $_POST["txtnomCargo"] : null;
$descCargo   = (isset($_POST["txtdescCargo"])) ? $_POST["txtdescCargo"] : null;
$idCargo     = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idCargo2    = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if($idCargo){
    $info = Cargo::onLoad($idCargo);
    $nomCargo = $info['nombreCargo'];
    $descCargo = $info['descripcionCargo'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Cargo::busquedaTotal() : Cargo::busqueda($search);
} else if (isset($_POST['btnGuardarCargo'])) {
    $resultado = Cargo::registrarCargo($nomCargo, $descCargo);
} else if (isset($_POST['btnActCargo'])) {
    Cargo::actualizarCargo($idCargo2, $nomCargo, $descCargo);
} else if (isset($_POST['btnEliCargo'])) {
    Cargo::suprimirCargo($idCargo2);
}

?>