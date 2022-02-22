<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
}

require('../Estructure/header.php');
include_once('../Models/mdl_solicitudInicial.php');

$userName   = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar"
        data-url="../Controllers/ctrl_busSolInicialGest.php">
    <button id="btn-search" class="btn" onclick="busquedaSolIniGest('../Controllers/ctrl_busSolInicialGest.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>Solicitudes iniciales donde soy gestor</h4>
</div>

<div class="row mt-5">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12">
        <?php
            $precarga   =  solicitudInicial::loadInicialesGestor($userName);
        ?>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalSolicitudInicial" class="modal">
    <?php
        require('modalSolicitudInicial.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
