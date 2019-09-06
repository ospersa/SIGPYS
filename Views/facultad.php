<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_facultad.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busFacultad.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busFacultad.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>FACULTAD</h4>
    <div class="row">
        <form action="../Controllers/ctrl_facultad.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <?php echo $selectEntidad;?>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomFacultad" name="txtNomFacultad" type="text" class="validate" placeholder="Ingrese el nombre de la facultad" required>
                    <label for="txtNomFacultad">Nombre de la facultad</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarFacultad">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalFacultad" class="modal">
    <?php
        require('modalFacultad.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');