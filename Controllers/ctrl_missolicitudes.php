<?php
    include_once('../Models/mdl_solicitudEspecifica.php');
    include_once('../Models/mdl_asignados.php');

    if (!empty($_REQUEST['cod'])) {
        $idAsig = $_REQUEST['cod'];
    }
    
    $search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
    $solIni = null;
    $solEsp = null;
    $nombreTipo = null;
    $idEstadoSol = null;
    $presupuesto = null;
    $horas = null;
    $equipo = null;
    $servicio = null;
    $proyecto = null;
    $fechaPrev = null;
    $observacion = null;
    $fechCreacion = null;
    $ultActualizacion = null;
    $idTipoSol = null;
    $idCM = null;

    if(empty($search)) {
        if(!isset($_SESSION)) { 
            session_start();
        }
        $usuario = $_SESSION['usuario'];
        SolicitudEspecifica::cargaEspecificasUsuario( $search, 2, $usuario);
    }

    if (!empty($search)) {
        if(!isset($_SESSION)) { 
            session_start();
        }
        $usuario = $_SESSION['usuario'];
        SolicitudEspecifica::cargaEspecificasUsuario($search, 2, $usuario);
    }

    if (isset($_POST['btnInactivar'])) {
        $resultado = Asignados::cambiarEstadoAsignacion($idAsig, 1);
    }
