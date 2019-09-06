<?php
//Iniciamos la sesiÃ³n
session_start();

//Evitamos que nos salgan los NOTICES de PHP
error_reporting(E_ALL ^ E_NOTICE);

//Comprobamos si la sesiÃ³n estÃ¡ iniciada
//Si existe una sesiÃ³n correcta, mostramos la pÃ¡gina para los usuarios
//Sino, mostramos la pÃ¡gina de acceso y registro de usuarios
if(isset($_SESSION['usuario']) and $_SESSION['estado'] == 'Autenticado') {
	header('Location: Views/home.php');
	die();
} else {
	header('Location: Views/login.php');
	die();
};