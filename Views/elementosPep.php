<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_elementoPep.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_elementoPep.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>ELEMENTOS PEP</h4>
    <div class="row">
        <form action="../Controllers/ctrl_elementoPep.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtNomPep" name="txtNomPep" type="text" class="validate" required>
                <label for="txtNomPep">Nombre Elemento PEP*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtCodPep" name="txtCodPep" type="text" class="validate" required>
                <label for="txtCodPep">Código Elemento PEP*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php 
                include_once('../Models/mdl_elementoPep.php');
                ElementoPep::selectCeco(null);
                ?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnGuardarEpep">Guardar</button>
            </div>
        </form>
    </div>
    <div class="row">
        <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalElementosPep" class="modal">
    <?php
        require('modalElementosPep.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>