<div class="modal-content center-align">
<?php 
require_once('../Controllers/ctrl_missolicitudes.php');
include_once('../Controllers/ctrl_regtime.php');
include_once('../Models/mdl_tiempos.php');

if ($prep == "GEN") {
    require('modalResultadoGeneral.php');
} else if ($prep == "TIE") {
    require('modalTiempos.php');
} else if ($prep == "SOP"){
    require('modalResultado.php');
} else if ($prep == "DIS"){
    require('modalResultadoDiseno.php');
} else if ($prep == "REA"){
    require('modalResultadoRealizacion.php');
}
?>
</div>