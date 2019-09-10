<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_equipo.php";

/* Inicialización variables*/
$nomEquipo      = (isset($_POST["txtNomEquipo"])) ? $_POST["txtNomEquipo"] : null;
$descEquipo     = (isset($_POST["txtDescEquipo"])) ? $_POST["txtDescEquipo"] : null;
$val            = (isset($_POST["val"])) ? $_POST["val"] : null;
$idEquipo       = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null;
$idEquipo2      = (isset($_POST["cod"])) ? $_POST["cod"] : null;

/* Carga de información en el Modal */
if($idEquipo){
    $info =  Equipo::onLoad($idEquipo);
    $nomEquipo = $info['nombreEqu'];
    $descEquipo = $info['descripcionEqu'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarEqu'])) {
    $resultado = Equipo::registrarEquipo($nomEquipo, $descEquipo);
} else if ($val == "1") {
    Equipo::actualizarEquipo($idEquipo2, $nomEquipo, $descEquipo);
} else if ($val == "2") {
    Equipo::suprimirEquipo($idEquipo2);
}

?>