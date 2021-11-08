<?php
/* Inclusión del Modelo */
require_once('../Models/mdl_infSeguimientoEstado.php');

/* Inicialización variables*/
$busqueda   = ( empty ( $_POST['txtBusquedaProy'] ) ) ? null : $_POST['txtBusquedaProy'] ;
$proyecto   = ( empty ( $_POST['sltProy'] ) ) ? null : $_POST['sltProy'] ;
$frenteinf  = ( empty ( $_POST['sltFrenteInf'] ) ) ? null : $_POST['sltFrenteInf'] ;
$gestor     = ( empty ( $_POST['sltGestor'] ) ) ? null : $_POST['sltGestor'] ;
$estado     = ( empty ( $_POST['estado'] ) ) ? null : $_POST['estado'] ;
$tiempos    = ( empty ( $_POST['tiempos'] ) ) ? null : $_POST['tiempos'] ;
$txtFechFin    = ( empty ( $_POST['txtFechFin'] ) ) ? null : $_POST['txtFechFin'] ;
$frente     = InformeSeguimientoEstados::selectFrente();
$gestores   = InformeSeguimientoEstados::selectGestor();

/* Verificación de envío de formulario para descarga del informe */
if (isset($_POST['btnDescargar'])) {
    InformeSeguimientoEstados::descarga($proyecto, $frenteinf, $gestor, $estado, $tiempos, $txtFechFin);
}


?>