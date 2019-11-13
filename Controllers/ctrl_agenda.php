<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
/* Inclusión del Modelo */
include_once "../Models/mdl_agenda.php";
include_once "../Models/mdl_personas.php";
include_once "../Models/mdl_tiempos.php";
/* Inicialización variables*/

$proyecto     = (isset($_POST['proyecto'])) ? $_POST['proyecto'] : null;
$fech         = (isset($_POST['fech'])) ? $_POST['fech'] : null;
$idper        = (isset($_POST['idper'])) ? $_POST['idper'] : null;
$cantidad     = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : null;
$horas        = (isset($_POST['horas'])) ? $_POST['horas'] : null;
$min        = (isset($_POST['min'])) ? $_POST['min'] : null;
$obser     = (isset($_POST['obser'])) ? $_POST['obser'] : null;
$idSol     = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;
$fecha     = (isset($_POST['fecha'])) ? $_POST['fecha'] : null;
$sltFase     = (isset($_POST['sltFase'])) ? $_POST['sltFase'] : null;
$long     = (isset($_POST['long'])) ? $_POST['long'] : null;
/*
cod = 1 si se va ha realizar el registro de tiempo
cod = 2 si se va ha cancelar la actividad en la agenda
*/
$cod     = (isset($_POST['cod'])) ? $_POST['cod'] : null;

$check ="";
$selectProyecto = "";
/* Procesamiento peticiones al controlador */
$usuario  = $_SESSION['usuario'];
$idPeriodo = PlaneacionAse::onPeriodoActual();
$panel = PlaneacionAse::onPeriodo($idPeriodo, $usuario);//Cambiar 8 por $idPeriodo
$personas = Personas::selectPersonas2($idPeriodo);
if(isset($_POST['fech'])){
 PlaneacionAse::crearDiv(1, $usuario, $fech);
}
if(isset($_POST['proyecto'])){
    $check = PlaneacionAse::selectSolUsuario($usuario, $proyecto, $idPeriodo, $long);  
} 
if (isset($_POST['cantidad'])){
    echo $divs = PlaneacionAse::crearDivP($cantidad, $usuario);
} else if (isset($_POST['btnGuardar']) ){
    PlaneacionAse::guardarPlaneacion($idSol, $horas, $min, $obser, $usuario, $fecha,$idPeriodo);
} else if ($cod == 1){
    Tiempos::registrarTiempos($idSol, $usuario,  date("Y-m-d", strtotime($fecha)), $obser, $horas, $min, $sltFase,1);
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $horas, $min, $obser, 2);
} else if (isset($_POST['btnActAgenda'])){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $horas, $min, $obser, 1);
} else if ($cod == 2){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $horas, $min, $obser, 3);
}

?>