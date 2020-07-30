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

const STYLEACTUALIZACION = [
    'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
        'argb' => 'B2CDCE',
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

    Class InformeProyectos {
        
        public static function selectEstProy(){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_estadoproy WHERE est = '1' 
            ORDER BY nombreEstProy";
            $resultado = mysqli_query($connection, $consulta);
            $select = '     <select name="sltEstProy" id="sltEstProy" required>
                                <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $idEstProy = $datos['idEstProy'];
                $nombreEstProy =  $datos['nombreEstProy'];
                $select .= '<option value="'.$idEstProy.'">'.$nombreEstProy.'</option>';   
            }
            $select .= '    </select>
                            <label for="sltEstProy">Estado</label>';
            return $select;
            mysqli_close($connection);
        }

        public static function busqueda ($estadoProy) {
            require('../Core/connection.php');
            $consultaNombre = "SELECT nombreEstProy FROM pys_estadoproy WHERE est = '1' AND idEstProy = '$estadoProy'";
            $resultadoNombre = mysqli_query($connection, $consultaNombre);
            $datosNombre = mysqli_fetch_array($resultadoNombre);
            $nombreEstado      = $datosNombre['nombreEstProy'];
            $consulta = "SELECT pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento,
			pys_tiposproy.nombreTProy, pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_estadoproy.idEstProy,
			pys_estadoproy.nombreEstProy, pys_etapaproy.nombreEtaProy, pys_proyectos.idProy, pys_actualizacionproy.proyecto, pys_actualizacionproy.codProy,
			pys_actualizacionproy.idEstProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy,
			pys_actualizacionproy.fechaIniProy,	pys_actualizacionproy.fechaCierreProy, pys_actualizacionproy.idConvocatoria, pys_proyectos.fechaCreacionProy,
			pys_actualizacionproy.fechaActualizacionProy, pys_actualizacionproy.idResponRegistro, pys_personas.apellido1, pys_personas.apellido2,
			pys_personas.nombres, pys_actualizacionproy.presupuestoProy, pys_actualizacionproy.financia,
			pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.fechaColciencias
			FROM pys_actualizacionproy
			INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
			INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
			INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
			INNER JOIN pys_tiposproy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
			INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente 
			INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona
			INNER JOIN pys_facdepto ON pys_actualizacionproy.idFacDepto=pys_facdepto.idFacDepto
			INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
			INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
			WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1'
			and pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '1'
			and	pys_actualizacionproy.est = '1' AND pys_personas.est = '1' and pys_convocatoria.est = '1' AND pys_actualizacionproy.idEstProy = '$estadoProy' 
			GROUP BY pys_proyectos.idProy
			ORDER BY pys_proyectos.fechaCreacionProy DESC";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if($registros > 0){
                echo'
                <h5>Reporte  por Estado del Proyecto : '.$nombreEstado.'</h5>
                <table class="table table-hover table-striped table-responsive-xl">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Facultad - Departamento</th>
                        <th>Frente</th> 
                        <!-- <th>Proyecto</th> -->
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Etapa</th>
                        <th>Cod. Conecta-TE</th>
                        <th>Proyecto</th>
                        <th>Convocatoria</th> 
                        <th>Nombre Corto</th>
                        <th>Contexto</th> 
                        <!-- <th>Presupuesto</th> -->
                        <th>Inicio</th>
                        <th>Cierre</th>
                        <th>Registro</th>
                        <th>*Fecha Colciencias</th>
                        <th>Creación</th>
                        <th>Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)) { 
                    $facDeptoFacultad = ($datos['facDeptoFacultad'] == 'No Aplica') ? '': $datos['facDeptoFacultad'];
                    $facDeptoDepartamento = ($datos['facDeptoDepartamento'] == 'No Aplica') ? '': $datos['facDeptoDepartamento'];
                    
                    echo '<tr>
                    <td>'.$datos['nombreEnt'].'</td>
					<td>'.$facDeptoFacultad." - ".$facDeptoDepartamento.'</td>			
					<td>'.$datos['nombreFrente']." ".$datos["descripcionFrente"].'</td>
					<td>'.$datos['nombreEstProy'].'</td>
					<td>'.$datos['nombreTProy'].'</td>
					<td>'.$datos['nombreEtaProy'].'</td>
					<td>'.$datos['codProy'].'</td>
					<td>'.$datos['nombreProy'].'</td>
					<td>'.$datos['nombreConvocatoria'].'</td>
					<td>'.$datos['nombreCortoProy'].'</td>
					<td>'.$datos['descripcionProy'].'</td>
					<td>'.$datos['fechaIniProy'].'</td>
					<td>'.$datos['fechaCierreProy'].'</td>
					<td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
					<td>'.$datos['fechaColciencias'].'</td>
					<td>'.$datos['fechaCreacionProy'].'</td>
                        ';
                    $fechaCreacionProy = $datos['fechaCreacionProy'];
					$fechaActualizacionProy = $datos['fechaActualizacionProy'];
			    	/*C�digo compara la fecha de creaci�n contra la fecha de actualizaci�n Le pregunta que si es diferente haga el procedimiento abajo.*/
					if ($fechaCreacionProy != $fechaActualizacionProy){				
                        echo '<td bgcolor="#B2CDCE">'.$datos['fechaActualizacionProy'].'</td>
                        </tr>';
					}else{	
                        echo '<td>'.$datos['fechaActualizacionProy'].'</td>
                        </tr>';
					}
                }
                echo'
                    </tbody>
                    </table>
                ';
            }
            mysqli_close($connection);
        }

        public static function descarga ($estadoProy) {
            require('../Core/connection.php');
            $consultaNombre = "SELECT nombreEstProy FROM pys_estadoproy WHERE est = '1' AND idEstProy = '$estadoProy'";
            $resultadoNombre = mysqli_query($connection, $consultaNombre);
            $datosNombre = mysqli_fetch_array($resultadoNombre);
            $nombreEstado = $datosNombre['nombreEstProy'];
            $consulta = "SELECT pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento,
			pys_tiposproy.nombreTProy, pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_estadoproy.idEstProy,
			pys_estadoproy.nombreEstProy, pys_etapaproy.nombreEtaProy, pys_proyectos.idProy, pys_actualizacionproy.proyecto, pys_actualizacionproy.codProy,
			pys_actualizacionproy.idEstProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.nombreCortoProy, pys_actualizacionproy.descripcionProy,
			pys_actualizacionproy.fechaIniProy,	pys_actualizacionproy.fechaCierreProy, pys_actualizacionproy.idConvocatoria, pys_proyectos.fechaCreacionProy,
			pys_actualizacionproy.fechaActualizacionProy, pys_actualizacionproy.idResponRegistro, pys_personas.apellido1, pys_personas.apellido2,
			pys_personas.nombres, pys_actualizacionproy.presupuestoProy, pys_actualizacionproy.financia,
			pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.fechaColciencias
			FROM pys_actualizacionproy
			INNER JOIN pys_estadoproy ON pys_estadoproy.idEstProy = pys_actualizacionproy.idEstProy
			INNER JOIN pys_etapaproy ON pys_etapaproy.idEtaProy = pys_actualizacionproy.idEtaProy
			INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_actualizacionproy.idProy
			INNER JOIN pys_tiposproy ON pys_actualizacionproy.idTProy = pys_tiposproy.idTProy
			INNER JOIN pys_frentes ON pys_actualizacionproy.idFrente = pys_frentes.idFrente 
			INNER JOIN pys_personas ON pys_actualizacionproy.idResponRegistro = pys_personas.idPersona
			INNER JOIN pys_facdepto ON pys_actualizacionproy.idFacDepto=pys_facdepto.idFacDepto
			INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
			INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
			WHERE pys_entidades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1'
			and pys_tiposproy.est = '1' AND pys_etapaproy.est = '1' AND pys_estadoproy.est = '1' AND pys_frentes.est = '1' AND pys_proyectos.est = '1'
			and	pys_actualizacionproy.est = '1' AND pys_personas.est = '1' and pys_convocatoria.est = '1' AND pys_actualizacionproy.idEstProy = '$estadoProy' 
			GROUP BY pys_proyectos.idProy
			ORDER BY pys_proyectos.fechaCreacionProy DESC";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de Proyectos')
                    ->setSubject('Informe de Proyectos')
                    ->setDescription('Informe de Proyectos')
                    ->setKeywords('Informe de Proyectos')
                    ->setCategory('Test result file');
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'inf-Proyectos');
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->getActiveSheet()->setShowGridlines(false); 
                    /**Arreglo titulos */
                    /**Aplicación de estilos */
                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray(STYLETABLETI);
                /**Dimensión columnas */
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(47);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(47);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(36);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Informe de Proyectos según el estado');
                $sheet->setCellValue('A2', 'Estado de los proyectos: '.$nombreEstado);                
                $sheet->mergeCells("A1:Q1");
                $sheet->mergeCells("A2:Q2"); 
                $titulos=['Empresa', 'Facultad - Departamento', 'Frente','Estado', 'Tipo', 'Cód. Conecta-TE', 'Proyecto', 'Extensión' , 'Convocatoria', 'Nombre Corto', 'Contexto', 'Inicio', 'Cierre', 'Registró', 'Fecha Colciencias', 'Creación', 'Última Actualización' ];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A4');
                $spreadsheet->getActiveSheet()->getStyle('A4:Q4')->applyFromArray(STYLETABLETITLE);
                $fila = 5;
                while ($datos = mysqli_fetch_array($resultado)) { 
                    $facDeptoFacultad = ($datos['facDeptoFacultad'] == 'No Aplica') ? '': $datos['facDeptoFacultad'];
                    $facDeptoDepartamento = ($datos['facDeptoDepartamento'] == 'No Aplica') ? '': $datos['facDeptoDepartamento'];
                    $informacion = [$datos['nombreEnt'], $facDeptoFacultad." - ".$facDeptoDepartamento			
					, $datos['nombreFrente']." ".$datos["descripcionFrente"], $datos['nombreEstProy'], $datos['nombreTProy'], $datos['nombreEtaProy'], $datos['codProy'], $datos['nombreProy'], $datos['nombreConvocatoria'], $datos['nombreCortoProy'], $datos['descripcionProy'], $datos['fechaIniProy'], $datos['fechaCierreProy'], $datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'], $datos['fechaColciencias'], $datos['fechaCreacionProy'], $datos['fechaActualizacionProy']];
                    $spreadsheet->getActiveSheet()->fromArray($informacion, null, 'A'.$fila);
                    $fechaCreacionProy = $datos['fechaCreacionProy'];
					$fechaActualizacionProy = $datos['fechaActualizacionProy'];
					if ($fechaCreacionProy != $fechaActualizacionProy){		
                        $spreadsheet->getActiveSheet()->getStyle('Q'.$fila)->applyFromArray(STYLEACTUALIZACION);
					}
                    $fila+= 1;
                }
            }
                $spreadsheet->getActiveSheet()->getStyle('A4:Q'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A4:Q'.($fila-1))->applyFromArray(STYLEBODY);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de Proyectos '.gmdate(' d M Y ').'.xlsx"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;  
                mysqli_close($connection);
        }
        
    }
?>