<?php
/* Inclusión del Modelo */
require('../Models/mdl_infPlaneacionXls.php');

/* Inicialización variables*/
$periodo    = (isset($_POST['sltPeriodoPlan'])) ?  $_POST['sltPeriodoPlan'] : null;
$persona    = (isset($_POST['sltPersonaPlan'])) ?  $_POST['sltPersonaPlan'] : null;
$persona1   = (isset($_POST['persona'])) ?  $_POST['persona'] : null;
$periodo1   = (isset($_POST['periodo'])) ?  $_POST['periodo']: null;

/* Procesamiento peticiones al controlador */
if (isset($_POST['consultar'])) {
    $resultado = InformePlaneacion::consultaDatosPeriodo($periodo, $persona);
} else if (isset($_POST['sltPeriodoPlan']) && $persona == null){
   $periodo = $_POST['sltPeriodoPlan'];
   $resultado = InformePlaneacion::listarPlaneacionPeriodo($periodo);
} else if ($persona1 != null && $periodo1 != null) {
    $resultado = InformePlaneacion::listarPlaneacionPeriodoPersona($periodo1, $persona1);
}
?>