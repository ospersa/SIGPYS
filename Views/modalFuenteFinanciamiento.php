<div class="modal-content center-align">
    <h4>Editar Fuente Financiación</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_fuenteFinanciamiento.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden">
                <input id="val" name="val" type="hidden">
                <?php 
                    require('../Controllers/ctrl_fuenteFinanciamiento.php');
                ?>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtFteFin" name="txtFteFin" type="text" value="<?php echo $nombre;?>">
                    <label for="txtFteFin" class="active">Nombre Fuente Financiación</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtSiglaFteFin" name="txtSiglaFteFin" type="text"  value="<?php echo $sigla;?>" required>
                    <label for="txtSiglaFteFin" class="active">Sigla Fuente Financiación*</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_fuenteFinanciamiento.php')">Actualizar</button>
        </form>
    </div>
</div>