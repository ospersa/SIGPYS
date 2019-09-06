<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_frente.php";
    
    if ($busqueda === null) {
        $resultado = Frente::busquedaTotal();
    } else {
        $resultado = Frente::busqueda($busqueda);
    }
    