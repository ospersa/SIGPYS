<?php
if (!isset($_SESSION['usuario'])) {
	session_start();
}
//Conectamos a la base de datos
require('../Core/connection.php');
include_once('../Models/mdl_login.php');


//Obtenemos los datos del formulario de acceso
$userPOST = (isset($_POST['usserName'])) ? $_POST["usserName"] : null;
$passPOST = (isset($_POST['password'])) ? $_POST["password"] : null;
$id		  = (isset($_POST["numCedula"])) ? $_POST["numCedula"] : null ;
$user 	  = (isset($_POST["nomUsu"])) ? $_POST["nomUsu"] : null ;
$pass 	  = (isset($_POST["txtpassPer"])) ? $_POST["txtpassPer"] : null ;
$invitado = (isset($_POST['invitado'])) ? $_POST['invitado'] : null;
$resul 	  = "";
$string = mysqli_real_escape_string($connection, $userPOST);
$validacion = false;

//Filtro anti-XSS
$userPOST = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

//Pasamos el input del usuario a minúsculas para compararlo después con
//el campo "usernamelowercase" de la base de datos
$userPOSTMinusculas = strtolower($userPOST);
$userPOSTMinusculas = mysqli_real_escape_string($connection, $userPOSTMinusculas);


//Escribimos la consulta necesaria
$consulta = "SELECT * FROM `pys_login` WHERE usrLogin ='".$userPOSTMinusculas."'";

//Obtenemos los resultados
$resultado = mysqli_query($connection, $consulta);
$datos = mysqli_fetch_array($resultado);
$hash = password_hash($passPOST, PASSWORD_DEFAULT, [15]);
//Guardamos los resultados del nombre de usuario en minúsculas
//y de la contraseña de la base de datos
$userBD = $datos['usrLogin'];
$passwordBD = $datos['passwLogin'];
$perfil = $datos['idPerfil'];
if (!empty($_POST['password'])){
	$validacion = password_verify($passPOST, $passwordBD);
}
if ($invitado == 1){
 echo 1;
} else {
	if (isset($_POST['txtpassPer'])){ //modal recuperacion de contraseña
		Login::cambiarPassword($user,$pass);
	} else if(isset($_POST['numCedula']) && isset($_POST['nomUsu'])){
		if ($id != null && $user != null){
			Login::validar($id, $user);
		} else {
			$json = array();
			$json[]     = array(
				'estado' => false,
				'datos'     =>'
				<div class=" teal darken-3 col l10 m10 s12 offset-l1 offset-m1">
					<h6 class=" white-text"> Valide que no hay ningun campo vacio </h6>
				</div>',
			);
			$jsonString = json_encode($json);
			echo $jsonString;
		}
	} 
	if(($userBD == $userPOSTMinusculas) && $validacion){ //Comprobamos si los datos son correctos
		if(!isset($_SESSION['usuario'])){
			$_SESSION['invitado'] = '';
			$_SESSION['usuario'] = $userBD;
			$_SESSION['estado'] = 'Autenticado';
			$_SESSION['perfil'] = $perfil;
		} 	
	//Si los datos no son correctos, o están vacíos, muestra un error
	//Además, hay un script que vacía los campos con la clase "acceso" (formulario)
	} else if ( $userBD != $userPOSTMinusculas || $passPOST != $passwordBD ) {
		die ('<script>
			var elementoMensaje = document.getElementById("message");
			elementoMensaje.disabled = false;		
			</script> User name or password incorrect');
	} else if ($userPOST != "" || $passPOST != "" ){
		die('<script>
		var elementoMensaje = document.getElementById("message");
		elementoMensaje.disabled = false;		
		</script> Error');
	}
}
?>