<?php
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar" data-url="../Controllers/ctrl_busDepartamento.php">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_departamento.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>DEPARTAMENTO</h4>

    <div class="row">
        <form action="../Controllers/ctrl_departamento.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <select id="selEntidad" name="selEntidad" required onchange="cargaSelect('#selEntidad','../Controllers/ctrl_departamento.php','#sltDinamico')">
                        <option value="" disabled selected>Seleccione</option>
                        <?php
                            include_once ('../Core/connection.php');

                            $consulta = "SELECT * FROM pys_entidades WHERE est = '1' ORDER BY nombreEnt;";

                            $resultado = mysqli_query($connection, $consulta);

                            $count = mysqli_num_rows($resultado);
            
                            if ($count==0){
                                echo "<script> alert ('No hay categorias registradas en la base de datos');</script>";
                            }else {
                                while ($datos = mysqli_fetch_array($resultado)){
                                    echo "<option value='". $datos["idEnt"] ."'> ". $datos["nombreEnt"] ." </option>";
                                }
                            }
                        ?>
                    </select>
                    <label>Entidad/Empresa</label>
                </div>
                <div id="sltDinamico" class="col l6 m6 s12 offset-l3 offset-m3"></div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input id="txtNomDepartamento" name="txtNomDepartamento" type="text" class="validate" required>
                    <label for="txtNomDepartamento">Nombre del departamento</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="btnGuardarDepto">Guardar</button>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1"></div>
</div>

<!-- Modal Structure -->
<div id="modalDepartamento" class="modal">
    <?php
        require('modalDepartamento.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');