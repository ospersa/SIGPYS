<?php
/** Carga del Modelo */
include_once('../Models/mdl_proyecto.php');

/** Inicialización de variables */
$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$val = (isset($_POST['val'])) ? $_POST['val'] : null;
$idProy = (isset($_POST['cod'])) ? $_POST['cod'] : null;
/** Variables de formulario: etapa de proyectos */
$nombreEta = (isset($_POST['txtNomEta'])) ? $_POST['txtNomEta'] : null;
$descripcionEta = (isset($_POST['txtDescEta'])) ? $_POST['txtDescEta'] : null;
$tipoProy = (isset($_POST['sltTipoProy'])) ? $_POST['sltTipoProy'] : null;
/** Variables de formulario: tipo de proyectos */
$nombreTip = (isset($_POST['txtNomTipoProy'])) ? $_POST['txtNomTipoProy'] : null;
$frente = (isset($_POST['sltFrente'])) ? $_POST['sltFrente'] : null;
/** Variables de formulario: estados de proyectos */
$nombreEst = (isset($_POST['txtNomEst'])) ? $_POST['txtNomEst'] : null;
$descripcionEst = (isset($_POST['txtDescEst'])) ? $_POST['txtDescEst'] : null;
/** Variables de formulario: proyectos */
$idFrente = null;
$idTipoProy = null;
$idEtapaPry = null;
$nombreCompleto = null;
$actualizacion = null;
$codConectate = null;
$entidad = (isset($_POST['sltEntidad'])) ? $_POST['sltEntidad'] : null;
$departamento = (isset($_POST['sltDepto'])) ? $_POST['sltDepto'] : null;
$facultad = (isset($_POST['sltFacul'])) ? $_POST['sltFacul'] : null;
$tipoIntExt = (isset($_POST['sltTipoIntExt'])) ? $_POST['sltTipoIntExt'] : null;
$estadoPry = (isset($_POST['sltEstadoProy'])) ? $_POST['sltEstadoProy'] : null;
$etapaPry = (isset($_POST['sltEtapaProy'])) ? $_POST['sltEtapaProy'] : null;
$siglaFrente = (isset($_POST['txtSiglaFr'])) ? $_POST['txtSiglaFr'] : null;
$anio = (isset($_POST['txtAnio'])) ? $_POST['txtAnio'] : null;
$siglaProy = (isset($_POST['txtSiglaProy'])) ? $_POST['txtSiglaProy'] : null;
$nombreProy = (isset($_POST['txtNomProy'])) ? $_POST['txtNomProy'] : null;
$nombreCorto = (isset($_POST['txtNomCorProy'])) ? $_POST['txtNomCorProy'] : null;
$contexto = (isset($_POST['txtContProy'])) ? $_POST['txtContProy'] : null;
$presupuesto = (isset($_POST['txtPresupuesto'])) ? $_POST['txtPresupuesto'] : null;
$convocatoria = (isset($_POST['sltConvocatoria'])) ? $_POST['sltConvocatoria'] : null;
$financia = (isset($_POST['sltFinancia'])) ? $_POST['sltFinancia'] : null;
$fechIni = (isset($_POST['txtFechIni'])) ? $_POST['txtFechIni'] : null;
$fechFin = (isset($_POST['txtFechFin'])) ? $_POST['txtFechFin'] : null;
$semanas = (isset($_POST['txtSemAcom'])) ? $_POST['txtSemAcom'] : null;
$fechaColciencias = (isset($_POST['txtFechColciencias'])) ? $_POST['txtFechColciencias'] : null;
$fteFinancia = (isset($_POST['sltFuenteFinanciamiento'])) ? $_POST['sltFuenteFinanciamiento'] : null;

/** Carga de información en Modales */
if ($id) {
    $prep = substr($id, 0, 3);
    if ($prep == "TIP") {
        $info = Proyecto::onLoadTipo($id);
        $nombreTip = $info['nombreTProy'];
    } else if ($prep == "PRY") {
        echo "<script>alert('Llegó id de proyecto');</script>";
        $info = Proyecto::onLoadProyecto($id);
        $entidad = $info['idEnt'];
        $idProy = $info['idProy'];
        $facultad = $info['idFac'];
        $idFrente = $info['idFrente'];
        $frente = $info['nombreFrente'];
        $tipoProy = $info['nombreTProy'];
        $idTipoProy = $info['idTProy'];
        $tipoIntExt = $info['proyecto'];
        $estadoPry = $info['idEstProy'];
        $idEtapaPry = $info['idEtaProy'];
        $codConectate = $info['codProy'];
        $nombreProy = $info['nombreProy'];
        $nombreCorto = $info['nombreCortoProy'];
        $financia = $info['financia'];
        $convocatoria = $info['idConvocatoria'];
        $presupuesto = $info['presupuestoProy'];
        $fechIni = $info['fechaIniProy'];
        $fechFin = $info['fechaCierreProy'];
        $nombreCompleto = $info['apellido1']." ".$info['apellido2']." ".$info['nombres'];
        $fechaColciencias = $info['fechaColciencias'];
        $actualizacion = $info['fechaActualizacionproy'];
        $semanas = $info['semAcompanamiento'];
        $celula = $info['idCelula'];
        $contexto = $info['descripcionProy'];
    } else if ($prep == "ETP"){
        $info = Proyecto::onLoadEtapa($id);
        $nombreEta = $info['nombreEtaProy'];
        $descripcionEta = $info['descripcionEtaProy'];
    }
}

