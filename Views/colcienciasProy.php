<?php
require('../Estructure/header.php');
$proyecto = $_GET['cod'];
?>

<div id="content" class="center-align">
    <h4>REGISTRO DE PROYECTOS EN COLCIENCIAS</h4>
    <form action="../Controllers/ctrl_colciencias.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <input type="hidden" name="txtIdProy" value="<?php echo $proyecto;?>">
        <?php 
            require('../Controllers/ctrl_colciencias.php');
        ?>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input name="txtFechaDNDA" id="txtFechaDNDA" type="text" class="datepicker">
                <label for="txtFechaDNDA">Fecha DNDA</label>
            </div>
            <div class="input-field col l3 m3 s12">
                <input name="txtFechaColciencias" id="txtFechaColciencias" type="text" class="datepicker">
                <label for="txtFechaColciencias">Fecha Colciencias*</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input type="text" name="txtAutor" id="txtAutor" required placeholder="Campo Obligatorio">
                <label for="txtAutor">Autor Externo*</label>
            </div>
            <div class="input-field col l3 m3 s12">
                <?php //Colciencias::selectInvestigador();
                    echo "Select Investigador";?>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <textarea name="txtObservacion" id="txtObservacion" class="materialize-textarea"></textarea>
                <label for="txtObservacion">Observación</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrar">Registrar</button>
        </div>
    </form>
</div>

<?php
require('../Estructure/footer.php');
?>