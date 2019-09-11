<div class="modal-content center-align">
    <h4>Editar Elemento PEP</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_elementoPep.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <?php
                    require('../Controllers/ctrl_elementoPep.php');
                ?>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomPep" name="txtNomPep" type="text" class="validate" value="<?php echo $nomPep;?>" required>
                    <label for="txtNomPep" class="active">Nombre Elemento PEP*</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtCodPep" name="txtCodPep" type="text" class="validate" value="<?php echo $codPep;?>" required>
                    <label for="txtCodPep" class="active">Código Elemento PEP*</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <?php echo $sltCeco; ?>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnActEPep">Actualizar</button>
        </form>
    </div>
</div>