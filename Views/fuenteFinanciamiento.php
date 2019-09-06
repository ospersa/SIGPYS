<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_fuenteFinanciamiento.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_fuenteFinanciamiento.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>FUENTES DE FINANCIACIÓN</h4>
    <div class="row">
        <form action="../Controllers/ctrl_fuenteFinanciamiento.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtFteFin" name="txtFteFin" type="text">
                <label for="txtFteFin">Nombre Fuente Financiación</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtSiglaFteFin" name="txtSiglaFteFin" type="text" required>
                <label for="txtSiglaFteFin">Sigla Fuente Financiación*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnGuardarFteFin">Guardar</button>
            </div>
        </form>
    </div>
    <div class="row">
        <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalFuenteFinanciamiento" class="modal">
    <?php
        require('modalFuenteFinanciamiento.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>