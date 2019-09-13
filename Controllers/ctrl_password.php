<?php  
/* Inclusión del Modelo */
include_once "../Models/mdl_password.php";

/* Inicialización variables*/
$userPerfil     = (isset($_POST['SMPerfil'])) ? $_POST['SMPerfil'] : null;
$userId         = (isset($_POST['SMPersona'])) ? $_POST['SMPersona'] : null;
$userName       = (isset($_POST['txtloginPer'])) ? $_POST['txtloginPer'] : null;
$userPass       = (isset($_POST['txtpassPer'])) ? $_POST['txtpassPer'] : null;
$val            = (isset($_POST['val'])) ? $_POST['val'] : null;
$idLogin        = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idLogin2       = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$busqueda       = (isset($_REQUEST["SMPersona"])) ? $_REQUEST["SMPersona"] : null;
/* Procesamiento peticiones al controlador */
if (isset($_POST["SMPersona"]) AND ( ($userPerfil AND $userName AND $userPass AND $val) == "" )) {
    $resultado = Password::selectUser($busqueda);
}

if( (($userPerfil AND $userName AND $userId AND $userPass) != "") AND ($val == NULL) ){
    $resultado = Password::registroUser($userPerfil, $userName, $userId, $userPass);
}

?>