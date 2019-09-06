<?php
include_once('../Models/mdl_celula.php');

$selectCoordinador = Celula::selectCoordinador("required");
$celula = (isset($_POST['txtNomCelula'])) ? $_POST['txtNomCelula'] : null;
$nombreCelula = "";
$idCelula = "";

if (isset($_POST['txt-search'])) {
    $busqueda = $_POST["txt-search"];
    if ($busqueda == null) {
        Celula::busquedaTotal();
    } else {
        Celula::busqueda($busqueda);
    }
}

if (isset($_POST['txtNomCelula']) && !isset($_POST['cod']) && isset($_POST['sltCoordinador'])) {
    $coordinador = $_POST['sltCoordinador'];
    Celula::registrarCelula($celula, $coordinador);
}

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $resultado = Celula::onLoadCelula($id);
    $nombreCelula = $resultado['nombreCelula'];
    $idCelula = $resultado['idCelula'];
}

if (isset($_POST['val']) && isset($_POST['cod'])) {
    $val = $_POST['val'];
    $cod = $_POST['cod'];
    $nombreCelula = $_POST['txtNomCelula2'];
    $eliminar = $_POST['chkDeleteCoor'];
    $agregar = $_POST['sltCoordinador'];
    if ($val == 1) {
        Celula::actualizarCelula($cod, $nombreCelula, $eliminar, $agregar);
    }
}

?>