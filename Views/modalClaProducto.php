<?php 
require_once('../Controllers/ctrl_productos.php');
?>
<div class="modal-content center-align">
    <h4>Editar Clase de Producto</h4>
    <div class="row">
        <form id="actform" action="../Controllers/ctrl_productos.php" method="post" class="col l12 m12 s12">
            <input type="hidden" name="cod" id="cod">
            <div class="input-field col l12 m12 s12">
                <input type="hidden" name="sltEquipo" value="<?php echo $idEquipo;?>">
                <?php echo $selectEquipo;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="hidden" name="sltServicio" value="<?php echo $idServicio;?>">
                <?php echo $selectServicio;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="text" name="txtNombreClase" id="txtNombreClase" value="<?php echo $nombreClase;?>" required>
                <label for="txtNombreClase" class="active">Nombre clase de producto*</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="text" name="txtNombreCorClase" id="txtNombreCorClase" value="<?php echo $nombreCortoClase;?>">
                <label for="txtNombreCorClase" class="active">Nombre corto clase de producto</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <textarea name="txtDescripcionClase" id="txtDescripcionClase" class="materialize-textarea textarea"><?php echo $descripcionClase;?></textarea>
                <label for="txtDescripcionClase" class="active">Descripción</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="text" name="txtCostoClase" id="txtCostoClase" value="<?php echo $costoClase;?>">
                <label for="txtCostoClase" class="active">Costo clase $</label>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="btnActClaPro">Actualizar</button>
            </div>
        </form>
    </div>
</div>