
    <h4>Resultado de servicio</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_missolicitudes.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <input type="text" name="idSol" id="idSol" value="<?php echo $idSol;?>" hidden>
                <input type="text" name="idSer" id="idSer" value="<?php echo $idSer;?>" hidden>
                <div class="input-field col l8 m12 s12 ">
                    <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                    <p class ="left-align"><?php echo $idProy. " - ".$nomProy ;?></p>
                </div>
                <div class="input-field col l2 m12 s12">
                    <label for="prodOSerNo" class="active">Producto/Servicio No:</label>
                    <p class ="left-align">P<?php echo $idSol;?></p>
                </div>
                <div class="input-field col l8 m12 s12 ">
                    <label for="monEqu" class="active">Equipo - Servicio:</label>
                    <p class ="left-align"><?php echo $equipo. " - ".$nomProdOSer;?></p>
                </div>
                <div class="input-field col l3 m12 s12">
                    <label for="duraSer" class="active">Duración del Servicio:</label>
                    <p class ="left-align"><?php echo $hora." h ".$min." m"?></p>
                </div>

                <div class="input-field col l12 m12 s12 ">
                    <label for="descripSol" class="active">Descripción Producto/Servicio:</label>
                    <p class ="left-align"><?php echo $desSol;?></p>
                </div>
                <div class="input-field col l6 m12 s12 ">
                    <?php echo $sltPlata;?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m12 s12">
                <?php echo $sltClase;?>
                </div>
                <div class="input-field col l5 m12 s12 offset-l1  " id="sltModalTipo">
                    <?php if ( !empty($tipo)){
                        echo $sltTipo;
                    }
                    ?>
                </div>
                <div class="input-field col l12 m12 s12  left-align">
                    <textarea name="descripSer" id="descripSer" class="materialize-textarea" ><?php echo $observacion;?></textarea>
                    <label for="descripSer" class="active">Descripción Producto/Servicio:</label>
                </div>
                <div class="input-field col l2 m12 s12">
                    <input type="number" name="numEst" id="numEst" min = 0 value = "<?php echo $estudiantesImpac;?>">
                    <label for="numEst" class="active">Numero de estudiantes:</label>
                </div>
                <div class="input-field col l2 m12 s12 offset-l1">
                    <input type="number" name="numDoc" id="numDoc" min = 0 value = "<?php echo $docentesImpac;?>" > 
                    <label for="numDoc" class="active">Numero de docentes:</label>
                </div>
                <div class="input-field col l6 m12 s12 offset-l1">
                    <input type="url" name="url" id="url" value="<?php echo $url;?>" >
                    <label for="url" class="active">URL:</label>
                </div>
            </div>
            <div class="input-field col l6 m12 s12 offset-l3">
                <button class="btn waves-effect waves-light" type="submit" name="btnActServicio">Guardar</button>
            </div>
        </form>
    </div>
