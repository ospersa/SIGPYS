<?php
    $nomEquipo = (isset($_POST["txtNomEquipo"])) ? $_POST["txtNomEquipo"] : null;
    $descEquipo = (isset($_POST["txtDescEquipo"])) ? $_POST["txtDescEquipo"] : null;
    $val = (isset($_POST["val"])) ? $_POST["val"] : null;
    $idEquipo = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null;
    $idEquipo2 = (isset($_POST["cod"])) ? $_POST["cod"] : null;
    
    include_once "../Models/mdl_equipo.php";

    if($idEquipo){
        $info = new Equipo();
        $detail = $info->onLoad($idEquipo);
        if (is_array($detail) || is_object($valor)) {
            foreach ($detail as $valor) {
                $var=$valor[0];
                $var1=$valor[1];
                $var2=$valor[2];
            }
        }
    }

    if (($nomEquipo != null) and ($val == null)) {
        $resultado = Equipo::registrarEquipo($nomEquipo, $descEquipo);
    }

    if ($val=="1") {
        Equipo::actualizarEquipo($idEquipo2, $nomEquipo, $descEquipo);
    } elseif ($val=="2") {
        Equipo::suprimirEquipo($idEquipo2);
    }

