<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infPersonas.php');

$idFacDepto = (isset($_POST['sltEntFac'])) ? $_POST['sltEntFac'] : null;


if (isset($_POST['btnDescargar'])) {
   InformePersonas::descarga($idFacDepto);

} else if (isset($_POST['sltEntFac'])) {
    InformePersonas::busqueda($idFacDepto);
} else {
    $selectEntidad = InformePersonas::selectEntFAcu();

} 

?>