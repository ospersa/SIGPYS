<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_subidaPyS.php');
$archivo = $_FILES["archivo"];
$resultado = move_uploaded_file($archivo["tmp_name"], $archivo["name"]);
        if ($resultado) {
            echo "Subido con éxito";
            SubidaPyS::leerArchivo();
        } else {
            echo "Error al subir archivo";
        }
?>