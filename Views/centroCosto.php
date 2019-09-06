<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_centroCosto.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_centroCosto.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>CENTROS DE COSTOS</h4>
    <div class="row">
        <form action="../Controllers/ctrl_centroCosto.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtCodCeco" name="txtCodCeco" type="text" class="validate" required>
                <label for="txtCodCeco">Código Centro de Costos*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtNomCeco" name="txtNomCeco" type="text" class="validate" required>
                <label for="txtNomCeco">Nombre Centro de Costos*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="action">Guardar</button>
            </div>
        </form>
    </div>
    <div class="row">
        <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalCentroCosto" class="modal">
    <?php
        require('modalCentroCosto.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>