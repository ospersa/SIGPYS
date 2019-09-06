<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_rol.php";
    
    if ($busqueda == null) {
        $resultado = Rol::busquedaTotal();
    } else {
        $resultado = Rol::busqueda($busqueda);
    }
    