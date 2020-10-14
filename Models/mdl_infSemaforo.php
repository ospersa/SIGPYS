<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
const STYLEGREEN = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => '89F986',
        ]
    ]
];
const STYLEYELLOW = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'F9F22C',
        ]
    ]
];
const STYLEORANGE = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'F7B14B',
        ]
    ]
];
const STYLERED = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'F8473B',
        ]
    ]
];
    Class InformeSemaforo {
        
        public static function descarga () {
            require('../Core/connection.php');
            $consulta = "SELECT idProy,codProy, nombreProy,nombreCortoProy FROM  pys_actualizacionproy
            WHERE  pys_actualizacionproy.est = '1'AND idEstProy='ESP001'
            ORDER BY pys_actualizacionproy.codProy ASC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe Semaforo')
                    ->setSubject('Informe Semáforo')
                    ->setDescription('Informe Semáforo')
                    ->setKeywords('Informe Semáforo')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-Semáforo');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(16);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(16)->setVisible(false);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16)->setVisible(false);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16)->setVisible(false);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(16);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(16)->setVisible(false);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(16);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(16);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(16)->setVisible(false);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Semáforo');           
                $sheet->mergeCells("A1:M1");
                $consultaPer = "SELECT inicioPeriodo, finPeriodo  FROM pys_periodos WHERE inicioPeriodo <= now() AND now() <= finPeriodo;";
                $resultadoPer = mysqli_query($connection, $consultaPer);
                while ($datosPer = mysqli_fetch_array($resultadoPer)) {
                    $inicioPeriodo = $datosPer['inicioPeriodo'];
                    $finperiodo = $datosPer['finPeriodo'];
                }
                $titulosA = [ 'Información Proyecto', '', '', '', 'Presupuesto total o cotizado para productos activos en el corte ('.$inicioPeriodo.')', 'Ejecutado total al corte anterior ('.$inicioPeriodo.')' , 'Contratos Civiles', 'Disponible segun S.I. Al corte anterior ('.$inicioPeriodo.')', 'Disponible (SAP)', 'Difirencia Ejecución S.I. y Reportes ADMIN.', 'Presupuesto ejecutado corte actual (S.I)', 'Disponible aprox. a la fecha','Diferencia total (diferencia Presupuesto  total contra todo lo ejecutado a la fecha)'];
                
                $titulos = [ 'Sigla', 'Proyecto', 'Profesor' , 'Gestor', 'Gestor + bolsa + montaje', 'Gestor + bolsa + montaje', '', 'Gestor + bolsa + montaje', 'Total', '', 'Total','Total', 'Total' ];
                $sheet->setCellValue('A2', '0 - 40 % Verde');
                $sheet->setCellValue('A3', '41 - 60 % Amarillo');
                $sheet->setCellValue('A4', '61 - 80% Naranja');
                $sheet->setCellValue('A5', '80 - 100 %	Rojo');
                $spreadsheet->getActiveSheet()->fromArray($titulosA, null, 'A6');
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A7');
                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray(STYLEGREEN);
                $spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray(STYLEYELLOW);
                $spreadsheet->getActiveSheet()->getStyle('A4')->applyFromArray(STYLEORANGE);
                $spreadsheet->getActiveSheet()->getStyle('A5')->applyFromArray(STYLERED);
                $sheet->mergeCells("A6:D6"); 
                $spreadsheet->getActiveSheet()->getStyle('A6:M7')->applyFromArray(STYLETABLETITLE);
                $fila = 8;
                while ($datos = mysqli_fetch_array($resultado)) {
                    $id = $datos['idProy'];
                    $consulta2=" SELECT nombres, apellido1, apellido2, idRol 
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona WHERE idProy ='".$id."' and pys_asignados.est='1' AND pys_personas.est ='1' AND (idRol='ROL025' OR idRol='ROL024' OR idRol='ROL018') AND pys_asignados.idSol='';";
                    $resultado1 = mysqli_query($connection, $consulta2);
                    $registros1 = mysqli_num_rows($resultado1);
                    $asesor = '';
                    $profesor = '';
                    $presupuesto = '0';
                    $valorPS = '0';
                    $valorPScivil = '0';
                    $valorPSperiodo = '0';
                    if($registros1 > 0){
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $rol= $datos1['idRol'];
                            if($rol=='ROL025' || $rol=='ROL024' ){
                                $asesor .= $datos1['nombres'].' '.$datos1['apellido1'].' '.$datos1['apellido2'].' / '; 
                            } else{
                                $profesor .= $datos1['nombres'].' '.$datos1['apellido1'].' '.$datos1['apellido2'].' / ';
                            }
                        }
                    }
                    $asesor = substr($asesor,0,-3);
                    $profesor = substr($profesor,0,-3);
                    $consulta3 = "SELECT SUM(pys_actsolicitudes.presupuesto)
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                    INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                    AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$id' AND pys_estadosol.est = '1'; ";
                    $resultado2 = mysqli_query($connection, $consulta3);
                    $registros2 = mysqli_num_rows($resultado2);
                    if($registros2 > 0){
                        while ($datos2 = mysqli_fetch_array($resultado2)) {
                           $presupuesto = $datos2['SUM(pys_actsolicitudes.presupuesto)'];
                        }
                    }
                    $presupuesto = ($presupuesto != null ) ? $presupuesto : 0;
                    $consultaPer = "SELECT idPeriodo FROM pys_periodos WHERE inicioPeriodo <= now() AND now() <= finPeriodo;";
                    $resultadoPer = mysqli_query($connection, $consultaPer);
                    $datosPer = mysqli_fetch_array($resultadoPer);
                    $periodo = $datosPer['idPeriodo']-1;
                    $consulta4 ="SELECT pys_asignados.idAsig,pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_salarios.salario AS salario, pys_tiempos.fechRegistroTiempo, pys_periodos.idPeriodo
                    FROM pys_tiempos 
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                    INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                    INNER JOIN pys_periodos ON pys_periodos.idPeriodo = ".$periodo."
                    WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2')  AND pys_tiempos.fechRegistroTiempo < pys_periodos.finPeriodo
                    AND pys_asignados.idProy = '".$id."' AND pys_salarios.estSal = '1' AND pys_tiempos.idFase <> 'FS0012' AND pys_actsolicitudes.est=1;";
                    $resultado3 = mysqli_query($connection, $consulta4);
                    $registros3 = mysqli_num_rows($resultado3);
                    if($registros3 > 0){
                        while ($datos3 = mysqli_fetch_array($resultado3)) {
                            $salarioHor = $datos3['salario'];
                            $salarioMin = $salarioHor / 60;
                            $valorPS += (($datos3['horas'] * 60) + $datos3['minutos']) * $salarioMin;
                        }
                    }
                    $valorPS = ($valorPS != null ) ? $valorPS : 0;
                    $consulta5 ="SELECT pys_asignados.idAsig,pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_salarios.salario AS salario, pys_tiempos.fechRegistroTiempo
                    FROM pys_tiempos 
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                    INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                    WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2')  
                    AND pys_asignados.idProy = '".$id."' AND pys_salarios.estSal = '1' AND pys_tiempos.idFase = 'FS0012' AND pys_actsolicitudes.est=1;";
                    $resultado4 = mysqli_query($connection, $consulta5);
                    $registros4 = mysqli_num_rows($resultado4);
                    if($registros4 > 0){
                        while ($datos4 = mysqli_fetch_array($resultado4)) {
                            $salarioHor = $datos4['salario'];
                            $salarioMin = $salarioHor / 60;
                            $valorPScivil += (($datos4['horas'] * 60) + $datos4['minutos']) * $salarioMin;
                        }
                    }
                    $valorPScivil = ($valorPScivil != null ) ? $valorPScivil : 0;
                    $consulta6 ="SELECT pys_asignados.idAsig,pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_salarios.salario AS salario, pys_tiempos.fechRegistroTiempo, pys_periodos.idPeriodo
                    FROM pys_tiempos 
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                    INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                    INNER JOIN pys_periodos ON pys_periodos.inicioPeriodo <= now() AND now() <= pys_periodos.finPeriodo
                    WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2')  AND pys_tiempos.fechRegistroTiempo < pys_periodos.finPeriodo AND pys_tiempos.fechRegistroTiempo > pys_periodos.inicioPeriodo
                    AND pys_asignados.idProy = '".$id."' AND pys_salarios.estSal = '1' AND pys_actsolicitudes.est=1 AND pys_tiempos.idFase <> 'FS0012' ;";
                    $resultado5 = mysqli_query($connection, $consulta6);
                    $registros5 = mysqli_num_rows($resultado5);
                    if($registros5 > 0){
                        while ($datos5 = mysqli_fetch_array($resultado5)) {
                            $salarioHor = $datos5['salario'];
                            $salarioMin = $salarioHor / 60;
                            $valorPSperiodo += (($datos5['horas'] * 60) + $datos5['minutos']) * $salarioMin;
                        }
                    }
                    
                    $SAP = ($datos['nombreCortoProy'] == '') ?  '=VLOOKUP(A'.$fila.',SAP!A:B,2,FALSE)' : $datos['nombreCortoProy'];
                    $listDatos=[ $datos['codProy'],$datos['nombreProy'],$profesor,$asesor, $presupuesto, $valorPS, $valorPScivil, '=(E'.$fila.'-(F'.$fila.'+G'.$fila.'))', $SAP, '=(H'.$fila.'-I'.$fila.')',$valorPSperiodo, '=(I'.$fila.'-K'.$fila.')', '=(F'.$fila.'+G'.$fila.'+K'.$fila.')/'.'E'.$fila];
                    $spreadsheet->getActiveSheet()->getCell('I'.$fila)->getStyle()->setQuotePrefix(true);
                    $spreadsheet->getActiveSheet()->fromArray($listDatos, null, 'A'.$fila);
                   
                    if($presupuesto != 0 && $presupuesto != null && $presupuesto != '0'){

                        $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                        $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                        $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                        $conditional1->addCondition('AND((F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'>=0,(F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'<=0.4)');
                        $conditional1->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                        $conditional1->getStyle()->getFill()->getEndColor()->setARGB('89F986');
                    
                        $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                        $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                        $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                        $conditional2->addCondition('AND((F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'>=0.41,(F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'<=0.6)');
                        $conditional2->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                        $conditional2->getStyle()->getFill()->getEndColor()->setARGB('F9F22C');
                    
                        $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                        $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                        $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                        $conditional3->addCondition('AND((F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'>=0.61,(F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.'<=0.8)');
                        $conditional3->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                        $conditional3->getStyle()->getFill()->getEndColor()->setARGB('F7B14B');
                    
                        $conditional4 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                        $conditional4->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                        $conditional4->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                        $conditional4->addCondition('((F'.$fila.'+G'.$fila.'+K'.$fila.')/E'.$fila.')>=0.81');
                        $conditional4->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                        $conditional4->getStyle()->getFill()->getEndColor()->setARGB('F8473B');
        
                        $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('L'.$fila)->getConditionalStyles();
                        $conditionalStyles[] = $conditional1;
                        $conditionalStyles[] = $conditional2;
                        $conditionalStyles[] = $conditional3;
                        $conditionalStyles[] = $conditional4;
                        $spreadsheet->getActiveSheet()->getStyle('L'.$fila)->setConditionalStyles($conditionalStyles);
                        $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->setConditionalStyles($conditionalStyles);
                    }
                    else{
                        $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->applyFromArray(STYLERED);
                        $spreadsheet->getActiveSheet()->getStyle('L'.$fila)->applyFromArray(STYLERED);
                    }
                    $fila +=1;
                } 
                
            }

                $spreadsheet->getActiveSheet()->getStyle('E8:L'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $spreadsheet->getActiveSheet()->getStyle('M8:L'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
                $spreadsheet->getActiveSheet()->getStyle('A6:M'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER); 
                $spreadsheet->getActiveSheet()->getStyle('A8:M'.($fila-1))->applyFromArray(STYLEBODY);

                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'SAP');
                $spreadsheet->addSheet($myWorkSheet, 0);
                                    
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe Semaforo '.gmdate('d M Y').'.xlsx"');
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