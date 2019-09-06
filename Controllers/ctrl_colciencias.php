<?php
include_once('../Models/mdl_colciencias.php');
if (isset($_GET['cod2'])) {
    $cod2 = $_GET['cod2'];
    $idProy = $_GET['cod'];
    if ($cod2 == '2') {
        $resultado = Colciencias::onLoadProyecto($idProy);
    }
}
?>