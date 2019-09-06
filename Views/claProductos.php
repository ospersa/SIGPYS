<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_productos.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busClaProductos.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busClaProductos.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>CLASE DE PRODUCTO</h4>
    <form action="../Controllers/ctrl_productos.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectEquipo;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3" id="sltServicioLoad">
                <?php echo $selectServicio;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtNombreClase" id="txtNombreClase" placeholder="Ingrese el nombre de la clase de producto a registrar" required>
                <label for="txtNombreClase">Nombre clase de producto*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtNombreCorClase" id="txtNombreCorClase" placeholder="Ingrese el nombre corto de la clase de producto a registrar">
                <label for="txtNombreCorClase">Nombre corto clase de producto</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtDescripcionClase" id="txtDescripcionClase" placeholder="Ingrese la descripción de la clase de producto a registrar" class="materialize-textarea"></textarea>
                <label for="txtDescripcionClase">Descripción</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtCostoClase" id="txtCostoClase" placeholder="Ingrese el nombre corto de la clase de producto a registrar">
                <label for="txtCostoClase">Costo clase $</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarCla">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
</div>

<!-- Modal Structure -->
<div id="modalClaProducto" class="modal">
    <?php
        require('modalClaProducto.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>