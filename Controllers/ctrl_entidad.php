<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_entidad.php";

/* Inicialización variables*/
$nomEnti        = (isset($_POST["txtNomEnti"])) ? $_POST["txtNomEnti"] : null;
$nomCortoEnti   = (isset($_POST["txtNomCortoEnti"])) ? $_POST["txtNomCortoEnti"] : null;
$descEnti       = (isset($_POST["txtDescEnti"])) ? $_POST["txtDescEnti"] : null;
$val            = (isset($_POST["val"])) ? $_POST["val"] : null;
$idEnti         = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null;
$idEnti2        = (isset($_POST["cod"])) ? $_POST["cod"] : null;

/* Carga de información en el Modal */
if($idEnti){
    $info = Entidad::onLoad($idEnti);
    $nomEnti = $info['nombreEnt'];
    $nomCortoEnti = $info['nombreCortoEnt'];
    $descEnti = $info['descripcionEnt'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarEnt'])) {
    $resultado = Entidad::registrarEntidad($nomEnti, $nomCortoEnti, $descEnti);
} else if (isset($_POST['btnActEnti'])) {
    Entidad::actualizarEntidad($idEnti2, $nomEnti, $nomCortoEnti, $descEnti);
} else if (isset($_POST['btnEliEnti'])) {
    Entidad::suprimirEntidad($idEnti2);
}

?>