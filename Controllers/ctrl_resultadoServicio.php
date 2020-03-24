<?php 
/* Inicializar variables de sesión */
if(!isset($_SESSION['usuario'])) { 
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_resultadoServicio.php');
include_once('../Models/mdl_terminacionProductoServicio.php');
include_once('../Models/mdl_asignados.php');
include_once('../Models/mdl_plataforma.php');
include_once('../Models/mdl_productos.php');

$busqueda               = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$usuario                = $_SESSION['usuario'];
$id                     = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$prepServicio           = null;
$infoServicioCompleto   = "";
$info                   = "";   
$idSol                  = "";
$desSol                 = "";
$idProy                 = "";
$nomProy                = "";
$nomProdOSer            = "";
$equipo                 = "";
$servicio               = "";
$solEspecifica          = "";
$fechaPrev              = "";
$idSer                  = "";
$sltPlata               = "";
$sltRED                 = "";
$sltClase               = "";
$tiempoTotal            = "";
$hora                   = "";
$min                    = "";
$info2                  = "";
$plat                   = "";
$clase                  = "";
$observacion            = "";
$estudiantesImpac       = "";
$docentesImpac          = "";
$url                    = "";
$tipo                   = "";
$sltPlata               = "";
$sltClase               = "";
$sltTipo                = "";
if ($id != null){
    $prepServicio           = substr($id, 0, 3);
    $id                     = substr($id, 3);
    $infoServicioCompleto   = Terminar::informacionProdSer($id); 
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
    $sltPlata               = Plataforma::selectPlataforma (null);
    $sltRED                 = SolicitudEspecifica::selectRED (null);
    $sltClase               = Producto::selectClaseConTipo ($idSer,null);
    $tiempoTotal            = SolicitudEspecifica::totalTiempo ($idSol);
    $hora                   = $tiempoTotal[0];
    $min                    = $tiempoTotal[1];
    $info2                  = SolicitudEspecifica::cargarInformacionServicio($id);
    $plat                   = $info2['idPlat']; 
    $clase                  = $info2['idClProd']; 
    $observacion            = $info2['observacion']; 
    $estudiantesImpac       = $info2['estudiantesImpac'];
    $docentesImpac          = $info2['docentesImpac'];
    $url                    = $info2['urlResultado'];
    $tipo                   = $info2['idTProd'];
    $sltPlata               = Plataforma::selectPlataforma ($plat);
    $sltClase               = Producto::selectClaseConTipo ($idSer,$clase);
    $sltTipo                = Producto::selectTipoProducto($clase, $idSer, $tipo);

} else if ($busqueda != null){
    ResulServicio::cargaBusqueda($usuario, $busqueda); 
} else {
    ResulServicio::cargaUsuario($usuario); 

}

?>