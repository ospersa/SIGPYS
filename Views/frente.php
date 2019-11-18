<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_frente.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_frente.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_frente.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>FRENTES</h4>
    <div class="row">
        <form action="../Controllers/ctrl_frente.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomFrente" name="txtNomFrente" type="text" class="validate" required placeholder="Ingrese el nombre del frente">
                    <label for="txtNomFrente">Nombre del frente</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea id="txtDescFrente" name="txtDescFrente" class="materialize-textarea" placeholder="Ingrese la descripción del frente"></textarea>
                    <label for="txtDescFrente">Descripción del frente</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <?php echo $selectCoorFrente;?>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarFrente">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalFrente" class="modal">
    <?php
        require('modalFrente.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');
?>