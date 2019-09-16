<div class="modal-content center-align">
	<h4>Editar/Eliminar Usuario/Password</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_servicios.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
                <?php
                require('../Controllers/ctrl_password.php');
                ?>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectPerfil;?>
                </div>
                <!-- <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtpassPer" name="txtpassPer" type="password"  class="validate" required  value="" />
                    <label for="txtpassPer">Nuevo Password*</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input id="txtpass1Per" name="txtpass1Per" type="password"  class="validate" required  value="" />
                    <label for="txtpass1Per">Confirmar nuevo Password*</label>
                    <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
                </div> -->
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnActPassword">Actualizar</button>
            </div>    
		</form>
	</div>
</div>