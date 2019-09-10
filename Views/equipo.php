<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busEquipo.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busEquipo.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>EQUIPOS</h4>

    <div class="row">
        <form action="../Controllers/ctrl_equipo.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomEquipo" name="txtNomEquipo" type="text" class="validate" required>
                    <label for="txtNomEquipo">Nombre del equipo</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea id="txtDescEquipo" name="txtDescEquipo" class="materialize-textarea"></textarea>
                    <label for="txtDescEquipo">Descripción del equipo</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarEqu">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>
</div>

<!-- Modal Structure -->
<div id="modalEquipo" class="modal">
    <?php
        require('modalEquipo.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');