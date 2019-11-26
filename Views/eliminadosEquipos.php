<?php
require('../Estructure/header.php');
?>
<div id="content" class="center-align">
    <h4>EQUIPOS ELIMINADOS</h4>
</div>
<div class="row">
    <div class="col l10 m10 s12 offset-l1 offset-m1">
        <?php
            require('../Controllers/ctrl_eliminadosEquipos.php');
        ?>
    </div>
</div>
<?php
require('../Estructure/footer.php');
?>
