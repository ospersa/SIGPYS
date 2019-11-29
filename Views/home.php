<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_home.php');
?>
<div class="row">
	<div class="input-field col l9 m9 s12 ">
		<div class="input-field col l11 m11 s12 ">
			<ul class="collapsible popout">
				<li>
					<div class="collapsible-header teal white-text">Utimos Productos/Servicios asignados</div>
					<div class="collapsible-body">
						<div class="row">
							<?php
						echo $solicitudes;
						?>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="input-field col l5 m5 s12 ">
			<div class="card-panel ">
				<span class="card-title white-text">
					<h5 class="center-align">Solicitudes segun su estado </h5>
				</span>
				<canvas id="chartSolicitud" width="50" height="50"></canvas>
			</div>
		</div>
		<div class="input-field col l5 m5 s12 ">
			<div class="card-panel">
				<span class="card-title white-text">
					<h5 class="center-align">Tiempo registrado Periodo Actual</h5>
				</span>
				<canvas id="chartTiempo" width="100" height="70"></canvas>
			</div>
		</div>
		<div class="input-field col l5 m5 s12 ">
			<div class="card-panel ">
				<span class="card-title white-text">
					<h5 class="center-align">Solicitudes Con/Sin Solicitud Especifica </h5>
				</span>
				<canvas id="chartSolIni" width="50" height="50"></canvas>
			</div>
		</div>
		<div class="input-field col l5 m5 s12 ">
			<div class="card-panel ">
				<span class="card-title white-text">
					<h5 class="center-align">Solicitudes Con/Sin Cotización </h5>
				</span>
				<canvas id="chartCot" width="50" height="50"></canvas>
			</div>
		</div>
		<div class="input-field col l5 m5 s12 ">
			<div class="card-panel ">
				<span class="card-title white-text">
					<h5 class="center-align">Estado de Inventarios </h5>
				</span>
				<canvas id="chartInv" width="50" height="50"></canvas>
			</div>
		</div>
	</div>
	<div class="input-field col l3 m3 s12 ">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal">
					<span class="card-title white-text">
						<h5 class="center-align white-text">Agenda del día</h5>
					</span>
					<?php
						echo $agenda;
						?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require('../Estructure/footer.php');
?>