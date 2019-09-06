<?php
require('../Estructure/header.php');
require('../Controllers/ctrl_busPersona.php');
require('../Controllers/ctrl_busPersonaProy.php');
?>

<div id="content" class="center-align">
    <h4>PLANEACIÓN</h4>
    <div class="row">
        <form id="frmPlaneacion" action="../Controllers/ctrl_planeacion.php" method="post" class="col l10 m10 s12 offset-l1 offset-m1" autocomplete="off">
            <?php
                $idPersona = Personas::idPersona($_SESSION['usuario']);
                $perfil = $_SESSION['perfil'];
                if ($perfil == 'PERF03') {
                    $idPeriodo = Personas::consultaPeriodo();
                    $planear = Planeacion::mostrarInfoPeriodo($idPeriodo, $idPersona);
                    if ($planear) {
                        $resultado = Planeacion::verificarInfo($idPeriodo, $idPersona);
                        if ($resultado) {
                            $resultado = Planeacion::listarProyectos2($idPeriodo, $idPersona);
                        } else {
                            $resultado = Planeacion::listarProyectos($idPeriodo, $idPersona);
                        }
                    }
                } else {
                    echo '  <div class="row">
                                <div class="input-field col l5 m5 s12 select-plugin">
                                    '.Personas::selectPeriodo().'
                                </div>
                                <div id="div_sltdinamico" class="col l5 m5 s12 offset-l1 offset-m1 select-plugin input-field"></div>
                            </div>';
                }
            ?>
            <div class="row">
                <div id="div_dinamico" name="div_dinamico" class="col l12 m12 s12"></div>
            </div>
        </form>
    </div>
</div>

<?php
require('../Estructure/footer.php');
?>