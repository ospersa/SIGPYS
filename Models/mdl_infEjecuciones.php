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

const STYLEACTUALIZACION = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'B2CDCE',
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

    Class InformeEjecuciones {
        public static function tiemposMes($idPersona) {
            require('../Core/connection.php');
            $today = date('Y-m-d');
            $totalAsig = false;
            $query0 = "SELECT idPeriodo, inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2, persona_IdPersona, porcentajeDedicacion1, porcentajeDedicacion2
                FROM pys_periodos 
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo AND pys_dedicaciones.estadoDedicacion = '1'
                WHERE inicioPeriodo <= '$today' AND finPeriodo >= '$today' AND estadoPeriodo = '1' AND persona_IdPersona='$idPersona';";
            $result0 = mysqli_query($connection, $query0);
            $registry0 = mysqli_num_rows($result0);
            if ($registry0 > 0) {
                while ($data0 = mysqli_fetch_array($result0)) {
                    $totalAsig = ((($data0['diasSegmento1'] * 8) * $data0['porcentajeDedicacion1']) / 100) + ((($data0['diasSegmento2'] * 8) * $data0['porcentajeDedicacion2']) / 100);
                    $totalAsig = ($totalAsig == 0) ? 160 : $totalAsig;
                }
            
            }
            mysqli_close($connection);
            return $totalAsig;
        }

        public static function busqueda ($txtFechIni, $txtFechFin) {
            require('../Core/connection.php');
            $tabla ='
                <h5><strong>Informe de ejecucion desde '.$txtFechIni.' hasta '.$txtFechFin.'</strong></h5>
                <table class="table table-hover table-striped table-responsive-xl">
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Cod. Proyecto</th>
                            <th>Nombre Proyecto</th>
                            <th>Tiempo trabajado</th>
                            <th>% ejecutado</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            $consultaNombre = "SELECT idPersona, apellido1, apellido2, nombres FROM pys_personas WHERE (est='1' OR est='0') AND idEquipo != 'EQU008' AND idEquipo != 'EQU007' AND idEquipo != 'EQU006' ORDER BY apellido1 ASC;";
            $resultadoNombre = mysqli_query($connection, $consultaNombre);
            while ($datosNombre = mysqli_fetch_array($resultadoNombre)){
                $idPersona      = $datosNombre['idPersona'];
                if(self::tiemposMes($idPersona) !=false){
                    $apellido1      = $datosNombre['apellido1'];
                    $apellido2      = $datosNombre['apellido2'];
                    $nombres        = $datosNombre['nombres'];
                    $consulta = "SELECT pys_asignados.idAsig, pys_asignados.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                                FROM pys_asignados
                                INNER JOIN pys_actualizacionproy ON  pys_actualizacionproy.idProy = pys_asignados.idProy
                                WHERE pys_asignados.idPersona='$idPersona' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_actualizacionproy.est= '1'
                                GROUP BY pys_actualizacionproy.codProy
                                ORDER BY pys_actualizacionproy.codProy ASC;";
                    $resultado = mysqli_query($connection, $consulta);    
                    $fila = "";   
                    $horasT = 0;
                    $minT = 0;
                    $TotalTiem = 0;
                    $ejec = 0;
                    $ejecTotal = 0;
                    $horas = 0;
                    $min = 0;
                    $count = 1;
                    $horasMes = self::tiemposMes($idPersona);
                    while ($datos = mysqli_fetch_array($resultado)) {  
                        $idProy        = $datos['idProy'];
                        $codProy       = $datos['codProy'];
                        $nombreProy    = $datos['nombreProy'];
                        
                        $consulta1 = "SELECT idAsig  FROM pys_asignados WHERE (est = '1' OR est = '2')  AND idProy = '$idProy' AND idPersona = '$idPersona';"; 
                        $resultado1 = mysqli_query($connection, $consulta1);         
                        while ($datos1 = mysqli_fetch_array($resultado1)) {  
                            $idAsig1    = $datos1['idAsig'];
                            $consulta2 = "SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos FROM pys_tiempos WHERE estTiempo = '1'  AND idAsig = '$idAsig1' AND fechTiempo >= '$txtFechIni' AND fechTiempo <= '$txtFechFin';";
                            $resultado2 = mysqli_query($connection, $consulta2);         
                            while ($datos2 = mysqli_fetch_array($resultado2)) {  
                                $horas += $datos2[0];
                                $min += $datos2[1];
                            }
                        }
                        $Total = round(((($horas*60)+ $min)/60), 2);
                        $horasT += $horas ;
                        $minT += $min ;
                        $TotalTiem = ((($horasT*60) + $minT)/60);
                        $ejec = round((($Total / $horasMes)*100), 2);
                        if ($Total > 0) {
                            $fila .= '
                            <tr>
                            <td>'.$codProy.'</td>
                            <td>'.$nombreProy.'</td>
                                    <td>'.$Total.'</td>
                                    <td>'.$ejec.'%</td>
                            </tr>';
                            $count += 1;
                        }

                        $horas = 0;
                        $min = 0;
                        $Total = 0;
                    }
                    $tabla .= '<td rowspan="'.$count.'"><strong> '.$apellido1.' '.$apellido2.' '.$nombres.' </strong></td>';
                    $ejecTotal = round((($TotalTiem / $horasMes)*100), 2);
                    $tabla .= $fila;
                    $tabla .= "<tr  style='background-color: lightgray; text-align: right;'>
                    <td colspan='3'><strong>Tiempo total ejecutado: </strong></td>
                    <td>$TotalTiem Horas</td>
                    <td>$ejecTotal %</td>
                    </tr>";
                    $horasT = 0;
                    $minT = 0;
                    $TotalTiem = 0;
                    $ejec = 0;
                    $ejecTotal = 0;
                }
            }
            
            $tabla .='
                    </tbody>
                </table>';
            echo $tabla;
            mysqli_close($connection);
        }

        public static function descarga ($txtFechIni, $txtFechFin) {
            require('../Core/connection.php');
            $consultaNombre = "SELECT idPersona, apellido1, apellido2, nombres FROM pys_personas WHERE  (est='1' OR est='0') AND idEquipo != 'EQU008' AND idEquipo != 'EQU007' AND idEquipo != 'EQU006' ORDER BY apellido1 ASC;";
            $resultadoNombre = mysqli_query($connection, $consultaNombre);
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('Conecta-TE')
                ->setLastModifiedBy('Conecta-TE')
                ->setTitle('Informe de Ejecuciones generales P&S')
                ->setSubject('Informe de Ejecuciones generales P&S')
                ->setDescription('Informe de Ejecuciones generales P&S')
                ->setKeywords('Informe de Ejecuciones generales P&S')
                ->setCategory('Test result file');
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-Ejecución');
            $spreadsheet->addSheet($myWorkSheet, 0);
            $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
            $spreadsheet->removeSheetByIndex($sheetIndex);
            $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                /**Arreglo titulos */
                /**Aplicación de estilos */
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
            /**Dimensión columnas */
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Informe de Ejecuciones generales P&S');
            $sheet->setCellValue('A2', 'Desde: '.$txtFechIni.' Hasta: '.$txtFechFin);                
            $sheet->mergeCells("A1:E1");
            $sheet->mergeCells("A2:E2"); 
            $titulos=['Persona', 'Cod. Proyecto', 'Nombre Proyecto','Tiempo trabajado', '% ejecutado'];
            $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A4');
            $spreadsheet->getActiveSheet()->getStyle('A4:E4')->applyFromArray(STYLETABLETITLE);
            $filas = 5;
            while ($datosNombre = mysqli_fetch_array($resultadoNombre)){
                $filasIni = $filas;
                $idPersona = $datosNombre['idPersona'];
                if(self::tiemposMes($idPersona) !=false){
                    $nombrePersona = $datosNombre['apellido1'].' '.$datosNombre['apellido2'].' '.$datosNombre['nombres'];
                    $sheet->setCellValue('A'.$filas, $nombrePersona);
                    $consulta = "SELECT pys_asignados.idAsig, pys_asignados.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                                FROM pys_asignados
                                INNER JOIN pys_actualizacionproy ON  pys_actualizacionproy.idProy = pys_asignados.idProy
                                WHERE pys_asignados.idPersona='$idPersona' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_actualizacionproy.est= '1'
                                GROUP BY pys_actualizacionproy.codProy
                                ORDER BY pys_actualizacionproy.codProy ASC;";
                    $resultado = mysqli_query($connection, $consulta);   
                    $horasT = 0;
                    $minT = 0;
                    $TotalTiem = 0;
                    $ejec = 0;
                    $ejecTotal = 0;
                    $horas = 0;
                    $min = 0;

                    while ($datos = mysqli_fetch_array($resultado)) {  
                        $idProy        = $datos['idProy'];
                        $codProy       = $datos['codProy'];
                        $nombreProy    = $datos['nombreProy'];
                        $consulta1 = "SELECT idAsig  FROM pys_asignados WHERE (est = '1' OR est = '2')  AND idProy = '$idProy' AND idPersona = '$idPersona';"; 
                        $resultado1 = mysqli_query($connection, $consulta1);         
                        while ($datos1 = mysqli_fetch_array($resultado1)) {  
                            $idAsig1    = $datos1['idAsig'];
                            $consulta2 = "SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos FROM pys_tiempos WHERE estTiempo = '1'  AND idAsig = '$idAsig1' AND (fechTiempo >= '$txtFechIni' AND fechTiempo <= '$txtFechFin') AND (horaTiempo != 0 OR minTiempo != 0);";
                            $resultado2 = mysqli_query($connection, $consulta2);         
                            while ($datos2 = mysqli_fetch_array($resultado2)) {  
                                $horas += $datos2[0];
                                $min += $datos2[1];
                            }
                        }
                        $horasMes = self::tiemposMes($idPersona);
                        $Total = round(((($horas*60)+ $min)/60), 2);
                        $horasT += $horas ;
                        $minT += $min ;
                        $TotalTiem = ((($horasT*60) + $minT)/60);
                        $ejec = $Total / $horasMes;
                        if ($Total > 0) {
                            $fila = [$codProy, $nombreProy, $Total, $ejec];
                            $spreadsheet->getActiveSheet()->fromArray($fila, null, 'B'.$filas);
                            $filas += 1;
                        }
                        $horas = 0;
                        $min = 0;
                        $Total = 0;
                        
                    }
                    if($TotalTiem > 0){
                    $sheet->mergeCells("A".$filasIni.":A".($filas-1));
                    } else{
                        $filas += 1;
                    }
                    $ejecTotal = $TotalTiem / $horasMes;
                    $fila=[ 'Tiempo total ejecutado:', '','',$TotalTiem.' Horas',$ejecTotal];
                    $spreadsheet->getActiveSheet()->fromArray($fila, null, 'A'.$filas);
                    $sheet->mergeCells("A".$filas.":C".$filas);
                    $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':E'.$filas)->applyFromArray(STYLETABLETITLESUB);

                    $horasT = 0;
                    $minT = 0;
                    $TotalTiem = 0;
                    $ejec = 0;
                    $ejecTotal = 0;
                    $filas += 1;
                }
            }
            $spreadsheet->getActiveSheet()->getStyle('E5:E'.$filas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $spreadsheet->getActiveSheet()->getStyle('D5:D'.$filas)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('A4:E'.($filas-1))->getBorders()->applyFromArray(STYLEBORDER);
            $spreadsheet->getActiveSheet()->getStyle('A4:E'.($filas-1))->applyFromArray(STYLEBODY);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Informe de Ejecuciones generales P&S '.gmdate(' d M Y ').'.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;  
            mysqli_close($connection);
        }
        
    }
?>