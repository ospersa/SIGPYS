<?php
/* Inclusión del Modelo */
include_once('../Models/mdl_celula.php');

/* Inicialización variables*/
$search         = (isset($_POST['txt-search'])) ? $_POST['txt-search'] : null;
$celula         = (isset($_POST['txtNomCelula'])) ? $_POST['txtNomCelula'] : null;
$cod            = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$coordinador    = (isset($_POST['sltCoordinador']) )? $_POST['sltCoordinador'] : null;
$eliminar   = (isset($_POST['chkDeleteCoor'])) ? $_POST['chkDeleteCoor'] : null;
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
    $busqueda = ($search == null) ? Celula::busquedaTotal() : Celula::busqueda($search);
}

if (isset($_POST['btnGuaCelula'])) {
    Celula::registrarCelula($celula, $coordinador);
}else if (isset($_POST['btnActCelula'])) {
        Celula::actualizarCelula($cod, $celula, $eliminar, $coordinador);
}

?>