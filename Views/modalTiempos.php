<?php
    include_once('../Controllers/ctrl_regtime.php')
?>
<div class="modal-content center-align">
    <h4>Registro de tiempos</h4>
    <div class="row">
        <div class="col s12 my-2">
            <ul class="tabs">
                <li class="tab col s4"><a href="#test1">Registro tiempo</a></li>
                <li class="tab col s4"><a href="#test2">Resumen tiempo invertido</a></li>
                <li class="tab col s4"><a href="#test3">Resumen Producto/Servicio</a></li>
            </ul>
        </div>
        <form id="actForm" action="../Controllers/ctrl_regtime.php" method="post">
            <div id="test1" class="col s12 tab-container">
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
                    <select>
                        <option value="" disabled selected>Choose your option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                    <label>Fase</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="textarea1" class="materialize-textarea textarea"></textarea>
                    <label for="textarea1">Nota:</label>
                </div>
                <div class="input-field col s12">
                    <input type="submit" class="waves-effect waves-light btn" value="Guardar">
                </div>
            </div>
        </form>
        <div id="test2" class="col s12 tab-container">
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Temporibus reprehenderit exercitationem
                architecto distinctio recusandae. Accusamus deleniti quod ad labore minima quam consequuntur
                voluptatum eaque, sapiente, ducimus ullam dignissimos at laborum.</p>
        </div>
        <div id="test3" class="col s12 tab-container">
            <input id="cod" name="cod" type="hidden" value="" disabled>
            <div class="input-field col s12 m2 l2">
                <input id="idSol" name="idSol" type="text" value="P<?php echo $idSolEsp; ?>" disabled>
                <label class="active" for="idSol">Cod. Producto/Servicio</label>
            </div>
            <div class="input-field col s12 m2 l2 offset-m1 offset-l1">
                <input id="codProyecto" name="codProyecto" type="text" value="<?php echo $codProy; ?>" disabled>
                <label class="active" for="codProyecto">Cod. Proyecto</label>
            </div>
            <div class="input-field col s12 m6 l6 offset-m1 offset-l1">
                <input id="nomProyecto" name="nomProyecto" type="text" value="<?php echo $nomProy; ?>" disabled>
                <label class="active" for="nomProyecto">Nom. Proyecto</label>
            </div>
            <div class="col s12  left-align">
                <label class="active" for="descSol">Solicitud</label>
                <p id="descSol" name="descSol" type="text" class=""><?php echo $solicitud; ?></textarea>
            </div>
        </div>
    </div>
</div>