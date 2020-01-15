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

    Class InformePersonas {
        
        public static function selectEntFAcu(){
            require('../Core/connection.php');
            $consulta = "SELECT pys_facdepto.idFacDepto, pys_entidades.nombreEnt, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento
		    FROM pys_facdepto
		    INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
			WHERE pys_entidades.est = '1' 
            AND pys_facdepto.estFacdeptoEnt = '1' 
            AND pys_facdepto.estFacdeptoFac = '1' 
            AND pys_facdepto.estFacdeptoDepto = '1' 
            ORDER BY pys_facdepto.facDeptoFacultad";
            $resultado = mysqli_query($connection, $consulta);
            $select = '     <select name="sltEntFac" id="sltEntFac" required>
                                <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $idFacDepto = $datos['idFacDepto'];
                $nombreEnt = ($datos['nombreEnt'] == 'No Aplica') ? '' : $datos['nombreEnt'];
                $facDeptoFacultad = ($datos['facDeptoFacultad'] == 'No Aplica') ? '': $datos['facDeptoFacultad'];
                $facDeptoDepartamento = ($datos['facDeptoDepartamento'] == 'No Aplica') ? '': $datos['facDeptoDepartamento'];
                $select .= '<option value="'.$idFacDepto.'">'.$nombreEnt.' / '.$facDeptoFacultad.' / '.$facDeptoDepartamento.'</option>';   
            }
            $select .= '    </select>
                            <label for="sltEntFac">Entidad / Departamento</label>';
            return $select;
            mysqli_close($connection);
        }

        public static function busqueda ($idFacDepto) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_facdepto.idEnt, pys_facdepto.Idfac, pys_entidades.nombreEnt, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento
            FROM pys_facdepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            WHERE pys_entidades.est = '1' 
            AND pys_facdepto.estFacdeptoEnt = '1' 
            AND pys_facdepto.estFacdeptoFac = '1' 
            AND pys_facdepto.estFacdeptoDepto = '1' 
            AND pys_facdepto.idFacDepto = '$idFacDepto'
            ORDER BY pys_facdepto.facDeptoFacultad";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if($registros > 0){
                $datos = mysqli_fetch_array($resultado);
                $nombreEntidad      = $datos['nombreEnt'];
                $idEnt      = $datos['idEnt'];
                $Idfac      = $datos['Idfac'];
                $nombreFacultad     = $datos['facDeptoFacultad'];
                $nombreDepartamento = $datos['facDeptoDepartamento'];
                echo'
                <h5>Reporte Personas Empresa: '.$nombreEntidad.' </h5>
                <table class="table table-hover table-striped table-responsive-xl">
                <thead>
                    <tr>
						<th>Facultad - Departamento</th>
						<th>Cargo</th>
						<th>Tipo</th>
						<th>Identificación</th>
						<th>Apellidos y Nombres</th>
						<th>E-Mail</th>
						<th>Tel. Fijo</th>
						<th>Extensión</th>
						<th>Celular</th>
                    </tr>
                </thead>
                <tbody>
                '; 
                if ($nombreDepartamento == '' || $nombreDepartamento == 'No Aplica'){
                    if ($nombreFacultad == '' || $nombreFacultad == 'No Aplica'){
                        $consultaENT = "SELECT idFacDepto, facDeptoFacultad, facDeptoDepartamento FROM pys_facdepto WHERE idEnt ='$idEnt'";
                    } else {
                        $consultaENT = "SELECT  idFacDepto, facDeptoFacultad, facDeptoDepartamento FROM pys_facdepto WHERE idEnt ='$idEnt' AND Idfac = '$Idfac'";
                    }                    
                    $resultadoENT  = mysqli_query($connection, $consultaENT );
                    while ($datosENT = mysqli_fetch_array($resultadoENT)) {
                        $idFacDeptoENT = $datosENT['idFacDepto'];
                        $nombreFacultadENT     = ($datosENT['facDeptoFacultad'] == 'No Aplica')? '': $datosENT['facDeptoFacultad'];
                        $nombreDepartamentoENT = ($datosENT['facDeptoDepartamento'] == 'No Aplica')? '': $datosENT['facDeptoDepartamento'];
                        $consulta1 = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, 
                        pys_personas.nombres, pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt,
                        pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo
                        FROM pys_personas
                        INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                        INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                        INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                        WHERE pys_personas.est = '1' 
                        AND pys_cargos.est = '1' 
                        AND pys_facdepto.estFacdeptoEnt = '1' 
                        AND pys_facdepto.estFacdeptoFac = '1' 
                        AND pys_facdepto.estFacdeptoDepto = '1' 
                        AND (pys_facdepto.idFacDepto ='$idFacDeptoENT')
                        ORDER BY pys_personas.apellido1";
                        $resultado1 = mysqli_query($connection, $consulta1);
                        while ($datos1 = mysqli_fetch_array($resultado1)) { 
                        echo '<tr>
                            <td>'. $nombreFacultadENT .' - '.$nombreDepartamentoENT.'</td>
                            <td>'.$datos1['nombreCargo'].'</td>
                            <td>'.$datos1['tipoPersona'].'</td>
                            <td>'.$datos1['identificacion'].'</td>
                            <td>'.$datos1['apellido1'].' '.$datos1['apellido2'].' '.$datos1['nombres'].'</td>
                            <td>'.$datos1['correo'].'</td>
                            <td>'.$datos1['telefono'].'</td>
                            <td>'.$datos1['extension'].'</td>
                            <td>'.$datos1['celular'].'</td>
                            </tr>';
                        }
                     }
                } else {
                    $nombreFacultad     = ($datos['facDeptoFacultad'] == 'No Aplica')? '': $datos['facDeptoFacultad'];
                    $nombreDepartamento = ($datos['facDeptoDepartamento'] == 'No Aplica')? '': $datos['facDeptoDepartamento'];
                    $consulta1 = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, 
                    pys_personas.nombres, pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt,
                    pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo
                    FROM pys_personas
                    INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                    INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                    INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                    WHERE pys_personas.est = '1' 
                    AND pys_cargos.est = '1' 
                    AND pys_facdepto.estFacdeptoEnt = '1' 
                    AND pys_facdepto.estFacdeptoFac = '1' 
                    AND pys_facdepto.estFacdeptoDepto = '1' 
                    AND (pys_facdepto.idFacDepto ='$idFacDepto')
                    ORDER BY pys_personas.apellido1";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    while ($datos1 = mysqli_fetch_array($resultado1)) { 
                    echo '<tr>
                        <td>'. $nombreFacultad .' - '.$nombreDepartamento.'</td>
                        <td>'.$datos1['nombreCargo'].'</td>
                        <td>'.$datos1['tipoPersona'].'</td>
                        <td>'.$datos1['identificacion'].'</td>
                        <td>'.$datos1['apellido1'].' '.$datos1['apellido2'].' '.$datos1['nombres'].'</td>
                        <td>'.$datos1['correo'].'</td>
                        <td>'.$datos1['telefono'].'</td>
                        <td>'.$datos1['extension'].'</td>
                        <td>'.$datos1['celular'].'</td>
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

        public static function descarga ($idFacDepto) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_facdepto.idEnt, pys_facdepto.Idfac, pys_entidades.nombreEnt, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento
            FROM pys_facdepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            WHERE pys_entidades.est = '1' 
            AND pys_facdepto.estFacdeptoEnt = '1' 
            AND pys_facdepto.estFacdeptoFac = '1' 
            AND pys_facdepto.estFacdeptoDepto = '1' 
            AND pys_facdepto.idFacDepto = '$idFacDepto'
            ORDER BY pys_facdepto.facDeptoFacultad";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()->setCreator('Conecta-TE')
                    ->setLastModifiedBy('Conecta-TE')
                    ->setTitle('Informe de Personas')
                    ->setSubject('Informe de Personas')
                    ->setDescription('Informe de Personas')
                    ->setKeywords('Informe de Personas')
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
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $sheet = $spreadsheet->getActiveSheet();
                $datos = mysqli_fetch_array($resultado);
                $nombreEntidad      = $datos['nombreEnt'];
                $idEnt      = $datos['idEnt'];
                $Idfac      = $datos['Idfac'];
                $nombreFacultad     = $datos['facDeptoFacultad'];
                $nombreDepartamento = $datos['facDeptoDepartamento'];
                $sheet->setCellValue('A1', 'Informe de Personas');
                $sheet->setCellValue('A2', $nombreEntidad);                
                $sheet->mergeCells("A1:I1");
                $sheet->mergeCells("A2:I2"); 
                $titulos=['Facultad - Departamento', 'Apellidos y Nombres','Identificación', 'Cargo', 'Tipo', 'E-Mail', 'Tel. Fijo', 'Extensión' , 'Celular' ];
                $spreadsheet->getActiveSheet()->fromArray($titulos, null, 'A4');
                $spreadsheet->getActiveSheet()->getStyle('A4:I4')->applyFromArray(STYLETABLETITLE);
                $fila = 5;
                if ($nombreDepartamento == '' || $nombreDepartamento == 'No Aplica'){
                    if ($nombreFacultad == '' || $nombreFacultad == 'No Aplica'){
                        $consultaENT = "SELECT idFacDepto, facDeptoFacultad, facDeptoDepartamento FROM pys_facdepto WHERE idEnt ='$idEnt'";
                    } else {
                        $consultaENT = "SELECT  idFacDepto, facDeptoFacultad, facDeptoDepartamento FROM pys_facdepto WHERE idEnt ='$idEnt' AND Idfac = '$Idfac'";
                    }                    
                    $resultadoENT  = mysqli_query($connection, $consultaENT );
                    while ($datosENT = mysqli_fetch_array($resultadoENT)) {
                        $idFacDeptoENT = $datosENT['idFacDepto'];
                        $nombreFacultadENT     = ($datosENT['facDeptoFacultad'] == 'No Aplica')? '': $datosENT['facDeptoFacultad'];
                        $nombreDepartamentoENT = ($datosENT['facDeptoDepartamento'] == 'No Aplica')? '': $datosENT['facDeptoDepartamento'];
                        $consulta1 = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, 
                        pys_personas.nombres, pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt,
                        pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo
                        FROM pys_personas
                        INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                        INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                        INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                        WHERE pys_personas.est = '1' 
                        AND pys_cargos.est = '1' 
                        AND pys_facdepto.estFacdeptoEnt = '1' 
                        AND pys_facdepto.estFacdeptoFac = '1' 
                        AND pys_facdepto.estFacdeptoDepto = '1' 
                        AND (pys_facdepto.idFacDepto ='$idFacDeptoENT')
                        ORDER BY pys_personas.apellido1";
                        $resultado1 = mysqli_query($connection, $consulta1);
                        while ($datos1 = mysqli_fetch_array($resultado1)) { 
                            $informacion = [$nombreFacultadENT.' - '.$nombreDepartamentoENT, $datos1['apellido1'].' '.$datos1['apellido2'].' '.$datos1['nombres'],  $datos1['identificacion'], $datos1['nombreCargo'], $datos1['tipoPersona'], $datos1['correo'], $datos1['telefono'], $datos1['extension'], $datos1['celular']];
                            $spreadsheet->getActiveSheet()->fromArray($informacion, null, 'A'.$fila);
                            $fila+= 1;
                        }
                     } 
                }else{
                    $nombreFacultad     = ($datos['facDeptoFacultad'] == 'No Aplica')? '': $datos['facDeptoFacultad'];
                    $nombreDepartamento = ($datos['facDeptoDepartamento'] == 'No Aplica')? '': $datos['facDeptoDepartamento'];
                    $consulta1 = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, 
                    pys_personas.nombres, pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt,
                    pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo
                    FROM pys_personas
                    INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                    INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                    INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                    WHERE pys_personas.est = '1' 
                    AND pys_cargos.est = '1' 
                    AND pys_facdepto.estFacdeptoEnt = '1' 
                    AND pys_facdepto.estFacdeptoFac = '1' 
                    AND pys_facdepto.estFacdeptoDepto = '1' 
                    AND (pys_facdepto.idFacDepto ='$idFacDepto')
                    ORDER BY pys_personas.apellido1";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    while ($datos1 = mysqli_fetch_array($resultado1)) { 
                        $informacion = [$nombreFacultad.' - '.$nombreDepartamento, $datos1['apellido1'].' '.$datos1['apellido2'].' '.$datos1['nombres'],  $datos1['identificacion'], $datos1['nombreCargo'], $datos1['tipoPersona'], $datos1['correo'], $datos1['telefono'], $datos1['extension'], $datos1['celular']];
                        $spreadsheet->getActiveSheet()->fromArray($informacion, null, 'A'.$fila);
                        $fila+= 1;
                    }
                 }
            }

                $spreadsheet->getActiveSheet()->getStyle('A4:I'.($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
                $spreadsheet->getActiveSheet()->getStyle('A4:I'.($fila-1))->applyFromArray(STYLEBODY);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Informe de Personas '.gmdate(' d M Y ').'.xlsx"');
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