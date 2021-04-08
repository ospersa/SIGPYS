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
      

        public static function descarga ($proyecto, $frente) {
            require('../Core/connection.php');
            if ($proyecto == "sltProy" && $frente == null){
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' ORDER BY codProy asc;";
            } else if($frente != null && $proyecto == "" ){
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idFrente = '$frente' ORDER BY codProy asc;";
            }else {
                $consulta = "SELECT codProy, nombreProy, idProy FROM  pys_actualizacionproy
                WHERE est = '1' AND idProy='$proyecto' ORDER BY codProy asc;";
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
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(22);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(35);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de seguimiento de estados y metadata');
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A4:J4");
                $titulos=['Código del proyecto', 'Código solicitud','Descripcion de la solicitud','fecha estimada de entrega','Estado','Responsable P&S','Código del recurso','Tipo de recurso','Plataforma','Clase del producto','Tipo de producto','Nombre del producto','Descripción del producto','Link producto','urlservidor','varios','Duracion Minutos','Durcion Segundos','sinopsis','Autor Externo','Idioma','Formato','Tipo Contenido'];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:W6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($datos = mysqli_fetch_array($resultado)) {
                    $codProy = $datos['codProy'];
                    $nombreProy = $datos['nombreProy'];
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$fila, $codProy);
                    $idProy = $datos['idProy'];
                    $consulta1="SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSer, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol , pys_solicitudes.idSolIni
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND (pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_actsolicitudes.idEstSol !='ESS007') AND pys_actualizacionproy.idProy ='$idProy' ORDER BY pys_actsolicitudes.idSol;";
                    $resultado1=mysqli_query($connection, $consulta1);
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
                            $personasAsig ='';
                            $nombreProd = '';                                   
                            $urlVimeo = '';     
                            $idiomaNombre = "";
                            $idioma = "";
                            $nombreTRec = "";
                            $nombrePlt = "";
                            $descripcionTProd  = "";
                            $nombreClProd  = "";
                            $nombreTProd = "";
                            $descripcionProd = "";
                            $palabrasClave = "";
                            $fechEntregaProd  = "";
                            $urlservidor = "";
                            $observacionesProd = "";
                            $varios = "";
                            $duracionmin = "";
                            $durcionseg = "";
                            $sinopsis = "";
                            $autorExterno = "";
                            $tipoContenido = "";
                            $idPlat = "";
                            $idProd = "";
                            $formatoNombre = "";                                     
                            $idTRec = '';                                                 
                            $idClProd = '';                                   
                            $idTProd = '';                                                 
                            $formato = '';                               
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                            FROM pys_asignados
                            INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                            WHERE  (pys_asignados.est = '1' OR pys_asignados.est = '2') AND  pys_asignados.idSol='$idSol'
                            ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            if(mysqli_num_rows($resultado2)> 0){
                                while ($datos2 = mysqli_fetch_array($resultado2)) {
                                    $personasAsig .= $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'].', ';
                                }
                            }
                            $consulta3 = "SELECT pys_actproductos.
                            nombreProd, pys_actproductos.urlVimeo, pys_actproductos.idProd,pys_actproductos.idTRec, pys_actproductos.idPlat,pys_actproductos.idClProd,pys_actproductos.idTProd,pys_actproductos.descripcionProd,pys_actproductos.palabrasClave,pys_actproductos.fechEntregaProd,pys_actproductos.urlVimeo,pys_actproductos.urlservidor,pys_actproductos.observacionesProd,pys_actproductos.varios,pys_actproductos.duracionmin,pys_actproductos.duracionseg,pys_actproductos.sinopsis,pys_actproductos.autorExterno,pys_actproductos.idioma,pys_actproductos.formato,pys_actproductos.tipoContenido 
                            FROM pys_actproductos
                            INNER JOIN  pys_productos ON pys_productos.idProd = pys_actproductos.idProd
                            INNER JOIN  pys_actsolicitudes ON pys_productos.idSol = pys_actsolicitudes.idSol
                            WHERE  pys_actsolicitudes.idSol='$idSol' AND pys_actsolicitudes.est ='1' and pys_actproductos.est='1'";
                            $resultado3 = mysqli_query($connection, $consulta3);
                            if(mysqli_num_rows($resultado3)> 0){
                                while ($datos3 = mysqli_fetch_array($resultado3)) {
                                    $nombreProd = $datos3['nombreProd'];                                   
                                    $urlVimeo = $datos3['urlVimeo'];                                   
                                    $idTRec = $datos3['idTRec'];                                   
                                    $idPlat = $datos3['idPlat'];                                   
                                    $idProd = $datos3['idProd'];                                   
                                    $idClProd = $datos3['idClProd'];                                   
                                    $idTProd = $datos3['idTProd'];                                   
                                    $descripcionProd = $datos3['descripcionProd'];                                   
                                    $palabrasClave = $datos3['palabrasClave'];                                   
                                    $fechEntregaProd = $datos3['fechEntregaProd'];                                   
                                    $urlservidor = $datos3['urlservidor'];                                   
                                    $observacionesProd = $datos3['observacionesProd'];                                   
                                    $varios = $datos3['varios'];                                   
                                    $duracionmin = $datos3['duracionmin'];                                   
                                    $durcionseg = $datos3['duracionseg'];                                   
                                    $sinopsis = $datos3['sinopsis'];                                   
                                    $autorExterno = $datos3['autorExterno'];                                   
                                    $idioma = $datos3['idioma'];                                   
                                    $formato = $datos3['formato'];                                   
                                    $tipoContenido = $datos3['tipoContenido'];            
                                                        
                                }
                            }
                            $consulta4 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                            FROM pys_solicitudes 
                            INNER JOIN pys_personas ON pys_solicitudes.idSolicitante = pys_personas.idPersona
                            WHERE  pys_solicitudes.idSol='$idSolIni'";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            if(mysqli_num_rows($resultado4)> 0){
                                while ($datos4 = mysqli_fetch_array($resultado4)) {                              
                                    $Solicitante = $datos4['apellido1'].' '.$datos4['apellido2'].' '.$datos4['nombres'];                        
                                }
                            }
                            if($idioma != null){
                                $consulta5 = "SELECT idiomaNombre FROM `idiomas` WHERE idIdiomas= '".$idioma."'";
                                $resultado5 = mysqli_query($connection, $consulta5);
                                if(mysqli_num_rows($resultado5)> 0){
                                    while ($datos5 = mysqli_fetch_array($resultado5)) {                              
                                        $idiomaNombre = $datos5['idiomaNombre'];                        
                                    }
                                } 
                            }
                            if($idTRec != null){
                                $consulta6 = "SELECT nombreTRec FROM pys_tiposrecursos WHERE idTRec= '".$idTRec."'";
                                $resultado6 = mysqli_query($connection, $consulta6);
                                if(mysqli_num_rows($resultado6)> 0){
                                    while ($datos6 = mysqli_fetch_array($resultado6)) {                              
                                         $nombreTRec = $datos6['nombreTRec'];                        
                                    }
                                }
                            }
                            if($idPlat != null){ 
                                $consulta7 = "SELECT nombrePlt FROM pys_plataformas WHERE idPlat= '".$idPlat."'";
                                $resultado7 = mysqli_query($connection, $consulta7);
                                if(mysqli_num_rows($resultado7)> 0){
                                    while ($datos7 = mysqli_fetch_array($resultado7)){                              
                                        $nombrePlt = $datos7['nombrePlt'];                    
                                    }
                                }
                            } 
                            if($idTProd != null){
                                $consulta8 = "SELECT descripcionTProd FROM pys_tiposproductos WHERE idTProd= '".$idTProd."'";
                                $resultado8 = mysqli_query($connection, $consulta8);
                                if(mysqli_num_rows($resultado8)> 0){
                                    while ($datos8 = mysqli_fetch_array($resultado8)) {                              
                                        $descripcionTProd = $datos8['descripcionTProd'];   
                                    }                     
                                }
                            } 
                            if($formato != null){
                                $consulta9 = "SELECT formatoNombre FROM formatos WHERE idFormatos= '".$formato."'";
                                $resultado9 = mysqli_query($connection, $consulta9);
                                if(mysqli_num_rows($resultado9)> 0){
                                    while ($datos9 = mysqli_fetch_array($resultado9)) {                              
                                        $formatoNombre = $datos9['formatoNombre'];                        
                                    }
                                }
                            }
                            if($idClProd != null){
                                $consulta10 = "SELECT nombreClProd FROM pys_claseproductos WHERE idClProd= '".$idClProd."'";
                                $resultado10 = mysqli_query($connection, $consulta10);
                                if(mysqli_num_rows($resultado10)> 0){
                                    while ($datos10 = mysqli_fetch_array($resultado10)) {                              
                                        $nombreClProd = $datos10['nombreClProd'];                        
                                    }
                                }
                            }
                            
                            $data=[$codProy,'P'.$idSol,$ObservacionAct, $fechPrev,$nombreEstSol,$personasAsig,$idProd,$nombreTProd,$nombrePlt, $nombreClProd, $descripcionTProd,$nombreProd,$observacionesProd,$urlVimeo,$urlservidor,$varios,$duracionmin,$durcionseg, $sinopsis,$autorExterno,$idiomaNombre,$formatoNombre,$tipoContenido];
                            $spreadsheet->getActiveSheet()->fromArray($data, null, 'A'.$fila);
                            $fila +=1;
                        }
                    }
                }
                $spreadsheet->getActiveSheet()->getStyle('A6:W'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:W'.($fila-1))->applyFromArray(STYLEBODY);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de ejecuciones por proyecto '.gmdate(' d M Y ').'.xlsx"');
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
                $string .= '  <select name="sltFrenteInf" id="sltFrenteInf">
                            <option value=" " selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '  <option value="'.$datos['idFrente'].'">'.$datos['nombreFrente'].' '.$datos['descripcionFrente'].'</option>';
                }
                $string .= '  </select>
                        <label for="sltFrente">Frente</label>';
            }
            return $string;
            mysqli_close($connection);
        }
    }
?>