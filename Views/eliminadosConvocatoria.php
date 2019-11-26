<?php
require('../Estructure/header.php');
?>
<div id="content" class="center-align">
    <h4>CONVOCATORIAS ELIMINADAS</h4>
</div>
<div class="row">
    <div class="col l10 m10 s12 offset-l1 offset-m1">
        <?php
            require('../Controllers/ctrl_eliminadosConvocatoria.php');
        ?>
    </div>
</div>
<?php
require('../Estructure/footer.php');
?>