<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_asignados.php');
?>

<div id="content" class="center-align">
    <h4>Carga masiva de información desde Excel</h4>
    <div class="row">
        <form class="col l12 m12 s12" action="" method="post" name="frmExcelImport" id="frmExcelImport"
            enctype="multipart/form-data">
            <div class="file-field input-field col l6 m6 s12 offset-l3 offset-m3">
                <div class="btn">
                    <span>Archivo</span>
                    <input required type="file" name="file" accept=".xls, .xlsx">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <select required id="operacion"  name="operacion" onchange="inicializarCampos();">
                    <option value="" selected>Seleccione</option>
                    <option value="servicios">Carga de servicios</option>
                    <option value="productos">Carga de productos</option>
                </select>
                <label for="operacion">Operación a realizar</label>
            </div>
            <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                <button type="submit" id="submit" name="import" class="btn">Importar</button>
            </div>
        </form>
    </div>
</div>

<div id="div_response"></div>

<?php
require('../Estructure/footer.php');
?>