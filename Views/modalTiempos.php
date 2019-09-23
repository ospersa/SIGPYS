
<div class="modal-content center-align">
    <h4>Registro de tiempos</h4>
    <div class="row">
        <div class="col s12 my-2">
            <ul class="tabs teal lighten-2">
                <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test1">Tiempos</a></li>
                <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test2">Resumen tiempo invertido</a></li>
                <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test3">Resumen Producto/Servicio</a></li>
                <li class="indicator"></li>
            </ul>
        </div>
        <div id="test1" class="col s12 tab-container">
            <ul class="collapsible">
                <li class="active">
                    <div class="collapsible-header teal-text"><h6>Registrar Tiempos</h6></div>
                    <div class="collapsible-body pd-0">
                        <div class="row">
                            <form id="actForm" action="../Controllers/ctrl_regtime.php" method="post">
                                <input id="idSol" name="idSol" value="<?php echo $idSolEsp;?>"type="hidden">    
                                <div class="input-field col s12 m2 l2">
                                    <input id="date" name="date" type="text" class="datepicker">
                                    <label for="date">Fecha</label>
                                </div>
                                <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                                    <input id="horas" name="horas" type="number" value="0" min="0" max="12">
                                    <label class="active" for="horas">Horas</label>
                                </div>
                                <div class="input-field col s12 m1 l1 offset-m1 offset-l1">
                                    <input id="minutos" name="minutos" type="number" value="0" min="0" max="59">
                                    <label class="active" for="minutos">Minutos</label>
                                </div>
                                <div class="input-field col s12 m5 l5 offset-m1 offset-l1">
                                    <?php echo Tiempos::selectFase(null)?> 
                                </div>
                                <div class="input-field col s12">
                                    <textarea id="notaT" name ="notaT" class="materialize-textarea textarea"></textarea>
                                    <label for="notaT">Nota:</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="submit" class="waves-effect waves-light btn" name="btnRegTiempo" value="Guardar">
                                </div>
                            </form>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header teal-text"><h6>Tiempos Registrados</h6></div>
                    <div class="collapsible-body pd-0">
                        <div class="row">
                            <?php echo $tiempoRegistrado;?>
                            <div id="fondo" class="overlay-edit hide"></div>
                            <div id="editRegistro" class="editTimes hide">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="test2" class="col s12 tab-container">
        <?php echo $tiempoInvertido;?>
    </div>
    <div id="test3" class="col s12 tab-container">
        <input id="cod" name="cod" type="hidden" value="" disabled>
        <div class="col s12  left-align">
            <label class="active" for="idSol">Cod. Producto/Servicio</label>
            <p id="idSol" name="idSol" type="text"><?php echo $idSolEsp; ?></p>

        </div>
        <div class="col s12  left-align">
            <label class="active" for="codProyecto">Cod. Proyecto</label>
            <p id="codProyecto" name="codProyecto" type="text"><?php echo $codProy; ?></p>
        </div>
        <div class="col s12  left-align m6 l6 offset-m1 offset-l1">
            <label class="active" for="nomProyecto">Nom. Proyecto</label>
            <p id="nomProyecto" name="nomProyecto" type="text"><?php echo $nomProy; ?></p>
        </div>
        <div class="col s12  left-align">
            <label class="active" for="descSol">Solicitud</label>
            <p id="descSol" name="descSol" type="text" class=""><?php echo $solicitud; ?></p>
        </div>
    </div>
</div>
</div>