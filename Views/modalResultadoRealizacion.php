<h4>Resultado de Realización</h4>
<div class="row">
    <form id="actForm" action="../Controllers/ctrl_missolicitudes.php" method="post" class="col l12 m12 s12">
        <div class="row">
            <input type="text" name="idSol" id="idSol" value="<?php echo $idSol;?>" hidden>
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
            <div class="input-field col l6 m12 s12 ">
                <label for="monEqu" class="active">Equipo - Servicio:</label>
                <p class="left-align"><?php echo $equipo. " - ".$nomProdOSer;?></p>
            </div>
            <div class="input-field col l3 m12 s12">
                <label for="duraSer" class="active">Duración del Servicio:</label>
                <p class="left-align"><?php echo $hora." h ".$min." m";?></p>
            </div>
            <div class="input-field col l5 m12 s12 ">
                <input type="text" name="nomProd" id="nomProd" value="<?php echo $nomProduc;?>" >
                <label for="nomProd" class="active">Nombre Producto*</label>
            </div>
            <div class="input-field col l5 m12 s12 offset-l1 ">
                <input class="datepicker" type="text" name="txtfechEntr" id="txtfechEntr"  value="<?php echo $fechaEntre;?>" >
                <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente*</label>
            </div>
            <div class="input-field col l11 m12 s12  left-align">
                <textarea name="sinopsis" id="sinopsis" class="materialize-textarea"><?php echo $sinopsis;?></textarea>
                <label for="sinopsis" class="active">Sinopsis:</label>
            </div>
            <div class="input-field col l5 m12 s12 ">
                <?php echo $sltRED;?>
            </div>
            <div class="input-field col l5 m12 s12 offset-l1 ">
                <?php echo $sltPlata;?>
            </div>
            <div class="input-field col l5 m12 s12">
                <?php echo $sltClase;?>
            </div>
            <div class="input-field col l5 m12 s12 offset-l1 " id="sltModalTipo">
            <?php 
            if ( !empty($tipo)){
                echo $sltTipo;
            }
            ?>
            </div>
            <div class="input-field col l11 m12 s12  left-align">
                <textarea name="labor" id="labor" class="materialize-textarea"><?php echo $labor;?></textarea>
                <label for="labor" class="active">Labor realizada:</label>
            </div>
            <div class="input-field col l11 m12 s12  left-align">
                <textarea name="autores" id="autores" class="materialize-textarea"><?php echo $autores;?></textarea>
                <label for="autores" class="active">Autores*:</label>
            </div>
            <div class="input-field col l11 m12 s12 ">
                <input type="text" name="url" id="url" value="<?php echo $url;?>">
                <label for="url" class="active">Enlace para inventario:</label>
            </div>
            <div class="input-field col l11 m12 s12 ">
                <input type="url" name="urlV" id="urlV" value="<?php echo $urlVimeo;?>">
                <label for="urlV" class="active">URL álbum de Vimeo:</label>
            </div>
            <div class="input-field col l12 m12 s12 ">
                <label class="active" >Duración Video</label>
            </div>
            <div class="input-field col l2 m12 s12 ">
                <input id="minutosDura" name="minutosDura" type="number" value="<?php echo $minDura;?>" min="0" max="59">
                <label class="active" for="minutosDura">Minutos</label>
            </div>
            <div class="input-field col l2 m12 s12 offset-m1 offset-l1">
                <input id="segundosDura" name="segundosDura" type="number" value="<?php echo $segDura;?>" min="0" max="59">
                <label class="active" for="segundosDura">Segundos</label>
            </div>            
        </div>
        <div class="input-field col l6 m12 s12 offset-l3 ">
            <button class="btn waves-effect waves-light" type="submit" name="btnGuaReal">Guardar</button>
        </div>
    </form>
</div>