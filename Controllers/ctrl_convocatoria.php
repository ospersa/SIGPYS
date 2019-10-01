<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_convocatoria.php";

/* Inicialización variables*/
$nomConv       = (isset($_POST["txtNomConv"])) ? $_POST["txtNomConv"] : null;
$descConv      = (isset($_POST["txtDescConv"])) ? $_POST["txtDescConv"] : null;
$val           = (isset($_POST["val"])) ? $_POST["val"] : null;
$idConv        = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idConv2       = (isset($_POST["cod"])) ? $_POST["cod"] : null;
$search        = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Carga de información en el Modal */
if($idConv){
    $info = Convocatoria::onLoad($idConv);
    $nomConv = $info['nombreConvocatoria'];
    $descConv = $info['descrConvocatoria'];   
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Convocatoria::busquedaTotal() : Convocatoria::busqueda($search);
} else if (isset($_POST['btnGuardarConv'])) {
    $resultado = Convocatoria::registrarConv($nomConv, $descConv);
} else if (isset($_POST['btnActConv'])) {
    Convocatoria::actualizarConv($idConv2, $nomConv, $descConv);
} else if (isset($_POST['btnEliConv'])){
    Convocatoria::suprimirConv($idConv2);
}

?>