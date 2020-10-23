<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');

/* Inicialización variables*/
$search             = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$solIni             = null;
$solEsp             = null;
$nombreTipo         = null;
$idEstadoSol        = null;
$presupuesto        = null;
$horas              = null;
$equipo             = null;
$servicio           = null;
$proyecto           = null;
$fechaPrev          = null;
$observacion        = null;
$fechCreacion       = null;
$ultActualizacion   = null;
$idTipoSol          = null;
$idCM               = null;

/* Variables que cargan Select en formularios*/
if (isset($_POST['sltEquipo']) && !isset($_POST['btnRegistrarSolEsp']) && !isset($_POST['val'])) {
    $equipo = $_POST['sltEquipo'];
    SolicitudEspecifica::selectServicio($equipo, null);
} 

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    SolicitudEspecifica::cargaEspecificas($search, 2);
}

if (isset($_POST['btnRegistrarSolEsp'])) {
    $idSolIni = $_POST['txtIdSol'];
    $tipoSol = $_POST['txtIdTipoSol'];
    $estadoSol = $_POST['txtIdEstadoSol'];
    $idProy = $_POST['txtIdProy'];
    $presupuesto = $_POST['txtPresupuesto'];
    $horas = $_POST['txtHora']." horas y ".$_POST['txtMinuto']." minutos";
    $equipo = $_POST['sltEquipo'];
    $servicio = $_POST['sltServicio'];
    $fechaPrev = $_POST['txtFechaPrevista'];
    $descripcion = $_POST['txtDescripcion'];
    $txtRegistrarT = (isset($_POST['txtRegistrarT'])) ? $_POST['txtRegistrarT'] : 0;
    $registra = $_SESSION['usuario'];
    SolicitudEspecifica::registrarSolicitudEspecifica($idSolIni, $tipoSol, $estadoSol, $idProy, 0, $horas, $equipo, $servicio, $fechaPrev, $descripcion, $registra,1, $txtRegistrarT);
}
if (isset($_POST['btnRegistrarSolEspPres'])) {
    $idSolIni = $_POST['txtIdSol'];
    $tipoSol = $_POST['txtIdTipoSol'];
    $estadoSol = $_POST['txtIdEstadoSol'];
    $idProy = $_POST['txtIdProy'];
    $presupuesto = $_POST['txtPresupuesto'];
    $horas = $_POST['txtHora']." horas y ".$_POST['txtMinuto']." minutos";
    $equipo = $_POST['sltEquipo'];
    $servicio = $_POST['sltServicio'];
    $fechaPrev = $_POST['txtFechaPrevista'];
    $descripcion = $_POST['txtDescripcion'];
    $txtRegistrarT = (isset($_POST['txtRegistrarT'])) ? $_POST['txtRegistrarT'] : 0;
    $registra = $_SESSION['usuario'];
    SolicitudEspecifica::registrarSolicitudEspecifica($idSolIni, $tipoSol, $estadoSol, $idProy, 0, $horas, $equipo, $servicio, $fechaPrev, $descripcion, $registra,2, $txtRegistrarT);
}

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $info = SolicitudEspecifica::onLoadSolicitudEspecifica($id);
    $solIni = $info['idSolIni'];
    $solEsp = $info['idSol'];
    $nombreTipo = $info['nombreTSol'];
    $idEstadoSol = $info['idEstSol'];
    $presupuesto = $info['presupuesto'];
    $horas = $info['horas'];
    $equipo = $info['idEqu'];
    $servicio = $info['idSer'];
    $proyecto = $info['codProy']." - ".$info['nombreProy'];
    $fechaPrev = $info['fechPrev'];
    $observacion = $info['ObservacionAct'];
    $fechCreacion = $info['fechSol'];
    $ultActualizacion = $info['fechAct'];
    $idTipoSol = $info['idTSol'];
    $idCM = $info['idCM'];
    $RegistraTiempo = ($info['registraTiempo']==1? 'checked="checked"':'');
}

if (isset($_POST['txtSolIni']) && isset($_POST['txtSolEsp']) && isset($_POST['txtIdTipoSol']) && isset($_POST['txtTipoSol']) && isset($_POST['sltEstadoSolicitud']) && isset($_POST['txtPresupuesto']) && isset($_POST['txtHoras']) ) {
    $solIni = $_POST['txtSolIni'];
    $solEsp = $_POST['txtSolEsp'];
    $tipoSol = $_POST['txtIdTipoSol'];
    $idCM = $_POST['txtCodCM'];
    $estSol = $_POST['sltEstadoSolicitud'];
    $presupuesto = $_POST['txtPresupuesto'];
    $horas = $_POST['txtHoras'];
    $servicio = $_POST['sltServicio'];
    $fechaPrev = $_POST['txtFechaPrev'];
    $descripcion = $_POST['txtObservacion'];
    $txtRegistrarT = (isset($_POST['txtRegistrarT'])) ? $_POST['txtRegistrarT'] : 0;
    $persona = $_SESSION['usuario'];
    return SolicitudEspecifica::actualizarSolicitudEspecifica($solIni, $solEsp, $tipoSol, $idCM, $estSol, $presupuesto, $horas, $servicio, $fechaPrev, $descripcion, $persona, $txtRegistrarT);
}



?>