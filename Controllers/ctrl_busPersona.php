<?php
       
    include_once "../Models/mdl_personas.php";
    
    if (isset($_POST["sltPeriodo"])) {
        $busqueda = $_REQUEST["sltPeriodo"];
        $resultado = Personas::validarDatos($busqueda);
        if ($resultado) {
            $resultado = Personas::completarInfo($busqueda);
        } else {
            $resultado = Personas::listPersonas($busqueda);
        }
    } else if (isset($_POST["sltPeriodo2"])) {
        $busqueda = $_REQUEST["sltPeriodo2"];
        $resultado = Personas::selectPersonas($busqueda);
    } else if (isset($_POST["sltPeriodoPlan"])) {
        $busqueda = $_REQUEST["sltPeriodoPlan"];
        $resultado = Personas::selectPersonasPlaneacion($busqueda);
    }

    