<?php
require('../Estructure/header.php');
include_once('../Models/mdl_solicitudInicial.php');
$precarga = SolicitudInicial::preLoadSolicitudInicial();
$selectSolicitante = SolicitudInicial::selectSolicitante(null);
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busSolInicial.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_busSolInicial.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>SOLICITUD INICIAL</h4>
    <div class="row">
        <form action="../Controllers/ctrl_solicitudInicial.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                    <input readonly type="text" name="txtIdSol" id="txtIdSol" value="<?php echo $precarga['idSolicitud'];?>" >
                    <label for="txtIdSol" class="active">Cod. Solicitud inicial</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input type="hidden" name="txtIdTipSol" value="<?php echo $precarga['idTipo'];?>">
                    <input readonly type="text" name="txtTipoSol" id="txtTipoSol" value="<?php echo $precarga['nombreTipo'];?>" >
                    <label for="txtTipoSol" class="active">Tipo solicitud</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input type="hidden" name="txtIdEstSol" value="<?php echo $precarga['idEstado'];?>">
                    <input readonly type="text" name="txtEstadoSol" id="txtEstadoSol" value="<?php echo $precarga['nombreEstado'];?>" >
                    <label for="txtEstadoSol">Estado solicitud</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                    <input type="text" name="txtBusquedaProy" id="txtBusquedaProy" required>
                    <label for="txtBusquedaProy">Buscar Cód. Proyecto Conecta-TE*</label>
                </div>
                <div class="input-field col l4 m4 s12" id="sltProyecto">
                    <select name="sltProyecto" id="">
                        <option value="sltProyecto">Seleccione</option>
                    </select>
                    <label for="sltProyecto">Seleccione un proyecto</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                    <input type="text" class="datepicker" name="txtFechaPrevista" id="txtFechaPrevista" placeholder="dd/mm/aaaa">
                    <label for="txtFechaPrevista">Fecha prevista entrega:</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <?php echo $selectSolicitante;?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea name="txtDescripcionSol" id="txtDescripcionSol" class="materialize-textarea"></textarea>
                    <label for="txtDescripcionSol">Descripción solicitud inicial</label>
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarSolIni">Registrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
</div>

<!-- Modal Structure -->
<div id="modalSolicitudInicial" class="modal">
    <?php
        require('modalSolicitudInicial.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>