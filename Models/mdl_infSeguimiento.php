<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

require '../php_libraries/vendor/autoload.php';
const STYLETABLETITLE = [
    'font' => [
        'bold' => true,
        'size' => '10'
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'wrapText' => TRUE,  
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ],
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => '80cbc4',
        ]
    ]
];

const STYLETABLETITLESUB = [
    'font' => [
        'bold' => true,
        'size' => '10'
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'wrapText' => TRUE,  
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ],
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'e0f2f1',
        ]
    ]
];

const STYLETABLETITLESUB2 = [
    'font' => [
        'bold' => true,
        'size' => '10'
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'wrapText' => TRUE,  
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ],
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'b8e6b9',
        ]
    ]
];
const STYLETABLETI = ['font' => [
    'bold' => true,
    'size' => '24'
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,  
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ]
];
const STYLEBORDER = ['allBorders'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    'color' => ['rgb' => '000000' ]
    ]
];

const STYLEBODY = ['font' => [
    'size' => '10'
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ]
];

    Class InformeSeguimiento {
        
        public static function busqueda ($proyecto, $frente, $fechaini, $fechafin) {
          
        }

        public static function descarga ($proyecto, $frente, $fechaini, $fechafin) {
            require('../Core/connection.php');
            if ($proyecto == "sltProy" && $frente == null){
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' ORDER BY codProy asc;";
            } else if($frente != null && $proyecto == "" ){
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idFrente = '$frente' ORDER BY codProy asc;";
            }else {
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de seguimiento')
                    ->setSubject('Informe de seguimiento')
                    ->setDescription('Informe de seguimiento')
                    ->setKeywords('Informe de seguimiento')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-Ejecuciones');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(22);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(35);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de seguimiento');
                if ($fechaini != ""  && $fechafin != ""){
                    $sheet->setCellValue('A4', 'Desde: '.$fechaini.' Hasta: '.$fechafin);
                }
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A4:J4");
                $titulos=['Nombre del programa','Nombre del curso','Código del curso','Tipo de productos/servicio','Código del recurso','Nombre del recurso','fecha estimada de entrega','Estado','Fecha ingreso al sistema','Solicitante','Responsable P&S','Link producto'];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:L6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($datos = mysqli_fetch_array($resultado)) {
                    $codProy = $datos['codProy'];
                    $nombreProy = $datos['nombreProy'];
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$fila, $codProy);
                    $idProy = $datos['idProy'];
                    $consulta1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSer, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol , pys_solicitudes.idSolIni
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' ";
                    if($fechaini != null && $fechafin!= null){
                        $consulta1 .= " AND pys_solicitudes.fechSol>='$fechaini' AND pys_solicitudes.fechSol<='$fechafin'";
                    }
                    $consulta1.="ORDER BY pys_actsolicitudes.idSol;";
                    $resultado1=mysqli_query($connection, $consulta1);
                    $registros1 = mysqli_num_rows($resultado1);
                    if ($registros1 > 0) {
                        $cont = 0;
                        $filaIni = $fila;
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $nomSer= $datos1['nombreSer'];
                            $fechPrev= $datos1['fechPrev'];
                            $idSol = $datos1['idSol'];
                            $idSolIni = $datos1['idSolIni'];
                            $fechSol = $datos1['fechSol']; 
                            $nombreEstSol = $datos1['nombreEstSol']; 
                            $personasAsig ='';
                            $nombreProd = '';                                   
                            $urlVimeo = '';     
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                            FROM pys_asignados
                            INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                            WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND  pys_asignados.idSol='$idSol'
                            ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            if(mysqli_num_rows($resultado2)> 0){
                                while ($datos2 = mysqli_fetch_array($resultado2)) {
                                    $personasAsig .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'].', ';
                                }
                            }
                            $consulta3 = "SELECT pys_actproductos.nombreProd, pys_actproductos.urlVimeo
                            FROM pys_actproductos
                            INNER JOIN  pys_productos ON pys_productos.idProd = pys_actproductos.idProd
                            INNER JOIN  pys_actsolicitudes ON pys_productos.idSol = pys_actsolicitudes.idSol
                            WHERE  pys_actsolicitudes.idSol='$idSol' AND pys_actsolicitudes.est ='1' and pys_actproductos.est='1'";
                            $resultado3 = mysqli_query($connection, $consulta3);
                            if(mysqli_num_rows($resultado3)> 0){
                                while ($datos3 = mysqli_fetch_array($resultado3)) {
                                    $nombreProd = $datos3['nombreProd'];                                   
                                    $urlVimeo = $datos3['urlVimeo'];                                   
                                }
                            }
                            $consulta4 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                            FROM pys_solicitudes 
                            INNER JOIN pys_personas ON pys_solicitudes.idSolicitante = pys_personas.idPersona
                            WHERE  pys_solicitudes.idSol='$idSolIni'";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            if(mysqli_num_rows($resultado4)> 0){
                                while ($datos4 = mysqli_fetch_array($resultado4)) {                              
                                    $Solicitante = $datos4['apellido1'].' '.$datos4['apellido2'].' '.$datos4['nombres'];                        
                                }
                            } 
                            $data=['',$nombreProy,$codProy,$nomSer,'P'.$idSol,$nombreProd,$fechPrev,$nombreEstSol,$fechSol,$Solicitante,$personasAsig,$urlVimeo];
                            $spreadsheet->getActiveSheet()->fromArray($data, null, 'A'.$fila);
                            $fila +=1;
                        }
                    }
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:L'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:L'.($fila-1))->applyFromArray(STYLEBODY);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de ejecuciones por proyecto '.gmdate(' d M Y ').'.xlsx"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;  
            }
            mysqli_close($connection);
        }

        public static function selectFrente () {
            require('../Core/connection.php');
            $consulta = "SELECT descripcionFrente, idFrente, nombreFrente FROM pys_frentes WHERE est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $string = "";
            if (mysqli_num_rows($resultado) > 0) {
                $string .= '  <select name="sltFrenteInf" id="sltFrenteInf">
                            <option value=" " selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '  <option value="'.$datos['idFrente'].'">'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                }
                $string .= '  </select>
                        <label for="sltFrente">Frente</label>';
            }
            return $string;
            mysqli_close($connection);
        }
    }
?>