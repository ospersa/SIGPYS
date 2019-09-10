<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_centroCosto.php');

/* Inicialización variables*/
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $resultado = CentroCosto::onLoadCentroCosto($id);
    $codCeco = $resultado['ceco'];
    $nomCeco = $resultado['nombre'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? CentroCosto::busquedaTotal() : CentroCosto::busqueda($busqueda);
}

if (isset($_POST['btnGuardarCenCos'])) {
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

?>