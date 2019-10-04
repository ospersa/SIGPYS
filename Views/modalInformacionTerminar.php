<h4>Más información sobre Producto/Servicio</h4>
<div class="row">
    <div class="col s12 my-2">
        <ul class="tabs teal lighten-2">
            <li class="tab col s6"><a class="waves-effect waves-light white-text" href="#test1">Resumen tiempo
                    invertido</a></li>
            <li class="tab col s6"><a class="waves-effect waves-light white-text" href="#test2">Información
                    Producto/servicio</a></li>
            <li class="indicator"></li>
        </ul>
    </div>
</div>

<div id="test1" class="col s12 tab-container">
    <?php echo $tiempoInvertido;?>
</div>
<div id="test2" class="col s12 tab-container">
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