
<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_cargo.php";
    if ($busqueda === null) {
        $resultado = Cargo::busquedaTotal();
    } else {
        $resultado = Cargo::busqueda($busqueda);
    }
    