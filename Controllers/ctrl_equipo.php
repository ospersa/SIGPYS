<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_equipo.php";

/* Inicialización variables*/
$nomEquipo      = (isset($_POST["txtNomEquipo"])) ? $_POST["txtNomEquipo"] : null;
$descEquipo     = (isset($_POST["txtDescEquipo"])) ? $_POST["txtDescEquipo"] : null;
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
} else if (isset($_POST['btnActEqu'])) {
    Equipo::actualizarEquipo($idEquipo2, $nomEquipo, $descEquipo);
} else if (isset($_POST['btnEliEqu'])) {
    Equipo::suprimirEquipo($idEquipo2);
}

?>