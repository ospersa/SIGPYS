<div class="modal-content center-align">
	<h4>Editar Opción del sistema</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_opciones.php" method="post" class="col l12 m12 s12" autocomplete="off">
			<?php require('../Controllers/ctrl_opciones.php');?>
			<input id="cod" name="cod" type="hidden">
			<div class="input-field col l12 m12 s12">
				<input id="txtOptionName" name="txtOptionName" type="text" class="validate" value="<?php echo $name;?>">
				<label for="txtOptionName" class="active">Nombre</label>
			</div>
			<div class="input-field col l12 m12 s12">
				<input id="txtOptionDescription" name="txtOptionDescription" type="text" class="validate" value="<?php echo $description;?>">
				<label for="txtOptionDescription" class="active">Descripción</label>
			</div>
			<div class="input-field col l12 m12 s12">
				<input id="txtOptionValue" name="txtOptionValue" type="text" class="validate" value="<?php echo $value;?>">
				<label for="txtOptionValue" class="active">Valor</label>
			</div>
			<button class="btn waves-effect waves-light" type="submit" name="btnUpdateOption">Actualizar</button>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnDeleteOption">Eliminar</button>
		</form>
	</div>
</div>