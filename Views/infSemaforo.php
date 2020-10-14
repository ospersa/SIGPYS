<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>INFORME SEMÁFORO</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infSemaforo.php" method="post" id="form">
            <div class="row">
                <div class="input-field col l2 m2 s12 offset-l5 offset-m5">
                    <button class="btn waves-effect waves-light " type="submit" name="btnDescargar">Descargar</button>
                </div>
        </form>
    </div>
</div>

<?php
require('../Estructure/footer.php');
?>