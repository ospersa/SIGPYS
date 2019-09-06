<div class="modal-content center-align">
    <h4>Editar Presupuesto</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_cotizacion.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <input id="val" name="val" type="hidden" value="3">
                <?php 
                    require('../Controllers/ctrl_cotizacion.php');
                ?>
                <input id="idCot" name="idCot" type="hidden" value="<?php echo $idAsig; ?>">
                <div class="input-field col l12 m12 s12">
                    <input id="txtValCot" name="txtValCot" type="number" value="<?php echo $valCot; ?>">
					<label for="txtValCot" class="active">Valor Cotización:</label>
				</div>
                <div class="input-field col l12 m12 s12">
                    <textarea name="txtObsSolicitante" id="txtObsSolicitante" class="materialize-textarea"><?php echo $obsSol; ?></textarea>
					<label for="txtObsSolicitante" class="active">Observación Cotización al Solicitante:</label>
				</div>
                <div class="input-field col l12 m12 s12">
                    <textarea name="txtObsPys" id="txtObsPys" class="materialize-textarea"><?php echo $obsPys; ?></textarea>
					<label for="txtObsPys" class="active">Observación Cotización PYS:</label>
				</div>
                <div class="input-field col l12 m12 s12">
                    <textarea name="txtObsPro" id="txtObsPro" class="materialize-textarea" data-original-height=0><?php echo $obsPro; ?></textarea>
                    <label for="txtObsPro" class="active">Especificación Producto / Servicio:</label>
                </div>
            </div>
            <button class="btn waves-effect" type="submit" name="action" onclick="actualiza('3','../Controllers/ctrl_cotizacion.php')">Actualizar</button>
        </form>
    </div>
</div>