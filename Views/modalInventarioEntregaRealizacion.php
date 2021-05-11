<h4>Entrega de inventario Producto de Realización</h4>
<div class="row">
    <div class="col s12 my-2">
        <ul class="tabs teal lighten-2">
            <li class="tab col s6">
                <a class="waves-effect waves-light white-text" href="#test1">Entrega de inventario</a>
            </li>
            <li class="tab col s6">
                <a class="waves-effect waves-light white-text" href="#test2">Actualizaciones</a>
            </li>
        </ul>
    </div>
    <div id="test1" class="col s12 tab-container">
        <form id="actForm" action="../Controllers/ctrl_inventario.php" method="post">
            <input type="text" name="idProducto" id="idProducto" value="<?php echo $idProducto; ?>" hidden>
            <input type="text" name="idInventario" id="idInventario" value="<?php echo $idInventario; ?>" hidden>
            <input type="text" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>" hidden>
            <input type="text" name="idSol" id="idSol" value="<?php echo $id; ?>" hidden>
            <div class="input-field col l2 m12 s12 ">
                <label for="idSol" class="active">Código PS:</label>
                <p class="left-align">P<?php echo $idSol ;?></p>
            </div>
            <div class="input-field col l10 m12 s12 ">
                <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                <p class="left-align"><?php echo $idProy. " - ".$nomProy ;?></p>
            </div>
            <div class="input-field col l12 m12 s12 ">
                <label for="descSol" class="active">Descripción Solicitud Específica:</label>
                <p class="left-align"><?php echo $desSol ;?></p>
            </div>
            <div class="input-field col l2 m12 s12">
                <label for="duraSer" class="active">Fecha prev. entrega cliente:</label>
                <p class="left-align"><?php echo $fechaPrev?></p>
            </div>
            <div class="input-field col l7 m12 s12 ">
                <label for="monEqu" class="active">Equipo - Servicio:</label>
                <p class="left-align"><?php echo $equipo. " - ".$nomProdOSer;?></p>
            </div>
            <div class="input-field col l3 m12 s12 ">
                <label class="active">Estado:</label>
                <p class="left-align"><?php echo $selectEstado ;?></p>
            </div>     
            <div class="input-field col l6 m6 s12 ">
                <input id="txtCrudosCarp" name="txtCrudosCarp" type="text" class="validate" value="<?php echo $crudosCarp;?>">
                <label for="txtCrudosCarp" class="active">Crudos - Carpeta</label>
            </div>
            <div class="input-field col l6 m6 s12">
                <input id="txtCrudosPes" name="txtCrudosPes" type="text" class="validate" value="<?php echo $crudosPes;?>">
                <label for="txtCrudosPes" class="active">Crudos - Peso</label>
            </div>
            <div class="input-field col l6 m6 s12 ">
                <input id="txtProyCarp" name="txtProyCarp" type="text" class="validate" value="<?php echo $proyCarp;?>">
                <label for="txtProyCarp" class="active">Proyecto - Carpeta</label>
            </div>
            <div class="input-field col l6 m6 s12">
                <input id="txtProyPeso" name="txtProyPeso" type="text" class="validate" value="<?php echo $proyPeso;?>">
                <label for="txtProyPeso" class="active">Proyecto - Peso</label>
            </div>
            <div class="input-field col l6 m6 s12 ">
                <input id="txtFinCarp" name="txtFinCarp" type="text" class="validate" value="<?php echo $finCarp;?>">
                <label for="txtFinCarp" class="active">Finales - Carpeta</label>
            </div>
            <div class="input-field col l6 m6 s12">
                <input id="txtFinPeso" name="txtFinPeso" type="text" class="validate" value="<?php echo $finPeso;?>">
                <label for="txtFinPeso" class="active">Finales - Peso</label>
            </div>
            <div class="input-field col l6 m6 s12 ">
                <input id="txtRecCarp" name="txtRecCarp" type="text" class="validate" value="<?php echo $recCarp;?>">
                <label for="txtRecCarp" class="active">Recursos - Carpeta</label>
            </div>
            <div class="input-field col l6 m6 s12">
                <input id="txtRecPeso" name="txtRecPeso" type="text" class="validate" value="<?php echo $recPeso;?>">
                <label for="txtRecPeso" class="active">Recursos - Peso</label>
            </div>
            <div class="input-field col l6 m6 s12 ">
                <input id="txtDocCarp" name="txtDocCarp" type="text" class="validate" value="<?php echo $docCarp;?>">
                <label for="txtDocCarp" class="active">Documento - Carpeta</label>
            </div>
            <div class="input-field col l6 m6 s12">
                <input id="txtDocPeso" name="txtDocPeso" type="text" class="validate" value="<?php echo $docPeso;?>">
                <label for="txtDocPeso" class="active">Documento - Peso</label>
            </div>
            <?php if ($perfil == 'PERF01' || $perfil == 'PERF02'){
                echo '
            <div class="input-field col l6 m6 s12 ">
                <select name="sltPerEnt" id="sltPerEnt">
                    '.$stlPerEnt.'
                </select>
                <label for="sltPerEnt">Persona que entrega</label>
            </div>
            <div class="input-field col l6 m6 s12">
            <label for="sltPerRec" class="active">Persona que recibe</label>
                <p class="left-align">'.$nombreUser.'</p>
                <input type="text" name="sltPerRec" id="sltPerRec" value="'.$idUser.'" hidden >
            </div>';
            } else {
                echo '
            <div class="input-field col l6 m6 s12 ">
                <label for="sltPerEnt" class="active">Persona que entrega</label>
                <p class="left-align">'.$nombreUser.'</p>
                <input type="text" name="sltPerEnt" id="sltPerEnt" value="'.$idUser.'" hidden >
            </div>
            <div class="input-field col l6 m6 s12">
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
                <textarea id="txtObs" name="txtObs" class="materialize-textarea" ><?php echo $obs;?></textarea>
                <label for="txtObs" class="active">Observaciones</label>
            </div>
            <?php if ($perfil == 'PERF01' || $perfil == 'PERF02'){
                echo '
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