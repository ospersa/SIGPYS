<?php
/* Inicializar variables de sesión */
if(!isset($_SESSION)) { 
    session_start();
}
/* Inclusión del Modelo */
include_once "../Models/mdl_agenda.php";
include_once "../Models/mdl_personas.php";

/* Inicialización variables*/
$fech   = (isset($_POST['fech'])) ? $_POST['fech'] : null;
$idper  = (isset($_POST['idper'])) ? $_POST['idper'] : null;
$cod    = (isset($_POST['cod'])) ? $_POST['cod'] : null;  //cod = 3 Para cargar el panel de la persona seleccionada 

/* Procesamiento peticiones al controlador */
$usuario  = PlaneacionAse::UsuarioPersona($idper);
$idPeriodo = PlaneacionAse::onPeriodoActual();
$personas = Personas::selectPersonas2($idPeriodo);
if ($cod == 3){
    echo $panel = PlaneacionAse::onPeriodo($idPeriodo, $usuario);
} else if (isset($_POST['fech'])){
    echo PlaneacionAse:: mostrarAgendaAdmin ($fech, $usuario);

}
?>