<?php
/* Carga del Modelo */
include_once("../Models/mdl_solicitud.php");

/* Inicialización de variables */
$nombreEst          = (isset($_POST['txtNomEst'])) ? $_POST['txtNomEst'] : null;
$descripcionEst     = (isset($_POST['txtDescEst'])) ? $_POST['txtDescEst'] : null;
$nombreTip          = (isset($_POST['txtNomTip'])) ? $_POST['txtNomTip'] : null;
$descripcionTip     = (isset($_POST['txtDescTip'])) ? $_POST['txtDescTip'] : null;
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$val                = (isset($_POST['val'])) ? $_POST['val'] : null;
$cod                = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Peticiones de registro de información en Estados de solicitud o Tipos de solicitud */
if (isset($_POST['btnRegistrarEst'])) {
    Solicitud::registrarEstadoSolicitud($nombreEst, $descripcionEst);
} else if (isset($_POST['btnRegistrarTip'])) {
    Solicitud::registrarTipoSolicitud($nombreTip, $descripcionTip);
}

/* Carga de información en el Modal, de acuerdo con la variable recibida */
if ($id) {
    $prep = substr($id, 0, 3);
    if ($prep == 'ESS') {
        $info = Solicitud::onLoadEstadoSolicitud($id);
        $nombreEst = $info['nombreEstSol'];
        $descripcionEst = $info['descripcionEstSol'];
    } else if ($prep == "TSO") {
        $info = Solicitud::onLoadTipoSolicitud($id);
        $nombreTip = $info['nombreTSol'];
        $descripcionTip = $info['descripcionTSol'];
    }
}

/* Peticiones de actualización de Estado o Tipo de solicitudes */
if ($val == '1') { // 1 -> Actualización de estado de solicitud
    Solicitud::actualizarEstadoSolicitud($cod, $nombreEst, $descripcionEst);
} else if ($val == '2') { // 2 -> Actualización de tipo de solicitud
    Solicitud::actualizarTipoSolicitud($cod, $nombreTip, $descripcionTip);
}

/* Petición de actualización de solicitud inicial */
if (isset($_POST['btnActualizarSolIni'])) {
    session_start();
    $idSolicitud = $_POST['txtIdSol'];
    $estSolicitud = $_POST['sltEstadoSolicitud'];
    $observacion = $_POST['txtObservacion2'];
    $solicitante = $_POST['sltSolicitante'];
    $registra = $_SESSION['usuario'];
    $idCM = $_POST['txtIdCM'];
    $fechPrev = $_POST['txtFechPrev2'];
    $estProy = $_POST['txtEstProy'];
    $accion = Solicitud::validarDatosSolIni($idSolicitud, $estSolicitud, $observacion, $solicitante, $fechPrev);
    if ($accion == "Actualizar") {
        Solicitud::actualizarSolicitudInicial($idSolicitud, $estSolicitud, $observacion, $solicitante, $registra, $idCM, $fechPrev, $estProy);
    }
}

?>