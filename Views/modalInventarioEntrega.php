<h4>Entrega de inventario Producto</h4>
<div class="row">
    <form id="actForm" action="../Controllers/ctrl_inventario.php" method="post" class="col l12 m12 s12">
        <div class="row">
        <input type="text" name="idSol" id="idSol" value="<?php echo $id; ?>" hidden>
            <div class="input-field col l3 m12 s12 ">
                <label for="idSol" class="active">Solicitud Específica No:</label>
                <p class="left-align">P<?php echo $idSol ;?></p>
            </div>
            <div class="input-field col l8 m12 s12 ">
                <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                <p class="left-align"><?php echo $idProy. " - ".$nomProy ;?></p>
            </div>
            <div class="input-field col l12 m12 s12 ">
                <label for="descSol" class="active">Descripción Solicitud Específica:</label>
                <p class="left-align"><?php echo $desSol ;?></p>
            </div>
            <div class="input-field col l3 m12 s12">
                <label for="duraSer" class="active">Fecha prevista de entrega al cliente:</label>
                <p class="left-align"><?php echo $fechaPrev?></p>
            </div>
            <div class="input-field col l7 m12 s12 ">
                <label for="monEqu" class="active">Equipo - Servicio:</label>
                <p class="left-align"><?php echo $equipo. " - ".$nomProdOSer;?></p>
            </div>
            <div class="input-field col l3 m12 s12 ">
                <?php echo $selectEstado ;?>
            </div>
            <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                <?php //echo $complemento ;?>
			</div>
            
        <div class="input-field col l6 m12 s12 offset-l3 ">
            <button class="btn waves-effect waves-light" type="submit" name="btnGuaInv">Guardar</button>
        </div>
    </form>
</div>