<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_perfil.php";

/* Inicialización variables*/
$nomPerf=$_POST["txtnomPerf"];
$descPerf=$_POST["txtdescPerf"];

/* Procesamiento peticiones al controlador */
if ($nomPerf == null) {
    echo '<script type="text/javascript">
        alert("Disculpe, no se guardó la información, existe algún campo vacío.");
    </script>';
    echo '<meta http-equiv="Refresh" content="0;url=../Views/perfil.php">';
} else {
    $resultado = Perfiles::registrarPerfil($nomPerf, $descPerf);
}

?>