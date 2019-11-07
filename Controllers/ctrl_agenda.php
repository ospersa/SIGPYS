<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
/* Inclusión del Modelo */
include_once "../Models/mdl_agenda.php";
include_once "../Models/mdl_personas.php";
/* Inicialización variables*/

$proyecto     = (isset($_POST['proyecto'])) ? $_POST['proyecto'] : null;
$fech         = (isset($_POST['fech'])) ? $_POST['fech'] : null;
$idper        = (isset($_POST['idper'])) ? $_POST['idper'] : null;
$check ="";
/* Procesamiento peticiones al controlador */
$usuario  = $_SESSION['usuario'];
$idPeriodo = PlaneacionAse::onPeriodoActual();
$panel = PlaneacionAse::onPeriodo(8);
$personas = Personas::selectPersonas2(9);
$selectProyecto = PlaneacionAse::selectProyectoUsuario($usuario);

if(isset($_POST['proyecto'])){
    $check = PlaneacionAse::selectSolUsuario($usuario, $proyecto);
    
}

?>