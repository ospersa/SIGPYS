<?php
/* Inclusión del Modelo */
include_once "../Models/mdl_departamento.php";

/* Inicialización variables*/
$idEntidad          = (isset($_POST["selEntidad"])) ? $_POST["selEntidad"] : null;
$idFacultad         = (isset($_POST["selFacultad"])) ? $_POST["selFacultad"] : null;
$nomDepartamento    = (isset($_POST["txtNomDepartamento"])) ? $_POST["txtNomDepartamento"] : null;
$nomFacultad        = (isset($_POST["txtNomFacultad"])) ? $_POST["txtNomFacultad"] : null;
$val                = (isset($_POST["val"])) ? $_POST["val"] : null;
$idDepartamento     = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idDepartamento2    = (isset($_POST["cod"])) ? $_POST["cod"] : null;

/* Carga de información en el Modal */
if($idDepartamento){
    $info = Departamento::onLoad($idDepartamento);
    $idEntidad = $info['idEnt'];
    $idFacultad = $info['idFac'];
    $nomEntidad = $info['nombreEnt'];
    $nomFacultad = $info['nombreFac'];
    $nomDepartamento = $info['nombreDepto'];
}

/* Variables que cargan Select en formularios*/
if (isset($_POST["selEntidad"])) {
    $busqueda = $_REQUEST["selEntidad"];
    $resultado = Departamento::selectFacultad($busqueda);
}

/* Procesamiento peticiones al controlador */
if (isset($_POST['btnGuardarDepto'])) {
    $resultado = Departamento::registrarDepartamento($idEntidad, $idFacultad, $nomDepartamento);
} else if ($val == "1") {
    Departamento::actualizarDepartamento($idDepartamento2, $idEntidad, $idFacultad, $nomFacultad, $nomDepartamento);
} else if ($val == "2") {
    Departamento::suprimirDepartamento($idDepartamento2);
}

?>