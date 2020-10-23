<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION['usuario'])) { 
    session_start();
}

/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');
include_once('../Models/mdl_asignados.php');
include_once('../Models/mdl_plataforma.php');
include_once('../Models/mdl_productos.php');

/* Inicialización variables*/
$search             = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$idSol              = (isset($_POST['idSol'])) ? $_POST['idSol']  : null;
$idPlat             = (isset($_POST['sltPlataforma'])) ?  $_POST['sltPlataforma'] : null;
$idSer              = (isset($_POST['idSer'])) ? $_POST['idSer'] : null;
$idClProd           = (isset($_POST['sltClaseM'])) ? $_POST['sltClaseM'] : null;
$idTipoPro          = (isset($_POST['sltTipo'])) ? $_POST['sltTipo'] : null;
$observacion        = (isset($_POST['descripSer'])) ? $_POST['descripSer'] : null;
$estudiantesImpac   = (isset($_POST['numEst'])) ? $_POST['numEst'] : null;
$docentesImpac      = (isset($_POST['numDoc'])) ? $_POST['numDoc'] : null;
$otrosImpac         = (isset($_POST['otrosImpac'])) ? $_POST['otrosImpac'] : null;
$motivoAnulacion    = (isset($_POST['motivoAnulacion'])) ? $_POST['motivoAnulacion'] : null;
$idResponRegistro   = (isset($_POST['idResponRegistro'])) ? $_POST['idResponRegistro'] : null;
$tiempoTotal        = SolicitudEspecifica::totalTiempo($idSol);
$hora               = $tiempoTotal[0];
$min                = $tiempoTotal[1];
$nomProduc          = (isset($_POST['nomProd'])) ? $_POST['nomProd'] : null;
$fechaEntre         = (isset($_POST['txtfechEntr'])) ? $_POST['txtfechEntr'] : null;
$red                = (isset($_POST['SMRed'])) ? $_POST['SMRed'] : null;
$url                = (isset($_POST['url'])) ? $_POST['url'] : null;
$labor              = (isset($_POST['labor'])) ? $_POST['labor'] : null;
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$sltClase           = (isset($_POST['sltClaseM'])) ? $_POST['sltClaseM'] : null;
$sltClase2          = (isset($_POST['dato1'])) ? $_POST['dato1'] : null;
$idSer2             = (isset($_POST['dato2'])) ? $_POST['dato2'] : null;
$sinopsis           = (isset($_POST['sinopsis'])) ? $_POST['sinopsis'] : null;
$autores            = (isset($_POST['autores'])) ? $_POST['autores'] : null;
$urlVimeo           = (isset($_POST['urlV'])) ? $_POST['urlV'] : null;
$minDura            = (isset($_POST['minutosDura'])) ? $_POST['minutosDura'] : null;
$segDura            = (isset($_POST['segundosDura'])) ? $_POST['segundosDura'] : null;
$prep               = null;
$usuario            = $_SESSION['usuario'];

/* Procesamiento peticiones al controlador */
if (!empty($_REQUEST['cod'])) {
    $idAsig = $_REQUEST['cod'];
}

if (!$id && !isset($_POST['dato1']) && !isset($_POST['btnActServicio']) && !isset($_POST['btnGuaSopo']) && !isset($_POST['btnGuaReal'])) {
    SolicitudEspecifica::cargaEspecificasUsuario( $search, 2, $usuario);
} else if (isset($_POST['dato1']) && isset($_POST['dato2']) && !isset($_POST['btnActServicio']) && !isset($_POST['btnGuaSopo'])){
     echo SolicitudEspecifica::selectTipoProducto($sltClase2, $idSer2, null);
}

