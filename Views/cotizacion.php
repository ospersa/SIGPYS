<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" autocomplete="off" data-url="../Controllers/ctrl_cotizacion.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_cotizacion.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PRESUPUESTO</h4>
    <div class="row">
        <form action="#">
            <?php
            include_once('../Controllers/ctrl_cotizacion.php');
            ?>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<?php
require('../Estructure/footer.php');
?>