<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    Class InformeTiemposProductoServicio {
        
        public static function consulta($fechaInicial, $fechaFinal){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_asignados.idSol, pys_solicitudes.fechSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.ObservacionAct, pys_actualizacionproy.idCelula, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_equipos.nombreEqu
                FROM pys_tiempos 
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy 
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
                WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_tiempos.estTiempo = '1' 
                AND pys_actualizacionproy.est = '1' AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' 
                AND pys_solicitudes.idTSol = 'TSOL02' AND pys_servicios.est = '1' AND pys_equipos.est = '1'
                AND (pys_tiempos.fechTiempo >= '$fechaInicial' AND pys_tiempos.fechTiempo <= '$fechaFinal') 
                GROUP BY pys_asignados.idSol 
                ORDER BY pys_actualizacionproy.codProy, pys_asignados.idSol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                return $datos = mysqli_fetch_array($resultado);
            }
        }
        public static function busqueda ($fechaInicial, $fechaFinal) {
            $datos = InformeTiemposProductoServicio::consulta($fechaInicial, $fechaFinal);
            $tabla = '  <table class="responsive-table" border="1">
                        <thead>
                            <tr class="row-teal3">
                                <th>Frente</th>
                                <th>Cod. Proyecto</th>
                                <th>Nombre Proyecto</th>
                                <th>Célula</th>
                                <th>Cod. Producto/Servicio</th>
                                <th>Estado Producto/Servicio</th>
                                <th>Equipo</th>
                                <th>Servicio</th>
                                <th>Descripción Producto/servicio</th>
                                <th>Fecha Creación</th>
                                <th>Fecha Estimada entrega</th>
                                <th>Tiempo Invertido en el Periodo</th>
                                <th>Tiempo Total a Dedicar P/S</th>
                                <th>Tiempo Total Invertido P/S</th>
                            </tr>
                        </thead>
                        <tbody>';
            while($datos){
                $equipo = $datos['nombreEqu'];
                $servicio = $datos['nombreSer'];
                $nombreEstado = $datos['nombreEstSol'];
                $idCelula = $datos['idCelula'];
                $idSol = $datos['idSol'];
                $codProy = $datos['codProy'];
                $frente = substr($codProy, 0, 2);
                $nombreProy = $datos['nombreProy'];
                $observacion = $datos['ObservacionAct'];
                $fecha = date_create($datos['fechSol']);
                $fechaCreacion = date_format($fecha, 'Y-m-d');
                $fechaEsperada = $datos['fechPrev'];
                $consulta2 = "SELECT SUM(pys_asignados.maxhora), SUM(pys_asignados.maxmin)
                    FROM pys_asignados
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $consulta3 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                    FROM pys_asignados
                    RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1'
                    AND (pys_tiempos.fechTiempo >= '$fechaInicial' AND pys_tiempos.fechTiempo <= '$fechaFinal');";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);

                $consulta4 = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula';";
                $resultado4 = mysqli_query($connection, $consulta4);
                $datos4 = mysqli_fetch_array($resultado4);
                $nombreCelula = $datos4['nombreCelula'];

                $consulta5 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                    FROM pys_asignados
                    RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1';";
                $resultado5 = mysqli_query($connection, $consulta5);
                $datos5 = mysqli_fetch_array($resultado5);

                $horasDedicar = $datos2[0];
                $minutosDedicar = $datos2[1];
                $totalDedicar = ($horasDedicar * 60) + $minutosDedicar;


                $horasInvertidas = $datos3[0];
                $minutosInvertidos = $datos3[1];
                $totalInvertido = ($horasInvertidas * 60) + $minutosInvertidos;

                $horasTotalesPS = $datos5[0];
                $minutosTotalesPS = $datos5[1];
                $totalPS = ($horasTotalesPS * 60) + $minutosTotalesPS;
                $tabla .= ' <tr class="middle">
                                <td>'.$frente.'</td>
                                <td>'.$codProy.'</td>
                                <td>'.$nombreProy.'</td>
                                <td>'.$nombreCelula.'</td>
                                <td>P'.$idSol.'</td>
                                <td>'.$nombreEstado.'</td>
                                <td>'.$equipo.'</td>
                                <td>'.$servicio.'</td>
                                <td>'.$observacion.'</td>
                                <td class="center">'.$fechaCreacion.'</td>
                                <td class="center">'.$fechaEsperada.'</td>
                                <td class="center">'.number_format(($totalInvertido / 60), 2, ",", ".").'</td>
                                <td class="center">'.number_format(($totalDedicar / 60), 2, ",", ".").'</td>
                                <td class="center">'.number_format(($totalPS / 60), 2, ",", ".").'</td>
                            </tr>';
            }
            $tabla .= '      </tbody>
                    </table>';
            return $tabla;
            mysqli_close($connection);
        }

        public static function descarga ($fechaInicial, $fechaFinal) {
            require '../php_libraries/vendor/autoload.php';
            $spreadsheet = new Spreadsheet();
            $styleArrayTableTitle = [
                        'font' => [
                            'bold' => true
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
            $styleArrayTable = ['font' => [
                        'bold' => true,
                        'size' => '24'
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ];
            $styleArrayBorderTitle= ['allBorders'=>[
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '000000' ]
                        ]
                    ];
            /**Dimensión columnas */
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(14);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(11);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->mergeCells("A1:N1"); 
            /**Titulos tabla */
            $sheet->setCellValue('A1', 'Informe de Tiempos - Productos/Servicios');
            $sheet->setCellValue('A5', 'Frente');
            $sheet->setCellValue('B5', 'Cod. Proyecto');
            $sheet->setCellValue('C5', 'Nombre Proyecto');
            $sheet->setCellValue('D5', 'Célula');
            $sheet->setCellValue('E5', 'Cod. Producto/Servicio');
            $sheet->setCellValue('F5', 'Estado Producto/Servicio');
            $sheet->setCellValue('G5', 'Equipo');
            $sheet->setCellValue('H5', 'Servicio');
            $sheet->setCellValue('I5', 'Descripción Producto/servicio');
            $sheet->setCellValue('J5', 'Fecha Creación');
            $sheet->setCellValue('K5', 'Fecha Estimada entrega');
            $sheet->setCellValue('L5', 'Tiempo Invertido en el Periodo');
            $sheet->setCellValue('M5', 'Tiempo Total a Dedicar P/S');
            $sheet->setCellValue('N5', 'Tiempo Total Invertido P/S');
            /**Aplicación de estilos */
            $spreadsheet->getActiveSheet()->getStyle('A5:N5')->applyFromArray($styleArrayTableTitle)->getBorders()->applyFromArray($styleArrayBorderTitle);
            $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArrayTable);
            /**Ingreso de datos tabla */

            $datos = InformeTiemposProductoServicio::consulta($fechaInicial, $fechaFinal);
            while($datos){
                $equipo = $datos['nombreEqu'];
                $servicio = $datos['nombreSer'];
                $nombreEstado = $datos['nombreEstSol'];
                $idCelula = $datos['idCelula'];
                $idSol = $datos['idSol'];
                $codProy = $datos['codProy'];
                $frente = substr($codProy, 0, 2);
                $nombreProy = $datos['nombreProy'];
                $observacion = $datos['ObservacionAct'];
                $fecha = date_create($datos['fechSol']);
                $fechaCreacion = date_format($fecha, 'Y-m-d');
                $fechaEsperada = $datos['fechPrev'];
                $consulta2 = "SELECT SUM(pys_asignados.maxhora), SUM(pys_asignados.maxmin)
                    FROM pys_asignados
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $consulta3 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                    FROM pys_asignados
                    RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1'
                    AND (pys_tiempos.fechTiempo >= '$fechaInicial' AND pys_tiempos.fechTiempo <= '$fechaFinal');";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);

                $consulta4 = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula';";
                $resultado4 = mysqli_query($connection, $consulta4);
                $datos4 = mysqli_fetch_array($resultado4);
                $nombreCelula = $datos4['nombreCelula'];

                $consulta5 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                    FROM pys_asignados
                    RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                    WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1';";
                $resultado5 = mysqli_query($connection, $consulta5);
                $datos5 = mysqli_fetch_array($resultado5);

                $horasDedicar = $datos2[0];
                $minutosDedicar = $datos2[1];
                $totalDedicar = ($horasDedicar * 60) + $minutosDedicar;


                $horasInvertidas = $datos3[0];
                $minutosInvertidos = $datos3[1];
                $totalInvertido = ($horasInvertidas * 60) + $minutosInvertidos;

                $horasTotalesPS = $datos5[0];
                $minutosTotalesPS = $datos5[1];
                $totalPS = ($horasTotalesPS * 60) + $minutosTotalesPS;
                $codProd = 'P'.$idSol;
                $tiempoInPer = number_format(($totalInvertido / 60), 2, ",", ".");
                $tiempoTotalDed = number_format(($totalDedicar / 60), 2, ",", ".");
                $tiempoTotalInve = number_format(($totalPS / 60), 2, ",", ".");
                $sheet->setCellValue('A4', 'P');
                $sheet->setCellValue('A6', $frente);
                $sheet->setCellValue('B6', $codProy);
                $sheet->setCellValue('C6', $nombreProy);
                $sheet->setCellValue('D6', $nombreCelula);
                $sheet->setCellValue('E6', $codProd);
                $sheet->setCellValue('F6', $nombreEstado);
                $sheet->setCellValue('G6', $equipo);
                $sheet->setCellValue('H6', $servicio);
                $sheet->setCellValue('I6', $observacion);
                $sheet->setCellValue('J6', $fechaCreacion);
                $sheet->setCellValue('K6', $fechaEsperada);
                $sheet->setCellValue('L6', $tiempoInPer);
                $sheet->setCellValue('M6', $tiempoTotalDed);
                $sheet->setCellValue('N6', $tiempoTotalInve);
            }
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('hello world.xlsx');

        }


    }
?>