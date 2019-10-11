<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_cotizacion.php";
include_once "../Models/mdl_asignados.php";

/* Inicialización variables*/
$disabledMail  = "";
$readNota      = "";
$readEnlace    = "";
$disabled      = "";
$cotizacion    = "";
$read          = "";
$valorCot      = 0;
$obsSol        = "";
$obsPys        = "";
$obsProd       = "";
$hidden        = "";
$cotizaciones  = null;
$idAsig        = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null;
$busqueda      = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if ($idAsig) {
    $id = substr($idAsig, 0, 2);
    if ($id == "C_") {
        $detail = Cotizacion::onLoad2(substr($idAsig, 2));
        $valCot = $detail['valorPresupuesto'];
        $obsSol = $detail['obsSolicitante'];
        $obsPys = $detail['obsPyS'];
        $obsPro = $detail['obsProducto'];
        $idSolEsp = $detail['idSolEsp'];
        $idCot = $detail['idCotizacion'];
    } else {
        $detail = Cotizacion::onLoad($idAsig);
        $nombreCompletoAsig = $detail['apellido1']." ".$detail['apellido2']." ".$detail['nombres'];
        $nombreFase = $detail['nombreFase'];
        $horas = $detail['maxhora'];
        $minutos = $detail['maxmin'];
        $idSol = $detail['idSol'];
        $estado = $detail['est'];
        $solicitante = $detail['idSolicitante'];
    }
}
 
/** Procesamiento peticiones al controlador */
if (isset($_POST['enviar'])){ 
    $valCot         = (isset($_POST['txtValCotizacion'])) ? $_POST['txtValCotizacion'] : null;
    $obsSol         = (isset($_POST['txtObsSolicitante'])) ? $_POST['txtObsSolicitante'] : null;
    $obsPys         = (isset($_POST['txtObsPyS'])) ? $_POST['txtObsPyS'] : null;
    $obsProd        = (isset($_POST['txtDescProd'])) ? $_POST['txtDescProd'] : null;
    $solEsp         = (isset($_POST['txtSolEsp'])) ? $_POST['txtSolEsp'] : null;
    $solicitante    = (isset($_POST['txtVal'])) ? $_POST['txtVal'] : null;
    $result = Cotizacion::guardarCotizacion($solEsp, $valCot, $obsSol, $obsPys, $obsProd, $solicitante);
} else if (isset($_POST['btnAsignar'])) {
    session_start();
    $solicitante    = $_POST['txtVal'];
    $usuario = $_SESSION['usuario'];
    $registra = Cotizacion::obtenerPersonaRegistra($usuario);
    $rol            = (isset($_POST['sltRol'])) ? $_POST[''] : null;
    $persona        = (isset($_POST['sltPersona'])) ? $_POST[''] : null;
    $fase           = (isset($_POST['sltFase'])) ? $_POST[''] : null;
    $proy           = (isset($_POST['txtIdProy'])) ? $_POST[''] : null;
    $solEsp = substr($_POST['txtSolEsp'],1);
    $horas          = (isset($_POST['txtHoras'])) ? $_POST[''] : null;
    $minutos        = (isset($_POST['txtMinutos'])) ? $_POST[''] : null;
    if ($rol != null && $persona != null && $fase != null && $horas != null && $minutos != null) {
        $result = Asignados::registrarAsignacion($solEsp, $proy, $persona, $rol, $fase, $registra, $horas, $minutos, $solicitante);
    } else {
        echo "<script>alert ('Existe algún campo VACÍO. Registro no válido');</script>";
        echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
    }
} else if (isset($_POST['btnAprobar'])) {
    $nota       = (isset($_POST['txtObsAprobacion'])) ? $_POST['txtObsAprobacion'] : null;
    $enlace     = (isset($_POST['txtEnlAprobacion'])) ? $_POST['txtEnlAprobacion'] : null;
    $cotizacion = $_REQUEST['txtCot'];
    $resultado = Cotizacion::aprobarCotizacion($cotizacion, $nota, $enlace);
} else if (isset($_POST['btnEnviarCorreoCot']) || isset($_POST['btnEnviarCorreoReCot'])) {
    if (isset($_POST['btnEnviarCorreoCot'])) {
        $accion = 1;
    } else {
        $accion = 2;
    }
    $solEsp         = (isset($_POST['txtSolEsp'])) ? $_POST['txtSolEsp'] : null;
    $solIni         = (isset($_POST['txtSolIni'])) ? $_POST['txtSolIni'] : null;
    $codProy        = (isset($_POST['txtCodProy'])) ? $_POST['txtCodProy'] : null;
    $nomProy        = (isset($_POST['txtNomProy'])) ? $_POST['txtNomProy'] : null;
    $obsProd        = (isset($_POST['txtObsProd'])) ? $_POST['txtObsProd'] : null;
    $obsProdCot     = (isset($_POST['txtObsProdCot'])) ? $_POST['txtObsProdCot'] : null;
    $valCot         = (isset($_POST['txtValCot'])) ? $_POST['txtValCot'] : null;
    $obsSol         = (isset($_POST['txtObsSol'])) ? $_POST['txtObsSol'] : null;
    $idProy         = (isset($_POST['txtIdProy'])) ? $_POST['txtIdProy'] : null;
    $idCot          = (isset($_POST['txtIdCot'])) ? $_POST['txtIdCot'] : null;
    $totRecurso     = (isset($_POST['txtTotalRecurso'])) ? $_POST['txtTotalRecurso'] : null;
    $result = Cotizacion::enviarCorreoCotizacion($solEsp, $solIni, $codProy, $nomProy, $obsProd, $obsProdCot, $valCot, $obsSol, $idProy, $idCot, $totRecurso, $accion);
} else if (isset($_POST['btnEstado'])) {
    $accion     = (isset($_POST['btnEstado'])) ? $_POST['btnEstado'] : null;
    $solEsp     = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;
    $solicita   = (isset($_POST['txtSolicita'])) ? $_POST['txtSolicita'] : null;
    $idAsig     = (isset($_POST['cod'])) ? $_POST['cod'] : null;
    Cotizacion::cambiarEstadoAsignacion($idAsig, $solEsp, $accion, $solicita);
}

