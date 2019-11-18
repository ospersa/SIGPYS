<?php
require('../Estructure/header.php');
?>
<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_perfil.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_perfil.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>PERFILES DE USUARIO</h4>

    <div class="row">
        <form action="../Controllers/ctrl_perfil.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtnomPerf" name="txtnomPerf" type="text" class="validate" required>
                    <label for="txtnomPerf">Nombre del perfil</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <textarea id="txtdescPerf" name="txtdescPerf" class="materialize-textarea"></textarea>
                    <label for="txtdescPerf">Descripción del perfil</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action">Guardar</button>
        </form>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>
</div>
<?php
require('../Estructure/footer.php');