/** Procesamiento de peticiones para registro desde los formularios de proyectos */
if (isset($_POST['btnRegistrarProy'])) {
    session_start();
    $usserName = $_SESSION['usuario'];
    $tipoProy = $_POST['sltTipoProy3'];
    $celula = $_POST['sltCelula'];
    $centroCosto = $_POST['sltCeco'];
    $pep = $_POST['sltElementoPep'];
    Proyecto::registrarProyecto($siglaFrente, $anio, $siglaProy, $tipoProy, $tipoIntExt, $frente, $estadoPry, $etapaPry, $nombreProy, $financia, $convocatoria, $departamento, $facultad, $entidad, $nombreCorto, $contexto, $fechIni, $fechFin, $usserName, $presupuesto, $fechaColciencias, $semanas, $fteFinancia, $celula, $centroCosto, $pep);
} else if (isset($_POST['btnRegistrarEst'])) {
    Proyecto::registrarEstado ($nombreEst, $descripcionEst);
} else if (isset($_POST['btnRegistrarEta'])) {
    Proyecto::registrarEtapa ($tipoProy, $nombreEta, $descripcionEta);
} else if (isset($_POST['btnRegistrarTip'])) {
    Proyecto::registrarTipo ($frente, $nombreTip);
}

/** Procesamiento de peticiones para actualización de datos desde los formularios de proyectos */
if ($val) {
    session_start();
    $persona = $_SESSION['usuario'];
    if ($val == '1') {
        Proyecto::actualizarEtapa($cod, $tipoProy, $nombreEta, $descripcionEta);
    } else if ($val == '2') {
        Proyecto::actualizarTipo($cod, $frente, $nombreTip);
    } else if ($val == '3') {
        $notaAnulacion = $_POST['txtAnulacion'];
        Proyecto::suprimirProyecto($idProy, $notaAnulacion, $persona);
    } else if ($val == '4') {
        $tipoProy = $_POST['txtTipProy2'];
        $frente = $_POST['txtFrente2'];
        $codProy = $_POST['txtCodProy'];
        $celula = $_POST['sltCelula2'];
        $centroCosto = $_POST['sltCeco2'];
        $pep = $_POST['sltElementoPep'];
        Proyecto::actualizarProyecto($idProy, $entidad, $facultad, $departamento, $tipoProy, $tipoIntExt, $frente, $estadoPry, $etapaPry, $codProy, $nombreProy, $nombreCorto, $contexto, $convocatoria, $presupuesto, $financia, $fechIni, $fechFin, $persona, $fechaColciencias, $semanas, $celula, $fteFinancia, $centroCosto, $pep);
    }
}

/** Peticiones AJAX recibidas en el controlador, por lo general para crear selects dinámicos */
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($frente && !$id) {
        echo "<script>$('#divInfo2').empty();</script>";
        Proyecto::cargaNombreProyecto($frente);
    }
    if (isset($_POST['sltTipoProy3']) && !$id) {
        $proyecto = $_POST['sltTipoProy3'];
        Proyecto::cargaInfoProyecto($proyecto);
    }
    if ($entidad && !$id) {
        echo "<script>$('#sltDeptoProy').empty();</script>";
        Proyecto::selectFacultad($entidad, null);
    }
    if ($facultad && !$id) {
        Proyecto::selectDepartamento($facultad, null);
    }
    if (isset($_POST['sltCeco']) && (!isset($_POST['btnRegistrarProy']))) {
        $ceco = $_POST['sltCeco'];
        Proyecto::selectElementoPep($ceco);
    }
    if (isset($_POST['sltCeco2']) && (!isset($_POST['val']))) {
        $ceco = $_POST['sltCeco2'];
        Proyecto::selectElementoPep($ceco);
    }
}

?>