<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_rol.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busRol.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_rol.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>ROLES</h4>
    <div class="row">
        <form action="../Controllers/ctrl_rol.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <?php echo $selectTipRol;?>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomRol" name="txtNomRol" type="text" class="validate" placeholder="Ingrese el nombre del Rol a registrar" required>
                    <label for="txtNomRol">Nombre del rol</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtDescRol" name="txtDescRol" type="text" class="validate" placeholder="Ingrese la descripción del Rol a registrar">
                    <label for="txtDescRol">Descripción del rol</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarRol">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalRol" class="modal">
    <?php
        require('modalRol.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');