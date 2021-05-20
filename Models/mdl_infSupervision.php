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
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.idSolIni, pys_solicitudes.fechSol, pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechAct, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_servicios.productoOservicio, pys_estadosol.nombreEstSol, pys_actproductos.nombreProd, pys_actproductos.fechEntregaProd
                FROM pys_solicitudes
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol AND pys_actsolicitudes.est = '1'
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1'
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy AND pys_actualizacionproy.est = '1'
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol AND pys_estadosol.est = '1'
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
                LEFT JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol AND pys_productos.est = '1'
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                WHERE pys_solicitudes.est = '1' AND pys_solicitudes.fechSol >= '$fechaini' AND pys_solicitudes.fechsol <= '$fechafin' AND pys_solicitudes.idTSol = 'TSOL02'
                ORDER BY pys_solicitudes.fechSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos = mysqli_fetch_array($resultado)) {
                $idProyecto = $datos['idProy'];
                $consulta2 = "SELECT pys_areaconocimiento.areaNombre 
                    FROM areaconocimientohasproyectos
                    INNER JOIN pys_areaconocimiento ON pys_areaconocimiento.idAreaConocimiento = areaconocimientohasproyectos.pys_areaconocimiento_idAreaConocimiento
                    WHERE areaconocimientohasproyectos.pys_proyectos_idProy = '$idProyecto' AND areaconocimientohasproyectos.areaEstado = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registros2 = mysqli_num_rows($resultado2);
                $areaConocimiento = '';
                if ($registros2 > 0) {
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $areaConocimiento .= $datos2['areaNombre'] . '; ';
                    }
                    $areaConocimiento = ($areaConocimiento != '') ? substr($areaConocimiento, 0, -2) : '';
                }
                $json[] = array (
                    'Nombre Proyecto' => $datos['codProy'] . ' - ' . $datos['nombreProy'],
                    'Solicitud Inicial' => $datos['idSolIni'],
                    'Solicitud Especifica' => 'P' . $datos['idSol'],
                    'Descripcion' => $datos['ObservacionAct'],
                    'Nombre Producto' => $datos['nombreProd'],
                    'Area Conocimiento' => $areaConocimiento,
                    'Fecha Creacion' => $datos['fechSol'],
                    'Nombre Equipo' => $datos['nombreEqu'],
                    'Nombre Servicio' => $datos['nombreSer'],
                    'Genera Producto' => $datos['productoOservicio'],
                    'Nombre Estado' => $datos['nombreEstSol'],
                    'Fecha Entrega Cliente' => $datos['fechEntregaProd'],
                    'Fecha Actualizacion' => $datos['fechAct']
                );
                $areaConocimiento = '';
            }
            mysqli_close($connection);
            return $json;            
        }
        
        public static function busqueda ($fechaini, $fechafin) {
            $string = "";
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
            $registros = count($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table" border="1">
                                <thead>
                                    <tr class="teal lighten-4">
                                        <th class="row-teal3">Proyecto</th>
                                        <th class="row-teal3">Solicitud Inicial</th>
                                        <th class="row-teal3">Producto/Servicio</th>
                                        <th class="row-teal3">Descripción Producto/Servicio</th>
                                        <th class="row-teal3">Nombre del Producto</th>
                                        <th class="row-teal3">Área de Conocimiento</th>
                                        <th class="row-teal3">Fecha de creación</th>
                                        <th class="row-teal3">Equipo</th>
                                        <th class="row-teal3">Servicio</th>
                                        <th class="row-teal3">Genera producto</th>
                                        <th class="row-teal3">Estado</th>
                                        <th class="row-teal3">Fecha entrega cliente</th>
                                        <th class="row-teal3">Fecha actualización</th>
                                    </tr>
                                </thead>
                                <tbody>';
            foreach ($resultado as $value) {
                $string .= '        <tr>
                                        <td>'.$value['Nombre Proyecto'].'</td>
                                        <td>'.$value['Solicitud Inicial'].'</td>
                                        <td>'.$value['Solicitud Especifica'].'</td>
                                        <td>'.$value['Descripcion'].'</td>
                                        <td>'.$value['Nombre Producto'].'</td>
                                        <td>'.$value['Area Conocimiento'].'</td>
                                        <td>'.$value['Fecha Creacion'].'</td>
                                        <td>'.$value['Nombre Equipo'].'</td>
                                        <td>'.$value['Nombre Servicio'].'</td>
                                        <td>'.$value['Genera Producto'].'</td>
                                        <td>'.$value['Nombre Estado'].'</td>
                                        <td>'.$value['Fecha Entrega Cliente'].'</td>
                                        <td>'.$value['Fecha Actualizacion'].'</td>
                                    </tr>';
            }
            $string .= '        </tbody>
                            </table>';
            }
            else {
                $string = "<h4>No hay resultados</h4>";
            }
            echo $string;
        }
        
        public static function descarga ($fechaini, $fechafin) {
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
            if (count($resultado) > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de productos-servicios')
                    ->setSubject('Informe de productos-servicios')
                    ->setDescription('Informe de productos-servicios')
                    ->setKeywords('Informe de productos-servicios')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-productos-servicios');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(18);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Productos - Servicios');
                $sheet->setCellValue('A3', 'Fecha Inicial S.E. '.$fechaini);
                $sheet->setCellValue('A4', 'Fecha Final  S.E. '.$fechafin);
                $sheet->mergeCells("A1:M1");
                $titulos=['Proyecto', 'Solicitud Inicial', 'Producto/Servicio', 'Descripción Producto/Servicio', 'Nombre del producto', 'Área de conocimiento', 'Fecha de creación', 'Equipo', 'Servicio', 'Genera producto', 'Estado', 'Fecha entrega cliente', 'Fecha actualización'];
                $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:M6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                foreach ($resultado as $value) {
                    $nombreProyecto = $value['Nombre Proyecto'];
                    $idSolIni = $value['Solicitud Inicial'];
                    $idSol = $value['Solicitud Especifica'];
                    $ObservacionAct = $value['Descripcion'];
                    $nombreProducto = $value['Nombre Producto'];
                    $areaConocimiento = $value['Area Conocimiento'];
                    $fechaCreacion = $value['Fecha Creacion'];
                    $nombreEquipo = $value['Nombre Equipo'];
                    $nombreServicio = $value['Nombre Servicio'];
                    $generaProducto = $value['Genera Producto'];
                    $nombreEstadoSolicitud = $value['Nombre Estado'];
                    $fechaEntregaCliente = $value['Fecha Entrega Cliente'];
                    $fechaActualizacion = $value['Fecha Actualizacion'];
                    $datos = [$nombreProyecto, $idSolIni, $idSol, $ObservacionAct, $nombreProducto, $areaConocimiento, $fechaCreacion, $nombreEquipo, $nombreServicio, $generaProducto, $nombreEstadoSolicitud, $fechaEntregaCliente, $fechaActualizacion];
                    $spreadsheet->getActiveSheet()->fromArray($datos,null,'A'.$fila);
                    $fila += 1;
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:M'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:M'.($fila-1))->applyFromArray(STYLEBODY);
               
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de productos-servicios'.gmdate(' d M Y ').'.xlsx"');
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
        }

        
    }
?>