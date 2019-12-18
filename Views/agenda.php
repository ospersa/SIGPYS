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
            <div class="row" id="div_dinamico" ></div>
        </div>
    </div>
</div>


<?php
require('../Estructure/footer.php');
?>