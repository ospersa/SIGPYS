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

if (!$id && !isset($_POST['sltClaseM']) && !isset($_POST['btnActServicio'])) {
    SolicitudEspecifica::cargaEspecificasUsuario( $search, 2, $usuario);
}
if (isset($_POST['sltClaseM']) && !isset($_POST['btnActServicio'])){
     echo Producto::selectTipoProducto($sltClase,null);
}
if($id) {
    $prep = substr($id, 0, 3);
    $id = substr($id, 3);
    /* Si el resultado es de servicio*/
    if ($prep == "GEN") {
        $info = SolicitudEspecifica::formResultadoServicio($id);
        $idSol = $info['idSol'];
        $idProy = $info['codProy'];
        $nomEqu = $info['nombreEqu'];
        $desSol = $info['descripcionSol'];
        $idSer = $info['idSer'];
        $nomProy = $info['nombreProy'];
        $nomSer = $info['nombreSer'];
        $sltPlata = Plataforma::selectPlataforma(null);
        $sltClase = Producto::selectClaseConTipo($idSer,null);
        $tiempoTotal = SolicitudEspecifica::totalTiempo($idSol);
        $hora = $tiempoTotal[0];
        $min = $tiempoTotal[1];
    } else if ($prep == "SOP"){
        $info = SolicitudEspecifica::formResultadoSoprte($id);
        $idSol = $info['idSol'];
        $idProy = $info['codProy'];
        $nomProy = $info['nombreProy'];
        $nomProd = $info['nombreSer'];
        $etiqueta = 'SOP'.substr($info['idSol'],1);
        $equipo = $info['nombreEqu'];
        $servicio = $info['nombreSer'];
        $solEspecifica = $info['ObservacionAct'];
        $fechaPrev = $info['fechPrev'];
        $idSer = $info['idSer'];
        $sltPlata = Plataforma::selectPlataforma(null);
        $sltRED = SolicitudEspecifica::selectRED(null);
        $sltClase = Producto::selectClaseConTipo($idSer,null);
        $tiempoTotal = SolicitudEspecifica::totalTiempo($idSol);
        echo "h";
        echo $hora = $tiempoTotal[0];
        $min = $tiempoTotal[1];
    } else if ($prep == "DIS"){
        echo "h2";
    } else if ($prep == "REA"){
        echo "h3";
    }
}

if (isset($_POST['btnInactivar'])) {
    $resultado = Asignados::cambiarEstadoAsignacion($idAsig, 1);
}if (isset($_POST['btnActServicio'])){
    $idSol = (isset($_POST['idSol'])) ? $_POST['idSol']  : null;
    $idPlat = (isset($_POST['sltPlataforma'])) ?  $_POST['sltPlataforma'] : null;
    $idSer = (isset($_POST['idSer'])) ? $_POST['idSer'] : null;
    $idClProd = (isset($_POST['sltClaseM'])) ? $_POST['sltClaseM'] : null;
    $observacion = (isset($_POST['descripSer'])) ? $_POST['descripSer'] : null;
    $estudiantesImpac = (isset($_POST['numEst'])) ? $_POST['numEst'] : null;
    $docentesImpac = (isset($_POST['numDoc'])) ? $_POST['numDoc'] : null;
    $otrosImpac = (isset($_POST['otrosImpac'])) ? $_POST['otrosImpac'] : null;
    $urlResultado = (isset($_POST['url'])) ? $_POST['url']  : null;
    $motivoAnulacion = (isset($_POST['motivoAnulacion'])) ? $_POST['motivoAnulacion'] : null;
    $idResponRegistro = (isset($_POST['idResponRegistro'])) ? $_POST['idResponRegistro'] : null;
    $tiempoTotal = SolicitudEspecifica::totalTiempo($idSol);
    $hora = $tiempoTotal[0];
    $min = $tiempoTotal[1];
    echo "si";
    $resultado = SolicitudEspecifica::guardarResultadoServicio ($idSol, $idPlat, $idSer, $idClProd,$idTipoPro, $observacion, $estudiantesImpac, $docentesImpac, $otrosImpac, $urlResultado, $motivoAnulacion, $usuario, $hora, $min);
}

?>