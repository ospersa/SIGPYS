<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infSeguimientoEstados.php');
?>
<div id="container" class="center-align">
    <h4>INFORME DE SEGUIMIENTO ESTADOS Y METADATA</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infSeguimientoEstados.php" method="post" id="form">
            <div class="row">
            </div>
            <div class="row" id = "frenteInf" >
                <div class="input-field col l2 m2 s12 offset-l2 offset-m1">
                 <?php echo $frente?>
                </div>
                
            </div>
            <div class="row" id = "proyecInf" >
                <div class="input-field col l2 m2 s12 offset-l2 offset-m1">
                    <input type="text" name="txtBusquedaProy" id="txtBusquedaProy"
                        placeholder="Ingrese el código del proyecto">
                    <label for="txtBusquedaProy">Cód. Proyecto Conecta-TE*</label>
                </div>
                <div class="input-field col l6 m8 s12" id="sltProyecto">
                    <select name="sltProyecto" id="">
                        <option value="sltProyecto">Seleccione</option>
                    </select>
                    <label for="sltProyecto">Seleccione un proyecto</label>
                </div>
            </div>
            <div class="row">
                
                <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                    <button class="btn waves-effect waves-light" type="submit" name="btnDescargar"
                        id="btnDescargar">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="div_dinamico"></div>
<?php
require('../Estructure/footer.php');
?>