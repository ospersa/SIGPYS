<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Inventario de Productos</h4>
</div>
<?php
if ($perfil == 'PERF01' || $perfil == 'PERF02' ){
    echo '<div class="row">
    <form id="terminarSerPro">
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input type="text" name="txtBusquedaPersona" id="txtBusquedaPersona">
                <label for="txtBusquedaPersona">Buscar Persona</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1" id="sltPersona">
                <select name="sltPersona" id="">
                    <option value="">Seleccione</option>
                </select>
                <label for="sltPersona">Seleccione un persona</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input type="text" name="txtBusquedaProyecto" id="txtBusquedaProyecto">
                <label for="txtBusquedaProyecto">Buscar Proyecto</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1" id="sltProyecto">
                <select name="sltProyecto" id="">
                    <option value="">Seleccione</option>
                </select>
                <label for="sltProyecto">Seleccione un Proyecto</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input type="text" name="txtBusquedaEquipo" id="txtBusquedaEquipo">
                <label for="txtBusquedaEquipo">Buscar Equipo</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1" id="sltEquipo">
                <select name="sltEquipo" id="">
                    <option value="">Seleccione</option>
                </select>
                <label for="sltEquipo">Seleccione un equipo</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input type="text" name="txtBusquedaProducto" id="txtBusquedaProducto">
                <label for="txtBusquedaProducto">Buscar producto</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1" id="sltProducto">
                <select name="sltProducto" id="">
                    <option value="">Seleccione</option>
                </select>
                <label for="sltProducto">Seleccione un Prodcuto</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l8 m8 s12 offset-l2 offset-m2">
                <input type="text" id="txtDescrip" name="txtDescrip">
                <label for="txtDescrip">Buscar por descripción del producto</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l5 offset-m5">
            <button class="btn waves-effect waves-light " type ="button"
                onclick="busquedaMultiple(\'../Controllers/ctrl_inventario.php\');"
                name="btnBuscar">Buscar</button>
        </div>
    </form>

</div>';
}
?>
<div class="row">
    <div id="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
        <?php
            include_once('../Controllers/ctrl_inventario.php');
        ?>
    </div>
</div>
<!-- Modal Structure -->
<div id="modalInventario" class="modal">
    <?php
        require('modalInventario.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>