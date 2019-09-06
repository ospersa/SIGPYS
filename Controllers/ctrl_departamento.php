<?php
       
    include_once "../Models/mdl_departamento.php";

    if (isset($_POST["selEntidad"])) {
        $busqueda = $_REQUEST["selEntidad"];
        $resultado = Departamento::selectFacultad($busqueda);
    }
    
    $idEntidad = (isset($_POST["selEntidad"])) ? $_POST["selEntidad"] : null;
    $idFacultad = (isset($_POST["selFacultad"])) ? $_POST["selFacultad"] : null;
    $nomDepartamento = (isset($_POST["txtNomDepartamento"])) ? $_POST["txtNomDepartamento"] : null;
    $nomFacultad = (isset($_POST["txtNomFacultad"])) ? $_POST["txtNomFacultad"] : null;
    $val = (isset($_POST["val"])) ? $_POST["val"] : null;
    $idDepartamento = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
    $idDepartamento2 = (isset($_POST["cod"])) ? $_POST["cod"] : null;
    
    if($idDepartamento){
        $info = new Departamento();
        $detail = $info->onLoad($idDepartamento);
        if (is_array($detail) || is_object($valor)) {
            foreach ($detail as $valor) {
                $var=$valor[0];
                $var1=$valor[1];
                $var2=$valor[2];
                $var3=$valor[3];
                $var4=$valor[4];
                $var5=$valor[5];
                $var6=$valor[6];
                $var7=$valor[7];
                $var8=$valor[8];
                $var9=$valor[9];
                $var10=$valor[10];
                $var11=$valor[11];	
            }
        }
    }

    if (($idEntidad != null) and ($idFacultad != null) and ($nomDepartamento != null) and ($val == null)) {
        $resultado = Departamento::registrarDepartamento($idEntidad, $idFacultad, $nomDepartamento);
    }

    if ($val=="1") {
        Departamento::actualizarDepartamento($idDepartamento2, $idEntidad, $idFacultad, $nomFacultad, $nomDepartamento);
    } elseif ($val=="2") {
        Departamento::suprimirDepartamento($idDepartamento2);
    }

?>