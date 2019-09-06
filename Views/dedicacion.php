<?php
require('../Estructure/header.php');
?>

<div id="content" class="center-align">
    <h4>DEDICACIONES</h4>
    <div class="row">
        <form id="frmDedicaciones" action="../Controllers/ctrl_dedicacion.php" method="post" class="col l12 m12 s12" autocomplete="off">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3 select-plugin">
                    <select name="sltPeriodo" id="sltPeriodo">
                        <option value="" selected disabled>Seleccionar</option>
                        <?php
                        include_once ('../Core/connection.php');
                        $cod = $_REQUEST['cod'];
                        $consulta = "SELECT * FROM pys_periodos ORDER BY inicioPeriodo DESC;";
                        $resultado = mysqli_query($connection, $consulta);
                        $count = mysqli_num_rows($resultado);
                        if ($count == 0){
                            echo "<script> alert ('No hay periodos registrados en la base de datos');</script>";
                        } else {
                            while ($datos =mysqli_fetch_array($resultado)){
                                if ($cod == $datos['idPeriodo']) {
                                        echo "<option value='". $datos["idPeriodo"] ."' selected> Periodo del: ". $datos["inicioPeriodo"].' al '. $datos["finPeriodo"].' '."</option>";
                                }
                                else {
                                    echo "<option value='". $datos["idPeriodo"] ."'> Periodo del: ". $datos["inicioPeriodo"].' al '. $datos["finPeriodo"].' '."</option>";
                                }
                            }
                        }
                        
                        mysqli_close($connection);
                        ?>
                    </select>
                    <label for="sltPeriodo">Periodo</label>
                </div>
            </div>
            <div class="row">
                <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
                    <?php
                    if ($cod > 0) {
                        require "../Models/mdl_personas.php";
                        $resultado = Personas::validarDatos($cod);
                        if ($resultado) {
                            $resultado = Personas::completarInfo($cod);
                        } else {
                            $resultado = Personas::listPersonas($cod);
                        }
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDedicacion" class="modal">
    <?php
        require('modalDedicacion.php');
    ?>
</div>
<?php
require('../Estructure/footer.php');
