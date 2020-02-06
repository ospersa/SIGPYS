<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_visitante.php');
?>

<div id="content" class="center-align">
    <h4>Formulario de solicitud de recursos educativos digitales o servicios al equipo PyS</h4>
</div>
<div class="section container">
    <div class="row">
        <div class="col s12">
            <form id="visitantesid">
                <div class="section">
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="busqueda" name="busqueda" required />
                            <label for="busqueda">Buscar cod. Proyecto Conecta-TE*:</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <select name="smProy" id="smProy" required>
                                <option value="" disabled selected>Seleccione</option>
                            </select>
                            <label for="smProy">Proyecto*:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col l4 m4 s12">
                            <textarea name="cordinador" id="cordinador" placeholder="Cordinador(es)"
                                class="materialize-textarea" disabled></textarea>
                            <label for="cordinador">Cordinador(es)*:</label>
                        </div>
                        <div class="input-field col l4 m4 s12">
                            <textarea name="gestor" id="gestor" placeholder="Asesor/Gestor RED"
                                class="materialize-textarea" disabled></textarea>
                            <label for="gestor">Asesor/Gestor RED*:</label>
                        </div>
                        <div class="input-field col l4 m4 s12">
                            <select name="smSolicitante" id="smSolicitante" required>
                                <option value="" selected>Seleccione</option>
                                <?php echo $selectSolicitante;?>
                            </select>
                            <label for="smSolicitante">Solicitante*:</label>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row section">
                    <a class="waves-effect waves-light btn" id="adding" onclick="duplicarDivVis()">+
                        Producto/Servicio</a>
                </div>
                <div class="row">
                    <div class="card-panel col s12 m4 l4 cardVis text" id="cardVis1">
                        <a class=" btn btn-floating right waves-effect waves-light red" onclick="eliminarDivVis(1)"><i
                                class="material-icons">delete</i></a>
                        <div class="input-field col s12">
                            <select id="text[]" name="text[]" required>
                                <option value="">Seleccione</option>
                                <option value="Diseño">Diseño</option>
                                <option value="Realización">Realización</option>
                                <option value="Soporte">Soporte</option>
                            </select>
                            <label for="text[]">Producto/Servicio de:*</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="text[]" name="text[]" class="materialize-textarea" required></textarea>
                            <label for="text[]">Producto/Servicio*</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="text[]" name="text[]" class="materialize-textarea" required></textarea>
                            <label for="text[]">Descripción Producto/Servicio*</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="text[]" name="text[]" class="materialize-textarea "></textarea>
                            <label for="text[]">Contexto de uso del Producto/Servicio</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="text[]" name="text[]" class="materialize-textarea "></textarea>
                            <label for="text[]">Profesor</label>
                        </div>

                        <div class="input-field col s12">
                            <input type="number" id="text[]" name="text[]" class=" " />
                            <label for="text[]">Monto máximo a invertir</label>
                        </div>

                        <div class="input-field col s12">
                            <input id="date" name="text[]" class="datepicker" />
                            <label class="active" for="dare">Fecha de pre entrega</label>
                        </div>

                        <div class="input-field col s12">
                            <input id="date1" name="text[]" class="datepicker" required />
                            <label class="active" for="date1">Fecha esperada de entrega*</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="divider"></div>
                    <div class="section">
                        <button class="btn waves-effect waves-light" type="submit" onclick="mostrardivVisit()" name="btn1">Registrar
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="loaderVisit" class="white scale-transition registrando hide">
    <h4 class="teal-text">Registrando</h4><br>
    <p>Estamos procesando su solicitud.</p><br>
    <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-teal-only">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div>
            <div class="gap-patch">
                <div class="circle"></div>
            </div>
            <div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
</div>
<?php
require('../Estructure/footer.php');
?>