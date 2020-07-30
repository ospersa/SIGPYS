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
const STYLETABLETITLESUBPRO = [
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
        'argb' => 'b2dfdb',
        ]
    ]
];
Const STYLECOLORRED = ['font' => [
    'color'=>['rgb' => 'd50000']
    ]
];
Const STYLECOLORTEAL = ['font' => [
    'color'=>['rgb' => '009688']
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

    Class InformeSupervision {

        public static function consulta ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_proyectos.idProy, 
            pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria,
            pys_cursosmodulos.idCurso, pys_cursosmodulos.nombreCursoCM, pys_cursosmodulos.codigoCursoCM, 
            pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.idSol, 
            pys_solicitudes.fechSol, pys_solicitudes.idSolIni,
            pys_estadosol.nombreEstSol,
            pys_equipos.nombreEqu,
            pys_servicios.nombreSer,
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres,
            pys_roles.nombreRol,
            pys_fases.idFase, pys_fases.nombreFase, 
            pys_asignados.idAsig,
            pys_celulas.nombreCelula,
            pys_actsolicitudes.fechPrev
           
           FROM pys_asignados
            
           inner join pys_solicitudes on pys_asignados.idSol = pys_solicitudes.idSol   
           inner join pys_actsolicitudes on pys_actsolicitudes.idSol = pys_solicitudes.idSol
           inner join pys_cursosmodulos on pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
           inner join pys_proyectos on pys_cursosmodulos.idProy = pys_proyectos.idProy
           inner join pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
           inner join pys_estadosol on pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
           inner join pys_servicios on pys_actsolicitudes.idSer = pys_servicios.idSer
           inner join pys_equipos on pys_servicios.idEqu = pys_equipos.idEqu
           inner join pys_personas on pys_asignados.idPersona = pys_personas.idPersona
           inner join pys_roles on pys_asignados.idRol = pys_roles.idRol
           inner join pys_fases on pys_asignados.idFase = pys_fases.idFase
           inner join pys_convocatoria on pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
           inner join pys_celulas on pys_actualizacionproy.idCelula = pys_celulas.idCelula
                  
           where pys_asignados.est = '1' and pys_actsolicitudes.est = '1' and pys_solicitudes.est = '1' and pys_cursosmodulos.estProy = '1' and
           pys_cursosmodulos.estCurso = '1' and pys_actualizacionproy.est = '1' and pys_proyectos.est = '1' and 
           ((pys_personas.est = '1') or (pys_personas.est = '0')) and pys_convocatoria.est = '1' and pys_roles.est = '1' and pys_fases.est = '1'  and pys_estadosol.est = '1' and pys_solicitudes.fechSol >= '$fechaini' and  pys_solicitudes.fechSol <= '$fechafin' 
                           
           ORDER BY pys_solicitudes.fechSol DESC";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            return $resultado;            
        }
        
        public static function busqueda ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $string = "";
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = '     <table class="responsive-table" border="1">
                        <thead>
                            <tr class="teal lighten-4">
                                <th class="row-teal3">Código Proyecto</th>
                                <th class="row-teal3">Nombre Proyecto</th>
                                <th class="row-teal3">Celula</th>
                                <th class="row-teal3">Solicitud Inicial</th>
                                <th class="row-teal3">Producto/Servicio</th>
                                <th class="row-teal3">Descripción Producto/Servicio</th>
                                <th class="row-teal3">Fecha de registro</th>
                                <th class="row-teal3">Equipo -- Servicio</th>
                                <th class="row-teal3">Estado Solicitud</th>
                                <th class="row-teal3">Fecha Prevista de Entrega</th>
                                <th class="row-teal3">Asignados</th>
                                <th class="row-teal3">Fase</th>
                                <th class="row-teal3">Fecha registro Producto</th>
                                <th class="row-teal3">Fecha registro Servicio</th>
                                <th class="row-teal3">Estado Inventario</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($result = mysqli_fetch_array($resultado)) {
                $codSol=$result['idSol'];
                $string .= '<tr><td>'.$result['codProy'].'</td>
                <td>'.$result['nombreProy'].'</td>
                <td>'.$result['nombreCelula'].'</td>
                <td>'.$result['idSolIni'].'</td>
                <td>P'.$result['idSol'].'</td>
                <td><p class="truncate">'.$result['ObservacionAct'].'</p></td>
                <td>'.$result['fechSol'].'</td>
                <td>'.$result['nombreEqu'].' -- '.$result['nombreSer'].'</td>
                <td>'.$result['nombreEstSol'].'</td>
                <td>'.$result['fechPrev'].'</td>
                <td>'.$result['apellido1'].' '.$result['apellido2'].' '.$result['nombres'].'</td>
                <td>'.$result['nombreFase'].'</td>';
                $dato="SELECT pys_productos.idProd, pys_productos.idSol, pys_productos.fechaCreacion  
                FROM pys_productos
                INNER JOIN pys_personas ON pys_productos.idResponRegistro = pys_personas.idPersona
                INNER JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd	
                WHERE pys_productos.est = '1' AND pys_actproductos.est = '1' AND pys_personas.est = '1' AND pys_productos.idSol = '$codSol'";
                $consult=mysqli_query($connection,$dato);
                $cont =mysqli_num_rows($consult);
                if ($cont>0){
                    $resultad = mysqli_fetch_array($consult);
                    $idProducto=$resultad['idProd'];
                    $fechaCreacion=$resultad['fechaCreacion'];                        
                    $string.= "<td>$fechaCreacion</td>";	
                }else{
                    $string.= "<td></td>";	
                    $idProducto='';
                }
                $dato1="SELECT pys_resultservicio.idSol, pys_resultservicio.fechaCreacion
                FROM pys_resultservicio
                INNER JOIN pys_personas ON pys_resultservicio.idResponRegistro = pys_personas.idPersona
                WHERE pys_resultservicio.est = '1' AND pys_personas.est = '1' AND  pys_resultservicio.idSol = '$codSol'";
                $consult1=mysqli_query($connection,$dato1);
                $cont2 = mysqli_num_rows($consult1);
                if ($cont2 > 0){
                    $resultado1 = mysqli_fetch_array($consult1);
                    $fechaCreacionResultServ=$resultado1['fechaCreacion'];
                    $string.= "<td>$fechaCreacionResultServ</td>";	
                }else{
                    $string.= "<td></td>";
                }
                $dato2="SELECT estadoInv, idProd FROM `pys_actinventario`						
                WHERE est = '1' AND idProd = '$idProducto'";
                $consult2 = mysqli_query($connection,$dato2);
                $cont3 = mysqli_num_rows($consult2);
                if ($cont3>0){
                    $resultado2 = mysqli_fetch_array($consult2);
                    $estadoInv = $resultado2['estadoInv'];	
                    $string.= "<td>$estadoInv</td>";
                    $idProducto = '';
                }else{							
                    $string.= "<td>Sin inventario</td>";	
                } 
                $string.='</tr>';
            }
            $string .= '    </tbody>
                    </table>';
            }
            else {
                $string = "<h4>No hay resultados</h4>";
            }
            echo $string;
            mysqli_close($connection);
        }
        
        public static function descarga ($fechaini, $fechafin) {
            require('../Core/connection.php');
            $resultado = InformeSupervision::consulta($fechaini, $fechafin); 
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
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-inventario');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(25);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Supervisión');
                $sheet->setCellValue('A3', 'Fecha Inicial S.E. '.$fechaini);
                $sheet->setCellValue('A4', 'Fecha Final  S.E. '.$fechafin);
                $sheet->mergeCells("A1:P1");
                $sheet->mergeCells("A3:P3");
                $sheet->mergeCells("A4:P4");
                $titulos=['Código Proyecto', 'Nombre Proyecto', 'Celula', 'Solicitud Inicial', 'Producto/Servicio', 'Descripción Producto/Servicio', 'Fecha de registro', 'Equipo', 'Servicio', 'Estado Solicitud', 'Fecha Prevista de Entrega', 'Asignados', 'Fase', 'Fecha registro Producto', 'Fecha registro Servicio', 'Estado Inventario'];
                $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A6');
                $spreadsheet->getActiveSheet()->getStyle('A6:P6')->applyFromArray(STYLETABLETITLE);
                $fila = 7;
                while ($result = mysqli_fetch_array($resultado)) {
                    $codSol=$result['idSol'];
                    $codProy =$result['codProy'];
                    $nombreProy =$result['nombreProy'];
                    $nombreCelula =$result['nombreCelula'];
                    $idSolIni =$result['idSolIni'];
                    $idSol ='P'.$result['idSol'];
                    $ObservacionAct =$result['ObservacionAct'];
                    $fechSol =$result['fechSol'];
                    $nombreEqu =$result['nombreEqu'];
                    $nombreSer =$result['nombreSer']; 
                    $nombreEstSol =$result['nombreEstSol'];
                    $fechPrev =$result['fechPrev'];
                    $nombre=$result['apellido1'].' '.$result['apellido2'].' '.$result['nombres'];
                    $nombreFase =$result['nombreFase'];
                    $dato="SELECT pys_productos.idProd, pys_productos.idSol, pys_productos.fechaCreacion  
                    FROM pys_productos
                    INNER JOIN pys_personas ON pys_productos.idResponRegistro = pys_personas.idPersona
                    INNER JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd	
                    WHERE pys_productos.est = '1' AND pys_actproductos.est = '1' AND pys_personas.est = '1' AND pys_productos.idSol = '$codSol'";
                    $consult=mysqli_query($connection,$dato);
                    $cont =mysqli_num_rows($consult);
                    if ($cont>0){
                        $resultad = mysqli_fetch_array($consult);
                        $idProducto=$resultad['idProd'];
                        $fechaCreacion=$resultad['fechaCreacion'];                        
                    }else{
                        $fechaCreacion=" ";
                        $idProducto='';
                    }
                    $dato1="SELECT pys_resultservicio.idSol, pys_resultservicio.fechaCreacion
                    FROM pys_resultservicio
                    INNER JOIN pys_personas ON pys_resultservicio.idResponRegistro = pys_personas.idPersona
                    WHERE pys_resultservicio.est = '1' AND pys_personas.est = '1' AND  pys_resultservicio.idSol = '$codSol'";
                    $consult1=mysqli_query($connection,$dato1);
                    $cont2 = mysqli_num_rows($consult1);
                    if ($cont2 > 0){
                        $resultado1 = mysqli_fetch_array($consult1);
                        $fechaCreacionResultServ=$resultado1['fechaCreacion'];
                    }else{
                        $fechaCreacionResultServ = "";
                    }
                    $dato2="SELECT estadoInv, idProd FROM `pys_actinventario`						
                    WHERE est = '1' AND idProd = '$idProducto'";
                    $consult2 = mysqli_query($connection,$dato2);
                    $cont3 = mysqli_num_rows($consult2);
                    if ($cont3>0){
                        $resultado2 = mysqli_fetch_array($consult2);
                        $estadoInv = $resultado2['estadoInv'];	
                        $idProducto = '';
                    }else{							
                        $estadoInv = "Sin Inventario";	
                    } 
                    $datos = [$codProy, $nombreProy, $nombreCelula, $idSolIni, $idSol, $ObservacionAct, $fechSol, $nombreEqu, $nombreSer, $nombreEstSol, $fechPrev, $nombre, $nombreFase, $fechaCreacion, $fechaCreacionResultServ,$estadoInv];
                    $spreadsheet->getActiveSheet()->fromArray($datos,null,'A'.$fila);
                    $fila += 1;

                }
                $spreadsheet->getActiveSheet()->getStyle('A6:P'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A6:P'.($fila-1))->applyFromArray(STYLEBODY);
               
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