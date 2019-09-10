<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_convocatoria.php";

/* Inicialización variables*/
$nomConv       = (isset($_POST["txtNomConv"])) ? $_POST["txtNomConv"] : null;
$descConv      = (isset($_POST["txtDescConv"])) ? $_POST["txtDescConv"] : null;
$val           = (isset($_POST["val"])) ? $_POST["val"] : null;
$idConv        = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idConv2       = (isset($_POST["cod"])) ? $_POST["cod"] : null;
$var1          = "";
$var2          = "";

/* Carga de información en el Modal */
if($idConv){
    $info = Convocatoria::onLoad($idConv);
    $nomConv = $info['nombreConvocatoria'];
    $descConv = $info['descrConvocatoria'];   
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarConv'])) {
    $resultado = Convocatoria::registrarConv($nomConv, $descConv);
} else if ($val == "1") {
    Convocatoria::actualizarConv($idConv2, $nomConv, $descConv);
} else if ($val == "2") {
    Convocatoria::suprimirConv($idConv2);
}

?>