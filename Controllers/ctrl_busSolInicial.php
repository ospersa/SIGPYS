<?php
require('../Models/mdl_solicitudInicial.php');
$busqueda = $_POST['txt-search'];
if ($busqueda == null) {
    SolicitudInicial::busquedaTotalIniciales();
} else {
    SolicitudInicial::busquedaIniciales($busqueda);
}

?>