<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_infSemaforo.php');


if (isset($_POST['btnDescargar'])) {
    InformeSemaforo::descarga();
}

?>