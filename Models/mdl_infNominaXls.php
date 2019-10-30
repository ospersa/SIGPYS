<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

require '../php_libraries/vendor/autoload.php';
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

Class InformeNomima {

    public static function selectAnioInforme () {
        require('../Core/connection.php');
        $anio = date("Y");
        $consulta = "SELECT YEAR(fechTiempo) 
            FROM pys_tiempos 
            WHERE YEAR(fechTiempo) >= '2018'
            AND fechTiempo <= '$anio-12-15'
            AND estTiempo = '1' 
            GROUP BY YEAR(fechTiempo);";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            $string = ' <select name="sltAnio" id="sltAnio" onchange="cargaSelect(\'#sltAnio\',\'../Controllers/ctrl_infNominaXls.php\',\'#sltMes2\');">
                            <option value="" disabled selected>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $string .= '<option value="'.$datos[0].'">'.$datos[0].'</option>';
            }
            $string .= '</select>
                        <label for="sltAnio">Año</label>';
            return $string;
        }
        mysqli_close($connection);
    }

    public static function selectMes ($anio) {
        require('../Core/connection.php');
        $consulta = "SELECT MONTH(fechTiempo)
            FROM pys_tiempos 
            WHERE YEAR(fechTiempo) = '$anio' 
            AND (fechTiempo BETWEEN '$anio-1-15' AND '$anio-11-30') 
            AND estTiempo = '1' 
            GROUP BY MONTH(fechTiempo);";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            $meses = [1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"];
            echo '  <select name="sltMes" id="sltMes" onchange="cargaSelect(\'#sltMes\',\'../Controllers/ctrl_infNominaXls.php\',\'#btnGenerar\');">
                        <option value="" selected disabled>Seleccione</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $mes = $datos[0] + 1;
                echo '  <option value="'.($datos[0]).'">'.$meses[$mes].'</option>';       
            }
            echo '  </select>
                    <label for="sltMes">Mes</label>';
        } else {
            echo '<h6>No hay tiempos registrados en el año seleccionado.</h6>';
        }
        mysqli_close($connection);
    }

    public static function mostrarBotonConsultar () {
        echo '<button class="btn waves-effect waves-light" type="submit" name="btnConsultar" onclick="mostrarInfo(\'../Controllers/ctrl_infNominaXls.php\');">Consultar</button>';
    }

    public static function informePreliminar ($anio, $mes) {
        require('../Core/connection.php');
        $i2 = $mes + 1;
        $meses = [1 => "ENE", 2 => "FEB", 3 => "MAR", 4 => "ABR", 5 => "MAY", 6 => "JUN", 7 => "JUL", 8 => "AGO", 9 => "SEP", 10 => "OCT", 11 => "NOV", 12 => "DIC"];
        $consulta = "SELECT pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.codProy, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy, pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo
            FROM pys_actualizacionproy 
            INNER JOIN pys_asignados ON pys_asignados.idProy = pys_actualizacionproy.idProy 
            INNER JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig 
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_actualizacionproy.idFrente
            INNER JOIN pys_convocatoria ON pys_convocatoria.idConvocatoria = pys_actualizacionproy.idConvocatoria
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            WHERE pys_actualizacionproy.est = '1'
            AND pys_asignados.est = '1'
            AND pys_tiempos.estTiempo = '1'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$anio-01-15' AND '$anio-$i2-15'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>FR</th>
                                <th>PROG</th>
                                <th>SIGLA</th>
                                <th>PROYECTO</th>
                                <th>CÉLULA</th>
                                <th>SEM</th>
                                <th>PROFESOR</th>
                                <th>FF</th>
                                <th>CECO</th>
                                <th>NOMBRE PEP</th>
                                <th>ELEMENTO PEP</th>
                                <th>NOMBRE</th>
                                <th>POSPRE</th>
                                ';
            for ($y=1; $y <= $mes ; $y++) {
                echo '          <th>'.$meses[$y+1].'</th>';
            }
            echo '              <th>PROMEDIO</th>
                                <th>HORAS REGISTRADAS</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT apellido1, apellido2, nombres
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est = '1'
                    AND pys_personas.est = '1'";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = $datos3['apellido1']." ".$datos3['apellido2']." ".$datos3['nombres'];
                /** Consulta de celula, centro de costos, elementos pep del proyecto */
                $consulta4 = "SELECT pys_celulas.nombreCelula, pys_fuentesfinanciamiento.sigla, pys_centrocostos.ceco, pys_elementospep.nombreElemento, pys_elementospep.codigoElemento
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cruceproypep ON pys_cruceproypep.idProy = pys_actualizacionproy.idProy
                    INNER JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                    INNER JOIN pys_elementospep ON pys_elementospep.idElemento = pys_cruceproypep.idElemento
                    INNER JOIN pys_fuentesfinanciamiento ON pys_fuentesfinanciamiento.idFteFin = pys_actualizacionproy.idFteFin
                    INNER JOIN pys_centrocostos ON pys_centrocostos.idCeco = pys_elementospep.idCeco
                    WHERE pys_actualizacionproy.idProy = '".$datos['idProy']."'
                    AND pys_actualizacionproy.est = '1'
                    AND pys_cruceproypep.estado = '1'
                    AND pys_celulas.estado = '1'
                    AND pys_elementospep.estado = '1'
                    AND pys_fuentesfinanciamiento.estado = '1'
                    AND pys_centrocostos.estado = '1';";
                $resultado4 = mysqli_query($connection, $consulta4);
                $datos4 = mysqli_fetch_array($resultado4);
                echo '      <tr>
                                <td>'.$datos['descripcionFrente'].'</td>
                                <td>'.$datos['nombreConvocatoria'].'</td>
                                <td>'.$datos['codProy'].'</td>
                                <td>'.$datos['nombreProy'].'</td>
                                <td>'.$datos4['nombreCelula'].'</td>
                                <td>'.$datos['semAcompanamiento'].'</td>
                                <td>'.$nombreProfesor.'</td>
                                <td>'.$datos4['sigla'].'</td>
                                <td>'.$datos4['ceco'].'</td>
                                <td>'.$datos4['nombreElemento'].'</td>
                                <td>'.$datos4['codigoElemento'].'</td>
                                <td>'.$nombreCompleto.'</td>
                                <td class="center-align">'.$datos['categoriaCargo'].'</td>';
                $porcentajeAcum = 0;
                for ($y=1; $y <= $mes; $y++) {
                    $y2 = $y + 1;
                    $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$anio-$y-15' AND finPeriodo = '$anio-$y2-15';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $per1 = $datos5['diasSegmento1'];
                    $per2 = $datos5['diasSegmento2'];
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    if ($hrsPeriodo == 0) {
                        $hrsPeriodo = 160;
                    }
                    if ($y == 1) {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-15' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    } else {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-16' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    }
                    
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $totalMinutos = ($datos2[0] * 60) + $datos2[1]; // Total de minutos registrados en el periodo correspondiente
                        $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                        $porcentaje = ($totalMinutos / $minutosPeriodo) * 100;
                        $porcentajeAcum += $porcentaje;
                        echo '  <td class="center-align">'.round($porcentaje, 0).' %</td>';
                    }
                }
                $promedio = ($porcentajeAcum/$mes);
                echo '          <td class="center-align">'.round($promedio, 0).' %</td>';
                echo '          <td>'.($totalMinutos/60).'</td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>
                <div class="row">
                    <button class="btn waves-effect waves-light col l2 m2 s12 offset-l5" type="submit" name="consultar">Descargar Excel</button>
                </div>';

        }
        mysqli_close($connection);
    }

    public static function descarga($anio, $mes) {
        require('../Core/connection.php');
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Conecta-TE')
            ->setLastModifiedBy('Conecta-TE')
            ->setTitle('Informe de Nomina -'.$anio.$mes)
            ->setSubject('Informe de Nomina-'.$anio.$mes)
            ->setDescription('Informe de Nomina-'.$anio.$mes)
            ->setKeywords('Informe de Nomina-'.$anio.$mes)
            ->setCategory('Test result file');
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'informe nomina-'.$anio.$mes);
        $spreadsheet->addSheet($myWorkSheet, 0);
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
        $spreadsheet->removeSheetByIndex($sheetIndex);
        $spreadsheet->getActiveSheet()->setShowGridlines(false);   
        $sheet = $spreadsheet->getActiveSheet();
        $i2 = $mes + 1;
        $meses = [1 => "ENE", 2 => "FEB", 3 => "MAR", 4 => "ABR", 5 => "MAY", 6 => "JUN", 7 => "JUL", 8 => "AGO", 9 => "SEP", 10 => "OCT", 11 => "NOV", 12 => "DIC"];
        $consulta = "SELECT pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.codProy, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy, pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo
            FROM pys_actualizacionproy 
            INNER JOIN pys_asignados ON pys_asignados.idProy = pys_actualizacionproy.idProy 
            INNER JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig 
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_actualizacionproy.idFrente
            INNER JOIN pys_convocatoria ON pys_convocatoria.idConvocatoria = pys_actualizacionproy.idConvocatoria
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            WHERE pys_actualizacionproy.est = '1'
            AND pys_asignados.est = '1'
            AND pys_tiempos.estTiempo = '1'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$anio-01-15' AND '$anio-$i2-15'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            $titulos = ['FR', 'PROG', 'SIGLA', 'PROYECTO', 'CÉLULA', 'SEM', 'PROFESOR', 'FF', 'CECO', 'NOMBRE PEP', 'ELEMENTO PEP', 'NOMBRE', 'POSPRE'];
            for ($y=1; $y <= $mes ; $y++) {
                array_push($titulos,($meses[$y + 1]));
            }
            array_push($titulos,'PROMEDIO');
            array_push($titulos,'HORAS REGISTRADAS');
            $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A3');
            $fila = 4;
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT apellido1, apellido2, nombres
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est = '1'
                    AND pys_personas.est = '1'";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = $datos3['apellido1']." ".$datos3['apellido2']." ".$datos3['nombres'];
                /** Consulta de celula, centro de costos, elementos pep del proyecto */
                $consulta4 = "SELECT pys_celulas.nombreCelula, pys_fuentesfinanciamiento.sigla, pys_centrocostos.ceco, pys_elementospep.nombreElemento, pys_elementospep.codigoElemento
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cruceproypep ON pys_cruceproypep.idProy = pys_actualizacionproy.idProy
                    INNER JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                    INNER JOIN pys_elementospep ON pys_elementospep.idElemento = pys_cruceproypep.idElemento
                    INNER JOIN pys_fuentesfinanciamiento ON pys_fuentesfinanciamiento.idFteFin = pys_actualizacionproy.idFteFin
                    INNER JOIN pys_centrocostos ON pys_centrocostos.idCeco = pys_elementospep.idCeco
                    WHERE pys_actualizacionproy.idProy = '".$datos['idProy']."'
                    AND pys_actualizacionproy.est = '1'
                    AND pys_cruceproypep.estado = '1'
                    AND pys_celulas.estado = '1'
                    AND pys_elementospep.estado = '1'
                    AND pys_fuentesfinanciamiento.estado = '1'
                    AND pys_centrocostos.estado = '1';";
                $resultado4 = mysqli_query($connection, $consulta4);
                $datos4 = mysqli_fetch_array($resultado4);
                $informacion = [$datos['descripcionFrente'], $datos['nombreConvocatoria'], $datos['codProy'], $datos['nombreProy'], $datos4['nombreCelula'], $datos['semAcompanamiento'], $nombreProfesor, $datos4['sigla'], $datos4['ceco'], $datos4['nombreElemento'], $datos4['codigoElemento'], $nombreCompleto, $datos['categoriaCargo']];
                $porcentajeAcum = 0;
                for ($y=1; $y <= $mes; $y++) {
                    $y2 = $y + 1;
                    if ($y == 1) {
                        $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                            FROM pys_periodos 
                            INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                            WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                            AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$anio-$y-15' AND finPeriodo = '$anio-$y2-15';";
                    } else {
                        $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$anio-$y-16' AND finPeriodo = '$anio-$y2-15';";
                    }
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $per1 = $datos5[0];
                    $per2 = $datos5[1];
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    if ($hrsPeriodo == 0) {
                        $hrsPeriodo = 160;
                    }
                    if ($y == 1) {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-08' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    } else if ($y == 12) {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-16' AND '$anio-$y-31'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    } else {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-16' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    }
                    
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $totalMinutos = ($datos2[0] * 60) + $datos2[1]; // Total de minutos registrados en el periodo correspondiente
                        $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                        $porcentaje = ($totalMinutos / $minutosPeriodo) * 100;
                        $porcentajeAcum += $porcentaje;
                        array_push($informacion,round($porcentaje, 0));
                    }
                    //
                }
                $promedio = ($porcentajeAcum/$mes);
                $totalHoras = $totalMinutos / 60;
                array_push($informacion,round($promedio, 0));
                array_push($informacion,number_format($totalHoras, 2));
                $spreadsheet->getActiveSheet()->fromArray($informacion,null,'A'.$fila);
                $fila += 1;
            }
            $col = count($informacion)+64;
            $sheet->setCellValue('A1', 'Informe de Nomina');
        $sheet->mergeCells("A1:".chr($col)."1");
        
            $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(STYLETABLETI);
            $spreadsheet->getActiveSheet()->getStyle('A3:'.chr($col).'3')->applyFromArray(STYLETABLETITLE);    
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(70);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(14);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(33);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);
            $spreadsheet->getActiveSheet()->getStyle('A3:'.chr($col).($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformeNomina.xlsx"');
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


    public static function descargarExcel($anio, $mes) {
        require('../Core/connection.php');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=inf-nomina-$anio$mes.xls");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <html>
                    <head>
                    </head>
                    <body>';
        $i2 = $mes + 1;
        $meses = [1 => "ENE", 2 => "FEB", 3 => "MAR", 4 => "ABR", 5 => "MAY", 6 => "JUN", 7 => "JUL", 8 => "AGO", 9 => "SEP", 10 => "OCT", 11 => "NOV", 12 => "DIC"];
        $consulta = "SELECT pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, pys_actualizacionproy.codProy, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy, pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo
            FROM pys_actualizacionproy 
            INNER JOIN pys_asignados ON pys_asignados.idProy = pys_actualizacionproy.idProy 
            INNER JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig 
            INNER JOIN pys_frentes ON pys_frentes.idFrente = pys_actualizacionproy.idFrente
            INNER JOIN pys_convocatoria ON pys_convocatoria.idConvocatoria = pys_actualizacionproy.idConvocatoria
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            WHERE pys_actualizacionproy.est = '1'
            AND pys_asignados.est = '1'
            AND pys_tiempos.estTiempo = '1'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$anio-01-15' AND '$anio-$i2-15'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        $bgColor = "#C2C2C2";
        if ($registros = mysqli_num_rows($resultado) > 0) {
            echo '  <table border="1">
                        <thead>
                            <tr>
                                <th bgcolor="'.$bgColor.'">FR</th>
                                <th bgcolor="'.$bgColor.'">PROG</th>
                                <th bgcolor="'.$bgColor.'">SIGLA</th>
                                <th bgcolor="'.$bgColor.'" style="width:500px">PROYECTO</th>
                                <th bgcolor="'.$bgColor.'" style="width:100px">CÉLULA</th>
                                <th bgcolor="'.$bgColor.'" style="width:50px;">SEM</th>
                                <th bgcolor="'.$bgColor.'">PROFESOR</th>
                                <th bgcolor="'.$bgColor.'" style="width:70px;">FF</th>
                                <th bgcolor="'.$bgColor.'" style="width:70px;">CECO</th>
                                <th bgcolor="'.$bgColor.'" style="width:350px;">NOMBRE PEP</th>
                                <th bgcolor="'.$bgColor.'">ELEMENTO PEP</th>
                                <th bgcolor="'.$bgColor.'">NOMBRE</th>
                                <th bgcolor="'.$bgColor.'">POSPRE</th>
                                ';
            for ($y=1; $y <= $mes ; $y++) {
                echo '          <th bgcolor="'.$bgColor.'">'.($meses[$y + 1]).'</th>';
            }
            echo '              <th bgcolor="'.$bgColor.'">PROMEDIO</th>
                                <th bgcolor="'.$bgColor.'">HORAS REGISTRADAS</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT apellido1, apellido2, nombres
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est = '1'
                    AND pys_personas.est = '1'";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = $datos3['apellido1']." ".$datos3['apellido2']." ".$datos3['nombres'];
                /** Consulta de celula, centro de costos, elementos pep del proyecto */
                $consulta4 = "SELECT pys_celulas.nombreCelula, pys_fuentesfinanciamiento.sigla, pys_centrocostos.ceco, pys_elementospep.nombreElemento, pys_elementospep.codigoElemento
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cruceproypep ON pys_cruceproypep.idProy = pys_actualizacionproy.idProy
                    INNER JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                    INNER JOIN pys_elementospep ON pys_elementospep.idElemento = pys_cruceproypep.idElemento
                    INNER JOIN pys_fuentesfinanciamiento ON pys_fuentesfinanciamiento.idFteFin = pys_actualizacionproy.idFteFin
                    INNER JOIN pys_centrocostos ON pys_centrocostos.idCeco = pys_elementospep.idCeco
                    WHERE pys_actualizacionproy.idProy = '".$datos['idProy']."'
                    AND pys_actualizacionproy.est = '1'
                    AND pys_cruceproypep.estado = '1'
                    AND pys_celulas.estado = '1'
                    AND pys_elementospep.estado = '1'
                    AND pys_fuentesfinanciamiento.estado = '1'
                    AND pys_centrocostos.estado = '1';";
                $resultado4 = mysqli_query($connection, $consulta4);
                $datos4 = mysqli_fetch_array($resultado4);
                echo '      <tr style="vertical-align:middle;">
                                <td>'.$datos['descripcionFrente'].'</td>
                                <td>'.$datos['nombreConvocatoria'].'</td>
                                <td>'.$datos['codProy'].'</td>
                                <td>'.$datos['nombreProy'].'</td>
                                <td>'.$datos4['nombreCelula'].'</td>
                                <td align="center">'.$datos['semAcompanamiento'].'</td>
                                <td>'.$nombreProfesor.'</td>
                                <td>'.$datos4['sigla'].'</td>
                                <td>'.$datos4['ceco'].'</td>
                                <td>'.$datos4['nombreElemento'].'</td>
                                <td>'.$datos4['codigoElemento'].'</td>
                                <td>'.$nombreCompleto.'</td>
                                <td>'.$datos['categoriaCargo'].'</td>';
                $porcentajeAcum = 0;
                for ($y=1; $y <= $mes; $y++) {
                    $y2 = $y + 1;
                    if ($y == 1) {
                        $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                            FROM pys_periodos 
                            INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                            WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                            AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$anio-$y-15' AND finPeriodo = '$anio-$y2-15';";
                    } else {
                        $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$anio-$y-16' AND finPeriodo = '$anio-$y2-15';";
                    }
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $per1 = $datos5[0];
                    $per2 = $datos5[1];
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    if ($hrsPeriodo == 0) {
                        $hrsPeriodo = 160;
                    }
                    if ($y == 1) {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-08' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    } else if ($y == 12) {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-16' AND '$anio-$y-31'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    } else {
                        $consulta2 = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                            WHERE pys_asignados.idPersona = '".$datos['idPersona']."' 
                            AND pys_asignados.idProy = '".$datos['idProy']."'
                            AND pys_tiempos.fechTiempo BETWEEN '$anio-$y-16' AND '$anio-$y2-15'
                            AND pys_tiempos.estTiempo = '1'
                            AND pys_asignados.est = '1';";
                        $resultado2 = mysqli_query($connection, $consulta2);
                    }
                    
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $totalMinutos = ($datos2[0] * 60) + $datos2[1]; // Total de minutos registrados en el periodo correspondiente
                        $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                        $porcentaje = ($totalMinutos / $minutosPeriodo) * 100;
                        $porcentajeAcum += $porcentaje;
                        echo '  <td align="center" style="width:50px;">'.round($porcentaje, 0).' %</td>';
                    }
                    //
                }
                $promedio = ($porcentajeAcum/$mes);
                $totalHoras = $totalMinutos / 60;
                echo '          <td align="center" style="width:100px;">'.round($promedio, 0).' %</td>
                                <td align="center" style="width:100px;">'.number_format($totalHoras, 2, ',', '.').'</td>';
                
                echo '      </tr>';
            }
            echo '      </tbody>
                    </table>
                </body>
                </html>';
        }
        mysqli_close($connection);
    }

}

?>