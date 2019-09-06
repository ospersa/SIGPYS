<div class="modal-content center-align">
    <h4>Editar Asignación de Tiempo</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_planeacion.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <input id="val" name="val" type="hidden">
                <?php
                    require('../Controllers/ctrl_planeacion.php');
                ?>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                        <input id="txtHrsInvertir" name="txtHrsInvertir" type="number" autofocus required value="<?php echo $hrsInvertir;?>">
                        <label for="txtHrsInvertir" class="active">Horas a Invertir</label>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                        <input id="txtMtsInvertir" name="txtMtsInvertir" type="number" max="59" autofocus required value="<?php echo $mtsInvertir;?>">
                        <label for="txtMtsInvertir" class="active">Minutos a Invertir</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','../Controllers/ctrl_planeacion.php')">Actualizar</button>
        </form>
    </div>
</div>