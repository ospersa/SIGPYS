<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busFase.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_fase.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>FASES</h4>
    <div class="row">
        <form action="../Controllers/ctrl_fase.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomFase" name="txtNomFase" type="text" class="validate" required>
                    <label for="txtNomFase">Nombre de la fase</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea id="txtDescFase" name="txtDescFase" class="materialize-textarea"></textarea>
                    <label for="txtDescFase">Descripción de la fase</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarFase">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>
</div>

<!-- Modal Structure -->
<div id="modalFase" class="modal">
    <?php
        require('modalFase.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');
?>