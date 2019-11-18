<?php
require('../Estructure/header.php');
include_once('../Models/mdl_proyecto.php');
include_once('../Models/mdl_usuario.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_proyecto.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_proyecto.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PROYECTO</h4>
    <form action="../Controllers/ctrl_proyecto.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Usuario::selectEntidad(null);?>
            </div>
            <div id="sltFacultad" class="input-field col l6 m6 s12 offset-l3 offset-m3">
            </div>
            <div id="sltDepartamento" class="input-field col l6 m6 s12 offset-l3 offset-m3">
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Proyecto::selectFinancia(null);?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Usuario::selectTipo(null, "Proyecto*");?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php $frente = Proyecto::selectFrente(null);?>
            </div>
            <div id="divInfo" class="">
            </div>
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <!--<input readonly name="txtEstProy" id="txtEstProy" type="text" value="Abierto">
                <label for="txtEstProy">Estado del Proyecto</label>-->
                <?php Proyecto::selectEstado(null);?>
            </div>
            <div class="input-field col l3 m3 s12">
                <?php Proyecto::selectCelula(null);?>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php 
                    if ($frente != null) {
                        echo Proyecto::selectTipoProyecto(null, $frente);
                    }
                ?>
            </div>
            <div id="divInfo2" class="">
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarProy">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
</div>

<!-- Modal Structure -->
<div id="modalProyecto" class="modal">
    <?php
        require('modalProyecto.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>