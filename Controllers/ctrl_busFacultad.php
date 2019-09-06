<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_facultad.php";
    
    if ($busqueda == null) {
        $resultado = Facultad::busquedaTotal();
    } else {
        $resultado = Facultad::busqueda($busqueda);
    }
    