<div class="modal-content center-align">
	<h4>Editar/Eliminar Plataforma</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_plataforma.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_plataforma.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomPlataforma" name="txtNomPlataforma" type="text" class="validate" autofocus required value="<?php echo $nomPlataforma;?>">
					<label for="txtNomPlataforma" class="active">Nombre de la plataforma</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliplataforma">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActplataforma">Actualizar</button>
		</form>
	</div>
</div>