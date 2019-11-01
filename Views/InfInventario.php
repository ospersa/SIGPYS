<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_infInventario.php');
?>

<div id="content" class="center-align">
    <h4>INFORME INVENTARIO</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infInventario.php" method="post" id="terminarSerPro">
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input type="text" name="txtBusquedaProyecto" id="txtBusquedaProyecto">
                    <label for="txtBusquedaProyecto">Buscar Proyecto</label>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1" id="sltProyecto">
                    <select name="sltProyecto" id="">
                        <option value="">Seleccione</option>
                    </select>
                    <label for="sltProyecto">Seleccione un Proyecto</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input type="text" name="txtBusquedaPersona" id="txtBusquedaPersona">
                    <label for="txtBusquedaPersona">Buscar Persona</label>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1" id="sltPersona">
                    <select name="sltPersona" id="">
                        <option value="">Seleccione</option>
                    </select>
                    <label for="sltPersona">Seleccione un persona</label>
                </div>
            </div>
            <div class="input-field col l2 m2 s12 offset-l2 offset-m2">
                <input type="text" name="fechIni" id="fechIni" class="datepicker">
                <label for="fechIni">Fecha Inicial </label>
            </div>
            <div class="input-field col l2 m2 s12 offset-l1 offset-m1">
                <input type="text" name="fechFin" id="fechFin" class="datepicker">
                <label for="fechFin">Fecha Final </label>
            </div>
            <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                <?php echo $selectEstado;?>
            </div>
            <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                <button class="btn waves-effect waves-light " type="button"
                    onclick="buscar('../Controllers/ctrl_infInventario.php')" name="btnBuscar">Buscar</button>
            </div>
            <div class="input-field col l2 m2 s12 ">
                <button class="btn waves-effect waves-light " type="submit" name="btnDescargar">Descargar</button>
            </div>
        </form>
    </div>
    <div class="content">
        <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>


    </div>

</div>

<?php
require('../Estructure/footer.php');
?>