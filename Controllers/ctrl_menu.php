<?php
/* Inicialización variables*/
$perfil = $_SESSION['perfil'];

/* Inclusión de la Estructura */
if ($perfil == 'PERF01') {
    require('../Estructure/menusuperadmin.php');
} elseif ($perfil == 'PERF02') {
    require('../Estructure/menuadmin.php');
} elseif ($perfil == 'PERF03') {
    require('../Estructure/menuestandar.php');
} elseif ($perfil == 'PERF04') {
    require('../Estructure/menuvisitante.php');
} elseif ($perfil == 'PERF06') {
    require('../Estructure/menucontratista.php');
}
?>