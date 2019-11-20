<div class="modal-content center-align">
    <h4>Editar Etapa Proyecto</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_proyecto.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" type="hidden">
            <input id="val" name="val" value = "1" type="hidden">
            <?php
                require_once('../Controllers/ctrl_proyecto.php');
                //include_once('../Models/mdl_proyecto.php');
            ?>
            
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <?php Proyecto::selectTipoProyecto($id, null);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <input required name="txtNomEta" id="txtNomEta" type="text" value="<?php echo $nombreEta;?>">
                    <label for="txtNomEta" class="active">Nombre etapa*</label>
                </div>
            </div>
            <div class="row">
            <div class="input-field col l12 m12 s12">
                <textarea name="txtDescEta" id="txtDescEta" cols="30" rows="10" class="materialize-textarea"><?php echo $descripcionEta;?></textarea>
                <label for="txtDescEta" class="active">Descripción</label>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="action" >Actualizar</button>
            </div>
        </div>
        </form>
    </div>
</div>