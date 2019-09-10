<?php

/* Carga del Modelo */
include_once('../Models/mdl_fuenteFinanciamiento.php');

/* Inicialización de Variables */
$nombre     = (isset($_POST['txtFteFin'])) ? $_POST['txtFteFin'] : null;
$sigla      = (isset($_POST['txtSiglaFteFin'])) ? $_POST['txtSiglaFteFin'] : null;
$search     = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$val        = (isset($_POST['val'])) ? $_POST['val'] : null;
$id         = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idFteFin   = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Procesamiento de peticiones de busqueda al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? FuenteFinanciamiento::busquedaTotal() : FuenteFinanciamiento::busqueda($search) ;
}

/* Procesamiento peticiones de inserción, y actualización al Controlador */
if (isset($_POST['btnGuardarFteFin'])) {
    FuenteFinanciamiento::registrarFuenteFinanciamiento($sigla, $nombre);
} else if ($val == 1) {
    FuenteFinanciamiento::actualizarFuenteFinanciamiento($idFteFin, $sigla, $nombre);
}

/** Carga de información en el Modal */
if ($id) {
    $info = FuenteFinanciamiento::onLoadFuenteFinanciamiento($id);
    $nombre = $info['nombre'];
    $sigla = $info['sigla'];
}
    
?>