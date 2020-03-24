<div class="modal-content center-align">
<?php
                include_once('../Controllers/ctrl_infMisTiempos.php');
?>
    <div class="row">
        <h4>Editar tiempo resgistrado</h4>
    </div>
    <div class="row">
        <form id="editTiempo" action="../Controllers/ctrl_infMisTiempos.php" method="post">
            <input id="idTiempo" name="idTiempo" value="<?php echo $idTiempo?>" type="hidden">
            <div class="input-field col s12 m2 l2">
                <input id="dateEdit" name="dateEdit" type="text" value='<?php echo $fechTiempo?>' class="datepicker">
                <label class="active" for="dateEdit">Fecha</label>
            </div>
            <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                <input id="horasEdit" name="horasEdit" type="number" value="<?php echo $horaTiempo?>" min="0" max="12">
                <label class="active" for="horasEdit">Horas</label>
            </div>
            <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                <input id="minutosEdit" name="minutosEdit" type="number" value="<?php echo $minTiempo?>" min="0" max="59">
                <label class="active" for="minutosEdit">Minutos</label>
            </div>
            <div class="input-field col s12 m5 l5 offset-m1 offset-l1">
            <?php echo $fase?>

            </div>
            <div class="input-field col s12">
                <textarea id="notaTEdit" name="notaTEdit" class="materialize-textarea textarea"><?php echo $notaTiempo?></textarea>
                <label class="active" for="notaTEdit">Nota:</label>
            </div>
            <div class="input-field col s12">
                <button class="btn waves-effect waves-light" type="submit" onclick="actTiempo();" name="btnActRegTiempo">Actualizar</button>
            </div>
        </form>
    </div>

</div>