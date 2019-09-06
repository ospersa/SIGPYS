<?php
$busqueda = $_POST["txt-search"];
include_once('../Models/mdl_salario.php');
if ($busqueda == null) {
    Salarios::busquedaTotal();
} else {
    Salarios::busqueda($busqueda);
}
?>