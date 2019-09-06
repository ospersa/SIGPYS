<div class="modal-content center-align">
    <h4>Editar Centro de Costos</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_centroCosto.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <input id="val" name="val" type="hidden">
                <?php
                    require('../Controllers/ctrl_centroCosto.php');
                ?>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtCodCeco2" name="txtCodCeco2" type="text" class="validate" value="<?php echo $codCeco;?>" required>
                    <label for="txtCodCeco2" class="active">Código Centro de Costos*</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomCeco2" name="txtNomCeco2" type="text" class="validate" value="<?php echo $nomCeco;?>" required>
                    <label for="txtNomCeco2" class="active">Nombre Centro de Costos*</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_centroCosto.php')">Actualizar</button>
        </form> 
    </div>
</div>