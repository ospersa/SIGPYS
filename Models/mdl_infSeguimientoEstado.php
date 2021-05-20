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

const ALPHABET = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
const SIZES = [45, 13, 45, 22, 30, 45, 45, 40, 40, 30, 40, 35, 35, 35, 15, 15, 35, 35, 35, 35, 35, 35];

    Class InformeSeguimientoEstados {
      

        public static function descarga ($proyecto, $frente, $estado, $tiempos) {
            require('../Core/connection.php');
            if ($proyecto != "" && $frente == null) {
                $consulta = "SELECT codProy, nombreProy, idProy FROM pys_actualizacionproy WHERE est = '1' AND idProy = '$proyecto' ORDER BY codProy asc;";
            } else if ($proyecto == "" && $frente != null   ) {
                $consulta = "SELECT codProy, nombreProy, idProy FROM pys_actualizacionproy WHERE est = '1' AND idFrente = '$frente' ORDER BY codProy asc;";
            } else {
                $consulta = "SELECT codProy, nombreProy, idProy FROM pys_actualizacionproy WHERE est = '1' ORDER BY codProy asc;";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de seguimiento estados y metadata')
                    ->setSubject('Informe de seguimiento estados y metadata')
                    ->setDescription('Informe de seguimiento estados y metadata')
                    ->setKeywords('Informe de seguimiento estados y metadata')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Seguimiento Estados');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                /** Arreglo títulos */
                if ( $tiempos == null ) {
                    $titulos = ['Código/Nombre del proyecto', 'Código solicitud', 'Descripción de la solicitud', 'Fecha estimada de entrega', 'Estado', 'Responsable P&S', 'Tipo de recurso', 'Plataforma', 'Clase de producto', 'Tipo de producto', 'Nombre de producto', 'Descripción de producto', 'Link producto', 'URL Servidor', 'Duración Minutos', 'Duración Segundos', 'Sinopsis', 'Autor Externo', 'Idioma', 'Formato', 'Tipo Contenido'];
                } else {
                    $titulos = ['Código/Nombre del proyecto', 'Código solicitud', 'Descripción de la solicitud', 'Fecha estimada de entrega', 'Estado', 'Responsable P&S', 'Horas por persona', 'Tipo de recurso', 'Plataforma', 'Clase de producto', 'Tipo de producto', 'Nombre de producto', 'Descripción de producto', 'Link producto', 'URL Servidor', 'Duración Minutos', 'Duración Segundos', 'Sinopsis', 'Autor Externo', 'Idioma', 'Formato', 'Tipo Contenido'];
                }
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                /** Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /** Dimensión columnas */
                foreach ($titulos as $key => $titulo) {
                    $spreadsheet->getActiveSheet()->getColumnDimension(ALPHABET[$key])->setWidth(SIZES[$key]);                    
                }
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de seguimiento de estados y metadata');
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A4:J4");
                
                $spreadsheet->getActiveSheet()->getStyle('A6:'.ALPHABET[count($titulos) - 1].'6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ( $datos = mysqli_fetch_array($resultado) ) {
                    $codProy = $datos['codProy'];
                    $nombreProy = $datos['nombreProy'];
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$fila, $codProy);
                    $idProy = $datos['idProy'];
                    $consulta1 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSer, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol, pys_solicitudes.idSolIni, pys_actproductos.nombreProd, pys_actproductos.urlVimeo, pys_actproductos.descripcionProd, pys_actproductos.palabrasClave, pys_actproductos.fechEntregaProd, pys_actproductos.urlservidor, pys_actproductos.observacionesProd, pys_actproductos.duracionmin, pys_actproductos.duracionseg, pys_actproductos.sinopsis, pys_actproductos.autorExterno, idiomas.idiomaNombre, pys_tiposrecursos.nombreTRec, pys_plataformas.nombrePlt, pys_tiposproductos.descripcionTProd, formatos.formatoNombre, pys_claseproductos.nombreClProd, tiposcontenido.tipoContenidoNombre, pys_tiposproductos.descripcionTProd, idiomas.idiomaNombre
                        FROM pys_actualizacionproy
                        INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                        INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                        INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                        INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
                        LEFT JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol AND pys_productos.est = '1'
                        LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                        LEFT JOIN idiomas ON idiomas.idIdiomas = pys_actproductos.idioma
                        LEFT JOIN pys_tiposrecursos ON pys_tiposrecursos.idTRec = pys_actproductos.idTRec AND pys_tiposrecursos.est = '1'
                        LEFT JOIN pys_plataformas ON pys_plataformas.idPlat = pys_actproductos.idPlat AND pys_plataformas.est = '1'
                        LEFT JOIN pys_tiposproductos ON pys_tiposproductos.idTProd = pys_actproductos.idTProd AND pys_tiposproductos.est = '1'
                        LEFT JOIN formatos ON formatos.idFormatos = pys_actproductos.formato
                        LEFT JOIN pys_claseproductos ON pys_claseproductos.idClProd = pys_actproductos.idClProd AND pys_claseproductos.est = '1'
                        LEFT JOIN tiposcontenido ON tiposcontenido.idtiposContenido = pys_actproductos.tipoContenido
                        WHERE pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_actualizacionproy.est = '1' AND pys_actsolicitudes.idSolicitante = '' ";
                    if ($estado != null) {
                        $consulta1 .= " AND pys_actsolicitudes.idEstSol != 'ESS007'";
                    } else {
                        $consulta1 .= " AND (pys_actsolicitudes.idEstSol != 'ESS001' AND pys_actsolicitudes.idEstSol != 'ESS006' AND pys_actsolicitudes.idEstSol != 'ESS007')";
                    }
                    $consulta1 .= " AND pys_actualizacionproy.idProy = '$idProy' 
                        ORDER BY pys_actsolicitudes.idSol;";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    $registros1 = mysqli_num_rows($resultado1);
                    if ($registros1 > 0) {
                        $cont = 0;
                        $filaIni = $fila;
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $nomSer= $datos1['nombreSer'];
                            $fechPrev= $datos1['fechPrev'];
                            $idSol = $datos1['idSol'];
                            $ObservacionAct = $datos1['ObservacionAct'];
                            $idSolIni = $datos1['idSolIni'];
                            $fechSol = $datos1['fechSol']; 
                            $nombreEstSol = $datos1['nombreEstSol']; 
                            $nombreProd = $datos1['nombreProd'];                                   
                            $urlVimeo = $datos1['urlVimeo'];     
                            $idiomaNombre = $datos1['idiomaNombre'];
                            $nombreTRec = $datos1['nombreTRec'];
                            $nombrePlt = $datos1['nombrePlt'];
                            $descripcionTProd  = $datos1['descripcionTProd'];
                            $nombreClProd  = $datos1['nombreClProd'];
                            $nombreTProd = $datos1['descripcionTProd'];
                            $descripcionProd = $datos1['descripcionProd'];
                            $palabrasClave = $datos1['palabrasClave'];
                            $fechEntregaProd  = $datos1['fechEntregaProd'];
                            $urlservidor = $datos1['urlservidor'];
                            $observacionesProd = $datos1['observacionesProd'];
                            $duracionmin = $datos1['duracionmin'];
                            $duracionseg = $datos1['duracionseg'];
                            $sinopsis = $datos1['sinopsis'];
                            $autorExterno = $datos1['autorExterno'];
                            $tipoContenido = $datos1['tipoContenidoNombre'];
                            $formatoNombre = $datos1['formatoNombre'];
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                                FROM pys_asignados
                                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                                WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND  pys_asignados.idSol = '$idSol'
                                ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            if (mysqli_num_rows($resultado2) > 0) {
                                $personasAsig = $personasTiempo = '';
                                while ($datos2 = mysqli_fetch_array($resultado2) ) {
                                    $personasAsig .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'].', ';
                                    if ($tiempos != null) {
                                        $horas = $minutos = 0;
                                        $idAsignado = $datos2['idAsig'];
                                        $query = "SELECT horaTiempo, minTiempo FROM pys_tiempos WHERE idAsig = '$idAsignado' AND estTiempo = '1';";
                                        $result = mysqli_query($connection, $query);
                                        while ( $data = mysqli_fetch_array($result) ) {
                                            $horas += $data['horaTiempo'];
                                            $minutos += $data['minTiempo'];
                                        }
                                        $total = " (".( ( ($horas * 60) + $minutos ) / 60 ) . " H); \n";
                                        $personasTiempo .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'].$total;
                                    } 
                                }
                                $personasAsig = trim($personasAsig, ', ');
                                $personasTiempo = trim($personasTiempo, "; \n");
                            }
                            $consulta4 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                            FROM pys_solicitudes 
                            INNER JOIN pys_personas ON pys_solicitudes.idSolicitante = pys_personas.idPersona
                            WHERE  pys_solicitudes.idSol='$idSolIni'";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            if(mysqli_num_rows($resultado4)> 0){
                                while ($datos4 = mysqli_fetch_array($resultado4)) {                              
                                    $solicitante = $datos4['apellido1'].' '.$datos4['apellido2'].' '.$datos4['nombres'];
                                }
                            }
                            if ($tiempos != null) {
                                $data = [$codProy.' - '.$nombreProy, 'P'.$idSol, $ObservacionAct, $fechPrev, $nombreEstSol, $personasAsig, $personasTiempo, $nombreTProd, $nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd, $observacionesProd, $urlVimeo, $urlservidor, $duracionmin, $duracionseg, $sinopsis, $autorExterno, $idiomaNombre, $formatoNombre, $tipoContenido];
                            } else {
                                $data = [$codProy.' - '.$nombreProy, 'P'.$idSol, $ObservacionAct, $fechPrev, $nombreEstSol, $personasAsig, $nombreTProd, $nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd, $observacionesProd, $urlVimeo, $urlservidor, $duracionmin, $duracionseg, $sinopsis, $autorExterno, $idiomaNombre, $formatoNombre, $tipoContenido];
                            }
                            $spreadsheet->getActiveSheet()->fromArray($data, null, 'A'.$fila);
                            $fila +=1;
                        }
                    }
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:'.ALPHABET[count($titulos) - 1].($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:'.ALPHABET[count($titulos) - 1].($fila-1))->applyFromArray(STYLEBODY);
                if ($tiempos != null) {
                    /* Creación de hoja con información de tiempos registrados en el mes */
                    $myWorkSheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Total tiempos');
                    $spreadsheet->addSheet($myWorkSheet2, 1);
                    $spreadsheet->setActiveSheetIndex(1);
                    $spreadsheet->getActiveSheet()->setShowGridlines(false);
                    $spreadsheet->getActiveSheet()->fromArray(['Persona', 'Proyecto', 'Tiempo trabajado', '% Ejecutado'], null, 'A1');
                    $datos = self::tiemposMes();
                    $fila = $filasIni = 2;
                    if ( is_array ( $datos ) ) {
                        foreach ($datos as $value) {
                            if (!empty($value['Nombre'])) {
                                $spreadsheet->getActiveSheet()->fromArray([$value['Nombre'], $value['Proyecto'], $value['Tiempo'], $value['Porcentaje']], null, "A".$fila);
                                $fila++;
                            } else if (!empty($value["Total"])) {
                                $spreadsheet->getActiveSheet()->mergeCells("A".$filasIni.":A".($fila - 1));
                                $spreadsheet->getActiveSheet()->fromArray(["Total tiempo ejecutado:", '', $value['Total'], $value['Porcentaje']], null, "A".$fila);
                                $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray(STYLETABLETITLESUB);
                                $fila++;
                                $filasIni = $fila;
                            }
                        }
                    } else {
                        $spreadsheet->getActiveSheet()->setCellValue('A2', 'No hay periodo configurado aún, no se puede mostrar información de tiempos');
                        $spreadsheet->getActiveSheet()->mergeCells("A2:D2");
                        $fila++;
                    }
                    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
                    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(70);
                    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                    $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray(STYLETABLETITLE);
                    $spreadsheet->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                    $spreadsheet->getActiveSheet()->getStyle('A1:D'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                    $spreadsheet->getActiveSheet()->getStyle('A2:D'.($fila-1))->applyFromArray(STYLEBODY);
                    /* Creación de hoja con información de ejecuciones de proyectos */
                    $myWorkSheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ejecuciones Proyecto');
                    $spreadsheet->addSheet($myWorkSheet3, 2);
                    $spreadsheet->setActiveSheetIndex(2);
                    $spreadsheet->getActiveSheet()->setShowGridlines(false);
                    $spreadsheet->getActiveSheet()->mergeCells("C1:D1");
                    $spreadsheet->getActiveSheet()->mergeCells("E1:F1");
                    $spreadsheet->getActiveSheet()->mergeCells("G1:H1");
                    $spreadsheet->getActiveSheet()->setCellValue('A2', 'Proyecto');
                    $spreadsheet->getActiveSheet()->setCellValue('B2', 'Asignado');
                    $spreadsheet->getActiveSheet()->setCellValue('C1', 'Corte Anterior');
                    $spreadsheet->getActiveSheet()->setCellValue('E1', 'Corte Actual');
                    $spreadsheet->getActiveSheet()->setCellValue('G1', 'Total');
                    $spreadsheet->getActiveSheet()->setCellValue('C2', 'Tiempo');
                    $spreadsheet->getActiveSheet()->setCellValue('D2', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('E2', 'Tiempo');
                    $spreadsheet->getActiveSheet()->setCellValue('F2', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('G2', 'Tiempo');
                    $spreadsheet->getActiveSheet()->setCellValue('H2', 'Ejecución');
                    $datos2 = self::ejecuciones($proyecto, $frente);
                    $fila = $filasIni = 3;
                    if ( is_array ( $datos2 ) ) {
                        foreach ($datos2 as $value) {
                            if ( ! empty ( $value['Proyecto'] ) ) {
                                $tiempoCorteAnterior = ( empty ( $value['Tiempo Corte Anterior'] ) ) ? '0' : $value['Tiempo Corte Anterior'];
                                $ejecutadoCorteAnterior = ( empty ( $value['Ejecutado Corte Anterior'] ) ) ? '0' : $value['Ejecutado Corte Anterior'];
                                $tiempoCorteActual = ( empty ( $value['Tiempo Corte Actual'] ) ) ? '0' : $value['Tiempo Corte Actual'];
                                $ejecutadoCorteActual = ( empty ( $value['Ejecutado Corte Actual'] ) ) ? '0' : $value['Ejecutado Corte Actual'];
                                $spreadsheet->getActiveSheet()->fromArray([$value['Proyecto'], $value['Asignado'], $tiempoCorteAnterior, $ejecutadoCorteAnterior, $tiempoCorteActual, $ejecutadoCorteActual], null, "A".$fila);
                                $spreadsheet->getActiveSheet()->setCellValue('G'.$fila, '=C'.$fila.'+E'.$fila);
                                $spreadsheet->getActiveSheet()->setCellValue('H'.$fila, '=D'.$fila.'+F'.$fila);
                                $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('E'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('F'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $fila++;
                            } else if ( ! empty ( $value["Total Tiempo Anterior"] ) ) {
                                $spreadsheet->getActiveSheet()->mergeCells("A".$filasIni.":A".($fila - 1));
                                $spreadsheet->getActiveSheet()->fromArray(["Totales:", '', $value['Total Tiempo Anterior'], $value['Total Ejecutado Anterior'], $value['Total Tiempo Actual'], $value['Total Ejecutado Actual']], null, "A".$fila);
                                $spreadsheet->getActiveSheet()->setCellValue('G'.$fila, '=C'.$fila.'+E'.$fila);
                                $spreadsheet->getActiveSheet()->setCellValue('H'.$fila, '=D'.$fila.'+F'.$fila);
                                $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('E'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('F'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':H'.$fila)->applyFromArray(STYLETABLETITLESUB);
                                $fila++;
                                $filasIni = $fila;
                            }
                            $spreadsheet->getActiveSheet()->getStyle('A1:H2')->applyFromArray(STYLETABLETITLE);
                            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(60);
                            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
                            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getStyle('A1:H'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                            $spreadsheet->getActiveSheet()->getStyle('A2:H'.($fila-1))->applyFromArray(STYLEBODY);
                        }
                    } else {
                        $spreadsheet->getActiveSheet()->setCellValue('A3', 'No hay periodo configurado aún, no se puede mostrar información de tiempos');
                        $spreadsheet->getActiveSheet()->mergeCells("A3:H3");
                        $fila++;
                    }
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  
                header('Content-Disposition: attachment;filename="Informe seguimiento estados y metadata'.gmdate(' d M Y ').'.xlsx"');
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
                $string .= '    <select name="sltFrenteInf" id="sltFrenteInf">
                                    <option value="" selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '    <option value="'.$datos['idFrente'].'">'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                }
                $string .= '    </select>
                                <label for="sltFrente">Frente</label>';
            }
            mysqli_close($connection);
            return $string;
        }

        public static function tiemposMes() {
            require('../Core/connection.php');
            $today = date('Y-m-d');
            $query0 = "SELECT idPeriodo, inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2, persona_IdPersona, porcentajeDedicacion1, porcentajeDedicacion2
                FROM pys_periodos 
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo AND pys_dedicaciones.estadoDedicacion = '1'
                WHERE inicioPeriodo <= '$today' AND finPeriodo >= '$today' AND estadoPeriodo = '1';";
            $result0 = mysqli_query($connection, $query0);
            $registry0 = mysqli_num_rows($result0);
            if ($registry0 > 0) {
                while ($data0 = mysqli_fetch_array($result0)) {
                    $idPeriodo = $data0['idPeriodo'];
                    $totalDias = $data0['diasSegmento1'] + $data0['diasSegmento2'];
                    $inicioPeriodo = $data0['inicioPeriodo'];
                    $finPeriodo = $data0['finPeriodo'];
                    $asignaciones[$data0['persona_IdPersona']] =  ((($data0['diasSegmento1'] * 8) * $data0['porcentajeDedicacion1']) / 100) + ((($data0['diasSegmento2'] * 8) * $data0['porcentajeDedicacion2']) / 100);
                }
                $query1 = "SELECT pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, SUM(pys_tiempos.horaTiempo) AS 'Horas', SUM(pys_tiempos.minTiempo) AS 'Minutos', pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                    FROM pys_tiempos
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy AND pys_actualizacionproy.est = '1'
                    WHERE (pys_tiempos.fechTiempo >= '$inicioPeriodo' AND pys_tiempos.fechTiempo <= '$finPeriodo')
                    AND pys_tiempos.estTiempo = '1'
                    GROUP BY pys_personas.apellido1, pys_actualizacionproy.idProy
                    ORDER BY pys_personas.apellido1 ASC;";
                $result1 = mysqli_query($connection, $query1);
                $idPersonaOld = $idProyectoOld = $asignacion = '0';
                $sumaHoras = $sumaMinutos = $total = 0;
                $registros = mysqli_num_rows($result1);
                $recorrido = 1;
                while ($data1 = mysqli_fetch_array($result1)) {
                    $idPersona = $data1['idPersona'];
                    $idPersonaOld = ($recorrido == 1) ? $idPersona : $idPersonaOld;
                    $nombreCompleto = $data1['apellido1'] . " " . $data1['apellido2'] . " " . $data1['nombres'];
                    $proyecto = $data1['codProy'] . " - " . $data1['nombreProy'];
                    $tiempo = (($data1['Horas'] * 60) + $data1['Minutos']) / 60;
                    if (($idPersona != $idPersonaOld && $recorrido != 1)) {
                        $json[] = array(
                            "Total" => number_format($total, 2),
                            "Asignacion" => $asignacion,
                            "Porcentaje" => number_format(($total / $asignacion), 2)
                        );
                        $idPersonaOld = $idPersona;
                        $total = $asignacion = 0;
                    }
                    $total += $tiempo;
                    $asignacion = $asignaciones[$idPersona];
                    $json[] = array(
                        "Nombre" => $nombreCompleto,
                        "Proyecto" => $proyecto,
                        "Tiempo" => number_format($tiempo, 2),
                        "Asignación" => $asignaciones[$idPersona],
                        "Porcentaje" => number_format(($tiempo / $asignaciones[$idPersona]), 2)
                    );
                    if ($recorrido == $registros) {
                        $json[] = array(
                            "Total" => number_format($total, 2),
                            "Asignacion" => $asignacion,
                            "Porcentaje" => number_format(($total / $asignacion), 2)
                        );
                    }
                    $recorrido++;
                }
            } else {
                $json = "";
            }
            mysqli_close($connection);
            return $json;
        }

        public static function ejecuciones($proyecto, $frente) {
            require('../Core/connection.php');
            $today = date('Y-m-d');
            $query = "SELECT inicioPeriodo, finPeriodo
                FROM pys_periodos WHERE (inicioPeriodo <= '$today' AND finPeriodo >= '$today') AND estadoPeriodo = '1';";
            $result = mysqli_query($connection, $query);
            $registry = mysqli_num_rows($result);
            if ($registry > 0) {
                $data = mysqli_fetch_array($result);
                $inicioPeriodo = $data['inicioPeriodo'];
                $finPeriodo = $data['finPeriodo'];
                $query1 = "SELECT pys_asignados.idPersona, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy
                    FROM pys_asignados 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND (pys_actsolicitudes.est = '1' AND pys_actsolicitudes.registraTiempo = '1')
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy AND pys_actualizacionproy.est = '1'
                    WHERE pys_asignados.est != '0' ";
                if ($proyecto != null) {
                    $where = " AND pys_asignados.idProy = '$proyecto' ";
                    $groupby = 'GROUP BY pys_asignados.idPersona;';
                } else if ($frente != null) {
                    $where = " AND pys_actualizacionproy.idFrente = '$frente' ";
                    $groupby = 'GROUP BY pys_asignados.idProy, pys_asignados.idPersona;';
                }
                $query1 = $query1 . $where . $groupby;
                /* echo $query1;
                echo"<hr>"; */
                $result1 = mysqli_query($connection, $query1);
                $registry1 = mysqli_num_rows($result1);
                $recorrido = 1;
                $consolidadoTiempoActual = $consolidadoEjecutadoActual = $consolidadoTiempoAnterior = $consolidadoEjecutadoAnterior = 0;
                if ($registry1 > 0) {
                    while ($datos1 = mysqli_fetch_array($result1)) {
                        $idPersona = $datos1['idPersona'];
                        $nombreProyecto = $datos1['codProy'] . " - " . $datos1['nombreProy'];
                        $proyecto = ($frente != null) ? $datos1['idProy'] : $proyecto;
                        $query2 = "SELECT pys_asignados.idAsig, pys_salarios.salario, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                            FROM pys_asignados 
                            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.registraTiempo = '1'
                            INNER JOIN pys_salarios ON pys_salarios.mes <= pys_asignados.fechAsig AND pys_salarios.anio >= pys_asignados.fechAsig AND pys_salarios.idPersona = pys_asignados.idPersona
                            WHERE pys_asignados.idProy = '$proyecto' AND pys_asignados.est != '0' AND pys_asignados.idPersona = '$idPersona' AND pys_asignados.idSol != '';";
                        $result2 = mysqli_query($connection, $query2);
                        $registros2 = mysqli_num_rows($result2);
                        $totalActual = $totalAnterior = $tiempoActual = $tiempoAnterior = 0;
                        if ($registros2 > 0) {
                            while ($datos2 = mysqli_fetch_array($result2)) {
                                $idAsignado = $datos2['idAsig'];
                                $salario = $datos2['salario'];
                                $nombre = $datos2['apellido1'] . " " . $datos2['apellido2'] . " " . $datos2['nombres'];
                                /* Información de tiempos en el corte actual */
                                $query3 = "SELECT SUM(horaTiempo) AS 'Horas', SUM(minTiempo) AS 'Minutos'
                                    FROM pys_tiempos WHERE pys_tiempos.idAsig = '$idAsignado' AND pys_tiempos.fechTiempo >= '$inicioPeriodo' AND pys_tiempos.fechTiempo <= '$finPeriodo' AND pys_tiempos.estTiempo = '1';";
                                $result3 = mysqli_query($connection, $query3);
                                $registros3 = mysqli_num_rows($result3);
                                if ($registros3 > 0) {
                                    $data3 = mysqli_fetch_array($result3);
                                    $tiempoActual += $tiempo = ((($data3['Horas'] * 60) + $data3['Minutos']) / 60);
                                    $totalActual +=  $tiempo * $salario;
                                    $consolidadoTiempoActual += $tiempoActual;
                                    $consolidadoEjecutadoActual += $totalActual;
                                }
                                /* Información de tiempos en el corte anterior */
                                $query4 = "SELECT SUM(horaTiempo) AS 'Horas', SUM(minTiempo) AS 'Minutos'
                                    FROM pys_tiempos WHERE pys_tiempos.idAsig = '$idAsignado' AND pys_tiempos.fechTiempo < '$inicioPeriodo'  AND pys_tiempos.estTiempo = '1';";
                                $result4 = mysqli_query($connection, $query4);
                                $registros4 = mysqli_num_rows($result4);
                                if ($registros4 > 0) {
                                    $data4 = mysqli_fetch_array($result4);
                                    $tiempoAnterior += $tiempo = ((($data4['Horas'] * 60) + $data4['Minutos']) / 60);
                                    $totalAnterior +=  $tiempo * $salario;
                                    $consolidadoTiempoAnterior += $tiempo;
                                    $consolidadoEjecutadoAnterior += $totalAnterior;
                                }
                            }
                            $json[] = array (
                                'Proyecto' => $nombreProyecto,
                                'Asignado' => $nombre,
                                'Tiempo Corte Anterior' => $tiempoAnterior,
                                'Ejecutado Corte Anterior' => $totalAnterior,
                                'Tiempo Corte Actual' => $tiempoActual,
                                'Ejecutado Corte Actual' => $totalActual
                            );
                        }
                        if ($recorrido == $registry1) {
                            $json[] = array(
                                "Total Tiempo Anterior" => $consolidadoTiempoAnterior,
                                "Total Ejecutado Anterior" => $consolidadoEjecutadoAnterior,
                                "Total Tiempo Actual" => $consolidadoTiempoActual,
                                "Total Ejecutado Actual" => $consolidadoEjecutadoActual,
                            );
                        }
                        /* echo "<h3>Registry: $registry1 Recorrido: $recorrido</h3>"; */
                        $recorrido++;
                    }
                    /* $html = "<table border='1'>";
                    foreach($json as $row) {
                        $html .= "<tr>";
                        foreach ($row as $cell) {
                            $html .= "<td>" . $cell . "</td>";
                        }
                        $html .= "</tr>";
                    }
                    $html .= "</table>";

                    echo $html; */
                    /* echo "<pre>";
                    var_dump($json);
                    echo "</pre>"; */
                }
            } else {
                $json = '';
            }
            mysqli_close($connection);
            return $json;
        }

    }
?>