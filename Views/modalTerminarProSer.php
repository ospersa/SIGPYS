<div class="modal-content center-align">
<?php 
include_once('../Controllers/ctrl_terminacionProductoServicio.php');

if ($prep == "TER") {
    require('modalTerminarEnviarProSer.php');
} else if ($prep == "INF") {
    require('modalInformacionTerminar.php');
} 
?>
</div>