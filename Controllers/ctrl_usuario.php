<?php
/** Inclusión del Modelo */
include_once('../Models/mdl_usuario.php');

/** Inicialización de Variables */
$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$entidad = (isset($_POST['sltEntidad'])) ? $_POST['sltEntidad'] : null;
$facultad = (isset($_POST['sltFacul'])) ? $_POST['sltFacul'] : null;
$departamento = (isset($_POST['sltDepto'])) ? $_POST['sltDepto'] : "";
$cargo = (isset($_POST['sltCargo'])) ? $_POST['sltCargo'] : null;
$tipo = (isset($_POST['sltTipoIntExt'])) ? $_POST['sltTipoIntExt'] : null;
$identificacion = (isset($_POST['txtIdDoc'])) ? $_POST['txtIdDoc'] : null;
$nombres = (isset($_POST['txtNom'])) ? $_POST['txtNom'] : null;
$apellido1 = (isset($_POST['txtApe1'])) ? $_POST['txtApe1'] : null;
$apellido2 = (isset($_POST['txtApe2'])) ? $_POST['txtApe2'] : null;
$ciudad = (isset($_POST['sltCiudad'])) ? $_POST['sltCiudad'] : null;
$equipo = (isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$mail = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : null;
$fijo = (isset($_POST['txtTel'])) ? $_POST['txtTel'] : null;
$extension = (isset($_POST['txtExt'])) ? $_POST['txtExt'] : null;
$celular = (isset($_POST['txtCel'])) ? $_POST['txtCel'] : null;
$categoriaCargo = (isset($_POST['sltCategoriaCargo'])) ? $_POST['sltCategoriaCargo'] : null;
$val = (isset($_POST['val'])) ? $_POST['val'] : null;
$cod = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/** Procesamiento de peticiones realizadas al controlador */
if (isset($_POST['btnRegistrar'])) {
    if (($entidad || $identificacion || $cargo || $tipo || $apellido1 || $nombres || $mail || $ciudad || $categoriaCargo) == null) {
        echo "<script>alert('Existe algún campo vacío. Registro no válido.');</script>";
    } else {
        Usuario::registrarUsuario($entidad, $facultad, $departamento, $cargo, $tipo, $identificacion, $nombres, $apellido1, $apellido2, $ciudad, $equipo, $mail, $fijo, $extension, $celular, $categoriaCargo);
    }
} else if ($val) {
    $entidad = (isset($_POST['sltEntidad2'])) ? $_POST['sltEntidad2'] : null;
    $cargo = $_POST['sltCargo2'];
    $equipo = $_POST['sltEquipo2'];
    $ciudad = $_POST['sltCiudad2'];
    if ($val == '1') {
        Usuario::actualizarUsuario($cod, $identificacion, $nombres, $apellido1, $apellido2, $entidad, $facultad, $departamento, $cargo, $tipo, $equipo, $ciudad, $mail, $fijo, $extension, $celular, $categoriaCargo);
    } else if ($val == '2') {
        Usuario::eliminarUsuario($cod);
    }
}

/** Carga de información en el Modal */
if ($id) {
    $info = Usuario::onLoad($id);
    $identificacion = $info['identificacion'];
    $nombres = $info['nombres'];
    $apellido1 = $info['apellido1'];
    $apellido2 = $info['apellido2'];
    $entidad = $info['idEnt'];
    $facultad = $info['idFac'];
    $correo = $info['correo'];
    $telefono = $info['telefono'];
    $extension = $info['extension'];
    $celular = $info['celular'];
}

/** Procesamiento de peticiones realizadas al controlador vía AJAX, para creación de select dinámicos */
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['sltEntidad'])) {
        echo "<script>$('#sltDepartamento').empty();</script>";
        $busqueda = $_POST['sltEntidad'];
        if ($busqueda == "ENT001") {
            Usuario::selectFacultades($busqueda, null, null);
        }
    }
    if (isset($_POST['sltEntidad2'])) {
        echo "<script>$('#sltDepartamento2').empty();</script>";
        $busqueda = $_POST['sltEntidad2'];
        if ($busqueda == "ENT001") {
            Usuario::selectFacultades($busqueda, null, "1");
        }
    }
    if (isset($_POST['sltFacul'])) {
        $busqueda = $_POST['sltFacul'];
        Usuario::selectDepartamentos($busqueda, null);
    }
    if (isset($_POST['sltPais'])) {
        $busqueda = $_POST['sltPais'];
        Usuario::selectCiudades($busqueda);
    }
    if (isset($_POST['sltPais2'])) {
        $busqueda = $_POST['sltPais2'];
        Usuario::selectCiudades($busqueda);
    }
    if (isset($_POST['sltFacul2'])) {
        $busqueda = $_POST['sltFacul2'];
        Usuario::selectDepartamentos($busqueda, '1');
    }
}

?>