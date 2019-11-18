<?php
require('../Estructure/header.php');
include_once('../Models/mdl_usuario.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_usuario.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_usuario.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PERSONA</h4>
    <form action="../Controllers/ctrl_usuario.php" method="post" autocomplete="off" class="col l12 m12 s12">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Usuario::selectEntidad(null);?>
            </div>
            
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <div id="sltFacultad"></div>
            </div>

            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <div id="sltDepartamento"></div>
            </div>

            <div class="input-field col l2 m2 s12 offset-l3 offset-m3">
                <?php Usuario::selectCargo(null);?>
            </div>
        
            <div class="input-field col l2 m2 s12">
                <?php Usuario::selectTipo(null, "Tipo*");?>
            </div>

            <div class ="input-field col l2 m2 s12">
                <select name="sltCategoriaCargo" id="sltCategoriaCargo">
                    <option value="" disabled>Seleccione</option>
                    <option value="41012">Profesional</option>
                    <option value="41027">No Profesional</option>
                </select>
                <label for="sltCategoriaCargo">Categoría del Cargo*</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input required name="txtIdDoc" id="txtIdDoc" type="number" value="" placeholder = "Campo numérico">
                <label for="txtIdDoc">Número de identificación</label>
            </div>

            <div class="input-field col l3 m3 s12">
                <input required name="txtNom" id="txtNom" type="text" value="" placeholder = "Campo obligatorio">
                <label for="txtNom">Nombres*</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input required name="txtApe1" id="txtApe1" type="text" value="" placeholder = "Campo obligatorio">
                <label for="txtApe1">Primer apellido*</label>
            </div>
            <div class="input-field col l3 m3 s12">
                <input name="txtApe2" id="txtApe2" type="text" value="" placeholder = "">
                <label for="txtApe2">Segundo apellido</label>
            </div>
        </div>
        
        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <?php Usuario::selectPais(null);?>
            </div>
            <div class="input-field col l3 m3 s12">
                <div id="sltCiudad">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php Usuario::selectEquipo(null);?>
            </div>
        </div>

        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input required type="email" name="txtCorreo" id="txtCorreo" placeholder="usuario@uniandes.edu.co">
                <label for="txtCorreo">Correo*</label>
            </div>

            <div class="input-field col l3 m3 s12">
                <input type="number" name="txtTel" id="txtTel">
                <label for="txtTel">Teléfono fijo</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                <input type="number" name="txtExt" id="txtExt">
                <label for="txtExt">Extensión</label>
            </div>
            
            <div class="input-field col l3 m3 s12">
                <input type="number" name="txtCel" id="txtCel">
                <label for="txtCel">Celular</label>
            </div>
        </div>
        <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
            <button class="btn waves-effect waves-light" type="submit" name="btnRegistrar">Registrar</button>
        </div>
    </form>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalUsuario" class="modal">
    <?php
        require('modalUsuario.php');
    ?>
</div>

<?php
require('../Estructure/footer.php');
?>