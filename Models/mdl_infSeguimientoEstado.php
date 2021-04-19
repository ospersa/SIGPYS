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

    Class InformeSeguimientoEstados {
      

        public static function descarga ($proyecto, $frente, $estado) {
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
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-Ejecuciones');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                /** Arreglo titulos */
                /** Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /** Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(22);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(35);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de seguimiento de estados y metadata');
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A4:J4");
                $titulos=['Código/Nombre del proyecto', 'Código solicitud', 'Descripción de la solicitud', 'Fecha estimada de entrega', 'Estado', 'Responsable P&S', 'Tipo de recurso', 'Plataforma', 'Clase de producto', 'Tipo de producto', 'Nombre de producto', 'Descripción de producto', 'Link producto', 'URL Servidor', 'Duración Minutos', 'Duración Segundos', 'Sinopsis', 'Autor Externo', 'Idioma', 'Formato', 'Tipo Contenido'];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:U6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($datos = mysqli_fetch_array($resultado)) {
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
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante=''";
                    if ($estado != null) {
                        $consulta1 .= " AND pys_actsolicitudes.idEstSol !='ESS007'";
                    } else {
                        $consulta1 .= " AND (pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_actsolicitudes.idEstSol !='ESS007')";
                    }
                    $consulta1 .= " AND pys_actualizacionproy.idProy ='$idProy' ORDER BY pys_actsolicitudes.idSol;";
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
                            if(mysqli_num_rows($resultado2)> 0){
                                $personasAsig = '';
                                while ($datos2 = mysqli_fetch_array($resultado2)) {
                                    $personasAsig .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'].', ';
                                }
                                $personasAsig = trim($personasAsig, ', ');
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
                            $data = [$codProy.' - '.$nombreProy, 'P'.$idSol, $ObservacionAct, $fechPrev, $nombreEstSol, $personasAsig, $nombreTProd, $nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd, $observacionesProd, $urlVimeo, $urlservidor, $duracionmin, $duracionseg, $sinopsis, $autorExterno, $idiomaNombre, $formatoNombre, $tipoContenido];
                            $spreadsheet->getActiveSheet()->fromArray($data, null, 'A'.$fila);
                            $fila +=1;
                        }
                    }
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:U'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:U'.($fila-1))->applyFromArray(STYLEBODY);
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
    }
?>