<?php
include_once "../Models/mdl_usuario.php";
if (isset($_POST['txt-search'])) {
    $busqueda = $_POST['txt-search'];
    if ($busqueda == null) {
        Usuario::busquedaTotal();
    } else {
        Usuario::busqueda($busqueda);
    }
}
?>