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
$fech         = (isset($_POST['fech2'])) ? $_POST['fech2'] : null;
$cantidad     = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : null;
$horas        = (isset($_POST['horas'])) ? $_POST['horas'] : null;
$min          = (isset($_POST['min'])) ? $_POST['min'] : null;
$obser        = (isset($_POST['obser'])) ? $_POST['obser'] : null;
$idSol        = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;
$idAgenda     = (isset($_POST['idAgenda'])) ? $_POST['idAgenda'] : null;
$fecha        = (isset($_POST['fecha'])) ? $_POST['fecha'] : null;
$sltFase      = (isset($_POST['sltFaseEdit'])) ? $_POST['sltFaseEdit'] : null;
$long         = (isset($_POST['long'])) ? $_POST['long'] : null;
$cod          = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$fechaCambio  = (isset($_POST['fechaCambio'])) ? $_POST['fechaCambio'] : null;
$check ="";
$selectProyecto = "";
$usuario    = $_SESSION['usuario'];
$registrados = "";        

/*
cod = 1 si se va ha realizar el registro de tiempo
cod = 2 si se va ha cancelar la actividad en la agenda
*/


/* Procesamiento peticiones al controlador */
$idPeriodo  = PlaneacionAse::onPeriodoActual();
$panel      = PlaneacionAse::onPeriodo($idPeriodo, $usuario);
$personas   = Personas::selectPersonas2($idPeriodo);
if(isset($_POST['fech'])){
 PlaneacionAse::crearDivP(1, $usuario);
}
if(isset($_POST['fech2'])){
 PlaneacionAse::mostrarAgenda ($fech, $usuario);
}
if(isset($_POST['proyecto'])){
    $check = PlaneacionAse::selectSolUsuario($usuario, $proyecto, $idPeriodo, $long);  
} 
if (isset($_POST['cantidad'])){
    echo $divs = PlaneacionAse::crearDivP($cantidad, $usuario);
} else if (isset($_POST['btnGuardar']) ){
    PlaneacionAse::guardarPlaneacion($idSol, $horas, $min, $obser, $usuario, $fecha,$idPeriodo);
} 
else if (isset($_POST['btnActAgenda'])){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $idAgenda, $horas, $min, $obser, 1, $sltFase, $fechaCambio);
} else if ($cod == 2){
    PlaneacionAse::cambiarEstadoAgenda(date("Y-m-d", strtotime($fecha)), $usuario, $idSol, $idAgenda, $horas, $min, $obser, 3, $sltFase, $fechaCambio);
}
else if (isset($_POST['idAgenda']) && isset($_POST['sltFaseEdit'])){
    $_SESSION['registrado'] = PlaneacionAse::registrarTiemposAge($idAgenda, $sltFase, $fecha, $usuario);
}  

?>