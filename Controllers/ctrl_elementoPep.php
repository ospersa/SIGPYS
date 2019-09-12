<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_elementoPep.php');

/* Inicialización variables*/
$nomPep     = (isset($_POST['txtNomPep'])) ? $_POST['txtNomPep'] : null;
$codPep     = (isset($_POST['txtCodPep'])) ? $_POST['txtCodPep'] : null;
$id         = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$search     = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$idCeco     = (isset($_POST['sltCeco'])) ? $_POST['sltCeco'] : null;
$cod        = (isset($_POST['cod'])) ? $_POST['cod'] : null;
/* Variables que cargan Select en formularios*/
$sltCeco = (isset($_REQUEST['id'])) ? ElementoPep::selectCeco($id) : null;

/* Carga de información en el Modal */
if (isset($_REQUEST['id'])) {
    $resultado = ElementoPep::onLoadElementoPep($id);
    $codPep = $resultado['codigoElemento'];
    $nomPep = $resultado['nombreElemento'];
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? ElementoPep::busquedaTotal() : ElementoPep::busqueda($search);
}

if (isset($_POST['btnGuardarEpep'])) {
    ElementoPep::registrarElementoPep($nomPep, $codPep, $idCeco);
}else if (isset($_POST['btnActEPep'])) {
    $idCeco = $_POST['sltCeco2'];
    ElementoPep::actualizarElementoPep($cod, $nomPep, $codPep, $idCeco);
}

?>