<?php
//Conectamos a la base de datos
require('../Core/connection.php');
include_once('../Models/mdl_login.php');


//Obtenemos los datos del formulario de acceso
$userPOST = (isset($_POST['usserName'])) ? $_POST["usserName"] : null;
$passPOST = (isset($_POST['password'])) ? $_POST["password"] : null;
$id		  = (isset($_POST["numCedula"])) ? $_POST["numCedula"] : null ;
$user 	  = (isset($_POST["nomUsu"])) ? $_POST["nomUsu"] : null ;
$resul 	  = "";
$string = mysqli_real_escape_string($connection, $userPOST);

//Filtro anti-XSS
$userPOST = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

//Pasamos el input del usuario a minúsculas para compararlo después con
//el campo "usernamelowercase" de la base de datos
$userPOSTMinusculas = strtolower($userPOST);

//Escribimos la consulta necesaria
$consulta = "SELECT * FROM `pys_login` WHERE usrLogin ='".$userPOSTMinusculas."'";

//Obtenemos los resultados
$resultado = mysqli_query($connection, $consulta);
$datos = mysqli_fetch_array($resultado);

//Guardamos los resultados del nombre de usuario en minúsculas
//y de la contraseña de la base de datos
$userBD = $datos['usrLogin'];
$passwordBD = $datos['passwLogin'];
$perfil = $datos['idPerfil'];

//Comprobamos si los datos son correctos
if(($userBD == $userPOSTMinusculas) and ($passPOST == $passwordBD)){

	//session_start();
	$_SESSION['usuario'] = $userBD;
	$_SESSION['estado'] = 'Autenticado';
	$_SESSION['perfil'] = $perfil;

//Si los datos no son correctos, o están vacíos, muestra un error
//Además, hay un script que vacía los campos con la clase "acceso" (formulario)
} else if ( $userBD != $userPOSTMinusculas || $userPOST == "" || $passPOST == "" || $passPOST != $passwordBD ) {
	die ('<script>$(".login").val("");</script> User name or password incorrect');
} else {
	die('Error');
}
//modal recuperacion de contraseña
if(isset($_POST['ValidarUser'])){
	$resul = Login::validar($id, $user);
}

