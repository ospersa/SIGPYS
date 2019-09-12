<?php
/*Inclusión del Modelo */
include_once('../Models/mdl_asignados.php');

/*
    cod1: ID de proyecto.
    cod2: ID de solicitud inicial.
    cod3: ID de solicitud específica.
    sup: ID de asignado para hacer eliminación.
    sol: ID de solicitud para hacer eliminación.
    pry: ID de proyecto para hacer eliminación.
*/

/* Inicialización variables  */
$estado            = (isset($_POST['est'])) ? $_POST['est'] : null ;
$maxHora           = (isset($_POST['txtHoras'])) ? $_POST['txtHoras'] : null;
$maxMin            = (isset($_POST['txtMinutos'])) ? $_POST['txtMinutos'] : null;
$idAsig            = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$idSol             = (isset($_POST['txtCodSol'])) ? $_POST['txtCodSol'] : null;
$nombreCompleto    = "";
$nombreFase        = "";
$horas             = "";
$minutos           = "";

/* Procesamiento peticiones al controlador  */
if (isset($_GET['cod1'])) {
    $idProy = (isset($_GET['cod1'])) ? $_GET['cod1'] : null;
    unset($_GET['cod1']);
    $resultado = Asignados::asignarPersonaProy($idProy);
} else if (isset($_GET['cod2'])) {
    $idSol = (isset($_GET['cod2'] )) ? $_GET['cod2'] : null;
    unset($_GET['cod2']);
    $resultado = Asignados::asignarPersonaSolIni($idSol);
} else if (isset($_GET['cod3'])) {
    $idSol = (isset($_GET['cod3'])) ? $_GET['cod3'] : null;
    unset($_GET['cod3']);
    $resultado = Asignados::asignarPersonaSolEsp($idSol);
} else if (isset($_GET['sup'])) {
    $idAsig = (isset($_GET['sup'])) ? $_GET['sup'] : null;
    unset($_GET['sup']);
    $resultado = Asignados::eliminarAsignacion($idAsig);
}

if (isset($_POST['btnAsignar'])) {
    session_start();
    $registra   = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
    $rol        = (isset($_POST['sltRol'])) ? $_POST['sltRol'] : null;
    $persona    = (isset($_POST['sltPersona'])) ? $_POST['sltPersona'] : null;
    $fase       = (isset($_POST['sltFase'])) ? $_POST['sltFase'] : null;
    $idProy     = (isset($_POST['txtIdProy'])) ? $_POST['txtIdProy'] : null;
    $idCurso    = "";
    $resultado = Asignados::registrarAsignacion($rol, $persona, $fase, $idProy, $idCurso, $registra, $maxHora, $maxMin, $idSol);
} else if (isset($_POST['btnActualizar'])) {
    $resultado = Asignados::actualizarAsignacion($idAsig, $maxHora, $maxMin);
} else if (isset($_POST['btnEliminar'])) {
    $resultado = Asignados::eliminarAsignacion($idAsig);
} else if (isset($_POST['btnInactivar'])) {
    $resultado = Asignados::cambiarEstadoAsignacion($idAsig, $estado);
}

if (isset($_GET['id'])) {
    $idAsig = (isset($_GET['id'])) ? $_GET['id'] : null;
    $info = Asignados::onLoadAsignacion($idAsig);
    $nombreCompleto = strtoupper($info['apellido1']." ".$info['apellido2']." ".$info['nombres']);
    $nombreFase = $info['nombreFase'];
    $horas = $info['maxhora'];
    $minutos = $info['maxmin'];
    $estado = $info['est'];
}

?>