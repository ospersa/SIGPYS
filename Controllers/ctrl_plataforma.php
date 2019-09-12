<?php
/* Inclusión del Modelo */
include_once("../Models/mdl_plataforma.php");

/* Inicialización de Variables */
$nomPlataforma  = (isset($_POST["txtNomPlataforma"])) ? $_POST['txtNomPlataforma'] : null;
$val            = (isset($_POST['val'])) ? $_POST['val'] : null;
$id             = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idPlataforma   = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$search = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if($id){
    $info = Plataforma::onLoad($id);
    $nomPlataforma = $info['nombrePlt'];
}
      
/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Plataforma::busquedaTotal() : Plataforma::busqueda($search);
} else if (isset($_POST['btnGuardarPlataforma'])) {
    Plataforma::registrarPlataforma($nomPlataforma);
} else if (isset($_POST['btnActplataforma'])) {
    Plataforma::actualizarPlataforma($idPlataforma, $nomPlataforma);
} else if (isset($_POST['btnEliplataforma'])) {
    Plataforma::suprimirPlataforma($idPlataforma);
}

?>