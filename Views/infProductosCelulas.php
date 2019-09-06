<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infProductosCelulas.php');
?>

<div id="container" class="center-align">
    <h4>INFORME PRODUCTO/SERVICIO - CÉLULAS</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infProductosCelulas.php" method="post">
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l1 offset-m1">
                    <?php echo $selectCelula;?>
                </div>
                <div class="input-field col l8 m8 s12" id="sltProyecto">
                    <?php echo $selectProyecto;?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                    <button class="btn waves-effect waves-light" type="button" name="btnBuscar" onclick="buscar('../Controllers/ctrl_infProductosCelulas.php');">Buscar</button>
                </div>
                <div class="input-field col l2 m2 s12">
                    <button class="btn waves-effect waves-light" type="submit" name="btnDescargar" id="btnDescargar">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" class="col l12 m12 s12"></div>
</div>

<?php
require('../Estructure/footer.php');
?>