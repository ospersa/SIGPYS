<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_cargo.php";

/* Inicialización variables*/
$nomCargo    = (isset($_POST["txtnomCargo"])) ? $_POST["txtnomCargo"] : null;
$descCargo   = (isset($_POST["txtdescCargo"])) ? $_POST["txtdescCargo"] : null;
$val         = (isset($_POST['val'])) ? $_POST['val'] : null;
$idCargo     = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idCargo2    = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if($idCargo){
    $info = Cargo::onLoad($idCargo);
    $nomCargo = $info['nombreCargo'];
    $descCargo = $info['descripcionCargo'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarCargo'])) {
    $resultado = Cargo::registrarCargo($nomCargo, $descCargo);
} else if ($val == "1") {
    Cargo::actualizarCargo($idCargo2, $nomCargo, $descCargo);
} else if ($val == "2") {
    Cargo::suprimirCargo($idCargo2);
}

?>