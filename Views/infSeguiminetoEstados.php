<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infSeguimientoEstados.php');
?>
<div id="container" class="center-align">
    <h4>INFORME DE SEGUIMIENTO ESTADOS Y METADATA</h4>
    <div class="row">
        <p class="left-align col l10 offset-l1"><strong>IMPORTANTE: </strong>Para generar el informe de seguimiento de estados y metadata, filtre <u>únicamente</u> por: <strong>Proyecto</strong>, <strong>Frente</strong> o <strong>Gestor/Asesor RED</strong>.</p>
        <form action="../Controllers/ctrl_infSeguimientoEstados.php" method="post" id="form" autocomplete="off">
            <div class="row" id="proyecInf" style="margin-bottom: 0;">
                <div class="input-field col l3 m2 s12 offset-l1 offset-m1">
                    <input type="text" name="txtBusquedaProy" id="txtBusquedaProy"
                        placeholder="Ingrese el código del proyecto">
                    <label for="txtBusquedaProy">Cód. Proyecto Conecta-TE*</label>
                </div>
                <div class="input-field col l7 m8 s12" id="sltProyecto">
                    <select name="sltProy" id="sltProy">
                        <option value="">Seleccione</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>
                </div>
            </div>
            <div class="row" style="margin-bottom: 0;">
                <div id="frenteInf" class="input-field col l3 m12 s12 offset-l1">
                    <?php echo $frente; ?>
                </div>
                <div id="frenteInf" class="input-field col l3 m12 s12">
                    <?php echo $gestores; ?>
                </div>
                <div class="input-field left-align col l2 m12 s12">
                    <p>
                        <label>
                            <input type="checkbox" id="estado" class="filled-in" name ="estado" data-checked="false">
                            <span>Incluir Productos/Servicios Terminados</span>
                        </label>
                    <p>
                </div>
                <div class="input-field col l2 m12 s12">
                    <p>
                        <label>
                            <input type="checkbox" id="tiempos" class="filled-in" name ="tiempos" data-checked="false">
                            <span>Incluir Tiempos Registrados</span>
                        </label>
                    <p>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <button class="btn waves-effect waves-light" type="submit" name="btnDescargar"
                        id="btnDescargar">Descargar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="div_dinamico"></div>
<?php
require('../Estructure/footer.php');
?>