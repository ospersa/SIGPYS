<div class="modal-content center-align">
    <h4>Editar Célula</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_celula.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <?php
                    require('../Controllers/ctrl_celula.php');
                ?>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomCelula" name="txtNomCelula" type="text" class="validate" autofocus required value="<?php echo $nombreCelula;?>">
					<label for="txtNomCelula" class="active">Nombre Célula</label>
				</div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <?php echo Celula::tablaCoordinadores($idCelula);?>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnActCelula">Actualizar</button>
        </form>
    </div>
</div>