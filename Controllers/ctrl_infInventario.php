<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_inventario.php');
include_once('../Models/mdl_infInventario.php');

$proyecto      = (isset($_POST['sltProyecto'])) ? $_POST['sltProyecto'] : null;
$fechIni      = (isset($_POST['fechIni'])) ? $_POST['fechIni'] : null;
$fechFin      = (isset($_POST['fechFin'])) ? $_POST['fechFin'] : null;
$persona      = (isset($_POST['sltPersona'])) ? $_POST['sltPersona'] : null;
$sltEstadoInv  = (isset($_POST['sltEstadoInv'])) ? $_POST['sltEstadoInv'] : null; 
$selectEstado  = Inventario::selectEstadoInv('');

if (isset($_POST['proyecto'])) {
    $busqueda = $_POST['proyecto'];
    Inventario::selectProyecto($busqueda);
} 
 if (isset($_POST['sltProyecto']) ||isset($_POST['fechIni']) || isset($_POST['fechFin']) || isset($_POST['sltPersona']) || isset($_POST['sltEstadoInv']))  {
    echo InformeInventario::busqueda($persona, $proyecto, $fechIni,$fechFin, $sltEstadoInv);

} else if (isset($_POST['btnDescargar'])) {
    echo InformeInventario::descarga($persona, $proyecto, $fechIni,$fechFin, $sltEstadoInv);

}

?>