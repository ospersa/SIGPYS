<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
$usuario    = $_SESSION['usuario'];
$chart      = (isset($_POST['action'])) ? $_POST['action'] : null;
/* Inclusión del Modelo */
include_once("../Models/mdl_home.php");

/* Inicialización de Variables */

/* Carga de información en el Modal */

      
/* Procesamiento peticiones al controlador */
$agenda = Home::agendaDia($usuario);
$solicitudes = Home::solicitudesAsig($usuario);

if ($chart == "Sol"){
    echo Home::solicitudes ($usuario);
} else if ($chart == "Tiem"){
    echo Home::tiempo($usuario);
} else if ($chart == "SolIni"){
    echo Home::solIniSinEsp();
} else if ($chart == "Inve"){
    echo Home::productosInventario();
} else if ($chart == "Coti"){
    echo Home::productoSinCotizacion();
}
?>
