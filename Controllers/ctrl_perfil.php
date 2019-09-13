<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_perfil.php";

/* Inicialización variables*/
$nomPerf    = (isset($_POST["txtnomPerf"])) ? $_POST["txtnomPerf"] : null;
$descPerf   = (isset($_POST["txtdescPerf"])) ? $_POST["txtdescPerf"] :null;
$search     = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;

/* Procesamiento peticiones al controlador */

if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Perfiles::busquedaTotal() : Perfiles::busqueda($search);
} else if ($nomPerf == null) {
    echo '<script type="text/javascript">
        alert("Disculpe, no se guardó la información, existe algún campo vacío.");
    </script>';
    echo '<meta http-equiv="Refresh" content="0;url=../Views/perfil.php">';
} else {
    $resultado = Perfiles::registrarPerfil($nomPerf, $descPerf);
}

?>