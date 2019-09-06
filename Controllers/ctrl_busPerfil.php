<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_perfil.php";
    if ($busqueda == null) {
        $resultado = Perfiles::busquedaTotal();
    } else {
        $resultado = Perfiles::busqueda($busqueda);
    }
    