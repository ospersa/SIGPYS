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
    require('modalResultadoSoporte.php');
} else if ($prep == "DIS"){
    echo "h2";
} else if ($prep == "REA"){
    echo "h3";
}
?>
</div>