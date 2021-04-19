<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Terminación Productos/Servicios</h4>
</div>
<div class="row">
    <form id="terminarSerPro">
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input id="txtFechPre" name="txtFechPre" type="text" class="datepicker"
                    placeholder="Seleccione la fecha inicial del periodo a registrar">
                <label for="txtFechPre">Fecha prevista entrega</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input type="text" name="txtBusquedaProyUsu" id="txtBusquedaProyUsu">
                <label for="txtBusquedaProyUsu">Buscar Cód. Proyecto Conecta-TE*</label>
            </div>
            <div class="input-field col l4 m4 s12 offset-l1 offset-m1" id="sltProyectoUsu">
                <select name="sltProyectoUsu" id="">
                    <option value="">Seleccione</option>
                </select>
                <label for="sltProyectoUsu">Seleccione un proyecto</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l5 offset-m5">
            <button class="btn waves-effect waves-light " type ="button"
                onclick="busquedaMultiple('../Controllers/ctrl_terminacionProductoServicio.php');"
                name="btnBuscar">Buscar</button>
        </div>
    </form>
</div>
<div class="row">
    <div id="div_dinamico" class="col l12 m12 s12">
        <?php
            include_once('../Controllers/ctrl_terminacionProductoServicio.php');
        ?>
    </div>
</div>
<!-- Modal Structure -->
<div id="modalTerminarProSer" class="modal">
    <?php
        require('modalTerminarProSer.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>