
<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_fase.php";
    if ($busqueda === null) {
        $resultado = Fase::busquedaTotal();
    } else {
        $resultado = Fase::busqueda($busqueda);
    }
    