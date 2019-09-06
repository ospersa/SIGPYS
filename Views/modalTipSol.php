<div class="modal-content center-align">
    <h4>Editar Tipo de Solicitud</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_solicitud.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input type="hidden" id="cod" name="cod">
				<input type="hidden" id="val" name="val">
                <?php
					require('../Controllers/ctrl_solicitud.php');
				?>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomTip" name="txtNomTip" type="text" class="validate" autofocus required value="<?php echo $nombreTip;?>">
					<label for="txtNomTip" class="active">Nombre Tipo Solicitud*</label>
				</div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtDescTip" name="txtDescTip" type="text" value="<?php echo $descripcionTip;?>">
					<label for="txtDescTip" class="active">Descripción Tipo Solicitud*</label>
				</div>
                <div class="row">
                    <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('2','Controllers/ctrl_solicitud.php')">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>