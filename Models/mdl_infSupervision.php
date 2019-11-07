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
const STYLETABLETITLESUBPRO = [
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
        'argb' => 'b2dfdb',
        ]
    ]
];
Const STYLECOLORRED = ['font' => [
    'color'=>['rgb' => 'd50000']
    ]
];
Const STYLECOLORTEAL = ['font' => [
    'color'=>['rgb' => '009688']
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

    Class InformeSupervision {

        public static function consulta ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.idSol, pys_solicitudes.idSolIni, pys_solicitudes.descripcionSol, pys_solicitudes.fechSol, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.idEstSol
            FROM pys_actualizacionproy
            INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
            INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
            WHERE pys_actualizacionproy.est = 1 AND pys_proyectos.est = 1 AND pys_actsolicitudes.est = 1 AND pys_solicitudes.est = 1  AND pys_solicitudes.idTSol = 'TSOL02' AND pys_servicios.est = 1 AND pys_equipos.est = 1  
            ORDER BY `pys_solicitudes`.`fechSol` DESC";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            return $resultado;            
        }
        
        public static function busqueda ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $string = "";
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
            
                $string = '     <table class="responsive-table" border="1">
                        <thead>
                            <tr class="teal lighten-4">
                                <th class="row-teal3">Código Proyecto</th>
                                <th class="row-teal3">Nombre Proyecto</th>
                                <th class="row-teal3">Celula</th>
                                <th class="row-teal3">Solicitud Inicial</th>
                                <th class="row-teal3">Producto/Servicio</th>
                                <th class="row-teal3">Descripción Producto/Servicio</th>
                                <th class="row-teal3">Fecha de registro</th>
                                <th class="row-teal3">Equipo -- Servicio</th>
                                <th class="row-teal3">Estado Solicitud</th>
                                <th class="row-teal3">FEcha Prevista de Entrega</th>
                                <th class="row-teal3">Asignados</th>
                                <th class="row-teal3">Fase</th>
                                <th class="row-teal3">Fecha registro Producto</th>
                                <th class="row-teal3">Fecha registro Servicio</th>
                                <th class="row-teal3">Estado Inventario</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        $idSol = $datos['idSol'];
                        $consultaE = "SELECT idEqu FROM pys_actsolicitudes ";
                        $resultadoE = mysqli_query($connection, $consultaE);
                        $datosE = mysqli_fetch_array($resultadoE);
                        $equipo = $datosE['idEqu'];
                        $asignados = InformeInventario::asignados($idSol);
                        $string .= '   <tr class="row60">
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                            <td class="middle"></td>
                         </tr>';
                    }
                    $string .= '    </tbody>
                                </table>';
            }
            else {
                $string = "<h4>No hay resultados</h4>";
            }
            return $string;
            mysqli_close($connection);


        }
        
        public static function descarga ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de supervisión')
                    ->setSubject('Informe de supervisión')
                    ->setDescription('Informe de supervisión')
                    ->setKeywords('Informe de supervisión')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-inventario');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(40);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Supervisión');
                $sheet->setCellValue('A3', 'Fecha Inicial S.E.');
                $sheet->setCellValue('A4', 'Fecha Final  S.E.');

                $sheet->mergeCells("A1:P1");
                $sheet->mergeCells("A3:P3");
                $sheet->mergeCells("A4:P4");
                $titulos=['Código Proyecto', 'Nombre Proyecto', 'Celula', 'Solicitud Inicial', 'Producto/Servicio', 'Descripción Producto/Servicio', 'Fecha de registro', 'Equipo', 'Servicio', 'Estado Solicitud', 'Fecha Prevista de Entrega', 'Asignados', 'Fase', 'Fecha registro Producto', 'Fecha registro Servicio', 'Estado Inventario'];
                $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:P6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($datos = mysqli_fetch_array($resultado)) {
                      
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de supervisión '.gmdate(' d M Y ').'.xlsx"');
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

        
    }
?>