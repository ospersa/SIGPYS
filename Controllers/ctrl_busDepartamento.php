
<?php
    $busqueda = $_POST["txt-search"];
  
    include_once "../Models/mdl_departamento.php";
    if ($busqueda === null) {
        $resultado = Departamento::busquedaTotal();
    } else {
        $resultado = Departamento::busqueda($busqueda);
    }
    