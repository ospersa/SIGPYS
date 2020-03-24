<?php
if(!isset($_SESSION['usuario'])){ 
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');
include_once('../Models/mdl_tiempos.php');
/* Inicialización variables*/
$user           = $_SESSION['usuario'];
$idSolEsp       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idSol2         = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;
$idTiempo       = (isset($_POST['idTiempo'])) ? $_POST['idTiempo'] : null;
$idDeletTiempo  = (isset($_POST['idDeletTiempo'])) ? $_POST['idDeletTiempo'] : null;
$fecha          = (isset($_POST['date'])) ? $_POST['date'] : null;
$horas1         = (isset($_POST['horas'])) ? $_POST['horas'] : null;
$minutos1       = (isset($_POST['minutos'])) ? $_POST['minutos'] : null; 
$idFase         = (isset($_POST['sltFase'])) ? $_POST['sltFase'] : null;
$nota           = (isset($_POST['notaTEdit'])) ? $_POST['notaTEdit'] : null;
$date           = (isset($_POST['dateEdit'])) ? $_POST['dateEdit'] : null;
$horas          = (isset($_POST['horasEdit'])) ? $_POST['horasEdit'] : null;
$minutos        = (isset($_POST['minutosEdit'])) ? $_POST['minutosEdit'] : null; 
$idFaseEdit     = (isset($_POST['sltFaseEdit'])) ? $_POST['sltFaseEdit'] : null;
$nota1          = (isset($_POST['notaT'])) ? $_POST['notaT'] : null;


/* Procesamiento peticiones al controlador */

if (!empty($idTiempo)){
    $editarRegistro = Tiempos::llenarFormEditar($idTiempo);
}

if (isset($_POST['btnActRegTiempo'])) {
    Tiempos::editarTiemposRe($idTiempo, $user, $date, $horas, $minutos, $idFaseEdit, $nota);
} else if (isset($_POST['btnRegTiempo'])){
    $result = Tiempos::registrarTiempos($idSol2, $user, $fecha, $nota1, $horas1, $minutos1, $idFase, 0);
}
if ($idDeletTiempo != null){
    Tiempos::SuprimirTiempoRe($idDeletTiempo);
}
if($idSolEsp){
    $idSolEsp = substr($idSolEsp, 3);
    $data = SolicitudEspecifica::onLoadSolicitudEspecifica($idSolEsp);
    $idSolIni   = $data['idSolIni'];
    $idSolEsp   = $data['idSol'];
    $codProy    = $data['codProy'];
    $nomProy    = $data['nombreProy'];
    $solicitud  = $data['ObservacionAct'];
    $tiempoInvertido = Tiempos::OnloadTiempoInvertido($idSolEsp);
    $tiempoRegistrado = Tiempos::OnloadTiempoRegistrado($idSolEsp, $user);
}
