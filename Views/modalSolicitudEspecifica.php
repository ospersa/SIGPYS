<h4>Editar Producto/Servicio</h4>
<div class="row">
    <form id="actFormSolEs" action="../Controllers/ctrl_solicitudEspecifica.php" method="post" class="col l12 m12 s12">
        <input id="cod" name="cod" type="hidden">
        <input id="val" name="val" type="hidden">
        <input id="valbus" name="valbus" type="hidden">
        <?php
        require_once('../Controllers/ctrl_solicitudEspecifica.php');
        require_once('../Controllers/ctrl_solicitud.php');
        ?>
        <div class="input-field col l4 m4 s12">
            <input type="text" name="txtSolIni" id="txtSolIni" value="<?php echo $solIni;?>" readonly>
            <label for="txtSolIni" class="active">Cód. Solicitud inicial</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="text" name="txtSolEsp" id="txtSolEsp" value="<?php echo "P".$solEsp;?>" readonly>
            <label for="txtSolEsp" class="active">Cód. Producto/Servicio</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="hidden" name="txtIdTipoSol" value="<?php echo $idTipoSol;?>">
            <input type="text" name="txtTipoSol" id="txtTipoSol" value="<?php echo $nombreTipo;?>" readonly>
            <label for="txtTipoSol" class="active">Tipo de Producto/Servicio</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <?php echo Solicitud::selectEstadoSolicitud($idEstadoSol, $equipo);?>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="number" name="txtPresupuesto" id="txtPresupuesto" value="<?php echo $presupuesto;?>">
            <label for="txtPresupuesto" class="active">Presupuesto</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="text" name="txtHoras" id="txtHoras" value="<?php echo $horas;?>">
            <label for="txtHoras" class="active">Máximo horas a invertir</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <?php echo SolicitudEspecifica::selectEquipo($equipo);?>
        </div>
        <div id="sltServicio2" class="input-field col l8 m8 s12">
            <?php SolicitudEspecifica::selectServicio($equipo, $servicio);?>
        </div>
        <div class="input-field col l8 m8 s12">
            <input type="hidden" name="txtCodCM" value="<?php echo $idCM;?>">
            <textarea name="txtProyecto" id="txtProyecto" class="materialize-textarea textarea" readonly><?php echo $proyecto;?></textarea>
            <label for="txtProyecto" class="active">Proyecto</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="date" name="txtFechaPrev" id="txtFechaPrev" value="<?php echo $fechaPrev;?>">
            <label for="txtFechaPrev" class="active">Fecha prevista entrega</label>
        </div>
        <div class="input-field col l12 m12 s12">
            <textarea name="txtObservacion" id="txtObservacion" class="materialize-textarea textarea"><?php echo $observacion;?></textarea>
            <label for="txtObservacion" class="active">Descripción Producto/Servicio</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="text" name="txtFechCreación" id="txtFechCreación" value ="<?php echo $fechCreacion; ?>" readonly>
            <label for="txtFechCreación" class="active">Fecha creación</label>
        </div>
        <div class="input-field col l4 m4 s12">
            <input type="text" name="txtUltActualizacion" id="txtUltActualizacion" value ="<?php echo $ultActualizacion;?>" readonly>
            <label for="txtUltActualizacion" class="active">Última actualización</label>
        </div>
        <div class="input-field col l4 m4 s12">
        <p>
            <label>
                <input class="filled-in" name="txtRegistrarT" id="txtRegistrarT" type="checkbox" <?php echo $RegistraTiempo;?>/>
                <span>Habilitar registro de tiempos en este producto/servicio.</span>
            </label>
        </p>
        </div>
        <div class="input-field col l12 m12 s12">
                <button class="btn waves-effect waves-light" type="submit" onclick="actSolEsp();" name="btnActualizarSolEsp">Actualizar</button>
        </div>
    </form>
</div>