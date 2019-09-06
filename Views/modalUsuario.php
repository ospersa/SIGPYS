<div class="modal-content center-align">
    <h4>Editar/Eliminar Usuario</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_usuario.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" type="hidden">
            <input id="val" name="val" type="hidden">
            <?php
                require('../Controllers/ctrl_usuario.php');
                include_once('../Models/mdl_usuario.php');
            ?>
            <div class="row">
                <div class="input-field col l3 m3 s12">
                    <input required name="txtIdDoc" id="txtIdDoc" type="number" value="<?php echo $identificacion;?>">
                    <label for="txtIdDoc" class="active">Número de identificación</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input required name="txtNom" id="txtNom" type="text" value="<?php echo $nombres;?>">
                    <label for="txtNom" class="active">Nombres*</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input required name="txtApe1" id="txtApe1" type="text" value="<?php echo $apellido1;?>">
                    <label for="txtApe1" class="active">Primer apellido*</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input required name="txtApe2" id="txtApe2" type="text" value="<?php echo $apellido2;?>">
                    <label for="txtApe2" class="active">Segundo apellido</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <?php Usuario::selectEntidad($id);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <div id="sltFacultad2">
                        <?php Usuario::selectFacultades($entidad, $id, null);?>
                    </div>
                </div>
                <div class="input-field col l4 m4 s12">
                    <div id="sltDepartamento2">
                        <?php Usuario::selectDepartamentos($facultad, $id);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l5 m5 s12">
                    <div id="sltCargo2">
                        <?php Usuario::selectCargo($id);?>
                    </div>
                </div>
                <div class="input-field col l2 m2 s12">
                    <?php Usuario::selectTipo($id, "Tipo*");?>
                </div>
                <div class="input-field col l5 m5 s12">
                    <?php Usuario::selectEquipo($id);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <?php Usuario::selectCategoriaCargo($id);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <?php Usuario::selectPais($id);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <div id="sltCiudad2">
                        <?php Usuario::selectCiudades($id);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <input required name="txtCorreo" id="txtCorreo" type="email" value="<?php echo $correo;?>">
                    <label for="txtCorreo" class="active">Correo*</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input type="number" name="txtTel" id="txtTel" value="<?php echo $telefono;?>">
                    <label for="txtTel" class="active">Teléfono fijo</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input type="number" name="txtExt" id="txtExt" value="<?php echo $extension;?>">
                    <label for="txtExt" class="active">Extensión</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input type="number" name="txtCel" id="txtCel" value="<?php echo $celular;?>">
                    <label for="txtCel" class="active" >Celular</label>
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_usuario.php')">Eliminar</button>
                <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_usuario.php')">Actualizar</button>
            </div>
        </form>
    </div>
</div>