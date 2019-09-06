<?php
require('../Estructure/header.php');
include_once('../Models/mdl_proyecto.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busProyTipo.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busProyTipo.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>TIPO DE PROYECTO</h4>
    <form action="../Controllers/ctrl_proyecto.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Proyecto::selectFrente(null);?>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input required name="txtNomTipoProy" id="txtNomTipoProy" type="text" placeholder="Campo obligatorio" value="">
                <label for="txtNomTipoProy">Nombre tipo de proyecto*</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarTip">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalTipoProy" class="modal">
    <?php
        require('modalTipoProy.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>