<div class="modal-content center-align">
    <h4>Editar Tipo Proyecto</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_proyecto.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" type="hidden">
            <input id="val" name="val" type="hidden">
            <?php
                require_once('../Controllers/ctrl_proyecto.php');
                include_once('../Models/mdl_proyecto.php');
            ?>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <?php Proyecto::selectFrente($id);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <input required name="txtNomTipoProy" id="txtNomTipoProy" type="text" value="<?php echo $nombreTip;?>">
                    <label for="txtNomTipoProy2" class="active">Nombre tipo de proyecto*</label>
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('2','Controllers/ctrl_proyecto.php')">Actualizar</button>
            </div>
        </form>
    </div>
</div>