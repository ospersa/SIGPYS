<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar por estado" data-url="../Controllers/ctrl_busProyEstado.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busProyEstado.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>ESTADO DEL PROYECTO</h4>
    <form action="../Controllers/ctrl_proyecto.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input required name="txtNomEst" id="txtNomEst" type="text" placeholder="Campo obligatorio" value="">
                <label for="txtNomEst">Nombre estado del proyecto*</label>
            </div>
        <div class="row">
        </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtDescEst" id="txtDescEst" cols="30" rows="10" class="materialize-textarea"></textarea>
                <label for="txtDescEst">Descripción</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarEst">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<?php
require('../Estructure/footer.php');
?>