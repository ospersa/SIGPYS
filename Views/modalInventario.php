<div class="modal-content center-align">
<?php 
include_once('../Controllers/ctrl_inventario.php');
if ($prep == 'ASI'){
    require('modalInventarioAsignados.php');
} else if ($prep == 'INF'){
    require('modalInventarioEntrega.php');   
}
?>
</div>