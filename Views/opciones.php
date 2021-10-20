<?php  
require('../Estructure/header.php');
require('../Controllers/ctrl_opciones.php'); 
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_opciones.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_opciones.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>Opciones del sistema</h4>
    <form action="../Controllers/ctrl_opciones.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtOptionName" name="txtOptionName" type="text" class="validate" placeholder="Ingrese el nombre de la opción a registrar" required>
                <label for="txtOptionName">Nombre</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtOptionDescription" name="txtOptionDescription" type="text" class="validate" placeholder="Ingrese la descripción de la opción a registrar" required>
                <label for="txtOptionDescription">Descripción</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <input id="txtOptionValue" name="txtOptionValue" type="text" class="validate" placeholder="Ingrese el valor de la opción a registrar" required>
                <label for="txtOptionValue">Valor</label>
            </div>      
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarOpcion">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
    </div>
</div>

<!-- Modal Structure -->
<div id="modalOpciones" class="modal">
    <?php
    require('modalOpciones.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>