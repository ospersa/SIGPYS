<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_salario.php');

/* Inicialización de Variables */
$idPersona          = (isset($_POST["sltPersona"])) ? $_POST["sltPersona"] : null;
$salario            = (isset($_POST['txtSalario'])) ? $_POST['txtSalario'] : null;
$vigDesde           = (isset($_POST['txtVigIni'])) ? $_POST['txtVigIni'] : null;
$vigFin             = (isset($_POST['txtVigFin'])) ? $_POST['txtVigFin'] : null;
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idSalario          = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$val                = (isset($_POST['val'])) ? $_POST['val'] : null;
$nombreCompleto     = null;

/** Variables que cargan select en otros formularios */
$selectPersona = Salarios::selectPersonaConectate(null);

/** Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarSalario'])) {
    Salarios::registrarSalario($idPersona, $salario, $vigDesde, $vigFin);
} else if ($val == '1') {
    Salarios::actualizarSalario($idSalario, $salario, $vigDesde, $vigFin);
} else if ($val == "2") {
    Salarios::eliminarSalario($idSalario);
}

/** Carga de información en el Modal */
if ($id) {
    $info = Salarios::onLoad($id);
    $nombreCompleto = $info['apellido1']." ".$info['apellido2']." ".$info['nombres'];
    $salario = $info['salario'];
    $vigDesde = $info['mes'];
    $vigFin = $info['anio'];
}

?>