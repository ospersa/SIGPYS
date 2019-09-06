<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_equipo.php";
    
    if ($busqueda === null) {
        $resultado = Equipo::busquedaTotal();
    } else {
        $resultado = Equipo::busqueda($busqueda);
    }
    