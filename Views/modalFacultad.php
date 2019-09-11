<div class="modal-content center-align">
	<h4>Editar/Eliminar Facultad</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_facultad.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<?php
					require ('../Controllers/ctrl_facultad.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <?php echo Facultad::selectEntidad($entidad);?>
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtNomFacultad" name="txtNomFacultad" type="text" class="validate" value="<?php echo $nomFacultad;?>" required >
                    <label for="txtNomFacultad" class="active">Nombre de la facultad</label>
                </div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliFac">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActFac">Actualizar</button>
		</form>
	</div>
</div>