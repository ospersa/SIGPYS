<div class="home">
<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_home.php');
?>

<div class="row">
	<div class=" col l9 m9 s12 ">
		<div class="col l12 m12 s12 ">
			<ul class="collapsible  margintop">
				<li>
					<div class="collapsible-header teal white-text collapHome"><span class="center-align"><h6 class="white-text"> Productos/Servicios asignados</h6></span><i class="material-icons right" id="flechaColla">arrow_drop_down</i></div>
					<div class="collapsible-body white">
						<div class="row">
							<?php
						echo $solicitudes;
						?>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class=" col l6 m6 s12 ">
			<div class="card-panel cardHome">
				<span class="card-title white-text">
					<h5 class="center-align">Tiempo registrado Periodo Actual</h5>
				</span>
				<canvas id="chartTiempo" width="100" height="70"></canvas>
			</div>
		</div>
		<div class=" col l6 m6 s12 ">
			<div class="card-panel cardHome">
				<span class="card-title white-text">
					<h5 class="center-align">Productos/Servicios segun su estado </h5>
				</span>
				<canvas id="chartSolicitud" width="100" height="70"></canvas>
			</div>
		</div>
		<?php
		if ($perfil == 'PERF01' || $perfil == 'PERF02') {
			echo '<div class=" col l6 m6 s12 ">
			<div class="card-panel cardHome">
				<span class="card-title white-text">
					<h5 class="center-align">Solicitudes Con/Sin Productos/Servicios</h5>
				</span>
				<canvas id="chartSolIni" width="100" height="64"></canvas>
			</div>
		</div>
		<div class=" col l6 m6 s12 ">
			<div class="card-panel cardHome">
				<span class="card-title white-text">
					<h5 class="center-align">Solicitudes Con/Sin Presupuesto </h5>
				</span>
				<canvas id="chartCot" width="100" height="70"></canvas>
			</div>
		</div>
		<div class=" col l6 m6 s12 ">
			<div class="card-panel cardHome">
				<span class="card-title white-text">
					<h5 class="center-align">Estado de Inventarios </h5>
				</span>
				<canvas id="chartInv" width="100" height="70"></canvas>
			</div>
		</div>
		<div class=" col l12 m12 s12 ">
			<div class="card-panel cardHome ">
				<span class="card-title white-text">
					<h5 class="center-align">Ejecución Proyecto</h5>
				</span>
				<canvas id="chartProyecto" width="100" height="100"></canvas>
			</div>
		</div>
		
	';

		} else if ( $validarAse = true){
			echo  '<div class=" col l12 m12 s12 ">
			<div class="card-panel cardHome ">
				<span class="card-title white-text">
					<h5 class="center-align">Ejecución Proyectos </h5>
				</span>
				<canvas id="chartProyecto" width="100" height="100"></canvas>
			</div>
		</div>';
		}
		?>
	</div>
	<div class=" col l3 m3 s12 ">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal cardHome margintop ">
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
</div>