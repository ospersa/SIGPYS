<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_salario.php')
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_salario.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_salario.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>SALARIOS</h4>
    <div class="row">
        <form action="../Controllers/ctrl_salario.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <?php echo $selectPersona;?>
                </div><!-- /input -->
                <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                    <input required id="txtSalario" name="txtSalario" type="number">
                    <label for="txtSalario">Valor salario</label>
                </div><!-- /input -->
                <div class="input-field col l2 m2 s12">
                    <input required id="txtVigIni" name="txtVigIni" type="text" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtVigIni">Vigente desde</label>
                </div><!-- /input -->
                <div class="input-field col l2 m2 s12">
                    <input required id="txtVigFin" name="txtVigFin" type="text" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtVigFin">Vigente hasta</label>
                </div><!-- /input -->
            </div><!-- /Row inside-->
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarSalario">Guardar</button>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalSalario" class="modal">
    <?php
        require('modalSalario.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>