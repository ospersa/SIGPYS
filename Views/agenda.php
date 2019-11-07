<?php
require('../Estructure/header.php');
include_once('../Controllers/ctrl_agenda.php');
?>
<div id="content" class="center-align">
    <h4>Ver planeación</h4>
    <div class="row">
        <div class="col l6 m6 s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col s6 m6 l6"><?php echo $personas;?></div>
                    </div>
                    <div id="card-stats" class="row">
                        <div class="dia col s2 m2 l2">
                            <h6>Lunes</h6>
                        </div>
                        <div class="dia col s2 m2 l2">
                            <h6>Martes</h6>
                        </div>
                        <div class="dia col s2 m2 l2">
                            <h6>Miércoles</h6>
                        </div>
                        <div class="dia col s2 m2 l2">
                            <h6>Jueves</h6>
                        </div>
                        <div class="dia col s2 m2 l2">
                            <h6>Viernes</h6>
                        </div>
                        <div class="dia col s2 m2 l2">
                            <h6>Sabado</h6>
                        </div>
                        <?php echo $panel;?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col l5 m5 s12 offset-l1 offset-m1">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <ul class="collapsible">
                            <li>
                                <div class="collapsible-header">Registrar Planeación</div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <form action="../Controllers/ctrl_agenda.php" method="post"
                                            class="col l12 m12 s12">
                                            <div class="input-field">               
                                                <?php echo $selectProyecto;?>
                                                <div id="div_produc" class="col l10 m10 s12 ">
                                                <?php
                                                include_once('../Controllers/ctrl_agenda.php');
                                                ?>
                                        </div>
                                            </div>

                                            <button id="btn-pass" class="btn waves-effect waves-light" type="submit"
                                                name="btnGuardar">Guardar</button>

                                        </form>
                                    </div>
                                </div>

                            </li>
                            <li>
                                <div class="collapsible-header">Planeación Registrada</div>
                                <div class="collapsible-body">
                                        <div id="div_dinamico" class="col l10 m10 s12 ">
                                            <h6><strong> Nombre del Proyecto:</strong></h6>
                                            <hr>
                                            <?php
                        //include_once('../Controllers/ctrl_agenda.php');
                        ?>
                                        </div>
                                </div>
                            </li>
                        </ul>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php
require('../Estructure/footer.php');