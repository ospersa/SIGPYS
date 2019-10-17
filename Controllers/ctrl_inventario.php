<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_inventario.php');
include_once('../Models/mdl_solicitudEspecifica.php');
if(!isset($_SESSION)){ 
    session_start();
}
/* Inicialización variables*/
$usuario    = $_SESSION['usuario'];
$perfil = $_SESSION['perfil'];
$infoUser = Inventario::nombrePersona($usuario);
$nombreUser = $infoUser['apellido1']." ".$infoUser['apellido2']." ".$infoUser['nombres'];
$idUser = $infoUser['idPersona']; 

$persona        =(isset($_POST['sltPersona'])) ? $_POST['sltPersona'] : null;
$proyecto       =(isset($_POST['sltProyecto'])) ? $_POST['sltProyecto'] : null;
$equipo         =(isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$idSol          =(isset($_POST['sltProducto'])) ? $_POST['sltProducto'] : null; 
$descrip        =(isset($_POST['txtDescrip'])) ? $_POST['txtDescrip'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$prep           = "";
$crudosCarp     = (isset($_POST['txtCrudosCarp'])) ? $_POST['txtCrudosCarp'] : null;
$crudosPes      = (isset($_POST['txtCrudosPes'])) ? $_POST['txtCrudosPes'] : null;
$proyCarp       = (isset($_POST['txtProyCarp'])) ? $_POST['txtProyCarp'] : null;
$proyPeso       = (isset($_POST['txtProyPeso'])) ? $_POST['txtProyPeso'] : null;
$finCarp        = (isset($_POST['txtFinCarp'])) ? $_POST['txtFinCarp'] : null;
$finPeso        = (isset($_POST['txtFinPeso'])) ? $_POST['txtFinPeso'] : null;
$recCarp        = (isset($_POST['txtRecCarp'])) ? $_POST['txtRecCarp'] : null;
$recPeso        = (isset($_POST['txtRecPeso'])) ? $_POST['txtRecPeso'] : null;
$docCarp        = (isset($_POST['txtDocCarp'])) ? $_POST['txtDocCarp'] : null;
$docPeso        = (isset($_POST['txtDocPeso'])) ? $_POST['txtDocPeso'] : null;
$rutSer         = (isset($_POST['txtRutSer'])) ? $_POST['txtRutSer'] : null;
$disCarp        = (isset($_POST['txtDisCarp'])) ? $_POST['txtDisCarp'] : null;
$disPeso        = (isset($_POST['txtDisPeso'])) ? $_POST['txtDisPeso'] : null;
$sopCarp        = (isset($_POST['txtSopCarp'])) ? $_POST['txtSopCarp'] : null;
$sopPeso        = (isset($_POST['txtSopPeso'])) ? $_POST['txtSopPeso'] : null;
$obs            = (isset($_POST['txtObs'])) ? $_POST['txtObs'] : null;
$idPerEnt       = (isset($_POST['sltPerEnt'])) ? $_POST['sltPerEnt'] : null;
$idPerRec       = (isset($_POST['sltPerRec'])) ? $_POST['sltPerRec'] : null;
$estadoInv      = (isset($_POST['sltEstadoInv'])) ? $_POST['sltEstadoInv'] : null;
$cod            = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;

if ($id != null){
    $prep  = substr($id, 0, 3);
    $id    = substr($id, 3);
    $infoAsig = Inventario::OnLoadAsignados($id); 
    $info                   = SolicitudEspecifica::formResultado($id);
    $idSol                  = $info['idSol'];
    $desSol                 = $info['descripcionSol'];
    $idProy                 = $info['codProy'];
    $nomProy                = $info['nombreProy'];
    $nomProdOSer            = $info['nombreSer'];
    $equipo                 = $info['nombreEqu'];
    $servicio               = $info['nombreSer'];
    $solEspecifica          = $info['ObservacionAct'];
    $fechaPrev              = $info['fechPrev'];
    $idSer                  = $info['idSer'];
    $validarInv = Inventario::validarInventario($id);
    $tabla = Inventario::tablaActualizaciones($id);
    if($validarInv != null){
        
        $crudosCarp     = $validarInv['crudoCarpeta'];
        $crudosPes      = $validarInv['crudoPeso'];
        $proyCarp       = $validarInv['proyectoCarpeta'];
        $proyPeso       = $validarInv['proyectoPeso'];
        $finCarp        = $validarInv['finalesCarpeta'];
        $finPeso        = $validarInv['finalesPeso'];
        $recCarp        = $validarInv['recursosCarpeta'];
        $recPeso        = $validarInv['recursosPeso'];
        $docCarp        = $validarInv['documentosCarpeta'];
        $docPeso        = $validarInv['documentosPeso'];
        $rutSer         = $validarInv['rutaServidor'];
        $disCarp        = $validarInv['diseñoCarpeta'];
        $disPeso        = $validarInv['diseñoPeso'];
        $sopCarp        = $validarInv['soporteCarpeta'];
        $sopPeso        = $validarInv['soportePeso'];
        $obs            = $validarInv['observacion'];
        $idPerEnt       = $validarInv['idPersonaEntrega'];
        $idPerRec       = $validarInv['idPersonaRecibe'];
        $estadoInv      = $validarInv['estadoInv'];
    }
    $selectEstado = Inventario::selectEstadoInv($estadoInv);
    $stlPerEnt              = Inventario::selectPersona(2, $idPerEnt);
    $stlPerRec              = Inventario::selectPersona(3, $idPerRec);
}else if (isset($_POST['btnGuaInv'])){
    $validarInv = Inventario::validarInventario($cod);
    if($validarInv != null){
        Inventario::actualizarInventario ($cod, $crudosCarp, $crudosPes, $proyCarp, $proyPeso, $finCarp, $finPeso, $recCarp, $recPeso, $docCarp, $docPeso, $rutSer, $disCarp, $disPeso, $sopCarp, $sopPeso, $obs, $idPerEnt, $idPerRec, $estadoInv); 
    } else{
        Inventario::ingresarInventario ($cod, $crudosCarp, $crudosPes, $proyCarp, $proyPeso, $finCarp, $finPeso, $recCarp, $recPeso, $docCarp, $docPeso, $rutSer, $disCarp, $disPeso, $sopCarp, $sopPeso, $obs, $idPerEnt, $idPerRec, $estadoInv); 
    }
} else if ($perfil == 'PERF01' || $perfil == 'PERF02' ||($persona || $proyecto || $equipo || $idSol || $descrip) != null){
    Inventario::onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip);
} else{ 
    Inventario::onLoadUsuario($usuario);
    
}

if (isset($_POST['persona'])) {
    $busqueda = $_POST['persona'];
    Inventario::selectPersona(1, $busqueda);
} else if (isset($_POST['equipo'])) {
    $busqueda = $_POST['equipo'];
    Inventario::selectEquipo($busqueda);
} else if (isset($_POST['producto'])) {
    $busqueda = $_POST['producto'];
    Inventario::selectProducto($busqueda);
} else if (isset($_POST['proyecto'])) {
    $busqueda = $_POST['proyecto'];
    Inventario::selectProyecto($busqueda);
} 


?>