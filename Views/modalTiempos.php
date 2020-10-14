<h4>Más Información</h4>
<div class="row">
    <div class="col s12 my-2">
        <ul class="tabs teal lighten-2">
            <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test1">Tiempo Individual</a></li>
            <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test2">Tiempo Grupal</a></li>
            <li class="tab col s4"><a class="waves-effect waves-light white-text" href="#test3">Información Producto/Servicio</a></li>
            <li class="indicator"></li>
        </ul>
    </div>
    <div id="test1" class="col s12 tab-container">
        <ul class="collapsible">
            <!--<li>
                <div class="collapsible-header teal-text">
                    <h6>Registrar Tiempos</h6>
                </div>
                <div class="collapsible-body pd-0">
                    <div class="row">
                        <form id="actForm" action="../Controllers/ctrl_regtime.php" method="post">
                            <input id="idSol" name="idSol" value="<?php echo $idSolEsp;?>" type="hidden">
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
                                <textarea id="notaT" name="notaT" class="materialize-textarea textarea"></textarea>
                                <label for="notaT">Nota:</label>
                            </div>
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light btn" id="btnRegTiempo" name="btnRegTiempo"
                                    >Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>-->
            <li class="active">
                <div class="collapsible-header teal-text">
                    <h6>Tiempos Registrados</h6>
                </div>
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