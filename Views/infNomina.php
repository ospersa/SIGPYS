<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>INFORME NÓMINA</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infNominaXls.php" method="post" id="frmInfNomina">
            <?php require('../Controllers/ctrl_infNominaXls.php');?>
            <div class="input-field col l3 m3 s12 offset-l2 offset-m2">
                <?php echo $selectAnio;?>
            </div>
            <div class="input-field col l3 m3 s12 offset-l1 offset-m1" id="sltMes2">
            </div>
            <div class="content">
                <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
            </div>
        </form>
        <div class="input-field col l2 m2 s12 offset-l5 offset-m5" id="btnGenerar">
        </div>
    </div>
    
</div>

<?php
require('../Estructure/footer.php');
?>