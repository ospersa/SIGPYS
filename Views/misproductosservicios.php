<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>Mis Productos/Servicios</h4>
</div>
<div class="row">
    <div class="col l10 m10 s12 offset-l1 offset-m1">
        <p class="left-align teal-text">
            Para consultar solicitudes de producto/servicio, ingrese el código del producto/servicio, código o nombre de
            proyecto Conecta-TE.
        </p>
        <nav>
            <div class="nav-wrapper teal lighten-4">
                <div class="input-field">
                    <input id="txt-search" name="txt-search" type="search"
                        data-url="../Controllers/ctrl_missolicitudes.php">
                    <label class="label-icon" for="txt-search"><i class="material-icons">search</i></label>
                    <i class="material-icons ico-clean">close</i>
                </div>
            </div>
        </nav>
    </div>
</div>
<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12">
        <?php
            include_once ('../Controllers/ctrl_missolicitudes.php');
        ?>
    </div>
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">description</i>
        </a>
        <ul>
            <li><a href="https://uniandes.sharepoint.com/:w:/s/PyS/EXxfoIefc4xNm3HQpazshTkB73kDruiIFXBFoXRliF4h4Q?e=alXb1a" target="_blank" class="btn-floating tooltipped blue" data-position="left" data-tooltip="Manual estados Diseño"><i class="material-icons">description</i></a></li>
            <li><a href="https://uniandes.sharepoint.com/:w:/s/PyS/EVZzVZ8mYORBrkXgbn-7foAB9CrRYchhIRntG2UPMk3BKw?e=PJTEdh" target="_blank" class="btn-floating tooltipped blue" data-position="left" data-tooltip="Manual estados Realización"><i class="material-icons">description</i></a></li>
            <li><a href="https://uniandes.sharepoint.com/:w:/s/PyS/EWL5LhxuGFRImvc65DtSSzUBdQSYkcy78d_vWM0oXzu4uQ?e=qwg3LA" target="_blank" class="btn-floating tooltipped green" data-position="left" data-tooltip="Manual metadata Diseño"><i class="material-icons">description</i></a></li>
            <li><a href="https://uniandes.sharepoint.com/:w:/s/PyS/Eeq6-oGHa4BOju2joGNlXEQB2ssa0SWjK_YlIlL5edPoPQ?e=Yxn1Fe" target="_blank" class="btn-floating tooltipped green" data-position="left" data-tooltip="Manual metadata Realización"><i class="material-icons">description</i></a></li>
        </ul>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalResultadoServicio" class="modal">
    <div class="modal-content center-align df">
    <?php
        require('modalResultadoServicio.php');
    ?>
    </div>
</div>

<?php
require('../Estructure/footer.php');
?>