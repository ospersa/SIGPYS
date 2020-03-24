<?php 
/* Inicializar variables de sesión */
if(!isset($_SESSION['usuario'])) { 
    session_start();
}
/* Inclusión del Modelo */
include_once('../Models/mdl_resultadoProducto.php');
include_once('../Models/mdl_terminacionProductoServicio.php');
include_once('../Models/mdl_asignados.php');
include_once('../Models/mdl_plataforma.php');
include_once('../Models/mdl_productos.php');

$busqueda = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$usuario  = $_SESSION['usuario'];
$id       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$infoProductoCompleto = "";
$prep = null;
$info           = "";
$idSol          = "";
$desSol         = "";
$idProy         = "";
$nomProy        = "";
$nomProdOSer    = "";
$equipo         = "";
$servicio       = "";
$solEspecifica  = "";
$fechaPrev      = "";
$idSer          = "";
$sltPlata       = "";
$sltRED         = "";
$sltClase       = "";
$tiempoTotal    = "";
$hora           = "";
$min            = "";
$info2          = "";
$plat           = "";
$clase          = "";
$tipo           = "";
$labor          = "";
$nomProduc      = "";
$RED            = "";
$url            = "";
$urlVimeo       = "";
$minDura        = "";
$segDura        = "";
$sinopsis       = "";
$autores        = "";
$fechaEntre     = "";
$sltRED         = "";
$sltPlata       = "";
$sltClase       = "";
$sltTipo        = "";

if ($id != null){
    $prep                   = substr($id, 0, 3);
    $id                     = substr($id, 3);
    $infoProductoCompleto   = Terminar::informacionProdSer($id); 
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
    $info2                  = SolicitudEspecifica::cargarInformacionProducto($id);
    $plat                   = $info2['idPlat']; 
    $clase                  = $info2['idClProd']; 
    $tipo                   = $info2['idTProd'];
    $labor                  = $info2['observacionesProd']; 
    $nomProduc              = $info2['nombreProd'];
    $RED                    = $info2['descripcionProd'];
    $url                    = $info2['urlservidor'];    
    $urlVimeo               = $info2['urlVimeo']; 
    $minDura                = $info2['duracionmin'];  
    $segDura                = $info2['duracionseg'];  
    $sinopsis               = $info2['sinopsis'];
    $autores                = $info2['autorExterno'];
    $fechaEntre             = $info2['fechEntregaProd'];
    $sltRED                 = SolicitudEspecifica::selectRED ($RED);
    $sltPlata               = Plataforma::selectPlataforma ($plat);
    $sltClase               = Producto::selectClaseConTipo ($idSer,$clase);
    $sltTipo                = Producto::selectTipoProducto($clase, $idSer, $tipo);
} else if ($busqueda != null){
    ResultadoProductoTer::cargaBusqueda($usuario, $busqueda, 2); 
} else {
    ResultadoProductoTer::cargaUsuario($usuario, 2); 

}

?>