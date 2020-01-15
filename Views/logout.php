<?php
//Reanudamos la sesión
session_start();

//Requerimos los datos de la conexión a la BBDD
require('../Core/connection.php');

//Des-establecemos todas las sesiones
unset($_SESSION);

//Destruimos las sesiones
session_destroy();

//Cerramos la conexión con la base de datos
mysqli_close($connection);

//Redireccionamos a el index
header("Location: /");
die();
