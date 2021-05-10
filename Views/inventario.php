<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Inventario de Productos</h4>
</div>
<?php
if ($perfil == 'PERF01' || $perfil == 'PERF02' ){
    echo '  <div class="row">
                <ul class="collapsible col l10 m10 s12 offset-l1 offset-m1">
                    <li>
                        <div class="collapsible-header teal-text">
                            <h6>Filtros de Búsqueda</h6>
                        </div>
                        <div class="collapsible-body pd-0">
                            <div class="row">
                                <form id="terminarSerPro">
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                                            <input type="text" name="txtBusquedaPersona" id="txtBusquedaPersona">
                                            <label for="txtBusquedaPersona">Buscar Persona</label>
                                        </div>
                                        <div class="input-field col l6 m6 s12 offset-l1 offset-m1" id="sltPersona">
                                            <select name="sltPersona" id="">
                                                <option value="">Seleccione</option>
                                            </select>
                                            <label for="sltPersona">Seleccione una persona</label>
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                                            <input type="text" name="txtBusquedaProyecto" id="txtBusquedaProyecto">
                                            <label for="txtBusquedaProyecto">Buscar Proyecto</label>
                                        </div>
                                        <div class="input-field col l6 m6 s12 offset-l1 offset-m1" id="sltProyecto">
                                            <select name="sltProyecto" id="">
                                                <option value="">Seleccione</option>
                                            </select>
                                            <label for="sltProyecto">Seleccione un Proyecto</label>
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                                            <input type="text" name="txtBusquedaEquipo" id="txtBusquedaEquipo">
                                            <label for="txtBusquedaEquipo">Buscar Equipo</label>
                                        </div>
                                        <div class="input-field col l6 m6 s12 offset-l1 offset-m1" id="sltEquipo">
                                            <select name="sltEquipo" id="">
                                                <option value="">Seleccione</option>
                                            </select>
                                            <label for="sltEquipo">Seleccione un equipo</label>
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                                            <input type="text" name="txtBusquedaProducto" id="txtBusquedaProducto">
                                            <label for="txtBusquedaProducto">Buscar Producto</label>
                                        </div>
                                        <div class="input-field col l6 m6 s12 offset-l1 offset-m1" id="sltProducto">
                                            <select name="sltProducto" id="">
                                                <option value="">Seleccione</option>
                                            </select>
                                            <label for="sltProducto">Seleccione un Producto</label>
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                                            <select name="sltEstado" id="">
                                                <option value="All" selected>Seleccione</option>
                                                <option value="Proceso de inventario">Proceso de inventario</option>
                                                <option value="Sin inventario">Sin inventario</option>
                                                <option value="Terminado">Terminado</option>
                                            </select>
                                            <label for="sltEstado">Seleccione un Estado</label>
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
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
                            </div>
                        </div>
                    </li>
                </ul>
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
    <div class="modal-content center-align">
    <?php
        require('modalInventario.php');
    ?>
    </div>
</div>

<?php
require('../Estructure/footer.php');
?>