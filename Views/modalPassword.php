<div class="modal-content center-align">
	<h4>Editar/Inactivar</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_password.php" method="post" class="col l12 m12 s12">
			<div class="row">
                <input id="cod" name="cod" type="hidden">
                
                <?php
                require('../Controllers/ctrl_password.php');
                ?>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectPerfil2;?>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <p>
                    <label>
                        <input type="checkbox" id="checkPassword" data-checked="false" onclick ="checkbox('#checkPassword')" />
                        <span>Cambiar contraseña</span>
                    </label>
                    </p>
                </div>
                
                <div id="passwords"class="hide">
                    <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                        <input id="txtpassPer1" name="txtpassPer" type="password"  class="validate"  disabled required  value="" />
                        <label for="txtpassPer1">Nuevo Password*</label>
                    </div>
                    <div class="input-field col l3 m3 s12">
                        <input id="txtpass1Per1" name="txtpass1Per" type="password"  class="validate" onkeyup="confirPassword('#txtpassPer1', '#txtpass1Per1', '#btnActPassword' )" disabled required  value="" />
                        <label for="txtpass1Per1">Confirmar nuevo Password*</label>
                        <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
                    </div>
                </div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" id="btnActPassword" name="btnActPassword">Actualizar</button>
                <button class="btn waves-effect red darken-4 waves-light " type="submit" id="btnIncPassword" name="btnincPassword">Inactivar</button>
            </div>    
		</form>
	</div>
</div>