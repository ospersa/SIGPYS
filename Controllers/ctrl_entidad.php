<?php
    $nomEnti = (isset($_POST["txtNomEnti"])) ? $_POST["txtNomEnti"] : null;
    $nomCortoEnti = (isset($_POST["txtNomCortoEnti"])) ? $_POST["txtNomCortoEnti"] : null;
    $descEnti = (isset($_POST["txtDescEnti"])) ? $_POST["txtDescEnti"] : null;
    $val = (isset($_POST["val"])) ? $_POST["val"] : null;
    $idEnti = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null;
    $idEnti2 = (isset($_POST["cod"])) ? $_POST["cod"] : null;

    include_once "../Models/mdl_entidad.php";

    if($idEnti){
        $info = new Entidad();
        $detail = $info->onLoad($idEnti);
        if (is_array($detail) || is_object($valor)) {
            foreach ($detail as $valor) {
                $var=$valor[0];
                $var1=$valor[1];
                $var2=$valor[2];
                $var3=$valor[3];
            }
        }
    }

    if (($nomEnti != null) and ($val == null)) {
        $resultado = Entidad::registrarEntidad($nomEnti, $nomCortoEnti, $descEnti);
    }

    if ($val=="1") {
        Entidad::actualizarEntidad($idEnti2, $nomEnti, $nomCortoEnti, $descEnti);
    } elseif ($val=="2") {
        Entidad::suprimirEntidad($idEnti2);
    }

