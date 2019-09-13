<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_servicios.php');
include_once('../Models/mdl_equipo.php');

/* Inicialización Variables */
$equipo         = (isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$nombre         = (isset($_POST['txtNombreServicio'])) ? $_POST['txtNombreServicio'] : null;
$nombreCorto    = (isset($_POST['txtNombreCortoServicio'])) ? $_POST['txtNombreCortoServicio'] : null;
$descripcion    = (isset($_POST['txtDescripcionServicio'])) ? $_POST['txtDescripcionServicio'] : null;
$producto       = (isset($_POST['sltProducto'])) ? $_POST['sltProducto'] : null;
$costo          = (isset($_POST['txtCostoServicio'])) ? $_POST['txtCostoServicio'] : null;
$busqueda       = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$cod             = (isset($_REQUEST['cod'])) ? $_REQUEST['cod'] : null;
$selectGenerarPro = "";

/** Carga de información en el Modal */
if ($id) {
    $info = Servicios::onLoad($id);
    $idEquipo = $info['idEqu'];
    $nombre = $info['nombreSer'];
    $nombreCorto = $info['nombreCorto'];
    $descripcion = $info['descripcionSer'];
    $proOser = $info['productoOservicio'];
    $costo = $info['costo'];
    $selectEquipo = Equipo::selectEquipo($idEquipo, "modal");
    $selectGenerarPro = Servicios::selectGeProd($id);
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnRegistrar'])) {
    Servicios::registrarServicio($equipo, $nombre, $nombreCorto, $descripcion, $producto, $costo);
} else if (isset($_POST['txt-search'])) {
    Servicios::busquedaServicios($busqueda);
} else if (isset($_POST['btnEliServicio'])){
    Servicios::suprimirServicio($cod);
} else if(isset($_POST['btnActServicio'])){
    Servicios::actualizarServicio($cod, $equipo, $nombre, $nombreCorto, $descripcion, $costo);
} 

?>