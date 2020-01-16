<?php
require('../Models/mdl_cotizacion.php');
if (isset($_POST['txt-search'])) {
    $busqueda = $_POST['txt-search'];
    if ($busqueda == null) {
        echo "<script>alert('Por favor no deje el campo de busqueda vacío.')</script>";
        echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; // Retornamos a la página anterior
    } else {
        $resultado = Cotizacion::busquedaPendientes($busqueda);
    }
}
?>