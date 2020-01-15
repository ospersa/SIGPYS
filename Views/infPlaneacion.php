<?php
require('../Estructure/header.php');
include_once('../Controllers/ctrl_infPlaneacionXls.php');
?>
<div id="container" class="center-align">
    <h4>INFORME PLANEACIÓN</h4>
    <div class="row">
        <form action="../Controllers/ctrl_infPlaneacionXls.php" method="post">
            <div class="row">
                <div class="input-field col l4 m4 s12 offset-l1 select-plugin">
                    <select name="sltPeriodoPlan" id="sltPeriodoPlan">
                        <option value="0">Seleccionar</option>
                        <?php
                        require ('../Core/connection.php');
                        $cod = $_REQUEST['cod'];
                        $consulta = "SELECT * FROM pys_periodos WHERE estadoPeriodo = 1 ORDER BY inicioPeriodo DESC;";
                        $resultado = mysqli_query($connection, $consulta);
                        $count = mysqli_num_rows($resultado);
                        if ($count == 0){
                            echo "<script> alert ('No hay periodos registrados en la base de datos' );</script>";
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
                    <label for="sltPeriodoPlan">Periodo</label>
                </div>
                <div id="div_sltdinamico" class="col l4 m4 s12 offset-l1 offset-m1 select-plugin"></div>
            </div>
            <div class="row">
                <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
            </div>
        </form>
    </div>
    
</div>
<?php
require('../Estructure/footer.php');
?>