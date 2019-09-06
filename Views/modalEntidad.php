<div class="modal-content center-align">
	<h4>Editar/Eliminar Entidad</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_entidad.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_entidad.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomEnti" name="txtNomEnti" type="text" class="validate" autofocus required value="<?php echo $var1;?>">
					<label for="txtNomEnti" class="active">Nombre de la Entidad</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomCortoEnti" name="txtNomCortoEnti" type="text" class="validate" value="<?php echo $var2;?>">
					<label for="txtNomCortoEnti" class="active">Nombre corto de la Entidad</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescEnti" name="txtDescEnti" class="materialize-textarea" ><?php echo $var3;?></textarea>
					<label for="txtDescEnti" class="active">Descripción de la Entidad</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_entidad.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_entidad.php')">Actualizar</button>
		</form>
	</div>
</div>