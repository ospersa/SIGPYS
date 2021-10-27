<?php 
/* Inclusión del Modelo */
include_once "../Models/mdl_opciones.php";

/* Inicialización de variables */
$search         = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$registry       = (isset($_POST['btnRegistrarOpcion'])) ? $_POST['btnRegistrarOpcion'] : null;
$name           = (isset($_POST['txtOptionName'])) ? $_POST['txtOptionName'] : null;
$description    = (isset($_POST['txtOptionDescription'])) ? $_POST['txtOptionDescription'] : null;
$value          = (isset($_POST['txtOptionValue'])) ? $_POST['txtOptionValue'] : null;
$idOption       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$updateOption   = (isset($_POST['btnUpdateOption'])) ? true : false;
$deleteOption   = (isset($_POST['btnDeleteOption'])) ? true : false;
$id             = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Procesamiento de peticiones al controlador */
if ( isset ($search) ) {
    Opcion::busqueda($search);
} else if ( isset ($registry) ) {
    Opcion::registrarOpcion($name, $description, $value);
} else if ( $updateOption ) {
    Opcion::actualizarOpcion($id, $name, $description, $value);
} else if ( $deleteOption ) {
    Opcion::eliminarOpcion($id);
}

/* Carga de información en el modal */
if ($idOption) {
    $info = Opcion::onLoad($idOption);
    $name = $info['option_name'];
    $description = $info['option_description'];
    $value = $info['option_value'];
}

?>