<?php
    $perfil = $_SESSION['perfil'];

    if ($perfil == 'PERF01') {
        require('../Estructure/menusuperadmin.php');
    } elseif ($perfil == 'PERF02') {
        require('../Estructure/menuadmin.php');
    } elseif ($perfil == 'PERF03') {
        require('../Estructure/menuestandar.php');
    } elseif ($perfil == 'PERF04') {
        require('../Estructure/menuvisitante.php');
    }
