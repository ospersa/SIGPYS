<?php
include_once('../Models/mdl_centroCosto.php');

if (isset($_POST['txt-search'])) {
    $busqueda = $_POST['txt-search'];
    if ($busqueda == null) {
        CentroCosto::busquedaTotal();
    } else {
        CentroCosto::busqueda($busqueda);
    }
}

if (isset($_POST['txtCodCeco']) && isset($_POST['txtNomCeco']) && !isset($_POST['cod'])) {
    $codCeco = $_POST['txtCodCeco'];
    $nomCeco = $_POST['txtNomCeco'];
    CentroCosto::registrarCeco($codCeco, $nomCeco);
}

if (isset($_POST['val']) && isset($_POST['cod'])) {
    $val = $_POST['val'];
    $cod = $_POST['cod'];
    $codCeco = $_POST['txtCodCeco2'];
    $nomCeco = $_POST['txtNomCeco2'];
    if ($val == 1) {
        CentroCosto::actualizarCeco($cod, $codCeco, $nomCeco);
    }
}

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $resultado = CentroCosto::onLoadCentroCosto($id);
    $codCeco = $resultado['ceco'];
    $nomCeco = $resultado['nombre'];
}

?>