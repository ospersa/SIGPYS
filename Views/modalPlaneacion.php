<div class="modal-content center-align">
    <h4>Editar Asignación de Tiempo</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_planeacion.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <?php
                    require('../Controllers/ctrl_planeacion.php');
                ?>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                        <input id="horasInvertir" name="horasInvertir" type="number" autofocus required value="<?php echo $hrsInvertir;?>">
                        <label for="horasInvertir" class="active">Horas a Invertir</label>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                        <input id="minutosInvertir" name="minutosInvertir" type="number" max="59" autofocus required value="<?php echo $mtsInvertir;?>">
                        <label for="minutosInvertir" class="active">Minutos a Invertir</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnActPlane">Actualizar</button>
        </form>
    </div>
</div>