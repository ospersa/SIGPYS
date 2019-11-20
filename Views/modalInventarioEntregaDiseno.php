<h4>Inventario Producto de Diseño</h4>
<div class="row">
    <div class="col s12 my-2">
        <ul class="tabs teal lighten-2">
            <li class="tab col s6"><a class="waves-effect waves-light white-text" href="#test1">Entrega de
                    inventario</a></li>
            <li class="tab col s6"><a class="waves-effect waves-light white-text" href="#test2">Actualizaciones</a></li>
    </div>
    <div id="test1" class="col s12 tab-container">
        <form id="actForm" action="../Controllers/ctrl_inventario.php" method="post" class="col l12 m12 s12">
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
                <div class="input-field col l5 m5 s12 offset-l6 offset-m6 "></div>  
                <div class="input-field col l6 m6 s12 ">
                    <input id="txtDisCarp" name="txtDisCarp" type="text" class="validate"
                        value="<?php echo $disCarp;?>">
                    <label for="txtDisCarp" class="active">Diseño-Carpeta</label>
                </div>

                <div class="input-field col l5 m5 s12 offset-l1 offset-m1 ">
                    <input id="txtDisPeso" name="txtDisPeso" type="text" class="validate"
                        value="<?php echo $disPeso;?>">
                    <label for="txtDisPeso" class="active">Diseño-Peso</label>
                </div>
                <?php if ($perfil == 'PERF01' || $perfil == 'PERF02'){
                    echo' 
                <div class="input-field col l6 m6 s12 ">
                    <select name="sltPerEnt" id="sltPerEnt">
                        '.$stlPerEnt.'
                    </select>
                    <label for="sltPerEnt">Persona que entrega</label>
                </div>
                <div class="input-field col l5 m5 s12 offset-l1 offset-m1 ">
                <label for="sltPerRec" class="active">Persona que recibe</label>
                    <p class="left-align">'.$nombreUser.'</p>
                    <input type="text" name="sltPerRec" id="sltPerRec" value="'.$idUser.'" hidden >
                </div>';
                } else {
                    echo'
                <div class="input-field col l6 m6 s12 ">
                    <label for="sltPerEnt" class="active">Persona que entrega</label>
                    <p class="left-align">'.$nombreUser.'</p>
                    <input type="text" name="sltPerEnt" id="sltPerEnt" value="'.$idUser.'"  >
                    </div>

                <div class="input-field col l5 m5 s12 offset-l1 offset-m1">
                    <select name="sltPerRec" id="sltPerRec">
                        '.$stlPerRec.'
                    </select>
                    <label for="sltPerRec">Persona que recibe</label>
                </div>';
                }
                ?>
                <div class="input-field col l12 m12 s12 ">
                    <input id="txtRutSer" name="txtRutSer" type="text" class="validate" value="<?php echo $rutSer;?>">
                    <label for="txtRutSer" class="active">Ruta-Servidor</label>
                </div>

                <div class="input-field col l12 m12 s12 ">
                    <textarea id="txtObs" name="txtObs" class="materialize-textarea"><?php echo $obs;?></textarea>
                    <label for="txtObs" class="active">Observaciones</label>
                </div>
                <?php if ($perfil == 'PERF01' || $perfil == 'PERF02'){
                    echo' 
                    <div class="input-field col l6 m12 s12 offset-l3 ">
                    <button class="btn waves-effect waves-light" type="submit" name="btnGuaInv">Guardar</button>
                </div>';
                }
                
                ?>

        </form>
    </div>
    <div id="test2" class="col s12 tab-container">
        <?php echo $tablaAct;?>
    </div>
</div>