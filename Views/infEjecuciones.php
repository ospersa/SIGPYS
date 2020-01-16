<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>INFORME EJECUCIONES</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infEjecuciones.php" method="post" id="form">
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input type="text" name="txtFechIni" id="txtFechIni" class="datepicker" placeholder="aaaa-mm-dd"
                        required>
                    <label for="txtFechIni">Fecha inicial</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input type="text" name="txtFechFin" id="txtFechFin" class="datepicker" placeholder="aaaa-mm-dd"
                        required>
                    <label for="txtFechFin">Fecha final</label>
                </div>
                <div class="input-field col l2 m12 s12 offset-m5 offset-l5">
                    <input id="diasLab" name="diasLab" type="number" value="" min="0" >
                    <label class="active" for="diasLab">Días laborales del mes</label>
                </div>
                <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                    <button class="btn waves-effect waves-light " type="button"
                        onclick="buscar('../Controllers/ctrl_infEjecuciones.php')" name="btnBuscar">Buscar</button>
                </div>
                <div class="input-field col l2 m2 s12 ">
                    <button class="btn waves-effect waves-light " type="submit" name="btnDescargar">Descargar</button>
                </div>
        </form>
    </div>
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>


</div>

<?php
require('../Estructure/footer.php');
?>