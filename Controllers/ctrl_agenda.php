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
$cantidad     = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : null;
$horas        = (isset($_POST['horas'])) ? $_POST['horas'] : null;
$obser     = (isset($_POST['obser'])) ? $_POST['obser'] : null;
$idSol     = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;


//var_dump($horas);
$check ="";
$selectProyecto = "";
/* Procesamiento peticiones al controlador */
$usuario  = $_SESSION['usuario'];
$idPeriodo = PlaneacionAse::onPeriodoActual();
$panel = PlaneacionAse::onPeriodo(8);
$personas = Personas::selectPersonas2(9);
if(isset($_POST['fech'])){
 PlaneacionAse::crearDiv(1, $usuario, $fech);
}
if(isset($_POST['proyecto'])){
    $check = PlaneacionAse::selectSolUsuario($usuario, $proyecto);
    
}
if (isset($_POST['cantidad'])){
    echo $divs = PlaneacionAse::crearDivP($cantidad, $usuario);
}
if (isset($_POST['btnGuardar'])){
    PlaneacionAse::guardarPlaneacion($idSol, $horas, $obser, $usuario);
}

?>