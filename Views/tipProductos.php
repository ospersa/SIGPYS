<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_productos.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_productos.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_productos.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>TIPO DE PRODUCTO</h4>
    <form action="../Controllers/ctrl_productos.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectEquipo;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3" id="sltServicioLoad">
                <?php echo $selectServicio;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3" id="sltClaseLoad">
                <?php echo $selectClase;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtNombreTipo" id="txtNombreTipo" placeholder="Ingrese el nombre del tipo de producto a registrar" required>
                <label for="txtNombreTipo">Nombre tipo producto*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtDescripcionTipo" id="txtDescripcionTipo" placeholder="Ingrese la descripción del tipo de producto a registrar" class="materialize-textarea"></textarea>
                <label for="txtDescripcionTipo">Descripción</label>
            </div>
            <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                <input type="number" name="txtCostoSin" id="txtCostoSin" placeholder="Campo numérico">
                <label for="txtCostoSin">Costo sin $</label>
            </div>
            <div class="input-field col l2 m2 s12">
                <input type="number" name="txtCostoCon" id="txtCostoCon" placeholder="Campo numérico">
                <label for="txtCostoCon">Costo con $</label>
            </div>
            <div class="input-field col l2 m2 s12">
                <input type="number" name="txtCostoTipo" id="txtCostoTipo" placeholder="Campo numérico">
                <label for="txtCostoTipo">Costo tipo $</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarTip">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
</div>

<!-- Modal Structure -->
<div id="modalTipProducto" class="modal">
    <?php
        require('modalTipProducto.php');
    ?>
</div>

<?php 
require('../Estructure/footer.php');
?>