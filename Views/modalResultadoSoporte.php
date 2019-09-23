<div class="modal-content center-align">
    <h4>Resultado de Soporte</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_missolicitudes.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l3 m3 s12 ">
                    <label for="codProy" class="active">Solicitud Específica No:</label>
                    <p class ="left-align">P<?php echo $idSol ;?></p>
                </div>
                <div class="input-field col l8 m8 s12 ">
                    <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                    <p class ="left-align"><?php echo $idProy. " - ".$nomProy ;?></p>
                </div>
                <div class="input-field col l8 m8 s12 ">
                    <label for="codProy" class="active">Descripción Solicitud Específica:</label>
                    <p class ="left-align"><?php echo $desSol ;?></p>
                </div>
                <div class="input-field col l3 m3 s12">
                    <label for="duraSer" class="active">Fecha prevista de entrega al cliente:</label>
                    <p class ="left-align"><?php echo $fechaPrev?></p>
                </div>
                <div class="input-field col l8 m8 s12 ">
                    <label for="monEqu" class="active">Equipo - Servicio:</label>
                    <p class ="left-align"><?php echo $equipo. " - ".$servicio;?></p>
                </div>
                <div class="input-field col l3 m3 s12">
                    <label for="duraSer" class="active">Duración del Servicio:</label>
                    <p class ="left-align"><?php echo $tiempoTotal?></p>
                </div>
                <div class="input-field col l5 m5 s12 ">
                    <input type="text" name="nomProd" id="nomProd" value="" require>
                    <label for="nomProd" class="active">Nombre Producto*</label>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
                    <input type="date" name="txtfechEntr" id="txtfechEntr" require>
                    <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente*</label>
                </div>
                <div class="input-field col l5 m5 s12 ">
                    <?php echo $sltRED;?>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
                    <?php echo $sltPlata;?>
                </div>
                <div class="input-field col l5 m5 s12">
                    <?php echo $sltClase;?>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1" id="sltModalTipo">
                </div>
                <div class="input-field col l8 m8 s12 ">
                    <input type="url" name="url" id="url" value="" >
                    <label for="url" class="active">URL:</label>
                </div>
                <div class="input-field col l11 m11 s12  left-align">
                    <textarea name="descripSer" id="descripSer" class="materialize-textarea" ></textarea>
                    <label for="descripSer" class="active">Labor realizada:</label>
                </div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button class="btn waves-effect waves-light" type="submit" name="btnActServicio">Guardar</button>
            </div>
        </form>
    </div>
</div>