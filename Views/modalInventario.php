<div class="modal-content center-align">
<?php 
include_once('../Controllers/ctrl_inventario.php');
if ($prep == 'ASI'){
    require('modalInventarioAsignados.php');
} else if ($prep == 'REA'){
    require('modalInventarioEntregaRealizacion.php');   
}else if ($prep == 'DIS'){
    require('modalInventarioEntregaDiseno.php');   
}else if ($prep == 'SOP'){
    require('modalInventarioEntregaSoporte.php');   
}
?>
</div>