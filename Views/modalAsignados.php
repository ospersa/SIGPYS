<div class="modal-content center-align">
    <h4>Editar Asignación</h4>
    <div class="row">
        <form id="actForm" method="post" action="../Controllers/ctrl_asignados.php" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <?php
                require('../Controllers/ctrl_asignados.php');
                ?>
                <input id="cod" name="cod" type="hidden">
                <input id="val" name="val" type="hidden">
                <input type="hidden" name="est" type="hidden" value="<?php echo $estado;?>">
                <!--<input id="idSol" name="idSol" type="hidden" value="<?php //echo $idSol; ?>">
                <input id="txtSolicita" name="txtSolicita" type="hidden" value="<?php //echo $solicitante;?>">-->
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtNombreAsignado" name="txtNombreAsignado" type="text" disabled value="<?php echo $nombreCompleto;?>">
					<label for="txtNombreAsignado" class="active">Asignado a:</label>
				</div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtNombreFase" name="txtNombreFase" type="text" disabled value="<?php echo $nombreFase; ?>">
					<label for="txtNombreFase" class="active">Fase:</label>
				</div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
                    <input id="txtHoras" name="txtHoras" type="number" class="validate" value="<?php echo $horas; ?>">
					<label for="txtHoras" class="active">Horas:</label>
				</div>
                <div class="input-field col l5 m5 s12">
                    <input id="txtMinutos" name="txtMinutos" type="number" class="validate" min="0" max="60" value="<?php echo $minutos; ?>">
					<label for="txtMinutos" class="active">Minutos:</label>
				</div>
            </div>
            <button class="btn waves-effect" name="btnActualizar" type="submit">Actualizar</button>
            <button class="btn waves-effect red darken-4 waves-light " name="btnEliminar" type="submit">Eliminar</button>
            <?php 
            if ($estado == '1') {
                echo '<button class="btn waves-effect grey darken-4 waves-light " name="btnInactivar" type="submit">Inactivar</button>';
            } else {
                echo '<button class="btn waves-effect green darken-4 waves-light " name="btnInactivar" type="submit">Activar</button>';
            }
            ?>  
        </form>
    </div>
</div>