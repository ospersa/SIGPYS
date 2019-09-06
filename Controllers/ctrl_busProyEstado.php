<?php
$busqueda = $_POST["txt-search"];
include_once('../Models/mdl_proyecto.php');
if ($busqueda == null) {
    Proyecto::busquedaTotalEstado();
} else {
    Proyecto::busquedaEstado($busqueda);
}
?>