<?php
// Carga la configuraci?n 
$config = parse_ini_file('config.ini'); 

// Conexi?n con los datos del 'config.ini' 
$connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']); 


// Cambiar formato de caracteres 
//mysqli_set_charset($connection,"ISO-8859-1");
//mysqli_set_charset($connection,"ISO-8859-1");
mysqli_query($connection, "SET NAMES UTF8;");

// Si la conexi?n falla, aparece el error 
if($connection === false) { 
    echo 'Ha habido un error <br>'.mysqli_connect_error(); 
}
