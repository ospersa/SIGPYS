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
$idAgenda     = (isset($_POST['idAgenda'])) ? $_POST['idAgenda'] : null;
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
$usuario  = PlaneacionAse::UsuarioPersona($idper);
$idPeriodo = PlaneacionAse::onPeriodoActual();
$personas = Personas::selectPersonas2($idPeriodo);
if ($cod == 3){
    echo $panel = PlaneacionAse::onPeriodo($idPeriodo, $usuario);
}
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
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $idAgenda, $horas, $min, $obser, 2, $sltFase);
} else if (isset($_POST['btnActAgenda'])){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $idAgenda, $horas, $min, $obser, 1, $sltFase);
} else if ($cod == 2){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $idAgenda, $horas, $min, $obser, 3, $sltFase);
}

?>