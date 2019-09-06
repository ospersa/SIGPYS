<?php
include_once('../Models/mdl_asignados.php');

/*
    cod1: ID de proyecto.
    cod2: ID de solicitud inicial.
    cod3: ID de solicitud específica.
    sup: ID de asignado para hacer eliminación.
    sol: ID de solicitud para hacer eliminación.
    pry: ID de proyecto para hacer eliminación.
*/

$estado = (isset($_POST['est'])) ? $_POST['est'] : null ;
$maxHora = (isset($_POST['txtHoras'])) ? $_POST['txtHoras'] : null;
$maxMin = (isset($_POST['txtMinutos'])) ? $_POST['txtMinutos'] : null;
$idAsig = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$idSol = (isset($_POST['txtCodSol'])) ? $_POST['txtCodSol'] : null;
$nombreCompleto = "";
$nombreFase = "";
$horas = "";
$minutos = "";

if (isset($_GET['cod1'])) {
    $idProy = $_GET['cod1'];
    unset($_GET['cod1']);
    $resultado = Asignados::asignarPersonaProy($idProy);
} else if (isset($_GET['cod2'])) {
    $idSol = $_GET['cod2'];
    unset($_GET['cod2']);
    $resultado = Asignados::asignarPersonaSolIni($idSol);
} else if (isset($_GET['cod3'])) {
    $idSol = $_GET['cod3'];
    unset($_GET['cod3']);
    $resultado = Asignados::asignarPersonaSolEsp($idSol);
} else if (isset($_GET['sup'])) {
    $idAsig = $_GET['sup'];
    unset($_GET['sup']);
    $resultado = Asignados::eliminarAsignacion($idAsig);
}

if (isset($_POST['btnAsignar'])) {
    session_start();
    $registra = $_SESSION['usuario'];
    $rol = $_POST['sltRol'];
    $persona = $_POST['sltPersona'];
    $fase = $_POST['sltFase'];
    $idProy = $_POST['txtIdProy'];
    $idCurso = "";
    $resultado = Asignados::registrarAsignacion($rol, $persona, $fase, $idProy, $idCurso, $registra, $maxHora, $maxMin, $idSol);
} else if (isset($_POST['btnActualizar'])) {
    $resultado = Asignados::actualizarAsignacion($idAsig, $maxHora, $maxMin);
} else if (isset($_POST['btnEliminar'])) {
    $resultado = Asignados::eliminarAsignacion($idAsig);
} else if (isset($_POST['btnInactivar'])) {
    $resultado = Asignados::cambiarEstadoAsignacion($idAsig, $estado);
}

if (isset($_GET['id'])) {
    $idAsig = $_GET['id'];
    $info = Asignados::onLoadAsignacion($idAsig);
    $nombreCompleto = strtoupper($info['apellido1']." ".$info['apellido2']." ".$info['nombres']);
    $nombreFase = $info['nombreFase'];
    $horas = $info['maxhora'];
    $minutos = $info['maxmin'];
    $estado = $info['est'];
}

?>