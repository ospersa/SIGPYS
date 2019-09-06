<?php
$persona = (isset($_POST['persona'])) ? $_POST['persona'] : null;
$periodo = (isset($_POST['periodo'])) ? $_POST['periodo'] : null;

include_once "../Models/mdl_planeacion.php";

if ($persona != null && $periodo != null){
    $resultado = Planeacion::mostrarInfoPeriodo($periodo, $persona);
    $resultado = Planeacion::verificarInfo($periodo, $persona);
    if ($resultado == true) {
        $resultado = Planeacion::listarProyectos2($periodo, $persona);
    } else {
        $resultado = Planeacion::listarProyectos($periodo, $persona);
    }
}

?>