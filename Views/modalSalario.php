<div class="modal-content center-align">
    <h4>Editar/Eliminar Salario</h4>
    <div class="row">
		<form id="actForm" action="../Controllers/ctrl_salario.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<?php
					require('../Controllers/ctrl_salario.php');
				?>
                <div class="input-field col l11 m10 s12 offset-l1 offset-m1">
					<input id="txtPersona" name="txtPersona" type="text" autofocus readonly value="<?php echo $nombreCompleto;?>">
					<label for="txtPersona" class="active">Persona</label>
				</div>
				<div class="input-field col l11 m10 s12 offset-l1 offset-m1">
					<input id="txtSalario" name="txtSalario" type="text" class="validate" autofocus required value="<?php echo $salario;?>">
					<label for="txtSalario" class="active">Valor salario</label>
				</div>
				<div class="input-field col l5 m5 s12 offset-l1 offset-m1">
					<input type="date" id="txtVigIni" name="txtVigIni" value="<?php echo $vigDesde;?>">
					<label for="txtVigIni" class="active">Vigente desde</label>
				</div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
					<input type="date" id="txtVigFin" name="txtVigFin" value="<?php echo $vigFin;?>">
					<label for="txtVigFin" class="active">Vigente hasta</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliSalario">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActSalario">Actualizar</button>
		</form>
	</div>
</div>