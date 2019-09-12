<?php  
/* Inclusión del Modelo */
include_once "../Models/mdl_password.php";

/* Inicialización variables*/
$userPerfil     = $_POST['SMPerfil'];
$userId         = $_POST['SMPersona'];
$userName       = $_POST['txtloginPer'];
$userPass       = $_POST['txtpassPer'];
$val            = $_POST['val'];
$idLogin        = $_REQUEST['id'];
$idLogin2       = $_POST['cod'];

/* Procesamiento peticiones al controlador */
if (isset($_POST["SMPersona"]) AND ( ($userPerfil AND $userName AND $userPass AND $val) == "" )) {
    $busqueda = $_REQUEST["SMPersona"];
    $resultado = Password::selectUser($busqueda);
}

if( (($userPerfil AND $userName AND $userId AND $userPass) != "") AND ($val == NULL) ){
    $resultado = Password::registroUser($userPerfil, $userName, $userId, $userPass);
}

?>