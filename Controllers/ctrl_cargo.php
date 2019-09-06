<?php
    $nomCargo=$_POST["txtnomCargo"];
    $descCargo=$_POST["txtdescCargo"];
    $val = $_POST['val'];
    $idCargo = $_REQUEST['id'];
    $idCargo2 = $_POST['cod'];

    include_once "../Models/mdl_cargo.php";

    if($idCargo != null){
        $info = new Cargo();
        $detail = $info->onLoad($idCargo);
        if (is_array($detail) || is_object($valor)) {
            foreach ($detail as $valor) {
                $var=$valor[0];
                $var1=$valor[1];
                $var2=$valor[2];
            }
        }
    }

    if (($nomCargo != null) and ($val == null)) {
        $resultado = Cargo::registrarCargo($nomCargo, $descCargo);
    }

    if ($val=="1") {
        Cargo::actualizarCargo($idCargo2, $nomCargo, $descCargo);
    } elseif ($val=="2") {
        Cargo::suprimirCargo($idCargo2);
    }

