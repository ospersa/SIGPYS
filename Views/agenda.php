<?php
require('../Estructure/header.php');
include_once('../Controllers/ctrl_agenda.php');

$hoy = (isset($_REQUEST['hoy'])) ? $_REQUEST['hoy'] : null;

if( !empty( $hoy ) ){
    echo '
    <script> window.onload= function() {
        seleccionarDia(\''.$hoy.'\');
    };
    </script>';
}
?>
<div id="content" class="center-align">
    <h4>Ver Agenda</h4>
    <div class="row">
        <div class="col l6 m6 s12">
            <div class="card">
                <div class="card-content">
                    <div id="card-stats" class="row">
                        <div class="dia ">
                        <h6>Lunes</h6>
                        </div>
                        <div class="dia ">
                        <h6> Martes</h6>
                        </div>
                        <div class="dia ">
                        <h6>Miércoles</h6>
                        </div>
                        <div class="dia ">
                        <h6>Jueves</h6>
                        </div>
                        <div class="dia ">
                            <h6>Viernes</h6>
                        </div>
                        <div class="dia ">
                            <h6>Sabado</h6>
                        </div>
                        <div class="dia ">
                            <h6>Domingo</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $panel;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l6 m6 s12">
            <ul class="collapsible">
                <li>
                    <div class="collapsible-header teal white-text">
                        <h6 class="white-text">Registrar Planeación <span id="fechaA"></span></h6>
                    </div>
                    <div class="collapsible-body">
                        <div class="row">
                            <form id="proyAgend" action="../Controllers/ctrl_agenda.php" method="post">
                                <div class="row btnmas ">


                                    <a class="sumarDiv btn btn-floating waves-effect waves-light teal tooltipped"
                                        data-position="top" data-tooltip="Añadir Actividad" onclick="duplicarDiv()"><i
                                            class="material-icons">add</i></a>
                                    <button id="btn-guardar" class="btn waves-effect btn-floating btn-large teal white-text waves-light botonGuardar tooltipped" type="submit"
                                        name="btnGuardar" data-position="left" data-tooltip="Guardar Planeacion"><i class="large material-icons white-text">save</i></button>
                                </div>
                                <input type="hidden" class="validate" name="fecha" id="fechaDia" value="">
                               
                                <div id="div_dinamico1"></div>

                            </form>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header teal">
                        <h6 class="white-text">Planeación Registrada</h6>
                    </div>
                    <div class="collapsible-body">
                        <div class="row">

                            <div id="div_dinamico2"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="input-field col l4 m4 s12  ">
    <button data-target="modalAgenda" class="botonRegistrarT btn btn-large btn-floating waves-effect teal white-text modal-trigger tooltipped disabled" name="btnRegTiempo"
    id="btnRegTiempo" data-position="left" data-tooltip="Registrar tiempo"><i class="large material-icons white-text">timer</i></button>
</div>
<div id="modalAgenda" class="modal">
    <?php
        require('modalAgenda.php');
    ?>
</div>

<?php
    require('../Estructure/footer.php');

?>