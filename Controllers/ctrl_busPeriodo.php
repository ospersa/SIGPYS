<?php
$busqueda = $_POST["txt-search"];
include_once "../Models/mdl_periodo.php";
if ($busqueda == null){
    $resultado = Periodo::busquedaTotal();
} else {
    $resultado = Periodo::busqueda($busqueda);
}
?>