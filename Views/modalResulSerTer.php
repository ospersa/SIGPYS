<div class="modal-content center-align">
<?php 
include_once('../Controllers/ctrl_resultadoServicio.php');
if ($prepServicio == 'COM'){
    require('modalResulSerTerCompleto.php');
} else{
    require('modalResultadoGeneral.php');   
}
?>
</div>