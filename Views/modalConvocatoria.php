<div class="modal-content center-align">
	<h4>Editar/Eliminar Convocatoria</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_convocatoria.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_convocatoria.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomConv" name="txtNomConv" type="text" class="validate" autofocus required value="<?php echo $var1;?>">
					<label for="txtNomConv" class="active">Nombre del convocatoria</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescConv" name="txtDescConv" class="materialize-textarea"><?php echo $var2;?></textarea>
					<label for="txtDescConv" class="active">Descripción de la convocatoria</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_convocatoria.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_convocatoria.php')">Actualizar</button>
		</form>
	</div>
</div>