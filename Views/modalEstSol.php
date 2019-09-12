<div class="modal-content center-align">
    <h4>Editar Estado de Solicitud</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_solicitud.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input type="hidden" id="cod" name="cod">
                <?php
					require('../Controllers/ctrl_solicitud.php');
				?>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomEst" name="txtNomEst" type="text" class="validate" autofocus required value="<?php echo $nombreEst;?>">
					<label for="txtNomEst" class="active">Nombre Estado Solicitud*</label>
				</div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtDescEst" name="txtDescEst" type="text" value="<?php echo $descripcionEst;?>">
					<label for="txtDescEst" class="active">Descripción Estado Solicitud*</label>
				</div>
                <div class="row">
                    <button class="btn waves-effect waves-light" type="submit" name="btnActEstSol">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>