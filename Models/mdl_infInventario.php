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

    Class InformeInventario {

        public static function consulta ($persona, $proyecto, $fechaini, $fechafin, $estado) {
            require('../Core/connection.php');
            require_once('mdl_inventario.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_productos.nombreProd, pys_actsolicitudes.ObservacionAct,  pys_actinventario.estadoInv
           FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu 
            INNER JOIN pys_productos ON pys_actsolicitudes.idSol = pys_productos.idSol
            INNER JOIN pys_actinventario ON pys_productos.idProd = pys_actinventario.idProd ";
            $where = " WHERE pys_solicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_servicios.est = 1 AND pys_actinventario.est = 1 AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = 1 AND pys_actsolicitudes.est = 1 AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_productos.est = 1   ";
            if ($persona != null){
                $consulta .= "INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona ";
                $where .= " AND pys_personas.est = 1 AND pys_personas.idPersona ='$persona' ";
            } if ($proyecto != null){
                $where .= "AND pys_actualizacionproy.idProy ='$proyecto' ";
            }  if ($fechaini != null && $fechafin != null){
                $where .= "AND pys_productos.fechEntregaProd < $fechafin AND  pys_productos.fechEntregaProd >$fechaini";      
            } if ($estado != null){
                $where .= "AND pys_actinventario.estadoInv = '$estado' ";
            }   
            $consulta .= $where;
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            return $resultado;            
        }
        
        public static function busqueda ($persona, $proyecto, $fechaini, $fechafin, $estado) {
            require('../Core/connection.php');
            $string = "";
            $resultado = InformeInventario::consulta($persona, $proyecto, $fechaini, $fechafin, $estado); 
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
            
                $string = '     <table class="responsive-table" border="1">
                        <thead>
                            <tr class="teal lighten-4">
                                <th class="row-teal3">Código solicitud</th>
                                <th class="row-teal3">Producto</th>
                                <th class="row-teal3">Cód. proyecto en Conecta-TE</th>
                                <th class="row-teal3">Proyecto</th>
                                <th class="row-teal3">Equipo -- Servicio</th>
                                <th class="row-teal3">Descripción Producto</th>
                                <th class="row-teal3">Nombre del producto</th>
                                <th class="row-teal3">Estado Inventario</th>
                                <th class="row-teal3">Asignados</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        $idSol = $datos['idSol'];
                        require('../Core/connection.php');
                        $consultaE = "SELECT idEqu FROM pys_actsolicitudes INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
                        $resultadoE = mysqli_query($connection, $consultaE);
                        $datosE = mysqli_fetch_array($resultadoE);
                        $equipo = $datosE['idEqu'];
                        $asignados = InformeInventario::asignados($idSol);
                        $string .= '   <tr class="row60">
                            <td class="middle">'.$datos['idSolIni'].'</td>
                            <td class="middle">P'.$datos['idSol'].'</td>
                            <td class="middle">'.$datos['codProy'].'</td>
                            <td class="middle">'.$datos['nombreProy'].'</td>
                            <td class="middle">'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                            <td class="middle"><p>'.$datos['ObservacionAct'].'</p></td>
                            <td class="middle"><p>'.$datos['nombreProd'].'</p></td>
                            <td class="middle">'.$datos['estadoInv'].'</td>
                            <td class="middle">'.$asignados.'</td>
                         </tr>';
                    }
                    $string .= '    </tbody>
                                </table>';
            }
            else {
                $string = "<h4>No hay resultados</h4>";
            }
            return $string;
            mysqli_close($connection);


        }
        
        public static function descarga ($persona, $proyecto, $fechaini, $fechafin, $estado) {
            require('../Core/connection.php');
            $resultado = InformeInventario::consulta($persona, $proyecto, $fechaini, $fechafin, $estado); 
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de inventario')
                    ->setSubject('Informe de inventario')
                    ->setDescription('Informe de inventario')
                    ->setKeywords('Informe de inventario')
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
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe Inventario');
                $sheet->mergeCells("A1:J1");
                $titulos=['Código solicitud', 'Producto', 'Cód. proyecto en Conecta-TE', 'Proyecto', 'Equipo', 'Servicio', 'Descripción Producto', 'Nombre del producto', 'Estado Inventario', 'Asignados'];
                $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A3');
                $spreadsheet->getActiveSheet()->getStyle('A3:J3')->applyFromArray(STYLETABLETITLE);
                $fila = 4;
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $consultaE = "SELECT idEqu FROM pys_actsolicitudes INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
                    $resultadoE = mysqli_query($connection, $consultaE);
                    $datosE = mysqli_fetch_array($resultadoE);
                    $equipo = $datosE['idEqu'];
                    $asignados = InformeInventario::asignados($idSol);
                    $datos = [$datos['idSolIni'], 'P'.$datos['idSol'], $datos['codProy'], $datos['nombreProy'], $datos['nombreEqu'], $datos['nombreSer'], $datos['ObservacionAct'], $datos['nombreProd'], $datos['estadoInv'], $asignados];
                    $spreadsheet->getActiveSheet()->fromArray($datos,null,'A'.$fila);
                    $fila += 1;
                    
                }

                $spreadsheet->getActiveSheet()->getStyle('A4:J'.$fila)->applyFromArray(STYLEBODY);
                $spreadsheet->getActiveSheet()->getStyle('A3:J'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de Inventario.xlsx"');
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

        public static function asignados($codsol){
            require('../Core/connection.php');
            $consulta = "SELECT  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.est
            FROM pys_asignados
            inner join pys_solicitudes on pys_asignados.idSol = pys_solicitudes.idSol
            inner join pys_actsolicitudes on pys_actsolicitudes.idSol = pys_solicitudes.idSol
            inner join pys_cursosmodulos on pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            inner join pys_proyectos on pys_cursosmodulos.idProy = pys_proyectos.idProy
            inner join pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
            inner join pys_frentes on pys_proyectos.idFrente = pys_frentes.idFrente
            inner join pys_personas on pys_asignados.idPersona = pys_personas.idPersona
            inner join pys_roles on pys_asignados.idRol = pys_roles.idRol
            inner join pys_fases on pys_asignados.idFase = pys_fases.idFase
            inner join pys_convocatoria on pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria

            where pys_asignados.est != '0' and pys_actsolicitudes.est = '1' and pys_solicitudes.est = '1' and pys_cursosmodulos.estProy = '1' and pys_cursosmodulos.estCurso = '1' and pys_actualizacionproy.est = '1' and pys_proyectos.est = '1' and pys_frentes.est = '1' and ((pys_personas.est = '1') or (pys_personas.est = '0')) and pys_convocatoria.est = '1' and pys_roles.est = '1' and pys_fases.est = '1' and pys_actsolicitudes.idSol = '$codsol'";
            $resultado = mysqli_query($connection, $consulta);
            $nombres = "";
            while ($datos = mysqli_fetch_array($resultado)){
                $nombres .= $datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'; ';
            }    
            mysqli_close($connection);               
            return $nombres;    
        }
    }
?>