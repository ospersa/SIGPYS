<div class="modal-content center-align">
    <h4>Editar/Eliminar Proyecto</h4>
    <div class="row">
        <form id="actForm" action="../Controllers/ctrl_proyecto.php" method="post" class="col l12 m12 s12">
            <input id="cod" name="cod" hidden>
			<input id="val" name="val" hidden>
            <?php
                include_once('../Models/mdl_proyecto.php');
                require('../Controllers/ctrl_proyecto.php');
            ?>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <?php Proyecto::selectEntidad($idProy);?>
                </div>
                <div class="input-field col l4 m4 s12" id="sltFacultadProy">
                    <?php Proyecto::selectFacultad($entidad, $idProy);?>
                </div>
                <div class="input-field col l4 m4 s12" id="sltDeptoProy">
                    <?php Proyecto::selectDepartamento($facultad, $idProy);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <input hidden name="txtFrente2" id="txtFrente2" value="<?php echo $idFrente;?>">
                    <input name="txtFrente" id="txtFrente" type="text" readonly value="<?php echo $frente;?>">
                    <label for="txtFrente" class="active">Frente</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <input hidden name="txtTipProy2" id="txtTipProy2" value="<?php echo $idTipoProy;?>">
                    <input name="txtTipProy" id="txtTipProy" type="text" readonly value="<?php echo $tipoProy;?>">
                    <label for="txtTipProy" class="active">Tipo Proyecto</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <?php Proyecto::selectTipoIntExt($tipoIntExt);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <?php Proyecto::selectEstado($estadoPry);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <?php Proyecto::selectEtapa($idEtapaPry, $idTipoProy);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <input name="txtCodProy" id="txtCodProy" type="text" value="<?php echo $codConectate;?>">
                    <label for="txtCodProy" class="active">Código Proyecto en Conecta-TE</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <textarea name="txtNomProy" id="txtNomProy" class="materialize-textarea"><?php echo $nombreProy;?></textarea>
                    <label for="txtNomProy" class="active">Nombre del Proyecto*</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <textarea name="txtNomCorProy" id="txtNomCorProy" class="materialize-textarea"><?php echo $nombreCorto;?></textarea>
                    <label for="txtNomCorProy" class="active">Diposnible SAP $</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <textarea name="txtContProy" id="txtContProy" class="materialize-textarea"><?php echo $contexto;?></textarea>
                    <label for="txtcontProy" class="active">Contexto del Proyecto</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <?php Proyecto::selectConvocatoria($convocatoria);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <input name="txtPresupuesto" id="txtPresupuesto" type="text" value="<?php echo $presupuesto;?>">
                    <label for="txtPresupuesto" class="active">Presupuesto Asignado ($)</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <?php Proyecto::selectFinancia($financia);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <input name="txtFechIni" id="txtFechIni" type="date" value="<?php echo "$fechIni";?>">
                    <label for="txtFechIni" class="active">Fecha de Inicio</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <input name="txtFechFin" id="txtFechFin" type="date" value="<?php echo "$fechFin";?>">
                    <label for="txtFechFin" class="active">Fecha de Cierre</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l4 m4 s12">
                    <?php echo Proyecto::selectCelula($idProy);?>
                </div>
                <div class="input-field col l4 m4 s12">
                    <input type="text" name="txtSemAcom" id="txtSemAcom" value="<?php echo $semanas; ?>">
                    <label for="txtSemAcom2" class="active">Semanas de Acompañamiento</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <?php echo Proyecto::selectFuenteFinanciacion($idProy);?>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <?php echo Proyecto::selectCeco($idProy);?>
                </div>
                <div id="divInfo4" class="input-field col l6 m6 s12"><?php Proyecto::selectElementoPep($idProy);?></div>
            </div>
            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <textarea name="txtAnulacion" id="txtAnulacion" class="materialize-textarea"></textarea>
                    <span class="helper-text"><strong>En caso de Suprimir, describa brevemente el motivo. <span class="red-text">SOLO SUPER ADMINISTRADOR</span></strong></span>
                    <label for="txtAnulacion" class="active">Motivo de Anulación</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 m6 s12">
                    <input readonly type="text" name="txtResponsable" id="txtResponsable" value="<?php echo $nombreCompleto;?>">
                    <label for="txtResponsable" class="active">Responsable</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input readonly type="text" name="txtFechColciencias" id="txtFechColciencias" value="<?php echo $fechaColciencias;?>">
                    <label for="txtFechColciencias" class="active">Fecha Colciencias</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <input readonly type="text" name="txtActualizacion" id="txtActualizacion" value="<?php echo $actualizacion;?>">
                    <label for="txtActualizacion" class="active">Última Actualización</label>
                </div>
            </div>
            <button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('3','Controllers/ctrl_proyecto.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('4','Controllers/ctrl_proyecto.php')">Actualizar</button>
        </form>
    </div>
</div>