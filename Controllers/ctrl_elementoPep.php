<?php
include_once('../Models/mdl_elementoPep.php');

$nomPep = (isset($_POST['txtNomPep'])) ? $_POST['txtNomPep'] : null;
$codPep = (isset($_POST['txtCodPep'])) ? $_POST['txtCodPep'] : null;
$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$sltCeco = (isset($_REQUEST['id'])) ? ElementoPep::selectCeco($id) : null;

if (isset($_POST['txt-search'])) {
    $busqueda = $_POST['txt-search'];
    if ($busqueda == null) {
        ElementoPep::busquedaTotal();
    } else {
        ElementoPep::busqueda($busqueda);
    }
}

if (isset($_POST['txtNomPep']) && isset($_POST['txtCodPep']) && !isset($_POST['cod'])) {
    $idCeco = $_POST['sltCeco'];
    ElementoPep::registrarElementoPep($nomPep, $codPep, $idCeco);
}

if (isset($_POST['val']) && isset($_POST['cod'])) {
    $val = $_POST['val'];
    $cod = $_POST['cod'];
    $idCeco = $_POST['sltCeco2'];
    ElementoPep::actualizarElementoPep($cod, $nomPep, $codPep, $idCeco);
}

if (isset($_REQUEST['id'])) {
    $resultado = ElementoPep::onLoadElementoPep($id);
    $codPep = $resultado['codigoElemento'];
    $nomPep = $resultado['nombreElemento'];
}

?>