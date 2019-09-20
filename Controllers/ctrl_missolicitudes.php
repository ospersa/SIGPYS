<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_solicitudEspecifica.php');
include_once('../Models/mdl_asignados.php');
include_once('../Models/mdl_plataforma.php');
include_once('../Models/mdl_productos.php');



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
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$sltClase           = (isset($_POST['sltClaseM'])) ? $_POST['sltClaseM'] : null;
$prep               = null;
/* Procesamiento peticiones al controlador */
if (!empty($_REQUEST['cod'])) {
    $idAsig = $_REQUEST['cod'];
}
//Cambio del comentado de abajo 
if(!isset($_SESSION)) { 
    session_start();
}
$usuario = $_SESSION['usuario'];

if (!$id && !isset($_POST['sltClaseM'])) {
    SolicitudEspecifica::cargaEspecificasUsuario( $search, 2, $usuario);
}
if (isset($_POST['sltClaseM'])){
     echo Producto::selectTipoProducto($sltClase,null);
}
if($id) {
    $prep = substr($id, 0, 3);
    $id = substr($id, 3);
    if ($prep == "GEN") {
        $info = SolicitudEspecifica::formResultadoServicio($id,$usuario);
        $idSol = $info['idSol'];
        $idProy = $info['codProy'];
        $nomEqu = $info['nombreEqu'];
        $desSol = $info['descripcionSol'];
        $idSer = $info['idSer'];
        $minuSer = $info['idSer'];
        $minuSer = $info['idSer'];
        $nomProy = $info['nombreProy'];
        $nomSer = $info['nombreSer'];
        $sltPlata = Plataforma::selectPlataforma(null);
        $sltClase = Producto::selectClaseConTipo($idSer,null);
        $tiempoTotal = SolicitudEspecifica::totalTiempo($idSol);
    }
}

if (isset($_POST['btnInactivar'])) {
    $resultado = Asignados::cambiarEstadoAsignacion($idAsig, 1);
}

?>