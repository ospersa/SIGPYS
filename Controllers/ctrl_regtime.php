<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');
include_once('../Models/mdl_tiempos.php');
if(!isset($_SESSION)){ 
    session_start();
}
$User = $_SESSION['username'];
$idSol = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

/* Procesamiento peticiones al controlador */
if (!empty($_REQUEST['id'])) {
    $idSolEsp = $_REQUEST['id'];
    $tiempoInvertido = Tiempos::OnloadTiempoInvertido($idSol);
    $tiempoRegistrado = Tiempos::OnloadTiempoRegistrado($idSol, $User);
}

if( !empty( $idSolEsp ) ){
    $data = SolicitudEspecifica::onLoadSolicitudEspecifica($idSolEsp);
    $idSolIni   = $data['idSolIni'];
    $idSolEsp   = $data['idSol'];
    $codProy    = $data['codProy'];
    $nomProy    = $data['nombreProy'];
    $solicitud  = $data['ObservacionAct'];
}
