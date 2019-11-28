<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_home.php');
?>
<div class="row">
	<div class="input-field col l3 m3 s12 offset-l1 offset-m1">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal">
				<span class="card-title white-text"><h5 class="center-align white-text">Agenda del día</h5></span>
						<?php
						echo $agenda;
						?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="input-field col l3 m3 s12 offset-l1 offset-m1">
			<div class="row">
				<div class="col l12 m12 s12 ">
					<div class="card-panel ">
					<span class="card-title white-text"><h5 class="center-align">Solicitudes segun su estado </h5></span>
				
						<canvas id="chartSolicitud" width="50" height="50"></canvas>
								
				</div>
			</div>
		</div>
	</div>
	<div class="input-field col l4 m4 s12 offset-l1 offset-m1">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal">
					<span class="white-text">Estadistica Estado de solicitudes
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="input-field col l4 m4 s12 offset-l1 offset-m1">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal">
					<span class="white-text">Agenda
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="input-field col l4 m4 s12 offset-l1 offset-m1">
		<div class="row">
			<div class="col col l12 m12 s12">
				<div class="card-panel teal">
					<span class="white-text">Agenda
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require('../Estructure/footer.php');
?>