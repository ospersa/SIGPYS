<?php 
include_once('../Controllers/ctrl_resultadoProductoDis.php');
if ($prep == "COM") {
    require('modalResulProTerCompleto.php');
} else if ($prep == "SOP"){
    require('modalResultadoSoporte.php');
} else if ($prep == "DIS"){
    require('modalResultadoDiseno.php');
} else if ($prep == "REA"){
    require('modalResultadoRealizacion.php');
}
?>