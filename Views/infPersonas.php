<?php
require('../Estructure/header.php');
require_once('../Controllers/ctrl_infPersonas.php');
?>

<div id="content" class="center-align">
    <h4>INFORME PERSONAS</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infPersonas.php" method="post" id="form">
        <div class="row">
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <?php echo $selectEntidad;?>
            </div>
            </div>
            <div class="input-field col l2 m2 s12 offset-l4 offset-m4">
                <button class="btn waves-effect waves-light " type="button"
                    onclick="buscar('../Controllers/ctrl_infPersonas.php')" name="btnBuscar">Buscar</button>
            </div>
            <div class="input-field col l2 m2 s12 ">
                <button class="btn waves-effect waves-light " type="submit" name="btnDescargar">Descargar</button>
            </div>
        </form>
    </div>
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
    

</div>

<?php
require('../Estructure/footer.php');
?>