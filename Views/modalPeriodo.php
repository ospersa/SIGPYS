<div class="modal-content center-align">
	<h4>Editar Periodo</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_periodo.php" method="post" class="col l12 m12 s12" autocomplete="off">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_periodo.php');
                ?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <h6>Periodo registrado del: <?php echo $fechaInicial. " al: ". $fechaFinal;?> </h6>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtFechaInicial" name="txtFechaInicial" type="date" class="validate" value="<?php echo $fechaInicial;?>">
					<label for="txtFechaInicial" class="active">Fecha Inicial</label>
				</div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtFechaFinal" name="txtFechaFinal" type="date" class="validate" value="<?php echo $fechaFinal;?>" >
					<label for="txtFechaFinal" class="active">Fecha Final</label>
				</div>
				<div class="input-field col l5 m5 s12 offset-l1 offset-m1">
					<input id="txtDiasSeg1" name="txtDiasSeg1" class="validate" value="<?php echo $diasSeg1;?>" type="number" oninput="txtEquivSeg1.value = txtDiasSeg1.value * 8">
					<label for="txtDiasSeg1" class="active">Días Segmento 1:</label>
				</div>
				<div class="input-field col l5 m5 s12">
					<input readonly id="txtEquivSeg1" name="txtEquivSeg1" value="<?php echo $diasSeg1 * 8;?>" type="number">
					<label for="txtHorasSeg1" class="active">Equivalente Horas Segmento 1:</label>
				</div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
					<input id="txtDiasSeg2" name="txtDiasSeg2" class="validate" value="<?php echo $diasSeg2;?>" type="number" oninput="txtEquivSeg2.value = txtDiasSeg2.value * 8">
					<label for="txtDiasSeg2" class="active">Días Segmento 2:</label>
				</div>
				<div class="input-field col l5 m5 s12">
					<input readonly id="txtEquivSeg2" name="txtEquivSeg2" value="<?php echo $diasSeg2 * 8;?>" type="number">
					<label for="txtHorasSeg2" class="active">Equivalente Horas Segmento 2:</label>
				</div>
			</div>
			<!--<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_periodo.php')">Eliminar</button>-->
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_periodo.php')">Actualizar</button>
		</form>
	</div>
</div>