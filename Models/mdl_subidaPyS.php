<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

require '../php_libraries/vendor/autoload.php';

Class SubidaPyS {
    public static function cargaArchivo($archivo){
        $resultado = move_uploaded_file($archivo["tmp_name"], $archivo["name"]);
        if ($resultado) {
            echo "Subido con éxito";
        } else {
            echo "Error al subir archivo";
        }
    }
    public static function leerArchivo(){
        $inputFileName = __DIR__ . '/Prueba.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); 
        $hoja = $spreadsheet->getSheet(0);
        $celda = $hoja->getCell('G3');
        echo $celda->getValue();
    }
}
?>