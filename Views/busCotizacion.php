<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_cotizacion.php');
?>
<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" autocomplete="off" data-url="../Controllers/ctrl_cotizacion.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_cotizacion.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>CONSULTA COTIZACIONES</h4>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12">
    <?php
        if ($cotizaciones == null){
            $cotizaciones = Cotizacion::listarCotizaciones();
            echo $cotizaciones;    
        } else {
            echo $cotizaciones;
        }
        ?>
    </div>
</div>

<?php
require('../Estructure/footer.php');
?>