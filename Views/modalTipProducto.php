<?php 
require_once('../Controllers/ctrl_productos.php');
?>
<div class="modal-content center-align">
    <h4>Editar Tipo Producto</h4>
    <div class="row">
        <form id="actform" action="../Controllers/ctrl_productos.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" type="hidden">
            <div class="input-field col l12 m12 s12">
                <input type="hidden" name="sltEquipo" value="<?php echo $idEquipo;?>">
                <?php echo $selectEquipo;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="hidden" name="sltServicio" value="<?php echo $idServicio;?>">
                <?php echo $selectServicio;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <?php echo $selectClase;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <input id="txtNombreTipo" name="txtNombreTipo" type="text" value="<?php echo $nombreTipo;?>">
                <label for="txtNombreTipo" class="active">Nombre tipo producto*</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <textarea name="txtDescripcionTipo" id="txtDescripcionTipo" class="materialize-textarea textarea"><?php echo $descripcionTipo?></textarea>
                <label for="txtDescripcionTipo" class="active">Descripción</label>
            </div>
            <div class="input-field col l4 m4 s12">
                <input type="text" name="txtCostoSin" id="txtCostoSin" value="<?php echo $costoSin;?>">
                <label for="txtCostoSin" class="active">Costo sin $</label>
            </div>
            <div class="input-field col l4 m4 s12">
                <input type="text" name="txtCostoCon" id="txtCostoCon" value="<?php echo $costoCon;?>">
                <label for="txtCostoCon" class="active">Costo con $</label>
            </div>
            <div class="input-field col l4 m4 s12">
                <input type="text" name="txtCostoTipo" id="txtCostoTipo" value="<?php echo $costoTipo;?>">
                <label for="txtCostoTipo" class="active">Costo tipo $</label>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="btnActTipProd">Actualizar</button>
            </div>
        </form>
    </div>
</div>