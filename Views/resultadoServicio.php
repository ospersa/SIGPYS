<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Resultado de Servicio</h4>
</div>
<div class="row">
    <form id="">
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <input id="txt-search" name="txt-search" type="text" >
                <label for="txt-search">Busqueda</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l5 offset-m5">
            <button class="btn waves-effect waves-light " type ="button"
                onclick="busqueda('../Controllers/ctrl_resultadoServicio.php');"
                name="btnBuscar">Buscar</button>
        </div>
    </form>
</div>
<div class="row">
    <div id="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
        <?php
            include_once('../Controllers/ctrl_resultadoServicio.php');
        ?>
    </div>
</div>
<!-- Modal Structure -->
<div id="modalResulSerTer" class="modal">
    <?php
       require('modalResulSerTer.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>