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

    Class InformeEjecucion {
        
        public static function busqueda ($proyecto, $fechaini, $fechafin) {
            $totPresupuesto = 0;
            $minutos = 0;
            $horas = 0;
            $minu = 0;
            $minTot = 0;
            $hor = 0;
            $horasTot = 0;
            $total = 0;
            $valTot =0;
            $valPer =0;
            $horPer = 0;
            $minPer = 0;
            require('../Core/connection.php');
            if ($proyecto == null){
                $sql = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' ORDER BY codProy asc;";
            } else {
                $sql = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
            }
            $cs = mysqli_query($connection, $sql);
            $registros = mysqli_num_rows($cs);
            if ($registros > 0) {
                echo'
                <table class="table table-hover table-striped table-responsive-xl">
                <thead>
                    <tr>
                    <th>Proyecto</th>
                    <th>Cod. Producto/servicio</th>
                    <th>Estado Producto/servicio</th>
                    <th>Producto/servicio</th>
                    <th>Presupuesto Producto/servicio</th>
                    <th>Asignado</th>
                    <th>Tiempo invertido</th>
                    <th>Valor Producto/Servicio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                ';
                while ($fila = mysqli_fetch_array($cs)) {
                    echo'<td>';
                    echo $fila['codProy'].' '.$fila['nombreProy'];
                    echo'</td>';
                    echo'<td colspan="7"><table><tbody>';
                    $sql1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                        FROM pys_actualizacionproy
                        INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$fila[2]'
                        ORDER BY pys_actsolicitudes.idSol;";
                    $cs1=mysqli_query($connection, $sql1);
                    while ($fila1 = mysqli_fetch_array($cs1)) {
                        echo'
                            <tr>
                            <td>P'.$fila1['idSol'].'</td>
                            <td>'.$fila1['nombreEstSol'].'</td>
                            <td>'.$fila1['ObservacionAct'].'</td>
                            <td>'.$fila1['presupuesto'].'</td>
                        ';
                        $totPresupuesto = $fila1[2] + $totPresupuesto;
                        echo'<td><table><tbody>';
                        $sql2="SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                        FROM pys_asignados
                        INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                        WHERE pys_asignados.est = '1' AND  pys_asignados.idSol='$fila1[0]'
                        ORDER BY pys_personas.apellido1;";
                        $cs2=mysqli_query($connection, $sql2);
                        while ($fila2 = mysqli_fetch_array($cs2)) {
                            echo'
                            <tr>
                                <td>'.$fila2['apellido1'].' '.$fila2['apellido2'].' '.$fila2['nombres'].'</td>
                            ';
                            if ($fechaini != null && $fechafin != null){
                                $sql3="SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo  FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]' AND (fechTiempo >= '$fechaini' AND fechTiempo <= '$fechafin')
                                GROUP BY fechTiempo;";    
                            } else {
                                $sql3="SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo  FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]' GROUP BY fechTiempo;";  
                            }
                            $cs3=mysqli_query($connection, $sql3);
                            while ($fila3 = mysqli_fetch_array($cs3)) {
                                $minutos = $fila3['minutos'] + $minutos ;
                                $horas = $fila3['horas'] + $horas;
                                if ($minutos>= 60) {
                                    $horas = ($minutos / 60) + $horas;
                                    $minutos = $minutos % 60;
                                    $horas = intval($horas);
                                    $minutos = intval($minutos);
                                }

                                $sql4="SELECT salario
                                FROM pys_salarios
                                WHERE estSal = '1' AND idPersona='$fila2[4]' AND( mes <= '$fila3[2]' AND anio >= '$fila3[2]') ;";
                                $cs4=mysqli_query($connection, $sql4);

                                while ($fila4 = mysqli_fetch_array($cs4)) {
                                    $valorMin = ($fila4[0]/60);
                                }
                                $tiempo = ($horas*60) + $minutos;
                                $minu = $minutos + $minu;
                                $hor = $horas + $hor;
                                if ($minu > '59') {
                                    $hor = ($minu / 60) + $hor;
                                    $minu = $minu % 60;
                                    $hor = intval($hor);
                                    $minu = intval($minu);
                                }
                                $minutos = 0;
                                $horas = 0;
                                $valorTot = $tiempo * $valorMin;
                                $valTot = $valTot + $valorTot;
                            }
                            echo'
                                <td>'.$hor.' Horas y '.$minu.' Minutos </td>
                                <td>$ '.round($valTot, 2).'</td>
                                </tr>';
                            $minTot = $minTot + $minu;
                            $horasTot = $horasTot + $hor;
                            if ($minTot>=60) {
                                $horasTot = ($minTot / 60) + $horasTot;
                                $minTot = $minTot % 60;
                                $horasTot = intval($horasTot);
                                $minTot = intval($minTot);
                            }
                            $total = $valTot + $total;
                            $min = 0;
                            $valorMin = 0;
                            $horPer = $hor + $horPer;
                            $minPer = $minu + $minPer;
                            if ($minPer>=60) {
                                $horPer = ($minPer / 60) + $horPer;
                                $minPer = $minPer % 60;
                                $horPer = intval($horPer);
                                $minPer = intval($minPer);
                            }
                            $valPer = $valTot + $valPer;
                            $dif = $fila1['presupuesto'] - $valPer;
                            $minu = 0;
                            $hor = 0;
                            $valorTot = 0;
                            $valTot = 0;
                        }
                        echo'
                        <tr>
                            <td><b>Tiempo invertido <br></b>'.$horPer.' horas y '.$minPer.' min </td>
                            <td><b>Costo total Producto/Servicio <br></b> $'.round($valPer, 2).'</td>
                            <td><b>Diferencia </b><br> $'.round($dif, 2).'</td>
                        </tr>
                        </tbody></table></td>';
                        $horPer = 0;
                        $minPer = 0;
                        $valPer = 0;
                        $dif = 0;
                    }
                    $valTotProy =  $totPresupuesto - round($total, 2);
                    echo'</tr></tbody></table></td>';
                    echo'</tr>
                    <tr>
                        <td colspan="2"><b>Presupuesto de proyecto: </b>'.$totPresupuesto.'</td>
                        <td colspan="2"><b>Tiempo trabajado: </b>'.$horasTot.' Horas y '.$minTot.' Minutos</td>
                        <td colspan="2"><b>Valor total de productos/servicios $</b> '.round($total, 2).'</td>
                        <td><b>Diferencia: </b>$'.$valTotProy.'</strong></td>
                    </tr>';
                    $horasTot = 0;
                    $minTot = 0;
                    $total = 0;
                    $totPresupuesto = 0;
                }
                echo'
                    </tbody>
                    </table>
                ';
            }
        }

        public static function descarga ($proyecto, $fechaini, $fechafin) {
            require('../Core/connection.php');
            if ($proyecto == null){
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' ORDER BY codProy asc;";
            } else {
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
            }
            $resultado = mysqli_query($connection, $consulta);
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
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(21);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(46);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(39);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(22);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(22);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Ejecuciones Productos/Servicios');
                if ($fechaini != null && $fechafin != null){
                    $sheet->setCellValue('A4', 'Desde: '.$fechaini.' Hasta: '.$fechafin);
                }
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A4:J4");
                $titulos=['Proyecto', 'Cod. Producto/servicio', 'Estado', 'Producto/servicio','Presupuesto Producto/servicio', 'Asignado', 'Tiempo invertido', 'Valor Producto/Servicio'];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:J6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($datos = mysqli_fetch_array($resultado)) {
                    $totPresupuesto = 0;
                    $minutos = 0;
                    $horas = 0;
                    $minu = 0;
                    $minTot = 0;
                    $hor = 0;
                    $horasTot = 0;
                    $total =0;
                    $valTot =0;
                    $valPer =0;
                    $horPer = 0;
                    $minPer = 0;
                    $valTotProy = 0;
                    $proy = $datos['codProy'].' '.$datos['nombreProy'];
                    $spreadsheet->getActiveSheet()->setCellValue('A'.$fila, $proy);
                    $idProy = $datos['idProy'];
                    $consulta1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy'
                    ORDER BY pys_actsolicitudes.idSol;";
                    $resultado1=mysqli_query($connection, $consulta1);
                    $registros1 = mysqli_num_rows($resultado1);
                    if ($registros1 > 0) {
                        $cont = 0;
                        $filaIni = $fila;
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $idSol = $datos1['idSol'];
                            $ObservacionAct = $datos1['ObservacionAct'];
                            $presupuesto = $datos1['presupuesto'];
                            $nombreEstSol = $datos1['nombreEstSol'];
                            $totPresupuesto += $datos1['presupuesto'];
                            $filaSol = $fila;
                            $producto =['P'.$idSol, $nombreEstSol, $ObservacionAct, $presupuesto];
                            $spreadsheet->getActiveSheet()->fromArray($producto,null,'B'.$fila);
                            $spreadsheet->getActiveSheet()->getStyle('E'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                            FROM pys_asignados
                            INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                            WHERE pys_asignados.est = '1' AND  pys_asignados.idSol='$idSol'
                            ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            while ($datos2 = mysqli_fetch_array($resultado2)) {
                                $nombre = $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'];
                                $idAsig = $datos2['idAsig'];
                                $idPersona = $datos2['idPersona'];
                                if ($fechaini != null && $fechafin != null){
                                    $consulta3="SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo  FROM pys_tiempos
                                    WHERE estTiempo = '1' AND idAsig = '$idAsig' AND (fechTiempo >= '$fechaini' AND fechTiempo <= '$fechafin')
                                    GROUP BY fechTiempo;";    
                                } else {
                                    $consulta3="SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo  FROM pys_tiempos
                                    WHERE estTiempo = '1' AND idAsig = '$idAsig' GROUP BY fechTiempo;";  
                                }
                                $resultado3 = mysqli_query($connection, $consulta3);
                                while ($datos3 = mysqli_fetch_array($resultado3)) {
                                    $fechTiempo = $datos3['fechTiempo'];
                                    $minutos = $datos3['minutos'] + $minutos ;
                                    $horas = $datos3['horas'] + $horas;
                                    if ($minutos>= 60) {
                                        $horas = ($minutos / 60) + $horas;
                                        $minutos = $minutos % 60;
                                        $horas = intval($horas);
                                        $minutos = intval($minutos);
                                    }
                                    $consulta4="SELECT salario
                                    FROM pys_salarios
                                    WHERE estSal = '1' AND idPersona='$idPersona' AND( mes <= '$fechTiempo' AND anio >= '$fechTiempo') ;";
                                    $resultado4=mysqli_query($connection, $consulta4);

                                    while ($datos4 = mysqli_fetch_array($resultado4)) {
                                        $valorMin = ($datos4['salario']/60);
                                    }
                                    $tiempo = ($horas*60) + $minutos;
                                    $minu = $minutos + $minu;
                                    $hor = $horas + $hor;
                                    if ($minu > '59') {
                                        $hor = ($minu / 60) + $hor;
                                        $minu = $minu % 60;
                                        $hor = intval($hor);
                                        $minu = intval($minu);
                                    }
                                    $minutos = 0;
                                    $horas = 0;
                                    $valorTot = $tiempo * $valorMin;
                                    $valTot = $valTot + $valorTot;
                                }
                                $tiempo = $hor.' Horas y '.$minu.' Minutos';
                                $persona = [$nombre, $tiempo, round($valTot, 2)];
                                $spreadsheet->getActiveSheet()->fromArray($persona,null,'F'.$fila);
                                $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                                $minTot = $minTot + $minu;
                                $horasTot = $horasTot + $hor;
                                if ($minTot>=60) {
                                    $horasTot = ($minTot / 60) + $horasTot;
                                    $minTot = $minTot % 60;
                                    $horasTot = intval($horasTot);
                                    $minTot = intval($minTot);
                                }
                                $total = $valTot + $total;
                                $min = 0;
                                $valorMin = 0;
                                $horPer = $hor + $horPer;
                                $minPer = $minu + $minPer;
                                if ($minPer>=60) {
                                    $horPer = ($minPer / 60) + $horPer;
                                    $minPer = $minPer % 60;
                                    $horPer = intval($horPer);
                                    $minPer = intval($minPer);
                                }
                                $valPer = $valTot + $valPer;
                                $dif = $datos1[2] - $valPer;
                                $minu = 0;
                                $hor = 0;
                                $valTot = 0;
                                $valorTot = 0;
                                $fila += 1;
                                $cont += 1;
                            }
                            $tiempoInvertido=['Tiempo invertido', '', $horPer.' horas y '.$minPer.' min'] ;
                            $costoTotal=['Costo total Producto/Servicio', '', round($valPer, 2)] ;
                            $diferencia=['Diferencia', '', round($dif, 2)] ;
                            $spreadsheet->getActiveSheet()->fromArray($tiempoInvertido,null,'F'.$fila);
                            $spreadsheet->getActiveSheet()->fromArray($costoTotal,null,'F'.($fila+1));
                            $spreadsheet->getActiveSheet()->fromArray($diferencia,null,'F'.($fila+2));
                            $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                            $spreadsheet->getActiveSheet()->getStyle('H'.($fila+1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                            $spreadsheet->getActiveSheet()->getStyle('H'.($fila+2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                            $spreadsheet->getActiveSheet()->getStyle('F'.$fila.':H'.($fila+2))->applyFromArray(STYLETABLETITLESUB);
                            $sheet->mergeCells("F".$fila.":G".$fila);
                            $sheet->mergeCells("F".($fila+1).":G".($fila+1));
                            $sheet->mergeCells("F".($fila+2).":G".($fila+2));
                            $sheet->mergeCells("B".$filaSol.":B".($fila+2));
                            $sheet->mergeCells("C".$filaSol.":C".($fila+2));
                            $sheet->mergeCells("D".$filaSol.":D".($fila+2));
                            $sheet->mergeCells("E".$filaSol.":E".($fila+2));
                            $fila += 3;
                            $cont += 3;
                        }
                        $valTotProy =  $totPresupuesto - round($total, 2);
                        $sheet->mergeCells("A".$filaIni.":A".($fila-1));
                    } else {
                        $sheet->mergeCells("B".$fila.":E".$fila);
                        $fila+= 1;
                    }
                    $presupuestoLis = ['Presupuesto de proyecto:', $totPresupuesto];
                    $tiempoTra = ['Tiempo trabajado:',  $horasTot.' Horas y '.$minTot.' Minutos'];
                    $valorTotalPS = ['Valor total de productos/servicios', round($total, 2)]; 
                    $diferenciaLis = ['Diferencia:', $valTotProy];
                    $spreadsheet->getActiveSheet()->fromArray($presupuestoLis,null,'F'.$fila);
                    $spreadsheet->getActiveSheet()->fromArray($tiempoTra,null,'F'.($fila+1));
                    $spreadsheet->getActiveSheet()->fromArray($valorTotalPS,null,'F'.($fila+2));
                    $spreadsheet->getActiveSheet()->fromArray($diferenciaLis,null,'F'.($fila+3));
                    $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                    $spreadsheet->getActiveSheet()->getStyle('G'.($fila+3))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                    $sheet->mergeCells("B".$fila.":E".$fila);
                    $sheet->mergeCells("B".($fila+1).":E".($fila+1));
                    $sheet->mergeCells("B".($fila+2).":E".($fila+2));
                    $sheet->mergeCells("B".($fila+3).":E".($fila+3));
                    $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':H'.($fila+3))->applyFromArray(STYLETABLETITLESUB2);
                    $fila += 4;  
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:H'.$fila)->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:H'.$fila)->applyFromArray(STYLEBODY);
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