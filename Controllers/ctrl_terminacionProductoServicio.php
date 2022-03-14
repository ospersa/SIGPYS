<?php
if(!isset($_SESSION['usuario'])) { 
    session_start();
}
include_once('../Models/mdl_terminacionProductoServicio.php');
include_once('../Models/mdl_tiempos.php');
include_once('../Models/mdl_enviarEmail.php');
$busProy            = (isset($_POST['sltProy'])) ? $_POST['sltProy'] : null;
$fechFin            = (isset($_POST['txtFechPre'])) ? $_POST['txtFechPre']: null;
$cod                = 0;
$usuario            = $_SESSION['usuario'];
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$id2                = (isset($_POST['id'])) ? $_POST['id'] : null;
$datosEnvio         = (isset($_POST['msjEmail2'])) ? $_POST['msjEmail2']: null; 
$ccemail            = (isset($_POST['ccemail'])) ? $_POST['ccemail']: null; 
$observaciones      = (isset($_POST['obs'])) ? $_POST['obs']: null; 
$nota               = (isset($_POST['nota'])) ? $_POST['nota']: null; 
$proOser            = (isset($_POST['proOser'])) ? $_POST['proOser']: null; 
$idSolIni           = (isset($_POST['idSolIni'])) ? $_POST['idSolIni']: null; 
$codProy            = (isset($_POST['codProy'])) ? $_POST['codProy']: null; 
$prep               = "";
$datoPoS            = "";
$cuerpo             = (isset($_POST['cuerpo'])) ? $_POST['cuerpo']: null;
$action             = (isset($_POST['action'])) ? $_POST['action'] : null;
/* Datos recibidos del formulario de actualización de metadata */
$equipo             = (isset($_POST['idEquipo'])) ? $_POST['idEquipo'] : null;
$nombreProducto     = (isset($_POST['nomProd'])) ? $_POST['nomProd'] : null;
$sinopsis           = (isset($_POST['sinopsis'])) ? $_POST['sinopsis'] : null;
$claseProducto      = (isset($_POST['sltClaseM'])) ? $_POST['sltClaseM'] : null;
$tipoProducto       = (isset($_POST['sltTipo'])) ? $_POST['sltTipo'] : null;
$idioma             = (isset($_POST['sltIdioma'])) ? $_POST['sltIdioma'] : null;
$formato            = (isset($_POST['sltFormato'])) ? $_POST['sltFormato'] : null;
$tipoContenido      = (isset($_POST['sltTipoContenido'])) ? $_POST['sltTipoContenido'] : null;
$red                = (isset($_POST['SMRed'])) ? $_POST['SMRed'] : null;
$palabrasClave      = (isset($_POST['palabrasClave'])) ? $_POST['palabrasClave'] : null;
$urlServidor        = (isset($_POST['url'])) ? $_POST['url'] : null;
$urlVimeo           = (isset($_POST['urlV'])) ? $_POST['urlV'] : null;
$autores            = (isset($_POST['autores'])) ? $_POST['autores'] : null;
$observaciones      = (isset($_POST['labor'])) ? $_POST['labor'] : null;
$duracionMin        = (isset($_POST['minutosDura'])) ? $_POST['minutosDura'] : null;
$duracionSeg        = (isset($_POST['segundosDura'])) ? $_POST['segundosDura'] : null;
$plataforma         = (isset($_POST['sltPlataforma'])) ? $_POST['sltPlataforma'] : null;
$fechaEntrega       = (isset($_POST['txtfechEntr'])) ? $_POST['txtfechEntr'] : null;
$idSol              = (isset($_POST['idSol'])) ? $_POST['idSol'] : null;
$areaConocimiento   = (isset($_POST['sltAreaConocimiento'])) ? $_POST['sltAreaConocimiento'] : null;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if ($action == 'close') {
        $id = null;
        Terminar::terminarProducto($id2);
    } else if ($action == 'guardarMetadata') {
        Terminar::guardarMetadata($idSol, $equipo, $nombreProducto, $sinopsis, $claseProducto, $tipoProducto, $idioma, $formato, $tipoContenido, $red, $palabrasClave, $urlServidor, $urlVimeo, $autores, $observaciones, $duracionMin, $duracionSeg, $plataforma, $fechaEntrega, $areaConocimiento);
    }
    /* if (isset($_POST['btnTerEnvi'])) {
    $correo = Terminar::infoSolicitante($idSolIni);
    $asunto = $codProy." - Terminación ".$proOser. "P".$id2;
    $asesor = $usuario."@uniandes.edu.co";
    $cuerpoTotal = $cuerpo;
    if ( $observaciones != null){
        $cuerpoTotal .='<strong>Observaciones: </strong>'.$observaciones.'<br><br>';
    }
    $cuerpoTotal .= $nota. $datosEnvio;
    $sendEmail = EnviarCorreo::enviarCorreos($correo, $asesor, $ccemail, $asunto, $cuerpoTotal);
    if($sendEmail == true){
        Terminar::terminarProducto($id2);
    }    
}*/ else  if (isset($_POST['b']) ) {
    $busqueda = $_POST['b'];
    Terminar::selectProyectoUsuario($busqueda, $usuario);
} else if ($id != null) {
    $prep = substr($id, 0, 3);
    $id = substr($id, 3);
    $tiempoInvertido = Tiempos::OnloadTiempoInvertido($id);
    $info = Terminar::informacionProdSer($id);  
    $datos = Terminar::infoEmail($id);
    $idEqu = $datos['idEqu'];
    $idSolIni = $datos['idSolIni'];
    $nombreProy = $datos['nombreProy'];
    $codProy = $datos['codProy'];
    $prodOser = $datos['productoOservicio']; 
    $correo = $datos['correo']; 
    if ($prodOser == 'SI') {
        $proOser ="Producto";
        $datoPoS = Terminar:: infoEmailPro($id, $idEqu);
    } else if($prodOser == 'NO'){
        $proOser ="Servicio";
        $datoPoS = Terminar:: infoEmailSer($id);
    }
    $datos =Terminar::infoUsuario($usuario,$id);
    $nombre= $datos['nombres'];
    $apellido1= $datos['apellido1'];
    $apellido2= $datos['apellido2'];
    $nombreRol= $datos['nombreRol'];

} else {
    if ($busProy != '' && $fechFin == null) {
        $cod = 1;
    } else if ($fechFin != null && $busProy == '') {
        $cod = 2;
    } else if ($fechFin != null && $busProy != '') {
        $cod = 3;
    } else {
        $cod = 4;
    }
    if (empty($action)) {
        echo Terminar::cargarProyectosUser($usuario, $cod, $busProy, $fechFin);
    }
}
}

?>
