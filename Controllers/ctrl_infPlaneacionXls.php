<?php
$periodo = $_POST['sltPeriodoPlan'];
$persona = $_POST['sltPersonaPlan'];
$persona1 = $_POST['persona'];
$periodo1 = $_POST['periodo'];
require('../Models/mdl_infPlaneacionXls.php');
if (isset($_POST['consultar'])) {
    $resultado = InformePlaneacion::consultaDatosPeriodo($periodo, $persona);
} else if (isset($_POST['sltPeriodoPlan']) && $persona == null){
   $periodo = $_POST['sltPeriodoPlan'];
   $resultado = InformePlaneacion::listarPlaneacionPeriodo($periodo);
} else if ($persona1 != null && $periodo1 != null) {
    $resultado = InformePlaneacion::listarPlaneacionPeriodoPersona($periodo1, $persona1);
}
?>