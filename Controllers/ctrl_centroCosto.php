<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_centroCosto.php');

/* Inicialización variables*/
$search     = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$codCeco    = (isset($_POST['txtCodCeco'])) ? $_POST['txtCodCeco'] : null;
$nomCeco    = (isset($_POST['txtNomCeco'])) ? $_POST['txtNomCeco'] : null;
$cod        = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$id         = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

/* Carga de información en el Modal */
if ($id!=null) {
    $resultado = CentroCosto::onLoadCentroCosto($id);
    $codCeco = $resultado['ceco'];
    $nomCeco = $resultado['nombre'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? CentroCosto::busquedaTotal() : CentroCosto::busqueda($search);
}

if (isset($_POST['btnGuardarCenCos'])) {
    CentroCosto::registrarCeco($codCeco, $nomCeco);
}else if (isset($_POST['btn_act'])) {
    CentroCosto::actualizarCeco($cod, $codCeco, $nomCeco);
}

?>