<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');

/* Procesamiento peticiones al controlador */
if (!empty($_REQUEST['id'])) {
    $idSolEsp = $_REQUEST['id'];
}

if( !empty( $idSolEsp ) ){
    $data = SolicitudEspecifica::onLoadSolicitudEspecifica($idSolEsp);
    $idSolIni   = $data['idSolIni'];
    $idSolEsp   = $data['idSol'];
    $codProy    = $data['codProy'];
    $nomProy    = $data['nombreProy'];
    $solicitud  = $data['ObservacionAct'];
}
