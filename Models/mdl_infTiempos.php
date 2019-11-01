<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
require '../php_libraries/vendor/autoload.php';
const STYLETABLETITLE = [
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
const STYLETABLETABLE = ['font' => [
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
const STYLETABLETABLEBORDER= ['allBorders'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
    'color' => ['rgb' => '000000' ]
    ]
];
const STYLEBODY= [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
        'textRotation' => 0, 
        'wrapText' => TRUE  
    ]
];
const STYLEBODYBORDER= ['allBorders'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    'color' => ['rgb' => '000000' ]
    ],
];
const STYLEBODYBORDERBOTTOM= ['bottom'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
    'color' => ['rgb' => '000000' ]
    ]
];
const STYLEBODYBORDERLEFT= ['left'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
    'color' => ['rgb' => '000000' ]
    ]
];
const STYLEBODYBORDERRIGHT= ['right'=>[
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
    'color' => ['rgb' => '000000' ]
    ]
];
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
                return $resultado;
            } else 
                return "<h5 class='red-text'>No se registra tiempos</h5>";

        }
        public static function busqueda ($fechaInicial, $fechaFinal) {
            require('../Core/connection.php');
            $resultado = InformeTiemposProductoServicio::consulta($fechaInicial, $fechaFinal);

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
            while($datos = mysqli_fetch_array($resultado)){
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
            require('../Core/connection.php');
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('Conecta-TE')
                ->setLastModifiedBy('Conecta-TE')
                ->setTitle('Informe de Tiempos - Productos/Servicios')
                ->setSubject('Informe de Tiempos - Productos/Servicios')
                ->setDescription('Informe de Tiempos - Productos/Servicios')
                ->setKeywords('Informe de Tiempos - Productos/Servicios')
                ->setCategory('Test result file');
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-tiempos-productos-servicios');
            $spreadsheet->addSheet($myWorkSheet, 0);
            $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
            $spreadsheet->removeSheetByIndex($sheetIndex);
            $spreadsheet->getActiveSheet()->setShowGridlines(false); 
            /**Dimensión columnas */
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
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
            $sheet->setCellValue('A1', 'Informe de Tiempos - Productos/Servicios');
            $sheet->mergeCells("A1:N1"); 
            /**Titulos tabla */
            $titulos = ['Frente', 'Cod. Proyecto', 'Nombre Proyecto', 'Célula', 'Cod. Producto/Servicio', 'Estado Producto/Servicio', 'Equipo', 'Servicio', 'Descripción Producto/servicio', 'Fecha Creación', 'Fecha Estimada entrega', 'Tiempo Invertido en el Periodo', 'Tiempo Total a Dedicar P/S', 'Tiempo Total Invertido P/S'];
            $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A5');
            /**Aplicación de estilos */
            $spreadsheet->getActiveSheet()->getStyle('A5:N5')->applyFromArray(STYLETABLETITLE)->getBorders()->applyFromArray(STYLETABLETABLEBORDER);
            $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray(STYLETABLETABLE);
            $resultado = InformeTiemposProductoServicio::consulta($fechaInicial, $fechaFinal);
            $datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            $fila = 5;        
            foreach ($datos as $item){
                $fila += 1;
                $equipo = $item['nombreEqu'];
                $servicio = $item['nombreSer'];
                $nombreEstado = $item['nombreEstSol'];
                $idCelula = $item['idCelula'];
                $idSol = $item['idSol'];
                $codProy = $item['codProy'];
                $frente = substr($codProy, 0, 2);
                $nombreProy = $item['nombreProy'];
                $observacion = $item['ObservacionAct'];
                $fecha = date_create($item['fechSol']);
                $fechaCreacion = date_format($fecha, 'Y-m-d');
                $fechaEsperada = $item['fechPrev'];
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
                $tiempoInPer = number_format(($totalInvertido / 60),2);
                $tiempoTotalDed = number_format(($totalDedicar / 60),2);
                $tiempoTotalInve = number_format(($totalPS / 60),2);
                $datos = [$frente, $codProy, $nombreProy, $nombreCelula, $codProd, $nombreEstado, $equipo, $servicio, $observacion, $fechaCreacion, $fechaEsperada, $tiempoInPer, $tiempoTotalDed, $tiempoTotalInve];
                $spreadsheet->getActiveSheet()->fromArray($datos,null,'A'.$fila);
                $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':N'.$fila)->applyFromArray(STYLEBODY)->getBorders()->applyFromArray(STYLEBODYBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->getBorders()->applyFromArray(STYLEBODYBORDERLEFT);
                $spreadsheet->getActiveSheet()->getStyle('N'.$fila)->getBorders()->applyFromArray(STYLEBODYBORDERRIGHT);
            }
            $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':N'.$fila)->getBorders()->applyFromArray(STYLEBODYBORDERBOTTOM);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="InformeTiempos-ProductosServicios '.gmdate(' d M Y ').'.xlsx"');
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
?>