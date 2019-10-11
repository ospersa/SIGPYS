<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_asignados.php');
?>

<!-- Modal Structure -->
<div id="modalAsignados" class="modal">
    <?php
        require('modalAsignados.php');
    ?>
</div>
<div id="modalCorreoAsignacion" class="modal">
    <?php
        require('modalCorreoAsignacion.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>