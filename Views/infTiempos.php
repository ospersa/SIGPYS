<?php
require('../Estructure/header.php');
?>

<div id="container" class="center-align">
    <h4>INFORME DE TIEMPOS - PRODUCTO/SERVICIO</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infTiempos.php" method="POST">
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input type="text" name="txtFechIni" id="txtFechIni" class="datepicker" placeholder="aaaa/mm/dd" required>
                    <label for="txtFechIni">Fecha inicial</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input type="text" name="txtFechFin" id="txtFechFin" class="datepicker" placeholder="aaaa/mm/dd" required>
                    <label for="txtFechFin">Fecha final</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                    <button class="btn waves-effect waves-light" type="button" name="btnBuscar" onclick="buscar('../Controllers/ctrl_infTiempos.php');">Buscar</button>
                </div>
                <div class="input-field col l2 m2 s12">
                    <button class="btn waves-effect waves-light" type="submit" name="btnDescargar" id="btnDescargar">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="div_dinamico"></div>

<?php
require('../Estructure/footer.php');
?>