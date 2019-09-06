
<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_entidad.php";
    if ($busqueda === null) {
        $resultado = Entidad::busquedaTotal();
    } else {
        $resultado = Entidad::busqueda($busqueda);
    }
    