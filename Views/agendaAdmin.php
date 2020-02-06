<?php
require('../Estructure/header.php');
include_once('../Controllers/ctrl_agendaAdmin.php');
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
                        <div  id="div_dinaPanel" ></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l5 m5 s12 offset-l1 offset-m1">
            <ul class="collapsible">
                <li class="active">
                    <div class="collapsible-header teal">
                        <h6 class="white-text">Planeación Registrada <span id="fechaA"></span></h6>
                    </div>
                    <div class="collapsible-body">
                        <div class="row">
                            <div class="row" id="div_dinamico2">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>    
        </div>
    </div>
</div>


<?php
require('../Estructure/footer.php');