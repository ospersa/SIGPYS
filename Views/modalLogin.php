<div class="modal-content center-align">
	<h4>Editar/Inactivar</h4>
	<div class="row">
		<form id="actForm" class="col l12 m12 s12">
			<div class="row">
                <?php
                require('../Controllers/ctrl_login.php');
                ?>   
                <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                    <input id="numCedula" name="numCedula" type="number" required  value="" />
                    <label for="numCedula">Numero de Cedula*</label>
                </div>    
                <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                    <input id="nomUsu" name="nomUsu" type="text" required  value="" />
                    <label for="nomUsu">Nombre de usuario*</label>
                    <button class="btn waves-effect waves-light" type="submit" id="ValidarUser"  onclick="buscar('../Controllers/ctrl_login.php')" name="ValidarUser">Validar</button>
                </div> 
                <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <!-- <button class="btn waves-effect waves-light" type="submit" id="ValidarUser" name="ValidarUser">Validar</button> -->
            </div>    
		</form>
	</div>
</div>