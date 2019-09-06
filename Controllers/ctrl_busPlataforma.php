<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_plataforma.php";
    
    if ($busqueda == null) {
        $resultado = Plataforma::busquedaTotal();
    } else {
        $resultado = Plataforma::busqueda($busqueda);
    }
    