<?php  
/* Inclusión del Modelo */
include_once "../Models/mdl_password.php";

/* Inicialización variables*/
$search         = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$userPerfil     = (isset($_POST['sltPerfil'])) ? $_POST['sltPerfil'] : null;
$userPerfil2     = (isset($_POST['sltPerfil2'])) ? $_POST['sltPerfil2'] : null;
$userId         = (isset($_POST['SMPersona'])) ? $_POST['SMPersona'] : null;
$userName       = (isset($_POST['txtloginPer'])) ? $_POST['txtloginPer'] : null;
$userPass       = (isset($_POST['txtpassPer'])) ? $_POST['txtpassPer'] : null;
$idLogin        = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idLogin2       = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$busqueda       = (isset($_REQUEST["SMPersona"])) ? $_REQUEST["SMPersona"] : null;
$busUsuario     = (isset($_REQUEST["txt-usu"])) ? $_REQUEST["txt-usu"] : null;
$user           ="";

/* Carga select */
$selectPerfil= Password::selectPerfil(null);
if (isset($_POST["txt-usu"]) AND ( ( $userName AND $userPass) == "" )){
    echo $resultado = Password::selectBuscarUsuario($busUsuario);
} else if (isset($_POST["SMPersona"]) AND ( ($userName AND $userPass) == "" )) {
    $resultado = Password::buscarUserCorreo($busqueda, null);    
}
/*Carga de información en el Modal */
if ($idLogin){
    $info = Password::onLoad($idLogin);
    $idlogin = $info['idLogin'];
    $idPersona = $info['idPersona'];
    $nomUsuario = $info['nombres'].' '.$info['apellido1'].' '.$info['apellido2'];
    $userName = $info['usrLogin'];
    $userPass = $info['passwLogin'];
    $userPerfil = $info['idPerfil'];
    $nomPerfil = $info['nombrePerfil'];
    $selectPerfil2 = Password::selectPerfil($userPerfil); 
    $user = Password::buscarUserCorreo ($idPersona, 'modal');  
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['txt-search'])) {
    $busqueda = ($search == null) ? Password::busquedaTotal() : Password::busqueda($search);
} else if (isset($_POST['btnGuardarPassword'])) {
    Password::registroUser($userPerfil, $userName, $userId, $userPass);
} else if (isset($_POST["btnActPassword"])) {
    ( !empty($userPass) ) ? Password::ActualizarConPassword($idLogin2, $userPerfil2, $userName,$userPass) : Password::Actualizar($idLogin2, $userPerfil2, $userName); 
} else if (isset($_POST["btnincPassword"])) {
    Password::Inactivar($idLogin2); 
}


?>