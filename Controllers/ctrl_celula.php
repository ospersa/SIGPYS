<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_celula.php');

/* Inicialización variables*/
$search         = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$celula         = (isset($_POST['txtNomCelula'])) ? $_POST['txtNomCelula'] : null;
$nombreCelula   = "";
$idCelula       = "";

/* Variables que cargan Select en formularios*/
$selectCoordinador = Celula::selectCoordinador("required");

/* Carga de información en el Modal */
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $resultado = Celula::onLoadCelula($id);
    $nombreCelula = $resultado['nombreCelula'];
    $idCelula = $resultado['idCelula'];
}

/* Procesamiento peticiones al controlador */
if(isset($_POST['txt-search'])){    
    $busqueda = ($search == null) ? Celula::busquedaTotal() : Celula::busqueda($busqueda);
}
if (isset($_POST['txtNomCelula']) && !isset($_POST['cod']) && isset($_POST['sltCoordinador'])) {
    $coordinador = $_POST['sltCoordinador'];
    Celula::registrarCelula($celula, $coordinador);
}

if (isset($_POST['val']) && isset($_POST['cod'])) {
    $val = $_POST['val'];
    $cod = $_POST['cod'];
    $nombreCelula = $_POST['txtNomCelula2'];
    $eliminar = $_POST['chkDeleteCoor'];
    $agregar = $_POST['sltCoordinador'];
    if ($val == 1) {
        Celula::actualizarCelula($cod, $nombreCelula, $eliminar, $agregar);
    }
}

?>