if (isset($_REQUEST['cod'])) {
    $cod = $_REQUEST['cod'];
    if ($cod == 1){
        $result = Cotizacion::listarSolicitudes();
    } else if ($cod != 1 && isset($_REQUEST['val']) && isset($_REQUEST['cod2']) == false) {
        $solicitante = $_REQUEST['val'];
        $result = Cotizacion::infoSolicitud($cod);
        $nombreCompleto = Cotizacion::consultaSolicitante($solicitante);
        $codProyecto = $result['codProy'];
        $nomProyecto = $result['nombreProy'];
        $solIni = $result['idSolIni'];
        $result2 = Cotizacion::infoSolicitudInicial($solIni);
        $hidden2 = "hidden";
        $descEsp = $result['ObservacionAct'];
        $descIni = $result2[0];
        $idProyecto = $result['idProy'];
    } else if ($cod != 1 && isset($_REQUEST['val']) && isset($_REQUEST['cod2'])) {
        $solicitante = $_REQUEST['val'];
        $cotizacion = $_REQUEST['cod2'];
        $result = Cotizacion::infoSolicitud($cod);
        $nombreCompleto = Cotizacion::consultaSolicitante($solicitante);
        $result3 = Cotizacion::verificarAprobacion($cotizacion);
        $result4 = Cotizacion::verificarCorreoEnviado($cotizacion);
        $hidden = "hidden";
        $hidden2 = "";
        $read = "readonly";
        $notaAprobacion = $result3['notaAprobacion'];
        $enlaceAprobacion = $result3['enlaceAprobacion'];
        if ($result4){
            $disabledMail = "disabled";
        }
        $codProyecto = $result['codProy'];
        $nomProyecto = $result['nombreProy'];
        $solIni = $result['idSolIni'];
        $result2 = Cotizacion::infoSolicitudInicial($solIni);
        $descIni = $result2['ObservacionAct'];
        $descEsp = $result['ObservacionAct'];
        $idProyecto = $result['idProy'];
        $datosCotizacion = Cotizacion::datosCotizacion($cotizacion);
        $valorCot = $datosCotizacion['valorPresupuesto'];
        $obsSol = $datosCotizacion['obsSolicitante'];
        $obsPys = $datosCotizacion['obsPyS'];
        $obsProd = $datosCotizacion['obsProducto'];
    }
}

/*if (isset($_POST['val'])) {
    $val = $_POST['val'];
    $idAsig = $_POST['cod'];
    $maxhora = $_POST['txtHoras'];
    $maxmin = $_POST['txtMinutos'];
    $solEsp = $_POST['idSol'];
    if ($val == "1") {
        $resultado = Cotizacion::actualizarAsignacion($idAsig, $maxhora, $maxmin, $solEsp);
    } else if ($val == "2") {
        $resultado = Cotizacion::suprimirAsignacion($idAsig, $solEsp);
    } else if ($val == "3") {
        $idCot = $_POST['idCot'];
        $valCot = $_POST['txtValCot'];
        $obsSol = $_POST['txtObsSolicitante'];
        $obsPys = $_POST['txtObsPys'];
        $obsPro = $_POST['txtObsPro'];
        $resultado = Cotizacion::actualizarCotizacion($idCot, $valCot, $obsSol, $obsPys, $obsPro, $solicitante);
    } 
}*/
if (isset($_POST['txt-search'])) {
    if ($busqueda == null) {
        echo $cotizaciones = Cotizacion::listarCotizaciones();
    } else {
        $cotizaciones = Cotizacion::busquedaPendientes($busqueda);
    }
}

?>