if($id) {
    $prep           = substr($id, 0, 3);
    $id             = substr($id, 3);
    $validarSer     = SolicitudEspecifica::comprobraExisResultadoServicio($id);
    $validarPro     = SolicitudEspecifica::comprobraExisResultadoProductos($id);
    $info           = SolicitudEspecifica::formResultado($id);
    $idSol          = $info['idSol'];
    $desSol         = $info['descripcionSol'];
    $idProy         = $info['codProy'];
    $nomProy        = $info['nombreProy'];
    $nomProdOSer    = $info['nombreSer'];
    $equipo         = $info['nombreEqu'];
    $servicio       = $info['nombreSer'];
    $solEspecifica  = $info['ObservacionAct'];
    $fechaPrev      = $info['fechPrev'];
    $idSer          = $info['idSer'];
    $sltPlata       = SolicitudEspecifica::selectPlataforma (null);
    $sltRED         = SolicitudEspecifica::selectRED (null);
    $sltClase       = SolicitudEspecifica::selectClaseConTipo ($idSer,null);
    $tiempoTotal    = SolicitudEspecifica::totalTiempo ($idSol);
    $hora           = $tiempoTotal[0];
    $min            = $tiempoTotal[1];

    if ($validarSer == True){
        $info2              = SolicitudEspecifica::cargarInformacionServicio($id);
        $plat               = $info2['idPlat']; 
        $clase              = $info2['idClProd']; 
        $observacion        = $info2['observacion']; 
        $estudiantesImpac   = $info2['estudiantesImpac'];
        $docentesImpac      = $info2['docentesImpac'];
        $url                = $info2['urlResultado'];
        $tipo               = $info2['idTProd'];
        $sltPlata           = SolicitudEspecifica::selectPlataforma ($plat);
        $sltClase           = SolicitudEspecifica::selectClaseConTipo ($idSer,$clase);
        $sltTipo            = SolicitudEspecifica::selectTipoProducto($clase, $idSer, $tipo);
            
    } else if ($validarPro == True){
        $info2      = SolicitudEspecifica::cargarInformacionProducto($id);
        $plat       = $info2['idPlat']; 
        $clase      = $info2['idClProd']; 
        $tipo       = $info2['idTProd'];
        $labor      = $info2['observacionesProd']; 
        $nomProduc  = $info2['nombreProd'];
        $RED        = $info2['descripcionProd'];
        $url        = $info2['urlservidor'];    
        $urlVimeo   = $info2['urlVimeo']; 
        $minDura    = $info2['duracionmin'];  
        $segDura    = $info2['duracionseg'];  
        $sinopsis   = $info2['sinopsis'];
        $autores    = $info2['autorExterno'];
        $fechaEntre = $info2['fechEntregaProd'];
        $sltRED     = SolicitudEspecifica::selectRED ($RED);
        $sltPlata   = SolicitudEspecifica::selectPlataforma ($plat);
        $sltClase   = SolicitudEspecifica::selectClaseConTipo ($idSer,$clase);
        $sltTipo    = SolicitudEspecifica::selectTipoProducto($clase, $idSer, $tipo);
    }
}

if (isset($_POST['btnInactivar'])) {
    if (!empty($_POST['cod'])) {
        $idAsig = $_POST['cod'];
    }
    $id = substr($idAsig, 3);
    $resultado = Asignados::cambiarEstadoAsignacion ($id, 1);
} else if (isset($_POST['btnActServicio'])){
    $compro = SolicitudEspecifica::comprobraExisResultadoServicio($idSol);
    if ($compro == False){
        SolicitudEspecifica::guardarResultadoServicio ($idSol, $idPlat, $idSer, $idClProd,$idTipoPro, $observacion, $estudiantesImpac, $docentesImpac, $otrosImpac, $url, $motivoAnulacion, $usuario, $hora, $min);
    } else{
        SolicitudEspecifica::actualizarResultadoServicio($idSol, $idPlat, $idSer, $idClProd,$idTipoPro, $observacion, $estudiantesImpac, $docentesImpac, $url, $usuario);
    }
} else if (isset($_POST['btnGuaSopo']) ){
    $compro = SolicitudEspecifica::comprobraExisResultadoProductos($idSol);
    if ($compro == False){
        SolicitudEspecifica::guardarResultadoSoporte ($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor);
    } else{
        SolicitudEspecifica::actualizarResultadoSoporte($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor);
    }
} else if (isset($_POST['btnGuaReal'])){
    $compro = SolicitudEspecifica::comprobraExisResultadoProductos($idSol);
    if ($compro == False){
        SolicitudEspecifica::guardarResultadoRealizacion ($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor,$sinopsis, $autores, $urlVimeo, $minDura, $segDura);
    } else{
        SolicitudEspecifica::actualizarResultadoRealizacion($idSol, $usuario, $nomProduc, $fechaEntre, $red, $idPlat, $idClProd, $idTipoPro, $url, $labor,$sinopsis, $autores, $urlVimeo, $minDura, $segDura);
    }
}
?>