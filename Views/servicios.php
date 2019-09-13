<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_productos.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_servicios.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_servicios.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>SERVICIO</h4>
    <form action="../Controllers/ctrl_servicios.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectEquipo;?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtNombreServicio" id="txtNombreServicio" placeholder="Ingrese el nombre del servicio a registrar" required>
                <label for="txtNombreServicio">Nombre de servicio*</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input type="text" name="txtNombreCortoServicio" id="txtNombreCortoServicio" placeholder="Ingrese el nombre corto del servicio a registrar">
                <label for="txtNombreCortoServicio">Nombre corto</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtDescripcionServicio" id="txtDescripcionServicio" class="materialize-textarea" placeholder="Ingrese la descripción del servicio a registrar"></textarea>
                <label for="txtDescripcionServicio">Descripción del servicio</label>
            </div>
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <select name="sltProducto" id="sltProducto" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
                <label for="sltProducto">Genera producto?*</label>
            </div>
            <div class="input-field col l3 m3 s12">
                <input type="number" name="txtCostoServicio" id="txtCostoServicio" onchange="format('#txtCostoServicio');" placeholder="$" min="0">
                <label for="txtCostoServicio">Costo servicio $</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrar">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalServicio" class="modal">
    <?php
        require('modalServicio.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');
?>