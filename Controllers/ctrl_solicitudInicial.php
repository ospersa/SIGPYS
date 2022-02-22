<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudInicial.php');

/* Inicialización variables*/
$idSolicitud        = null;
$tipoSolicitud      = null;
$idEstado           = null;
$registro           = null;
$proyecto           = null;
$observacion        = null;
$fechActualizacion  = null;
$idSolicitante      = null;
$idCM               = null;
$fechPrev           = null;
$estProy            = null;

/* Variables que cargan Select en formularios*/
if (isset($_POST['b'])) {
    $busqueda = $_POST['b'];
    SolicitudInicial::selectProyecto($busqueda);
}

/* Carga de información en el Modal */
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $prep = substr($id, 0, 3);
    if ($prep == "SIS") {
        $id = substr($id, 2);
        $info = SolicitudInicial::onLoadSolicitudInicial($id);
        $idSolicitud = $info['idSol'];
        $tipoSolicitud = $info['nombreTSol'];
        $idEstado = $info['idEstSol'];
        $registro = $info['apellido1']." ".$info['apellido2']." ".$info['nombres'];
        $proyecto = $info['nombreProy'];
        $observacion = $info['ObservacionAct'];
        $fechActualizacion = $info['fechAct'];
        $idSolicitante = $info['idSolicitante'];
        $idCM = $info['idCM'];
        $fechPrev = $info['fechPrev'];
        $estProy = $info['idEstProy'];
    }
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnRegistrarSolIni']) && !isset($_POST['val']) && !isset($_POST['cod'])) {
    $solicitud = $_POST['txtIdSol'];
    $idTipo = $_POST['txtIdTipSol'];
    $idEstado = $_POST['txtIdEstSol'];
    $proyecto = $_POST['sltProy'];
    $fecha = $_POST['txtFechaPrevista'];
    $solicita = $_POST['sltSolicitante'];
    $descripcion = $_POST['txtDescripcionSol'];
    $registra = $_SESSION['usuario'];
    SolicitudInicial::registrarSolicitudInicial($solicitud, $idTipo, $idEstado, $proyecto, $fecha, $descripcion, $solicita, $registra);
}
