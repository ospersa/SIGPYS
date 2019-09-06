<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" autocomplete="off" data-url="../Controllers/ctrl_busPeriodo.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busPeriodo.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PERIODOS</h4>
    <div class="row">
        <form id="frmPeriodos" action="../Controllers/ctrl_periodo.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtFechaInicial" name="txtFechaInicial" type="text" class="datepicker" placeholder="Seleccione la fecha inicial del periodo a registrar">
                    <label for="txtFechaInicial">Fecha Inicial</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtFechaFinal" name="txtFechaFinal" type="text" class="datepicker" placeholder="Seleccione la fecha final del periodo a registrar">
                    <label for="txtFechaFinal">Fecha Final</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtDiasSeg1" name="txtDiasSeg1" type="number" class="materialize-textarea validate" oninput="cantHorasSeg1.value = txtDiasSeg1.value * 8" placeholder="Cantidad días laborales 1er segmento">
                    <label for="txtDiasSeg1">Días Segmento 1:</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input readonly id="cantHorasSeg1" name="cantHorasSeg1" type="number" class="materialize-textarea" value="0">
                    <label for="cantHorasSeg1" class="active">Equivalencia Horas Segmento 1:</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtDiasSeg2" name="txtDiasSeg2" type="number" class="materialize-textarea validate" oninput="cantHorasSeg2.value = txtDiasSeg2.value * 8" placeholder="Cantidad días laborales 2do segmento">
                    <label for="txtDiasSeg2">Días Segmento 2:</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input readonly id="cantHorasSeg2" name="cantHorasSeg2" type="number" class="materialize-textarea" value="0">
                    <label for="cantHorasSeg2">Equivalencia Horas Segmento 2:</label>
                </div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnGuardarPeriodo">Guardar</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalPeriodo" class="modal">
    <?php
        require('modalPeriodo.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>