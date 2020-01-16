<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infProyectos.php');

$estProy = (isset($_POST['sltEstProy'])) ? $_POST['sltEstProy'] : null;


if (isset($_POST['btnDescargar'])) {
    InformeProyectos::descarga($estProy);

} else if (isset($_POST['sltEstProy'])) {
    InformeProyectos::busqueda($estProy);
} else {
    $selectEstProyect = InformeProyectos::selectEstProy();

} 

?>