<?php
include_once('../Models/mdl_infTiempos.php');

$fechIni = (empty($_POST['txtFechIni'])) ? null : $_POST['txtFechIni'];
$fechFin = (empty($_POST['txtFechFin'])) ? null : $_POST['txtFechFin'];

if (!empty($_POST['txtFechIni']) && !empty($_POST['txtFechFin']) && !isset($_POST['btnDescargar'])) {
    $tabla = InformeTiemposProductoServicio::busqueda($fechIni, $fechFin);
    echo $tabla;
} else if (isset($_POST['btnDescargar'])) {
    InformeTiemposProductoServicio::descarga($fechIni, $fechFin);
} else {
    echo '  <div class="row" id="mensaje">
                <div class="col l7 m7 s12 offset-l2 offset-m2">
                    <div class="card orange darken-1">
                        <div class="card-content white-text">
                        <span class="card-title"><strong>Informe de Tiempos - Productos/Servicios</strong></span>
                        <div class="divider"></div>
                        <p><strong>Por favor seleccione la fecha inicial y final para realizar la consulta.</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $("#mensaje").fadeOut(8000, function() {
                    $(this).remove();
                });
            </script>';
}
    
?>