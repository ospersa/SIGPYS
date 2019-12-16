<?php
require('../Estructure/header.php');
?>
<div id="content" class="center-align">
    <h4>PRESUPUESTO</h4>
    <h5>Información Solicitud:</h5>
    <div class="container">
        <form id="frmCotizador" action="../Controllers/ctrl_cotizacion.php" method="post" autocomplete="off" onKeyPress="if(event.keyCode == 13) event.returnValue = false;">
            <?php
            include_once('../Controllers/ctrl_cotizacion.php');
            ?>
            <div class="input-field col l2 m2 s12">
                <input readonly hidden id="txtIdProy" name="txtIdProy" type="text" value="<?php echo $idProyecto; ?>">
                <input readonly hidden id="txtVal" name="txtVal" type="text" value="<?php echo $solicitante; ?>">
                <input readonly hidden id="txtCot" name="txtCot" type="text" value="<?php echo $cotizacion; ?>">
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12">
                    <input readonly id="txtCodProy" name="txtCodProy" type="text" value="<?php echo $codProyecto; ?>">
                    <label for="txtCodProy" class="active">Código Proyecto</label>
                </div>
                <div class="input-field col l5 m5 s12">
                    <input readonly id="txtNomProy" name="txtNomProy" type="text" value="<?php echo $nomProyecto; ?>">
                    <label for="txtNomProy" class="active">Nombre Proyecto</label>
                </div>
                <div class="input-field col l5 m5 s12">
                    <input readonly id="txtSolicitante" name="txtSolicitante" type="text" value="<?php echo $nombreCompleto; ?>">
                    <label for="txtSolicitante" class="active">Solicitante</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12">
                    <input readonly id="txtSolIni" name="txtSolIni" type="text" value="<?php echo $solIni; ?>">
                    <label for="txtSolIni" class="active">Solicitud Inicial</label>
                </div>
                <div class="input-field col l10 m10 s12">
                    <textarea readonly id="txtDescIni" name="txtDescIni" type="text" class="materialize-textarea"><?php echo $descIni; ?></textarea>
                    <label for="txtDescIni" class="active">Descripción Solicitud</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l2 m2 s12">
                    <input readonly id="txtSolEsp" name="txtSolEsp" type="text" value="P<?php echo $cod; ?>">
                    <label for="txtSolEsp" class="active">Código Producto/Servicio</label>
                </div>
                <div class="input-field col l10 m10 s12">
                    <textarea readonly id="txtDescEsp" name="txtDescEsp" type="text" class="materialize-textarea"><?php echo $descEsp; ?></textarea>
                    <label for="txtDescEsp" class="active">Descripción Producto/Servicio</label>
                </div>
            </div>
            <div class="divider" id="infCotizacion"></div>
            <div class="row">
                <h5>Información Presupuesto:</h5>
                <div class="input-field col l2 m2 s12">
                    <input required <?php echo $read;?> id="txtValCotizacion" name="txtValCotizacion" id="txtValCotizacion" type="text" step="any" value="$ <?php echo number_format($valorCot, 2, ",", ".");?>" onkeyup="calcula()"/>
                    <label for="txtValCotizacion" class="active">Valor Cotización</label>
                    <span hidden id="txtValCot"><?php echo $valorCot;?></span>
                </div>
                <div class="input-field col l4 m4 s12">
                    <textarea <?php echo $read;?> name="txtObsSolicitante" id="txtObsSolicitante" class="materialize-textarea"><?php echo $obsSol;?></textarea>
                    <label for="txtObsSolicitante" class="active">Observación Presupuesto al Solicitante</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <textarea <?php echo $read;?> name="txtObsPyS" id="txtObsPyS" class="materialize-textarea"><?php echo $obsPys;?></textarea>
                    <label for="txtObsPyS" class="active">Observación Presupuesto para PYS</label>
                </div>
                <div class="input-field col l2 m2 s12">
                    <input readonly id="txtDiferencia" name="txtDiferencia" type="text" step="0.01" value="0">
                    <label for="txtDiferencia" class="active">Dif. Cot. / Asig.</label>
                </div>
                <div class="input-field col l12 m12 s12">
                <textarea <?php echo $read;?> name="txtDescProd" id="txtDescProd" class="materialize-textarea"><?php echo $obsProd;?></textarea>
                    <label for="txtDescProd" class="active">Especificación del Producto</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3" <?php echo $hidden;?>>
                    <button class="btn waves-effect waves-light" type="submit" name="enviar">Guardar</button>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3" <?php echo $hidden2;?>>
                    <a href="#modalCotizacion" class="waves-effect btn modal-trigger" onclick="envioData('C_<?php echo $cotizacion;?>','modalCotizacion.php')">Editar</a>
                    <a <?php echo $disabledMail;?> href="#modalMailCotizacion" class="waves-effect btn modal-trigger"onclick="envioData('C_<?php echo $cotizacion;?>','modalMailCotizacion.php')">Enviar Correo</a>
                </div>
            </div>
            <div class="row">
                <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"><?php $result3 = Cotizacion::mostrarAsignados($cod);?></div>
            </div>
            <div class="divider"></div>
            <div class="row">
            <h5>Asignar Personas:</h5>
                <div class="input-field col l3 m3 s12 select-plugin">
                    <select name="sltRol" type="text" class="asignacion">
                        <option value="" selected disabled>Seleccionar</option>
                        <?php 
                        require('../Core/connection.php');
                        $consulta = "SELECT * 
                            FROM pys_roles 
                            WHERE est =1 
                            AND (idRol = 'ROL001' OR idRol = 'ROL002' OR idRol = 'ROL003' OR idRol = 'ROL004' OR idRol = 'ROL005' OR idRol = 'ROL006' OR idRol = 'ROL008' OR idRol = 'ROL010' OR idRol = 'ROL016' OR idRol = 'ROL024' OR idRol = 'ROL025')
                            ORDER BY nombreRol;";
                        $resultado = mysqli_query($connection, $consulta);
                        $registros = mysqli_num_rows($resultado);
                        if ($registros == 0) {
                            echo "<script>alert ('No hay roles registrados en el sistema');</script>";
                        }
                        while ($datos = mysqli_fetch_array($resultado)) {
                            echo "<option value='". $datos["idRol"] ."'> ".$datos["nombreRol"]."</option>";
                        }
                        mysqli_close($connection);
                        ?>
                    </select>
                    <label for="sltRol">Rol</label>
                </div>
                <div class="input-field col l4 m4 s12 select-plugin">
                    <select name="sltPersona" type="text" class="asignacion">
                        <option value="" selected disabled>Seleccionar</option>
                        <?php 
                        require('../Core/connection.php');
                        $consulta = "SELECT * 
                            FROM pys_personas 
                            WHERE est = '1' 
                            AND ((idCargo = 'CAR012') OR (idCargo = 'CAR013') OR (idCargo = 'CAR014') OR (idCargo = 'CAR015') OR (idCargo = 'CAR016') OR (idCargo = 'CAR017') OR (idCargo = 'CAR019') OR (idCargo = 'CAR027') OR (idCargo = 'CAR033') OR (idCargo = 'CAR035') OR (idCargo = 'CAR036'))
                            ORDER BY apellido1;";
                        $resultado = mysqli_query($connection, $consulta);
                        $registros = mysqli_num_rows($resultado);
                        if ($registros == 0) {
                            echo "<script>alert ('No hay personas registradas en el sistema');</script>";
                        }
                        while ($datos = mysqli_fetch_array($resultado)) {
                            echo "<option value='". $datos["idPersona"] ."'> ".$datos["apellido1"].' '. $datos["apellido2"].' '. $datos["nombres"]."</option>";
                        }
                        mysqli_close($connection);
                        ?>
                    </select>
                    <label for="sltPersona">Persona</label>
                </div>
                <div class="input-field col l2 m2 s12 select-plugin">
                    <select id="sltFase" name="sltFase" type="text" class="asignacion">
                        <option value="" selected disabled>Seleccionar</option>
                        <?php 
                        require('../Core/connection.php');
                        $consulta = "SELECT * FROM pys_fases WHERE est = 1;";
                        $resultado = mysqli_query($connection, $consulta);
                        $registros = mysqli_num_rows($resultado);
                        if ($registros == 0) {
                            echo "<script>alert ('No hay fases registradas en el sistema');</script>";
                        }
                        while ($datos = mysqli_fetch_array($resultado)) {
                            echo "<option value='". $datos["idFase"] ."'> ".$datos["nombreFase"]."</option>";
                        }
                        mysqli_close($connection);
                        ?>
                    </select>
                    <label for="sltFase">Fase</label>
                </div>
                <div class="input-field col l1 m1 s12">
                    <input id="txtHoras" name="txtHoras" type="number" class="asignacion">
                    <label for="txtHoras">Horas</label>
                </div>
                <div class="input-field col l1 m1 s12">
                    <input id="txtMinutos" name="txtMinutos" type="number" class="asignacion">
                    <label for="txtMinutos">Minutos</label>
                </div>
                <div class="input-field col l1 m1 s12">
                    <button class="btn waves-effect" type="submit" name="btnAsignar" id="btnAsignar">Asignar</button>
                </div>
            </div>
            
            <div class="divider"></div>
            <div class="row" <?php echo $hidden2;?>>
                <h5>Aprobación de Presupuesto:</h5>
                <div class="input-field col l5 m4 s12">
                    <textarea <?php echo $readNota;?> name="txtObsAprobacion" id="txtObsAprobacion" class="materialize-textarea"><?php echo $notaAprobacion;?></textarea>
                    <label for="txtObsAprobacion" class="active">Nota de aprobación de la solicitud:</label>
                </div>
                <div class="input-field col l4 m4 s12">
                    <input <?php echo $readEnlace;?> type="url" name="txtEnlAprobacion" id="txtEnlAprobacion" class="materialize-textarea" value="<?php echo $enlaceAprobacion;?>">
                    <label for="txtEnlAprobacion" class="active">Enlace sharepoint de la aprobación:</label>
                </div>
                <div class="input-field col l3 m3 s12">
                    <button <?php echo $disabled;?> class="btn waves-effect waves-light" type="submit" name="btnAprobar" id="btnAprobar">Autorizar Inicio</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalAsignados" class="modal">
    <?php
    require('modalAsignados.php');
    ?>
</div>
<div id="modalCotizacion" class="modal">
    <?php
    require('modalCotizacion.php');
    ?>
</div>
<div id="modalMailCotizacion" class="modal">
    <?php
    require('modalMailCotizacion.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');
?>