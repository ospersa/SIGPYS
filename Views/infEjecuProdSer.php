<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infEjecuProdSer.php');
?>
<div id="container" class="center-align">
    <h4>INFORME DE EJECUCIONES POR PROYECTO</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infEjecuProdSer.php" method="post" id="form">
            <div class="row">
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input type="text" name="txtFechIni" id="txtFechIni" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtFechIni">Fecha desde</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input type="text" name="txtFechFin" id="txtFechFin" class="datepicker" placeholder="aaaa/mm/dd">
                    <label for="txtFechFin">Fecha hasta</label>
                </div>
                <div class="input-field col l2 m12 s12 ">
                    <input id="diasLab" name="diasLab" type="number" value="" min="0" >
                    <label class="active" for="diasLab">Días laborales del mes</label>
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
            <div class="row" id = "frenteInf" >
                <div class="input-field col l2 m2 s12 offset-l2 offset-m1">
                 <?php echo $frente?>
                </div>
                
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                    <button class="btn waves-effect waves-light" type="button" name="btnBuscar"
                        onclick="buscar('../Controllers/ctrl_infEjecuProdSer.php');">Buscar</button>
                </div>
                <div class="input-field col l2 m2 s12">
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