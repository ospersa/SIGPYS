<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require '../php_libraries/vendor/autoload.php';
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
const STYLESALMON = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'ff7f50',
        ]
    ]
];
const STYLEBLUE = [
    'font' => [
        'color' => ['argb' => 'FFFFFF']
    ],
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => '0000cd',
        ]
    ]
];

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

const STYLETABLETITLEORANGE = [
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
        'argb' => 'ff8c00',
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

const ALPHABET =    ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
const SIZES =       [45 , 13 , 45 , 22 , 30 , 45 , 45 , 40 , 40 , 30 , 40 , 35 , 35 , 35 , 15 , 15 , 35 , 35 , 35 , 35 , 35 , 35 , 35 , 35];

    Class InformeSeguimientoEstados {
      

        public static function descarga ($proyecto, $frente, $gestor, $estado, $tiempos) {
            require('../Core/connection.php');
            if ($proyecto != "" && $frente == null && $gestor == null) {
                $consulta = "SELECT codProy, nombreProy, idProy FROM pys_actualizacionproy WHERE est = '1' AND idProy = '$proyecto' ORDER BY codProy asc;";
            } else if ($proyecto == "" && $frente != null && $gestor == null   ) {
                $consulta = "SELECT codProy, nombreProy, idProy FROM pys_actualizacionproy WHERE est = '1' AND idFrente = '$frente' ORDER BY codProy asc;";
            } else if ($proyecto == "" && $frente == null && $gestor != null   ) {
                $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy 
                    FROM pys_asignados
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy AND pys_actualizacionproy.est = '1'
                    WHERE (pys_asignados.idRol = 'ROL024' OR pys_asignados.idRol = 'ROL025') AND pys_asignados.idSol = '' AND pys_asignados.est = '1' AND pys_asignados.idPersona = '$gestor' 
                    ORDER BY pys_actualizacionproy.codProy asc;";
            }else {
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
                    $titulos = ['Código/Nombre del proyecto', 'Código Producto', 'Descripción / Nombre Inicial', 'Fecha estimada de entrega', 'Estado', 'Responsable P&S', 'Tipo de recurso', 'Plataforma', 'Clase de producto', 'Tipo de producto', 'Nombre de producto', 'Descripción de producto', 'Link producto', 'URL Servidor', 'Duración Minutos', 'Duración Segundos', 'Sinopsis', 'Palabras Clave', 'Autor Externo', 'Idioma', 'Formato', 'Tipo Contenido', 'Área de Conocimiento'];
                } else {
                    $titulos = ['Código/Nombre del proyecto', 'Código Producto', 'Descripción / Nombre Inicial', 'Fecha estimada de entrega', 'Estado', 'Responsable P&S', 'Horas por persona', 'Tipo de recurso', 'Plataforma', 'Clase de producto', 'Tipo de producto', 'Nombre de producto', 'Descripción de producto', 'Link producto', 'URL Servidor', 'Duración Minutos', 'Duración Segundos', 'Sinopsis', 'Palabras Clave', 'Autor Externo', 'Idioma', 'Formato', 'Tipo Contenido', 'Área de Conocimiento'];
                }
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                /** Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray(STYLETABLETI);
                /** Dimensión columnas */
                foreach ($titulos as $key => $titulo) {
                    $spreadsheet->getActiveSheet()->getColumnDimension(ALPHABET[$key])->setWidth(SIZES[$key]);
                }
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de seguimiento de estados y metadata');
                $sheet->mergeCells("A1:E1");
                $spreadsheet->getActiveSheet()->getStyle('A6:G6')->applyFromArray(STYLETABLETITLE);
                $spreadsheet->getActiveSheet()->getStyle('H6:X6')->applyFromArray(STYLETABLETITLEORANGE);
                $fila = 7;
                $presupuestoTotal = '0';
                while ( $datos = mysqli_fetch_array($resultado) ) {
                    $codProy = $datos['codProy'];
                    $nombreProy = $datos['nombreProy'];
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$fila, $codProy);
                    $idProy = $datos['idProy'];
                    $consulta1 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSer, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_actsolicitudes.fechPrev, pys_actsolicitudes.presupuesto, pys_solicitudes.fechSol, pys_solicitudes.idSolIni, pys_actproductos.nombreProd, pys_actproductos.urlVimeo, pys_actproductos.descripcionProd, pys_actproductos.palabrasClave, pys_actproductos.fechEntregaProd, pys_actproductos.urlservidor, pys_actproductos.observacionesProd, pys_actproductos.duracionmin, pys_actproductos.duracionseg, pys_actproductos.sinopsis, pys_actproductos.autorExterno, idiomas.idiomaNombre, pys_tiposrecursos.nombreTRec, pys_plataformas.nombrePlt, pys_tiposproductos.descripcionTProd, formatos.formatoNombre, pys_claseproductos.nombreClProd, tiposcontenido.tipoContenidoNombre, pys_tiposproductos.descripcionTProd, idiomas.idiomaNombre, pys_areaconocimiento.areaNombre
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
                        LEFT JOIN areaconocimientohasproyectos ON areaconocimientohasproyectos.pys_proyectos_idProy = pys_actualizacionproy.idProy
                        LEFT JOIN pys_areaconocimiento ON pys_areaconocimiento.idAreaConocimiento = areaconocimientohasproyectos.pys_areaconocimiento_idAreaConocimiento
                        WHERE pys_actsolicitudes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_actualizacionproy.est = '1' AND pys_actsolicitudes.idSolicitante = '' ";
                    if ($estado != null) {
                        $consulta1 .= " AND pys_actsolicitudes.idEstSol != 'ESS007'";
                    } else {
                        $consulta1 .= " AND (pys_actsolicitudes.idEstSol != 'ESS001' AND pys_actsolicitudes.idEstSol != 'ESS006' AND pys_actsolicitudes.idEstSol != 'ESS007')";
                    }
                    $consulta1 .= " AND pys_actualizacionproy.idProy = '$idProy' 
                        ORDER BY pys_actsolicitudes.registraTiempo DESC, pys_actsolicitudes.idSol ASC;";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    $registros1 = mysqli_num_rows($resultado1);
                    if ($registros1 > 0) {
                        $cont = 0;
                        $filaIni = $fila;
                        while ($datos1 = mysqli_fetch_array($resultado1)) {
                            $fechPrev = $datos1['fechPrev'];
                            $idSol = $datos1['idSol'];
                            $ObservacionAct = $datos1['ObservacionAct'];
                            $nombreEstSol = $datos1['nombreEstSol']; 
                            $presupuesto = $datos1['presupuesto'];
                            $presupuestoTotal += $presupuesto;
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
                            $areaConocimiento = $datos1['areaNombre'];
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona, pys_asignados.maxhora, pys_asignados.maxmin
                                FROM pys_asignados
                                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                                WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND  pys_asignados.idSol = '$idSol'
                                ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            $ejecutadoAcumulado = '0';
                            if (mysqli_num_rows($resultado2) > 0) {
                                $personasAsig = $personasTiempo = $tiempoAsignado = '';
                                while ($datos2 = mysqli_fetch_array($resultado2) ) {
                                    $personasAsig .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres']."\n";
                                    $tiempoAsignado = ( ( ($datos2['maxhora'] * 60) + $datos2['maxmin'] ) / 60 ) . ' H';
                                    if ($tiempos != null) {
                                        $horas = $minutos = $ejecutado = 0;
                                        $idAsignado = $datos2['idAsig'];
                                        $query = "SELECT horaTiempo, minTiempo, salario
                                            FROM pys_tiempos 
                                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig AND pys_asignados.est != '0'
                                            INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND pys_salarios.estSal = '1' AND pys_salarios.mes <= pys_asignados.fechAsig AND pys_salarios.anio >= pys_asignados.fechAsig
                                            WHERE pys_asignados.idAsig = '$idAsignado' AND pys_tiempos.estTiempo = '1';";
                                        $result = mysqli_query($connection, $query);
                                        while ( $data = mysqli_fetch_array($result) ) {
                                            $horas += $data['horaTiempo'];
                                            $minutos += $data['minTiempo'];
                                            $ejecutado += ((($data['horaTiempo'] * 60) + $data['minTiempo']) / 60) * $data['salario'];
                                        }
                                        $total = " (".( ( ($horas * 60) + $minutos ) / 60 ) . " H / ".$tiempoAsignado.")\n";
                                        $ejecutadoAcumulado += $ejecutado;
                                        $personasTiempo .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'] . $total;
                                    } 
                                }
                                $personasAsig = trim($personasAsig, "\n");
                                $personasTiempo = trim($personasTiempo, "\n");
                            }
                            $ejecutadoAcumulado = ($ejecutadoAcumulado == 0) ? '0' : $ejecutadoAcumulado;
                            if ($tiempos != null) {
                                $dataRow = [$codProy.' - '.$nombreProy, 'P'.$idSol, $ObservacionAct, $fechPrev, $nombreEstSol, $personasAsig, $personasTiempo, $nombreTProd, $nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd, $observacionesProd, $urlVimeo, $urlservidor, $duracionmin, $duracionseg, $sinopsis, $palabrasClave, $autorExterno, $idiomaNombre, $formatoNombre, $tipoContenido, $areaConocimiento];
                            } else {
                                $dataRow = [$codProy.' - '.$nombreProy, 'P'.$idSol, $ObservacionAct, $fechPrev, $nombreEstSol, $personasAsig, $nombreTProd, $nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd, $observacionesProd, $urlVimeo, $urlservidor, $duracionmin, $duracionseg, $sinopsis, $palabrasClave, $autorExterno, $idiomaNombre, $formatoNombre, $tipoContenido, $areaConocimiento];
                            }
                            $spreadsheet->getActiveSheet()->fromArray($dataRow, null, 'A'.$fila);
                            $fila++;
                        }
                    }
                }
                
                $spreadsheet->getActiveSheet()->getStyle('A6:'.ALPHABET[count($titulos) - 1].($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:G'.ALPHABET[count($titulos) - 1].($fila-1))->applyFromArray(STYLEBODY);
                if ($tiempos != null) {
                    // Creación de hoja con información de tiempos registrados en el mes
                    $myWorkSheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ejecuciones proyectos');
                    $spreadsheet->addSheet($myWorkSheet2, 1);
                    $spreadsheet->setActiveSheetIndex(1);
                    $spreadsheet->getActiveSheet()->setCellValue('A1', '0%  - 40%  Verde');
                    $spreadsheet->getActiveSheet()->setCellValue('A2', '41% - 60%  Amarillo');
                    $spreadsheet->getActiveSheet()->setCellValue('A3', '61% - 80%  Naranja');
                    $spreadsheet->getActiveSheet()->setCellValue('A4', '80% - 100% Rojo');
                    $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(STYLEGREEN);
                    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray(STYLEYELLOW);
                    $spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray(STYLEORANGE);
                    $spreadsheet->getActiveSheet()->getStyle('A4')->applyFromArray(STYLERED);
                    $spreadsheet->getActiveSheet()->setShowGridlines(false);
                    $spreadsheet->getActiveSheet()->mergeCells("C5:D5");
                    $spreadsheet->getActiveSheet()->mergeCells("F5:G5");
                    $spreadsheet->getActiveSheet()->mergeCells("H5:M5");
                    $spreadsheet->getActiveSheet()->mergeCells("N5:O5");
                    $spreadsheet->getActiveSheet()->mergeCells("P5:Q5");
                    $spreadsheet->getActiveSheet()->mergeCells("R5:S5");
                    $spreadsheet->getActiveSheet()->mergeCells("T5:U5");
                    $spreadsheet->getActiveSheet()->setCellValue('C5', 'Corte Anterior');
                    $spreadsheet->getActiveSheet()->setCellValue('E5', 'Oracle');
                    $spreadsheet->getActiveSheet()->setCellValue('F5', 'Corte Actual');
                    $spreadsheet->getActiveSheet()->setCellValue('H5', 'Total'); 
                    $spreadsheet->getActiveSheet()->setCellValue('N5', 'Bolsa de recursos: Videos');
                    $spreadsheet->getActiveSheet()->setCellValue('P5', 'Bolsa de recursos: Línea Gráfica');
                    $spreadsheet->getActiveSheet()->setCellValue('R5', 'Gestoría');
                    $spreadsheet->getActiveSheet()->setCellValue('T5', 'Montaje');
                    $spreadsheet->getActiveSheet()->setCellValue('A6', 'Proyecto');
                    $spreadsheet->getActiveSheet()->setCellValue('B6', 'Asignado');
                    $spreadsheet->getActiveSheet()->setCellValue('C6', 'Tiempo');
                    $spreadsheet->getActiveSheet()->setCellValue('D6', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('F6', 'Tiempo');
                    $spreadsheet->getActiveSheet()->setCellValue('G6', 'Ejecución');                    
                    $spreadsheet->getActiveSheet()->setCellValue('H6', 'Horas presupuestadas');
                    $spreadsheet->getActiveSheet()->setCellValue('I6', 'Horas ejecutadas');
                    $spreadsheet->getActiveSheet()->setCellValue('J6', 'Horas por ejecutar');
                    $spreadsheet->getActiveSheet()->setCellValue('K6', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('L6', 'Disponible presupuesto');
                    $spreadsheet->getActiveSheet()->setCellValue('M6', 'Horas disponibles promedio ($36.000 hora)');
                    $spreadsheet->getActiveSheet()->setCellValue('N6', 'Presupuesto');
                    $spreadsheet->getActiveSheet()->setCellValue('O6', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('P6', 'Presupuesto');
                    $spreadsheet->getActiveSheet()->setCellValue('Q6', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('R6', 'Presupuesto');
                    $spreadsheet->getActiveSheet()->setCellValue('S6', 'Ejecución');
                    $spreadsheet->getActiveSheet()->setCellValue('T6', 'Presupuesto');
                    $spreadsheet->getActiveSheet()->setCellValue('U6', 'Ejecución');
                    $datos2 = self::ejecuciones($proyecto, $frente, $gestor);
                    $fila = $filasIni = 7;
                    if ( is_array ( $datos2 ) ) {
                        foreach ($datos2 as $value) {
                            if ( ! empty ( $value['Proyecto'] ) ) {
                                $tiempoCorteAnterior = ( empty ( $value['Tiempo Corte Anterior'] ) ) ? '0' : $value['Tiempo Corte Anterior'];
                                $ejecutadoCorteAnterior = ( empty ( $value['Ejecutado Corte Anterior'] ) ) ? '0' : $value['Ejecutado Corte Anterior'];
                                $tiempoCorteActual = ( empty ( $value['Tiempo Corte Actual'] ) ) ? '0' : $value['Tiempo Corte Actual'];
                                $ejecutadoCorteActual = ( empty ( $value['Ejecutado Corte Actual'] ) ) ? '0' : $value['Ejecutado Corte Actual'];
                                $horasPresupuestadas = ( empty ( $value['Horas Presupuestadas'] ) ) ? '0' : $value['Horas Presupuestadas'];
                                $horasPendientes = $horasPresupuestadas - $tiempoCorteAnterior - $tiempoCorteActual;
                                $spreadsheet->getActiveSheet()->fromArray([$value['Proyecto'], $value['Asignado'], $tiempoCorteAnterior, $ejecutadoCorteAnterior, '', $tiempoCorteActual, $ejecutadoCorteActual, $horasPresupuestadas], null, "A".$fila);
                                $spreadsheet->getActiveSheet()->setCellValue('I'.$fila, ($tiempoCorteAnterior + $tiempoCorteActual));
                                $spreadsheet->getActiveSheet()->setCellValue('J'.$fila, $horasPendientes);
                                $spreadsheet->getActiveSheet()->setCellValue('K'.$fila, ($ejecutadoCorteAnterior + $ejecutadoCorteActual));
                                $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('F'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('I'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('J'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('K'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $fila++;
                            } else if ( ! empty ( $value["Total Proyecto"] ) ) {
                                $presupuestoProyecto = ( empty ( $value['Presupuesto Proyecto'] ) ) ? '0' : $value['Presupuesto Proyecto'];
                                $totalTiempoAnterior = ( empty ( $value['Total Tiempo Anterior'] ) ) ? '0' : $value['Total Tiempo Anterior'];
                                $totalEjecutadoAnterior = ( empty ( $value['Total Ejecutado Anterior'] ) ) ? '0' : $value['Total Ejecutado Anterior'];
                                $totalTiempoActual = ( empty ( $value['Total Tiempo Actual'] ) ) ? '0' : $value['Total Tiempo Actual'];
                                $totalEjecutadoActual = ( empty ( $value['Total Ejecutado Actual'] ) ) ? '0' : $value['Total Ejecutado Actual'];
                                $totalHorasPresupuestadas = ( empty ( $value['Total Horas Presupuestadas'] ) ) ? '0' : $value['Total Horas Presupuestadas'];
                                $oracle = (empty ($value['Oracle']) ? '0' : $value['Oracle']);
                                $totalHorasPendientes = $totalHorasPresupuestadas - $totalTiempoAnterior - $totalTiempoActual;
                                $spreadsheet->getActiveSheet()->fromArray([$value['Nombre Proyecto'], $presupuestoProyecto, $totalTiempoAnterior, $totalEjecutadoAnterior, $oracle, $totalTiempoActual, $totalEjecutadoActual, $totalHorasPresupuestadas], null, "A".$fila);
                                $idProyecto = $value['idProyecto'];
                                $servicioGestoria = "SER039";
                                $servicioMontaje = "SER023";
                                $servicioLineaGrafica = "SER010";
                                $query = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.presupuesto, pys_servicios.idSer
                                    FROM pys_actsolicitudes 
                                    INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                                    WHERE pys_actsolicitudes.est = '1' AND pys_cursosmodulos.idProy = '$idProyecto' AND (pys_servicios.idSer = '$servicioGestoria' OR pys_servicios.idSer = '$servicioMontaje' OR pys_servicios.idSer = '$servicioLineaGrafica');";
                                $result = mysqli_query($connection, $query);
                                $presupuestoGestoria = $presupuestoMontaje = $presupuestoLineaGrafica = $ejecucionGestoria = $ejecucionMontaje = $ejecucionLineaGrafica = 0;
                                while ( $data = mysqli_fetch_array($result) ) {
                                    $idSol = $data['idSol'];
                                    $presupuesto = $data['presupuesto'];
                                    $totalEjecucion = self::ejecucionProducto($idSol);
                                    if ($data['idSer'] == $servicioGestoria) {
                                        $presupuestoGestoria = $presupuesto;
                                        $ejecucionGestoria = $totalEjecucion;
                                    } else if ($data['idSer'] == $servicioMontaje) {
                                        $presupuestoMontaje = $presupuesto;
                                        $ejecucionMontaje = $totalEjecucion;
                                    } else if ($data['idSer'] == $servicioLineaGrafica) {
                                        $presupuestoLineaGrafica = $presupuesto;
                                        $ejecucionLineaGrafica = $totalEjecucion;
                                    }
                                }
                                $totalTiempo = $totalTiempoAnterior + $totalTiempoActual;
                                $totalEjecutado = $totalEjecutadoAnterior + $totalEjecutadoActual;
                                $spreadsheet->getActiveSheet()->setCellValue('I'.$fila, $totalTiempo);
                                $spreadsheet->getActiveSheet()->setCellValue('J'.$fila, $totalHorasPendientes);
                                $spreadsheet->getActiveSheet()->setCellValue('K'.$fila, $totalEjecutado);
                                if ($oracle != 0) {
                                    $disponiblePresupuesto = $presupuestoProyecto - $oracle;
                                } else {
                                    $disponiblePresupuesto = $presupuestoProyecto - $totalEjecutado;
                                }
                                $spreadsheet->getActiveSheet()->setCellValue('L'.$fila, $disponiblePresupuesto);
                                $horasDisponibles = ($disponiblePresupuesto > 0) ? $disponiblePresupuesto / 36000 : 0;
                                $spreadsheet->getActiveSheet()->setCellValue('M'.$fila, $horasDisponibles);
                                $bolsaRecursosPresupuesto = $presupuestoProyecto - $presupuestoLineaGrafica - $presupuestoGestoria - $presupuestoMontaje;
                                $bolsaRecursosEjecutado = $totalEjecutado - $ejecucionLineaGrafica - $ejecucionGestoria - $ejecucionMontaje;
                                $spreadsheet->getActiveSheet()->setCellValue('N'.$fila, $bolsaRecursosPresupuesto);
                                $spreadsheet->getActiveSheet()->setCellValue('O'.$fila, $bolsaRecursosEjecutado);
                                $spreadsheet->getActiveSheet()->setCellValue('P'.$fila, $presupuestoLineaGrafica);
                                $spreadsheet->getActiveSheet()->setCellValue('Q'.$fila, $ejecucionLineaGrafica);
                                $spreadsheet->getActiveSheet()->setCellValue('R'.$fila, $presupuestoGestoria);
                                $spreadsheet->getActiveSheet()->setCellValue('S'.$fila, $ejecucionGestoria);
                                $spreadsheet->getActiveSheet()->setCellValue('T'.$fila, $presupuestoMontaje);
                                $spreadsheet->getActiveSheet()->setCellValue('U'.$fila, $ejecucionMontaje);
                                $spreadsheet->getActiveSheet()->getStyle('B'.$fila)->getNumberFormat()->setFormatCode('"Presupuesto proyecto: $" #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('E'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('F'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('I'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('J'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('K'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('L'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('N'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('O'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('P'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('Q'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('R'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('S'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('T'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('U'.$fila)->getNumberFormat()->setFormatCode('$ #,##0.00');
                                $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':U'.$fila)->applyFromArray(STYLETABLETITLESUB);
                                // Formato condicional para mostrar el color de la celda 
                                if ($oracle != 0) {
                                    $filaTotal = 'E';
                                } else {
                                    $filaTotal = 'K'; // FIla donde se muestra el total ejecutado
                                }
                                $spreadsheet->getActiveSheet()->getStyle($filaTotal.$fila)->applyFromArray(STYLEBLUE);
                                $filaColor = 'L'; // Columna que muestra el color del semáforo
                                $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                                $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                                $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                                $conditional1->addCondition('AND('.$filaTotal.$fila.'/B'.$fila.' > 0,'.$filaTotal.$fila.'/B'.$fila.' <= 0.4)');
                                $conditional1->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                                $conditional1->getStyle()->getFill()->getEndColor()->setARGB('89F986');

                                $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                                $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                                $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                                $conditional2->addCondition('AND('.$filaTotal.$fila.'/B'.$fila.' > 0.4,'.$filaTotal.$fila.'/B'.$fila.' <= 0.6)');
                                $conditional2->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                                $conditional2->getStyle()->getFill()->getEndColor()->setARGB('F9F22C');
                                
                                $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                                $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                                $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                                $conditional3->addCondition('AND('.$filaTotal.$fila.'/B'.$fila.' > 0.6,'.$filaTotal.$fila.'/B'.$fila.' <= 0.8)');
                                $conditional3->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                                $conditional3->getStyle()->getFill()->getEndColor()->setARGB('F7B14B');
                                
                                $conditional4 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                                $conditional4->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                                $conditional4->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                                $conditional4->addCondition('('.$filaTotal.$fila.'/B'.$fila.' > 0.8)');
                                $conditional4->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                                $conditional4->getStyle()->getFill()->getEndColor()->setARGB('F8473B');
                                
                                $conditional5 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                                $conditional5->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION);
                                $conditional5->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NONE);
                                $conditional5->addCondition('ISERROR('.$filaTotal.$fila.'/B'.$fila.')');
                                $conditional5->getStyle()->getFill()->setFillType(fill::FILL_SOLID);
                                $conditional5->getStyle()->getFill()->getEndColor()->setARGB('F8473B');

                                $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle($filaColor.$fila)->getConditionalStyles();
                                $conditionalStyles[] = $conditional1;
                                $conditionalStyles[] = $conditional2;
                                $conditionalStyles[] = $conditional3;
                                $conditionalStyles[] = $conditional4;
                                $conditionalStyles[] = $conditional5;
                                $spreadsheet->getActiveSheet()->getStyle($filaColor.$fila)->setConditionalStyles($conditionalStyles);
                                $fila++;
                                $filasIni = $fila;
                            }
                            $spreadsheet->getActiveSheet()->getStyle('A5:U6')->applyFromArray(STYLETABLETITLE);
                            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(60);
                            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
                            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(15);
                            $spreadsheet->getActiveSheet()->getStyle('A5:U'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                            $spreadsheet->getActiveSheet()->getStyle('A6:U'.($fila-1))->applyFromArray(STYLEBODY);
                            $spreadsheet->getActiveSheet()->getStyle('H5:M6')->applyFromArray(STYLESALMON);
                        }
                    } else {
                        $spreadsheet->getActiveSheet()->setCellValue('A3', 'No hay periodo configurado aún, no se puede mostrar información de tiempos');
                        $spreadsheet->getActiveSheet()->mergeCells("A3:H3");
                        $fila++;
                    }
                    // Creación de hoja con información de ejecuciones de proyectos 
                    $myWorkSheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ejecuciones en el corte');
                    $spreadsheet->addSheet($myWorkSheet3, 2);
                    $spreadsheet->setActiveSheetIndex(2);
                    $spreadsheet->getActiveSheet()->setShowGridlines(false);
                    $spreadsheet->getActiveSheet()->fromArray(['Persona', 'Proyecto', 'Tiempo trabajado', '% Ejecutado'], null, 'A1');
                    $datos = self::tiemposMes();
                    $fila = $filasIni = 2;
                    if ( is_array ( $datos ) ) {
                        foreach ($datos as $value) {
                            if ( ! empty ( $value['Nombre'] ) ) {
                                $spreadsheet->getActiveSheet()->fromArray([$value['Nombre'], $value['Proyecto'], $value['Tiempo'], $value['Porcentaje']], null, "A".$fila);
                                $fila++;
                            } else if ( ! empty ( $value["Total"] ) ) {
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
                    $spreadsheet->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                    $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray(STYLETABLETITLE);
                    $spreadsheet->getActiveSheet()->getStyle('A1:D'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                    $spreadsheet->getActiveSheet()->getStyle('A2:D'.($fila-1))->applyFromArray(STYLEBODY);
                    $spreadsheet->setActiveSheetIndex(0);
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
                    $inicioPeriodo = $data0['inicioPeriodo'];
                    $finPeriodo = $data0['finPeriodo'];
                    $totalAsig = ((($data0['diasSegmento1'] * 8) * $data0['porcentajeDedicacion1']) / 100) + ((($data0['diasSegmento2'] * 8) * $data0['porcentajeDedicacion2']) / 100);
                    $asignaciones[$data0['persona_IdPersona']] = ($totalAsig == 0) ? 160 : $totalAsig;
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
                $json = array();
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
                    $asignacion = isset( $asignaciones[$idPersona] ) ? $asignaciones[$idPersona] : 160;

                    $json[] = array(
                        "Nombre" => $nombreCompleto,
                        "Proyecto" => $proyecto,
                        "Tiempo" => number_format($tiempo, 2),
                        "Asignación" => $asignacion,
                        "Porcentaje" => number_format(($tiempo / $asignacion), 2)
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

        public static function ejecuciones($proyecto, $frente, $gestor) {
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
                $query1 = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy, pys_actualizacionproy.nombreCortoProy
                    FROM pys_asignados
                    
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy AND pys_actualizacionproy.est = '1' 
                    WHERE pys_asignados.est != '0' ";
                if ($proyecto != null) {
                    $where = " AND pys_asignados.idProy = '$proyecto' 
                        GROUP BY pys_actualizacionproy.idProy;";
                } else if ($frente != null) {
                    $where = " AND pys_actualizacionproy.idFrente = '$frente' 
                        GROUP BY pys_actualizacionproy.idProy;";
                } else if ($gestor != null) {
                    $where = " AND pys_asignados.idPersona = '$gestor' AND pys_asignados.idSol = ''
                        GROUP BY pys_actualizacionproy.idProy;";
                }
                $query1 = $query1 . $where;
                $result1 = mysqli_query($connection, $query1);
                $registry1 = mysqli_num_rows($result1);
                $consolidadoTiempoActual = $consolidadoEjecutadoActual = $consolidadoTiempoAnterior = $consolidadoEjecutadoAnterior = 0;
                if ($registry1 > 0) {
                    while ($datos1 = mysqli_fetch_array($result1)) {
                        $idProy = $datos1['idProy'];
                        $nombreProyecto = $datos1['codProy'] . " - " . $datos1['nombreProy'];
                        $oracle = $datos1['nombreCortoProy'];
                        $presupuestoProyecto = 0;
                        /* Obtener suma de presupuesto de todas las solicitudes asociadas al proyecto */
                        $query0 = "SELECT pys_actsolicitudes.presupuesto 
                            FROM pys_actsolicitudes 
                            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol AND pys_solicitudes.est = '1' 
                            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                            WHERE pys_actsolicitudes.est = '1' AND pys_solicitudes.idTSol = 'TSOL02' AND pys_cursosmodulos.idProy = '$idProy';";
                        $result0 = mysqli_query($connection, $query0);
                        $registry0 = mysqli_num_rows($result0);
                        if ($registry0 > 0) {
                            while ($data0 = mysqli_fetch_array($result0)) {
                                $presupuestoProyecto += $data0['presupuesto'];
                            }
                        }
                        $query2 = "SELECT pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                            FROM pys_asignados
                            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                            WHERE pys_asignados.idProy = '$idProy' AND pys_asignados.est != '0' AND pys_asignados.idSol != ''
                            GROUP BY pys_asignados.idPersona;";
                        $result2 = mysqli_query($connection, $query2);
                        $registry2 = mysqli_num_rows($result2);
                        $consolidadoTiempoAnterior = $consolidadoEjecutadoAnterior = $consolidadoTiempoActual = $consolidadoEjecutadoActual = $consolidadoTiempoAsignado = 0;
                        if ( $registry2 > 0 ) {
                            while ($data2 = mysqli_fetch_array($result2)) {
                                $idPersona = $data2['idPersona'];
                                $nombreAsignado = $data2['apellido1'] . " " . $data2['apellido2'] . " " . $data2['nombres'];
                                $query3 = "SELECT pys_asignados.idAsig, pys_salarios.salario, pys_asignados.maxhora, pys_asignados.maxmin
                                    FROM pys_asignados
                                    INNER JOIN pys_salarios ON pys_salarios.mes <= pys_asignados.fechAsig AND pys_salarios.anio >= pys_asignados.fechAsig AND pys_salarios.idPersona = pys_asignados.idPersona
                                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1'
                                    WHERE pys_asignados.idPersona = '$idPersona' AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol != '' AND pys_asignados.est != 0 AND pys_actsolicitudes.registraTiempo = '1';";
                                $result3 = mysqli_query($connection, $query3);
                                $registry3 = mysqli_num_rows($result3);
                                if ($registry3 > 0) {
                                    $tiempoActual = $totalActual = $tiempoAnterior = $totalAnterior = $tiempoAsignado = $totalTiempoAsignado = 0;
                                    while ($data3 = mysqli_fetch_array($result3)) {
                                        $idAsignado = $data3['idAsig'];
                                        $tiempoAsignado = (($data3['maxhora'] * 60) + $data3['maxmin']) / 60;
                                        $totalTiempoAsignado += $tiempoAsignado;
                                        $consolidadoTiempoAsignado += $tiempoAsignado;
                                        $salario = $data3['salario'];
                                        /* Información de tiempos en el corte actual */
                                        $query4 = "SELECT SUM(horaTiempo) AS 'Horas', SUM(minTiempo) AS 'Minutos'
                                            FROM pys_tiempos WHERE pys_tiempos.idAsig = '$idAsignado' AND pys_tiempos.fechTiempo >= '$inicioPeriodo' AND pys_tiempos.fechTiempo <= '$finPeriodo' AND pys_tiempos.estTiempo = '1';";
                                        $result4 = mysqli_query($connection, $query4);
                                        $registros4 = mysqli_num_rows($result4);
                                        if ($registros4 > 0) {
                                            $data4 = mysqli_fetch_array($result4);
                                            $tiempo = ((($data4['Horas'] * 60) + $data4['Minutos']) / 60);
                                            $costo = $tiempo * $salario;
                                            $tiempoActual += $tiempo;
                                            $totalActual +=  $tiempo * $salario;
                                            $consolidadoTiempoActual += $tiempo;
                                            $consolidadoEjecutadoActual += $costo;
                                        }
                                        /* Información de tiempos en el corte anterior */
                                        $query5 = "SELECT SUM(horaTiempo) AS 'Horas', SUM(minTiempo) AS 'Minutos'
                                            FROM pys_tiempos WHERE pys_tiempos.idAsig = '$idAsignado' AND pys_tiempos.fechTiempo < '$inicioPeriodo'  AND pys_tiempos.estTiempo = '1';";
                                        $result5 = mysqli_query($connection, $query5);
                                        $registros5 = mysqli_num_rows($result5);
                                        if ($registros5 > 0) {
                                            $data5 = mysqli_fetch_array($result5);
                                            $tiempo = ((($data5['Horas'] * 60) + $data5['Minutos']) / 60);
                                            $costo = $tiempo * $salario;
                                            $tiempoAnterior += $tiempo;
                                            $totalAnterior +=  $tiempo * $salario;
                                            $consolidadoTiempoAnterior += $tiempo;
                                            $consolidadoEjecutadoAnterior += $costo;
                                        }
                                    }
                                    if ($totalAnterior + $totalActual > 0) {
                                        $json[] = array (
                                            'Proyecto' => $nombreProyecto,
                                            'Asignado' => $nombreAsignado,
                                            'Horas Presupuestadas' => $totalTiempoAsignado,
                                            'Tiempo Corte Anterior' => $tiempoAnterior,
                                            'Ejecutado Corte Anterior' => $totalAnterior,
                                            'Tiempo Corte Actual' => $tiempoActual,
                                            'Ejecutado Corte Actual' => $totalActual
                                        );
                                    }
                                    $tiempoActual = $totalActual = $tiempoAnterior = $totalAnterior = 0;
                                }
                            }
                            if ($consolidadoEjecutadoAnterior + $consolidadoEjecutadoActual > 0) {
                                $json[] = array(
                                    "Total Proyecto" => $nombreProyecto,
                                    "Presupuesto Proyecto" => $presupuestoProyecto,
                                    "Total Horas Presupuestadas" => $consolidadoTiempoAsignado,
                                    "Nombre Proyecto" => $nombreProyecto,
                                    "Total Tiempo Anterior" => $consolidadoTiempoAnterior,
                                    "Total Ejecutado Anterior" => $consolidadoEjecutadoAnterior,
                                    "Total Tiempo Actual" => $consolidadoTiempoActual,
                                    "Total Ejecutado Actual" => $consolidadoEjecutadoActual,
                                    'idProyecto' => $idProy,
                                    'Oracle' => $oracle
                                );
                            }
                            $consolidadoTiempoActual = $consolidadoEjecutadoActual = $consolidadoTiempoAnterior = $consolidadoEjecutadoAnterior = $consolidadoTiempoAsignado = 0;
                        }
                    }
                }
            } else {
                $json = '';
            }
            mysqli_close($connection);
            return $json;
        }

        public static function ejecucionProducto ($idSol) {
            require('../Core/connection.php');
            $query = "SELECT pys_asignados.idAsig, pys_salarios.salario
                FROM pys_asignados
                INNER JOIN pys_salarios ON pys_salarios.mes <= pys_asignados.fechAsig AND pys_salarios.anio >= pys_asignados.fechAsig AND pys_salarios.idPersona = pys_asignados.idPersona
                WHERE idSol = '$idSol' AND pys_asignados.est != 0;";
            $result = mysqli_query($connection, $query);
            $registry = mysqli_num_rows($result);
            $totalEjecucion = 0;
            if ($registry > 0) {
                while ($data = mysqli_fetch_array($result)) {
                    $salario = $data['salario'];
                    $asignado = $data['idAsig'];
                    $query2 = "SELECT SUM(horaTiempo) AS 'Horas', SUM(minTiempo) AS 'Minutos'
                        FROM pys_tiempos WHERE pys_tiempos.idAsig = '$asignado' AND pys_tiempos.estTiempo = '1';";
                    $result2 = mysqli_query($connection, $query2);
                    $registry2 = mysqli_num_rows($result2);
                    if ($registry2 > 0) {
                        $data2 = mysqli_fetch_array($result2);
                        $tiempo = (($data2['Horas'] * 60) + $data2['Minutos']) / 60;
                        $ejecucion = $tiempo * $salario;
                        $totalEjecucion += $ejecucion;
                    }
                }
            }
            mysqli_close($connection);
            return $totalEjecucion;
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

        public static function selectGestor () {
            require('../Core/connection.php');
            $consulta = "SELECT idPersona, CONCAT(apellido1, ' ', apellido2, ' ', nombres) AS 'Nombre'
                FROM pys_personas WHERE idCargo = 'CAR041' AND est = '1' ORDER BY apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            $select = '     <select name="sltGestor" id="sltGestor">
                                <option value="" selected>Seleccione</option>';
            if ($registros > 0) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $select .=  '<option value="'.$datos['idPersona'].'">'.$datos['Nombre'].'</option>';
                }
            } else {
                $select .= '<option value="" selected disabled>No hay personas con cargo Gestor/Asesor RED</option>';
            }
            $select .= '    </select>
                            <label for="sltGestor">Gestor/Asesor RED</label>';
            mysqli_close($connection);
            return $select;
        }

    }
?>