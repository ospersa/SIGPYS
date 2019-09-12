<div class="modal-content center-align">
	<h4>Editar/Eliminar Rol</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_rol.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<?php
					require('../Controllers/ctrl_rol.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <?php echo Rol::selectTipoRol($tipRol);?>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtNomRol" name="txtNomRol" type="text" class="validate" required value="<?php echo $nomRol;?>">
                    <label for="txtNomRol" class="active">Nombre del rol</label>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtDescRol" name="txtDescRol" type="text" class="validate" value="<?php echo $descRol;?>">
                    <label for="txtDescRol" class="active">Descripción del rol</label>
                </div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliRol">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActRol">Actualizar</button>
		</form>
	</div>
</div>