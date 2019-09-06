<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busPlataforma.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busPlataforma.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PLATAFORMAS</h4>
    <div class="row">
        <form action="../Controllers/ctrl_plataforma.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomPlataforma" name="txtNomPlataforma" type="text" class="validate" required>
                    <label for="txtNomPlataforma">Nombre de la plataforma</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarPlataforma">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalPlataforma" class="modal">
    <?php
        require('modalPlataforma.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');