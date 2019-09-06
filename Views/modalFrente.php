<div class="modal-content center-align">
	<h4>Editar/Eliminar Frente</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_frente.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_frente.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomFrente" name="txtNomFrente" type="text" class="validate" autofocus required value="<?php echo $nomFrente;?>">
					<label for="txtNomFrente" class="active">Nombre del frente</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescFrente" name="txtDescFrente" class="materialize-textarea"><?php echo $descFrente;?></textarea>
					<label for="txtDescFrente" class="active">Descripción del frente</label>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <?php echo Frente::selectCoordinadorFrente($idPersona);?>
                </div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_frente.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_frente.php')">Actualizar</button>
		</form>
	</div>
</div>