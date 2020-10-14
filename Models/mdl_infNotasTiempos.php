<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

    require '../php_libraries/vendor/autoload.php';
    const STYLETABL = [
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
    const STYLEBOR = ['allBorders'=>[
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => '000000' ]
        ]
    ];
    
    const STYLEB = ['font' => [
        'size' => '10'
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
            'textRotation' => 0, 
        ]
    ];

    Class InformeNotasTiempo {
        
        public static function busqueda ($proyecto, $solicitud, $fechaini, $fechafin, $diasLab) {
            $minutos = 0;
            $horas = 0;
            $totalH = 0;
            $totalM = 0;
            $porcen = 0;
            $totalHPro = 0;
            $totalMPro = 0;
            $tabla ="";
            require('../Core/connection.php');
            $sql = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
            WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
            $cs = mysqli_query($connection, $sql);
            $registros = mysqli_num_rows($cs);
            if ($registros > 0) {
                $tabla .='
                <table class="responsive-table" border="1">
                <thead>
                    <tr class="teal lighten-4">
                    <th>Proyecto</th>
                    <th>Cod. Producto/servicio</th>
                    <th>Estado Producto/servicio</th>
                    <th>Producto/servicio</th>
                    <th>Asignado</th>
                    <th>Fecha</th>
                    <th>Tiempo Registrado (Horas)</th>
                    <th>Nota</th>
                    <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                ';
                while ($fila = mysqli_fetch_array($cs)) {
                    $sql1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                        FROM pys_actualizacionproy
                        INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$fila[2]'
                        ORDER BY pys_actsolicitudes.idSol;";
                    if ($solicitud != null){
                        $sql1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$fila[2]' AND pys_actsolicitudes.idSol='$solicitud'
                    ORDER BY pys_actsolicitudes.idSol;";
                    } 
                    $cs1=mysqli_query($connection, $sql1);
                    while ($fila1 = mysqli_fetch_array($cs1)) {
                        $sql2="SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                        FROM pys_asignados
                        INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                        WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol='$fila1[0]'
                        ORDER BY pys_personas.apellido1;";
                        $solTiempo= false;
                        $cs2=mysqli_query($connection, $sql2);
                        while ($fila2 = mysqli_fetch_array($cs2)) {
                            if ($fechaini != null && $fechafin != null){
                                $sql3="SELECT minTiempo, horaTiempo, fechTiempo, notaTiempo FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]' AND (fechTiempo >= '$fechaini' AND fechTiempo <= '$fechafin')";    
                            } else {
                                $sql3="SELECT minTiempo, horaTiempo, fechTiempo, notaTiempo FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]';";  
                            }
                            $cs3=mysqli_query($connection, $sql3);
                            if( mysqli_num_rows($cs3)>0){
                                $solTiempo= true;
                                while ($fila3 = mysqli_fetch_array($cs3)) {
                                    $minutos = $fila3['minTiempo'];
                                    $horas = $fila3['horaTiempo'];
                                    $fechTiempo = $fila3['fechTiempo'];
                                    $notaTiempo = $fila3['notaTiempo'];
                                    $tiempo= ($minutos / 60) + $horas;
                                    if($diasLab != null){                            
                                        $horasMes = $diasLab*8;
                                        $porcen = ($tiempo/$horasMes)*100;
                                    } else {
                                        $porcen = intval(0);
                                    }
                                    $tabla .='
                                    <tr>
                                    <td>'.$fila['codProy'].' '.$fila['nombreProy'].'</td>
                                    <td>P'.$fila1['idSol'].'</td>
                                    <td>'.$fila1['nombreEstSol'].'</td>
                                    <td> <p class= "truncate">'.$fila1['ObservacionAct'].'</p></td>
                                    <td>'.$fila2['apellido1'].' '.$fila2['apellido2'].' '.$fila2['nombres'].'</td>
                                    <td>'.$fechTiempo.'</td>
                                    <td>'.$horas.'Horas '.$minutos.'Minutos</td>
                                    <td> <p class= "truncate">'.$notaTiempo.'</p></td>
                                    <td>'.round($porcen,2).'%</td>
                                    </tr>';
                                    $totalH += $horas;
                                    $totalM += $minutos;
                                }
                            }
                        }                        

                        if ($totalM>= 60) {
                            $totalH = ($totalM / 60) + $totalH;
                            $totalM = $totalM % 60;
                            $totalH = intval($totalH);
                            $totalM = intval($totalM);
                        }
                        $tiempo= ($totalM / 60) + $totalH;
                        if($diasLab != null){                            
                            $horasMes = $diasLab*8;
                            $porcenS = ($tiempo/$horasMes)*100;
                        } else {
                            $porcenS = intval(0);
                        }
                        if($solTiempo == true){

                            $tabla .='
                            <tr>
                            <td>'.$fila['codProy'].' '.$fila['nombreProy'].'</td>
                            <td>P'.$fila1['idSol'].'</td>
                            <td>'.$fila1['nombreEstSol'].'</td>
                            <td > <p class= "truncate">'.$fila1['ObservacionAct'].'</p></td>
                            <td><b>Total:</b></td>
                            <td></td>
                            <td>'.$totalH.' Horas '.$totalM.' Minutos</td>
                            <td></td>
                            <td>'.round($porcenS,2).'%</td>
                            </tr>';
                        } 
                            $totalHPro += $totalH;
                            $totalMPro += $totalM;
                        $totalH = 0;
                        $totalM = 0;
                        
                    
                    }
                    if($solicitud == ""){

                        if ($totalMPro>= 60) {
                            $totalHPro = ($totalMPro / 60) + $totalHPro;
                            $totalMPro = $totalMPro % 60;
                            $totalHPro = intval($totalHPro);
                            $totalMPro = intval($totalMPro);
                        }
                        $tiempo= ($totalMPro / 60) + $totalHPro;
                        if($diasLab != null){                            
                            $horasMes = $diasLab*8;
                            $porcenS = ($tiempo/$horasMes)*100;
                        } else {
                            $porcenS = intval(0);
                        }
                        $tabla .='
                        <tr>
                        <td>'.$fila['codProy'].' '.$fila['nombreProy'].'</td>
                        <td></td>
                        <td><b>Total:</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$totalHPro.'Horas '.$totalMPro.'Minutos</td>
                        <td></td>
                        <td>'.round($porcenS,2).'%</td>
                        </tr>';
                        $totalHPro = 0;
                        $totalMPro = 0;
                    }
                }
                $tabla .='
                </tbody>
                </table>
                ';
            }
            echo $tabla;
        }
        
        public static function descarga ($proyecto, $solicitud, $fechaini, $fechafin, $diasLab) {
            $minutos = 0;
            $horas = 0;
            $sumH = 0;
            $sumM = 0;
            $porcen = 0;
            $tiempoTotal = 0;
            $sumHP = 0;
            $sumMP = 0;
            $tiempoTotalP = 0;
            $sumHPro = 0;
            $sumMPro = 0;
            $tiempoTotalPro = 0;
            require('../Core/connection.php');
            $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de notas tiempos')
                    ->setSubject('Informe de notas tiempos')
                    ->setDescription('Informe de notas tiempos')
                    ->setKeywords('Informe de notas tiempos')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-NotasTiempos');
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
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Notas Tiempos');
                $sheet->setCellValue('A3', 'Desde: '.$fechaini.' Hasta: '.$fechafin);
                $sheet->setCellValue('A4', 'Días laborales: '.$diasLab);
                
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A3:J3");
                $sheet->mergeCells("A4:J4");
                $titulos=['Proyecto', 'Cod. Producto/servicio', 'Estado', 'Producto/servicio', 'Asignado', 'Fecha', 'Tiempo Registrado','', 'Nota', 'Porcentaje'];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                $sheet->mergeCells("G6:H6");
                $sheet->mergeCells("A6:A7");
                $sheet->mergeCells("B6:B7");
                $sheet->mergeCells("C6:C7");
                $sheet->mergeCells("D6:D7");
                $sheet->mergeCells("E6:E7");
                $sheet->mergeCells("F6:F7");
                $sheet->mergeCells("I6:I7");
                $sheet->mergeCells("J6:J7");
                $spreadsheet->getActiveSheet()->setCellValue('G7', 'Horas');
                $spreadsheet->getActiveSheet()->setCellValue('H7', 'Minutos');
                $spreadsheet->getActiveSheet()->getStyle('A6:j7')->applyFromArray(STYLETABL);
                $filaEX = 8;
                $sql = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
                $cs = mysqli_query($connection, $sql);
                $registros = mysqli_num_rows($cs);
            if ($registros > 0) {
               
                while ($fila = mysqli_fetch_array($cs)) {
                    $sql1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                        FROM pys_actualizacionproy
                        INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$fila[2]'
                        ORDER BY pys_actsolicitudes.idSol;";
                    if ($solicitud != null){
                        $sql1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$fila[2]' AND pys_actsolicitudes.idSol='$solicitud'
                    ORDER BY pys_actsolicitudes.idSol;";
                    } 
                    $cs1=mysqli_query($connection, $sql1);
                    while ($fila1 = mysqli_fetch_array($cs1)) {
                        $sql2="SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                        FROM pys_asignados
                        INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                        WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol='$fila1[0]'
                        ORDER BY pys_personas.apellido1;";
                        $cs2=mysqli_query($connection, $sql2);
                        while ($fila2 = mysqli_fetch_array($cs2)) {
                            if ($fechaini != null && $fechafin != null){
                                $sql3="SELECT minTiempo, horaTiempo, fechTiempo, notaTiempo FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]' AND (fechTiempo >= '$fechaini' AND fechTiempo <= '$fechafin')";    
                            } else {
                                $sql3="SELECT minTiempo, horaTiempo, fechTiempo, notaTiempo FROM pys_tiempos
                                WHERE estTiempo = '1' AND idAsig = '$fila2[0]';";  
                            }
                            $cs3=mysqli_query($connection, $sql3);
                            if( mysqli_num_rows($cs3)>0){
                                while ($fila3 = mysqli_fetch_array($cs3)) {
                                    $minutos = $fila3['minTiempo'];
                                    $horas = $fila3['horaTiempo'];
                                    $fechTiempo = $fila3['fechTiempo'];
                                    $notaTiempo = $fila3['notaTiempo'];
                                    $proyecto = $fila['codProy'].' '.$fila['nombreProy'];
                                    $soli = 'P'.$fila1['idSol'];
                                    $estado = $fila1['nombreEstSol'];
                                    $obs = $fila1['ObservacionAct'];
                                    $pers = $fila2['apellido1'].' '.$fila2['apellido2'].' '.$fila2['nombres'];
                                    $tiempo=($minutos / 60) + $horas;
                                    if($diasLab != null){                            
                                        $horasMes = $diasLab*8;
                                        $porcen = $tiempo/$horasMes;
                                    } else {
                                        $porcen = intval(0);
                                    }
                                    $infoPer = [$proyecto, $soli, $estado, $obs,$pers,$fechTiempo,$horas,$minutos,$notaTiempo,$porcen];
                                    $spreadsheet->getActiveSheet()->fromArray($infoPer,null,'A'.$filaEX);
                                    $filaEX +=1;
                                    $sumH+=$horas;
                                    $sumM+=$minutos;
                                }   
                            }     
                        }
                    }
                }
            }
            $spreadsheet->getActiveSheet()->getStyle('J8:J'.$filaEX)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
            $spreadsheet->getActiveSheet()->getStyle('F8:G'.$filaEX)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('A6:J'.($filaEX-1))->getBorders()->applyFromArray(STYLEBOR);
            $spreadsheet->getActiveSheet()->getStyle('A8:J'.($filaEX-1))->applyFromArray(STYLEB);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Informe de notas tiempos '.gmdate(' d M Y ').'.xlsx"');
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

        // Busca solicitudes de un proyecto
        public static function selectProyectoSol ($busqueda) {
            require ('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.idFrente, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_actualizacionproy.descripcionProy
            FROM pys_actualizacionproy
            WHERE pys_actualizacionproy.est = '1' AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
            $resultado = mysqli_query($connection, $consulta);
            if ($registros = mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                $select = '     <select name="stlProyecto" id="stlProyecto" onchange="cargaSelectTipProduc(\'#stlProyecto\',\''.$busqueda.'\',\'../Controllers/ctrl_infNotasTiempos.php\',\'#stlSolicitud\')" required>
                                    <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                $select.= '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
            }
            $select .=  '  </select>
                    <label for="stlProyecto">Seleccione un proyecto</label>';
            } else {
                $select =  '  <select name="stlProyecto" id="stlProyecto" required>
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        </select>
                        <label for="stlProyecto">Seleccione un proyecto</label>';
            }
            echo $select;
            mysqli_close($connection);
                
        }
        
        public static function selectSolicitudes ($proyecto) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct
            FROM pys_actualizacionproy
            INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
            WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$proyecto'
            ORDER BY pys_actsolicitudes.idSol;";
            $resultado = mysqli_query($connection, $consulta); 
            $string = '<select name="stlSolicitud" id="stlSolicitud" class="asignacion"> <option value="0" selected disabled>Seleccione</option>';
            if (mysqli_num_rows($resultado) > 0) {
                $string .= ' ';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '  <option value="'.$datos[0].'">P'.$datos[0].' - '.$datos[1].'</option>';
                }
                $string .= '  </select>
                        <label for="stlSolicitud">Producto/Servicio</label>';
            }
            echo $string;
            mysqli_close($connection);
        }
    }
?>