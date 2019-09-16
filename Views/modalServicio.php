<div class="modal-content center-align">
	<h4>Editar/Eliminar Servicio</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_servicios.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
                <?php
                    require('../Controllers/ctrl_servicios.php');
				?>
				<div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectEquipo;?>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input type="text" name="txtNombreServicio" id="txtNombreServicio" required value="<?php echo $nombre;?>">
                    <label for="txtNombreServicio" class="active">Servicio</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input type="text" name="txtNombreCortoServicio" id="txtNombreCortoServicio" value="<?php echo $nombreCorto;?>">
                    <label for="txtNombreCortoServicio" class="active">Nombre corto</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea name="txtDescripcionServicio" id="txtDescripcionServicio" class="materialize-textarea" ><?php echo $descripcion;?></textarea>
                    <label for="txtDescripcionServicio" class="active">Descripción del servicio</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <?php echo $selectGenerarPro;?>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input type="number" name="txtCostoServicio" id="txtCostoServicio" value="<?php echo $costo;?>" min="0">
                    <label for="txtCostoServicio" class="active">Costo servicio $</label>
                </div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliServicio">Eliminar</button>
                <button class="btn waves-effect waves-light" type="submit" name="btnActServicio">Actualizar</button>
            </div>    
		</form>
	</div>
</div>