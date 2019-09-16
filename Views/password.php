<?php  
require('../Estructure/header.php');
require('../Controllers/ctrl_password.php'); 
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_password.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>Asignar Password</h4>

    <div class="row">
        <form action="../Controllers/ctrl_password.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                   <?php echo $selectPerfil; ?>
                </div>
                <div class="input-field col l5 m5 s12 offset-l3 offset-m3">
                        <label>Usuario</label>
                        <input id="txt-usu" name="txt-usu" type="text" class="validate"/>
                </div>
                <div class="input-field col l1 m1 s12">
                    <a id="btn-searchUsu" class="btn" onclick="busquedaUsu('../Controllers/ctrl_password.php')">
                        <i class="material-icons">search</i>
                    </a>
                </div>
                <div id="div_usuario" class="input-field col l6 m6 s12 offset-l3 offset-m3"></div>
                <div id="sltDinamico" class="col l6 m6 s12 offset-l3 offset-m3"></div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtpassPer" name="txtpassPer" type="password"  class="validate" required  value="Conecta-TE" />
                    <label for="txtpassPer">Password*</label>
                </div>
                <div class="input-field col l3 m3 s12 ">
                    <input id="txtpass1Per" name="txtpass1Per" type="password"  class="validate" required  value="" />
                    <label for="txtpass1Per">Confirmar Password*</label>
                    <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
                    <a onmouseover="M.toast({html: 'Default Pass: Conecta-TE.'})" class="help teal-text text-accent-4"><i class="material-icons small">help_outline</i></a>
                </div>
            </div>
            <button id="btn-pass" class="btn waves-effect waves-light disabled" type="submit" name="btnGuardarPassword">Guardar</button>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
    </div>
</div>

<!-- Modal Structure -->
<div id="modalPassword" class="modal">
    <?php
        require('modalPassword.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');