<?php
/* Inclusión del Modelo */
require('../Models/mdl_infNominaXls.php');

/* Variables que cargan Select en formularios*/
$selectAnio = InformeNomima::selectAnioInforme();

/* Procesamiento peticiones al controlador */
if (isset($_POST['sltAnio']) && !isset($_POST['sltMes'])) {
    $anio = $_POST['sltAnio'];
    echo "  <script>
                $('#btnGenerar').empty();
                $('#div_dinamico').empty();
            </script>";
    echo $selectMes = InformeNomima::selectMes($anio);
}

if (isset($_POST['sltAnio']) && isset($_POST['sltMes']) && isset($_POST['consultar'])) {
    $anio = $_POST['sltAnio'];
    $mes = $_POST['sltMes'];
    $selectMes = InformeNomima::descargarExcel($anio, $mes);
}

if (isset($_POST['sltMes']) && !isset($_POST['sltAnio'])) {
    echo "  <script>
                $('#div_dinamico').empty();
            </script>";
    InformeNomima::mostrarBotonConsultar();
}

if (isset($_POST['sltAnio']) && isset($_POST['sltMes']) && !isset($_POST['consultar'])) {
    echo "  <script>
                $('#btnGenerar').empty();
            </script>";
    $anio = $_POST['sltAnio'];
    $mes = $_POST['sltMes'];
    InformeNomima::informePreliminar($anio, $mes);
}



?>