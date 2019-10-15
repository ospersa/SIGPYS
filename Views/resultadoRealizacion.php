<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Resultado de Producto de Realización</h4>
</div>
<div class="row">
    <div class="col l10 m10 s12 offset-l1 offset-m1">
        <p class="left-align teal-text">
            Para consultar solicitudes de producto, ingrese el código del producto, código o nombre de
            proyecto Conecta-TE.
        </p>
        <nav>
            <div class="nav-wrapper teal lighten-4">
                <div class="input-field">
                    <input id="txt-search" name="txt-search" type="search"
                        data-url="../Controllers/ctrl_resultadoProductoRea.php">
                    <label class="label-icon" for="txt-search"><i class="material-icons">search</i></label>
                    <i class="material-icons ico-clean">close</i>
                </div>
            </div>
        </nav>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
        <?php
            include_once('../Controllers/ctrl_resultadoProductoRea.php');
        ?>
    </div>
</div>
<!-- Modal Structure -->
<div id="modalResulProTer" class="modal">
    <?php
       require('modalResulProTer.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>
