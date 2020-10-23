<?php
require('../Estructure/header.php');

//$idInicial = $_REQUEST['cod'];
$idInicial = (isset($_REQUEST['cod'])) ? $_REQUEST['cod'] : "";

if ($idInicial != "") {
    require_once('../Models/mdl_solicitudEspecifica.php');
    $precarga = SolicitudEspecifica::preLoadSolicitudEspecifica($idInicial);
    $selectEquipo = SolicitudEspecifica::selectEquipo(null);
    $cargaEspecificas = SolicitudEspecifica::cargaEspecificas($idInicial, 1);
    $string = ' <div class="row">
                    <form action="../Controllers/ctrl_solicitudEspecifica.php" method="post" autocomplete="off">
                        <input type="text" name="txtPresupuesto" id="txtPresupuesto" hidden>
                        <div class="row">
                            <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                                <input readonly type="text" name="txtIdSol" id="txtIdSol" value="'.$idInicial.'" >
                                <label for="txtIdSol" class="active">Código solicitud inicial</label>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <input hidden type="text" name="txtIdTipoSol" id="txtIdTipoSol" value="'.$precarga['idTipo'].'" >
                                <input readonly type="text" name="txtTipoSol" id="txtTipoSol" value="'.$precarga['nombreTipo'].'" >
                                <label for="txtTipoSol" class="active">Tipo de producto/servicio</label>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <input hidden type="text" name="txtIdEstadoSol" id="txtIdEstadoSol" value="'.$precarga['idEstado'].'" >
                                <input readonly type="text" name="txtEstadoSol" id="txtEstadoSol" value="'.$precarga['nombreEstado'].'" >
                                <label for="txtEstadoSol" class="active">Estado del producto/servicio</label>
                            </div>
                            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                <input hidden type="text" name="txtIdProy" id="txtIdProy" value="'.$precarga['idProy'].'">
                                <input type="text" id="txtProyecto" name="txtProyecto" value="'.$precarga['codProy'].' - '.$precarga['nombreProy'].'" readonly >
                                <label for="txtProyecto" class="active">Proyecto</label>
                            </div>
                            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                <ul class="collapsible">
                                    <li>
                                        <div class="collapsible-header"><i class="material-icons">assignment</i><strong>Observación solicitud inicial</strong></div>
                                        <div class="collapsible-body"><span><textarea class="materialize-textarea" readonly>'.$precarga['observacion'].'</textarea></span></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="input-field col l1 m1 s12 offset-l3 offset-m3">
                                <input type="number" min="0" name="txtHora" id="txtHora" placeholder="0" value="0">
                                <label for="txtHora">Horas</label>
                            </div>
                            <div class="input-field col l1 m1 s12">
                                <input type="number" max="59" min="0" name="txtMinuto" id="txtMinuto" placeholder="0" value="0">
                                <label for="txtMinuto">Minutos</label>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <input type="text" class="datepicker" name="txtFechaPrevista" id="txtFechaPrevista" placeholder="dd/mm/aaaa" title="Fecha para entrega del producto / servicio">
                                <label for="txtFechaPrevista">Fecha prevista entrega</label>
                            </div>
                            <div class="input-field col l2 m2 s12">'
                                .$selectEquipo.
                            '</div>
                            <div id="sltServicio" class="input-field col l4 m4 s12 offset-l3 offset-m3"></div>
                            <div class="input-field col l4 m4 s12 offset-l2 offset-m2">
                                <p>
                                    <label>
                                        <input class="filled-in" name="txtRegistrarT" id="txtRegistrarT" type="checkbox"/>
                                        <span>Habilitar registrar tiempos (No para productos MOOCs)</span>
                                    </label>
                                </p>
                                </div>
                            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                                <textarea name="txtDescripcion" id="txtDescripcion" class="materialize-textarea" required></textarea>
                                <label for="txtDescripcion">Descripción del Producto/Servicio*</label>
                            </div>
                            <div class="input-field col l5 m5 s12 offset-l3 offset-m3">
                                <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarSolEsp">Registrar</button>
                                <button class="btn waves-effect waves-light" type="submit" name="btnRegistrarSolEspPres">Registrar y Presupuestar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">'.
                    $cargaEspecificas
                .'</div>';
} else {
    $string = ' <div class="row">
                    <div class="col l10 m10 s12 offset-l1 offset-m1">
                        <p class="left-align teal-text">Para consultar solicitudes de producto/servicio, ingrese el código de la solicitud, código de proyecto Conecta-TE, o estado de la solicitud.</p>
                        <nav>
                            <div class="nav-wrapper teal lighten-4">
                                <div class="input-field">
                                    <input id="txt-search" name="txt-search" type="search" data-url="../Controllers/ctrl_solicitudEspecifica.php">
                                    <label class="label-icon" for="txt-search"><i class="material-icons">search</i></label>
                                    <i class="material-icons ico-clean">close</i>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
                </div>';
}
?>

<div id="content" class="center-align">
    <h4>PRODUCTO/SERVICIO</h4>
    <?php echo $string;?>
</div>

<!-- Modal Structure -->
<div id="modalSolicitudEspecifica" class="modal">
    <?php
        require('modalSolicitudEspecifica.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>