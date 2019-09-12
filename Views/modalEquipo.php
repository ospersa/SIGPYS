<div class="modal-content center-align">
	<h4>Editar/Eliminar Equipo</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_equipo.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input type="hidden" id="cod" name="cod">
				<?php
					require('../Controllers/ctrl_equipo.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomEquipo" name="txtNomEquipo" type="text" class="validate" autofocus required value="<?php echo $nomEquipo;?>">
					<label for="txtNomEquipo" class="active">Nombre del equipo</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescEquipo" name="txtDescEquipo" class="materialize-textarea" ><?php echo $descEquipo;?></textarea>
					<label for="txtDescEquipo" class="active">Descripción del equipo</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliEqu">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActEqu">Actualizar</button>
		</form>
	</div>
</div>