
<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_convocatoria.php";
    if ($busqueda === null) {
        $resultado = Convocatoria::busquedaTotal();
    } else {
        $resultado = Convocatoria::busqueda($busqueda);
    }
    