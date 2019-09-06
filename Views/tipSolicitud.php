<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar por tipo" data-url="../Controllers/ctrl_busSolTipo.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busSolTipo.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>TIPO DE SOLICITUDES</h4>
    <form action="../Controllers/ctrl_solicitud.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input required name="txtNomTip" id="txtNomTip" type="text" placeholder="Campo obligatorio" value="">
                <label for="txtNomTip">Nombre tipo de solicitud*</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtDescTip" id="txtDescTip" class="materialize-textarea"></textarea>
                <label for="txtDescTip" class="active">Descripción tipo de solicitud</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarTip">Registrar</button>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalTipSol" class="modal">
    <?php
        require('modalTipSol.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>