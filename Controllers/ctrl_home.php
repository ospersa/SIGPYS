<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
$usuario    = $_SESSION['usuario'];
$perfil     = $_SESSION['perfil'];
/* Inclusión del Modelo */
include_once("../Models/mdl_home.php");
include_once('../Models/mdl_menu.php');

/* Inicialización de Variables */
$chart      = (isset($_POST['action'])) ? $_POST['action'] : null;

/* Carga de información en el Modal */

      
/* Procesamiento peticiones al controlador */
$agenda = Home::agendaDia($usuario);
$solicitudes = Home::solicitudesAsig($usuario);
$validarAse = Menu:: validar ($usuario);
$fechaActual = date("d-m-Y"); 

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
}  else if ($chart == "Proy"){
    if($validarAse){
        echo Home::presupuestoProyect($usuario, 2);
    } else {
        echo Home::presupuestoProyect($usuario, 1);
    }
}
?>
