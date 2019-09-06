<?php
    $nomConv = (isset($_POST["txtNomConv"])) ? $_POST["txtNomConv"] : null;
    $descConv = (isset($_POST["txtDescConv"])) ? $_POST["txtDescConv"] : null;
    $val = (isset($_POST["val"])) ? $_POST["val"] : null;
    $idConv = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
    $idConv2 = (isset($_POST["cod"])) ? $_POST["cod"] : null;
    $var1 = "";
    $var2 = "";

    include_once "../Models/mdl_convocatoria.php";

    if($idConv != null){
        $info = new Convocatoria();
        $detail = $info->onLoad($idConv);
        if (is_array($detail) || is_object($valor)) {
            foreach ($detail as $valor) {
                $var=$valor[0];
                $var1=$valor[1];
                $var2=$valor[2];
            }
        }
    }

    if (($nomConv != null) and ($val == null)) {
        $resultado = Convocatoria::registrarConv($nomConv, $descConv);
    }

    if ($val=="1") {
        Convocatoria::actualizarConv($idConv2, $nomConv, $descConv);
    } elseif ($val=="2") {
        Convocatoria::suprimirConv($idConv2);
    }

