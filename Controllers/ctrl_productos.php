<?php 
/* Inclusión del Modelo */
include('../Models/mdl_productos.php');
include('../Models/mdl_equipo.php');

/** Inicialización de Variables */
$idEquipo        = (isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$idServicio     = (isset($_POST['sltServicio'])) ? $_POST['sltServicio'] : null;
$idClase        = (isset($_POST['sltClase'])) ? $_POST['sltClase'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$cod            = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/** Variables formulario tipos de productos */
$nombreTipo         = (isset($_POST['txtNombreTipo'])) ? $_POST['txtNombreTipo'] : null;
$descripcionTipo    = (isset($_POST['txtDescripcionTipo'])) ? $_POST['txtDescripcionTipo'] : null;
$costoSin           = (isset($_POST['txtCostoSin'])) ? $_POST['txtCostoSin'] : null;
$costoCon           = (isset($_POST['txtCostoCon'])) ? $_POST['txtCostoCon'] : null;
$costoTipo          = (isset($_POST['txtCostoTipo'])) ? $_POST['txtCostoTipo'] : null;
$search             = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/** Variables formulario clases de productos */
$nombreClase        = (isset($_POST['txtNombreClase'])) ? $_POST['txtNombreClase'] : null;
$nombreCortoClase   = (isset($_POST['txtNombreCorClase'])) ? $_POST['txtNombreCorClase'] : null;
$descripcionClase   = (isset($_POST['txtDescripcionClase'])) ? $_POST['txtDescripcionClase'] : null;
$costoClase         = (isset($_POST['txtCostoClase'])) ? $_POST['txtCostoClase'] : null;

/** Variables que cargan select */
$selectEquipo       = Equipo::selectEquipo(null, null);
$selectServicio     = Producto::selectServicio(null, null);
$selectClase        = Producto::selectClase(null, null);

/** Procesamiento de peticiones realizadas al controlador */
if (isset($_POST['btnRegistrarTip'])) {
    Producto::registrarTipoProducto($idEquipo, $idServicio, $idClase, $nombreTipo, $descripcionTipo, $costoSin, $costoCon, $costoTipo);
} else if (isset($_POST['btnRegistrarCla'])) {
    Producto::registrarClaseProducto($idEquipo, $idServicio, $nombreClase, $nombreCortoClase, $descripcionClase, $costoClase);
} else if (isset($_POST['btnActTipProd'])) {
    Producto::actualizarTipoProducto($cod, $idServicio, $idClase, $nombreTipo, $descripcionTipo, $costoSin, $costoCon, $costoTipo);
} else if (isset($_POST['btnActClaPro'])) {
    Producto::actualizarClaseProducto($cod, $idServicio, $nombreClase, $nombreCortoClase, $descripcionClase, $costoClase);
}
if (isset($_POST['txt-search'])) {
    Producto::busquedaTipoProductos($search);
}


/** Carga de información en el Modal */
if ($id) {
    $prep = substr($id, 0, 3);
    if ($prep == "TPR") {
        $info = Producto::onLoadTipoProducto($id);
    } else if ($prep == "CLA") {
        $info = Producto::onLoadClaseProducto($id);
    }
    if (!empty($info)) {
        $idEquipo = (isset($info['idEqu'])) ? $info['idEqu'] : null;
        $idServicio = (isset($info['idSer'])) ? $info['idSer'] : null;
        $idClase = (isset($info['idClProd'])) ? $info['idClProd'] : null;
        $nombreTipo = (isset($info['nombreTProd'])) ? $info['nombreTProd'] : null;
        $descripcionTipo = (isset($info['descripcionTProd'])) ? $info['descripcionTProd'] : null;
        $costoSin = (isset($info['costoSin'])) ? $info['costoSin'] : null;
        $costoCon = (isset($info['costoCon'])) ? $info['costoCon'] : null;
        $costoTipo = (isset($info['costo'])) ? $info['costo'] : null;
        $nombreClase = (isset($info['nombreClProd'])) ? $info['nombreClProd'] : null;
        $nombreCortoClase = (isset($info['nombreCortoClProd'])) ? $info['nombreCortoClProd'] : null;
        $descripcionClase = (isset($info['descripcionClProd'])) ? $info['descripcionClProd'] : null;
        $costoClase = (isset($info['costo'])) ? $info['costo'] : null;
        $selectEquipo = Equipo::selectEquipo($idEquipo, null);//revisar 
        $selectServicio = Producto::selectServicio($idEquipo, $idServicio);
        $selectClase = Producto::selectClase($idServicio, $idClase);
    }
}

/** Procesamiento de peticiones realizadas al Controlador vía Ajax */
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($id == null) {
        if ($idEquipo) {
            echo Producto::selectServicio($idEquipo, null);
        }
        if ($idServicio) {
            echo Producto::selectClase($idServicio, null);
        }
    }
}

?>