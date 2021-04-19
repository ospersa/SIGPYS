<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infSeguimientoEstados.php');
?>
<div id="container" class="center-align">
    <h4>INFORME DE SEGUIMIENTO ESTADOS Y METADATA</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infSeguimientoEstados.php" method="post" id="form">
            <div class="row">
                <div id="frenteInf" class="input-field col l6 m12 s12 offset-l1">
                    <?php echo $frente?>
                </div>
                <div class="input-field col l4 m12 s12">
                    <p>
                        <label>
                            <input type="checkbox" id="estado" class="filled-in" name ="estado" data-checked="false">
                            <span>Incluir Productos/Servicios Terminados</span>
                        </label>
                    <p>
                </div>
            </div>
            <div class="row" id="proyecInf">
                <div class="input-field col l3 m2 s12 offset-l1 offset-m1">
                    <input type="text" name="txtBusquedaProy" id="txtBusquedaProy"
                        placeholder="Ingrese el código del proyecto">
                    <label for="txtBusquedaProy">Cód. Proyecto Conecta-TE*</label>
                </div>
                <div class="input-field col l7 m8 s12" id="sltProyecto" style="margin-left: 15px;">
                    <select name="sltProy" id="">
                        <option value="">Seleccione</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>
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