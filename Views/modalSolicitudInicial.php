<div class="modal-content center-align">
    <h4>Editar Proyecto de Contenido Digital (PCD)</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_solicitud.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" type="hidden">
            <input id="val" name="val" type="hidden">
            <?php
                include_once('../Controllers/ctrl_solicitudInicial.php');
                include_once('../Controllers/ctrl_solicitud.php');
            ?>
            <input type="hidden" id="txtIdCM" name="txtIdCM"
                value="<?php echo $idCM; ?>">
            <input type="hidden" id="txtEstProy" name="txtEstProy"
                value="<?php echo $estProy; ?>">
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <input required name="txtIdSol" id="txtIdSol" type="text" readonly
                        value="<?php echo $idSolicitud;?>">
                    <label for="txtIdSol" class="active">CPCD:</label>
                </div>
                <!-- <div class="input-field col l4 m4 s12">
                    <label for="txtTipoSol" class="active">Tipo de Solicitud:</label>
                </div> -->
                <input required name="txtTipoSol" id="txtTipoSol" type="hidden" readonly
                    value="<?php echo $tipoSolicitud;?>">
                <div class="input-field col l8 m8 s12 green-text">
                    <?php echo Solicitud::selectEstadoSolicitudInicial($idEstado, "");?>
                </div>
            </div>
            <input required name="txtRegistro" id="txtRegistro" type="hidden" readonly
                value="<?php echo $registro;?> ">
            <!-- <div class="row">
                <div class="input-field col l12 m12 s12">
                    <label for="txtRegistro" class="active">Registró*</label>
                </div>
            </div> -->
            <div class="row">
                <div class="input-field col l4 m4 s4">
                    <input required name="txtProyecto2" id="txtProyecto2" type="text" readonly
                        value="<?php echo $codProyecto;?>">
                    <label for="txtProyecto2" class="active">Cod. Proyecto*</label>
                </div>
                <div class="input-field col l8 m8 s8">
                    <input required name="txtProyecto2" id="txtProyecto2" type="text" readonly
                        value="<?php echo $proyecto;?>">
                    <label for="txtProyecto2" class="active">Proyecto*</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <input name="txtFechPrev2" id="txtFechPrev2" type="date"
                        value="<?php echo $fechPrev;?>">
                    <label for="txtFechPrev2">Fecha prevista entrega</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <textarea name="txtObservacion2" id="txtObservacion2"
                        class="materialize-textarea textarea"><?php echo $observacion;?></textarea>
                    <label for="txtObservacion2" class="active">Descripción Proyecto de Contenido digital (PCD):</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l3 m3 s12">
                    <input required name="txtActualizacion" id="txtActualizacion" type="text" readonly
                        value="<?php echo $fechActualizacion;?>">
                    <label for="txtActualizacion" class="active">Fecha Actualización</label>
                </div>
                <div class="input-field col l9 m9 s12">
                    <?php echo SolicitudInicial::selectSolicitante($idSolicitante);?>
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit"
                    name="btnActualizarSolIni">Actualizar</button>
            </div>
        </form>
    </div>
</div>