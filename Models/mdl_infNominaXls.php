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
            GROUP BY YEAR(fechTiempo)
            ORDER BY YEAR(fechTiempo) DESC;";
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

    public static function informePreliminarOld ($anio, $mes) {
        require('../Core/connection.php');
        $i2 = $mes + 1;
        $anio2 = $anio;
        if($i2 == 13){
            $i2 = 1;
            $anio2 = $anio+1;
        }
        $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$mes-01' AND '$anio-$mes-30') AND (`finPeriodo` BETWEEN '$anio2-$i2-01' AND '$anio2-$i2-30');";
        $resultadoPer = mysqli_query($connection, $consultaPer);
        $datosPer = mysqli_fetch_array($resultadoPer);
        $inicioPeriodo =$datosPer['inicioPeriodo'];
        $finPeriodo =$datosPer['finPeriodo'];
        $consultaPerIniA = "SELECT inicioPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-01-01' AND '$anio-1-30');";
        $resultadoPerIniA = mysqli_query($connection, $consultaPerIniA);
        $datosPerA = mysqli_fetch_array($resultadoPerIniA);
        $inicioPeriodoA =$datosPerA['inicioPeriodo'];
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
            AND pys_asignados.est <> '0'
            AND pys_tiempos.estTiempo = '1'
            AND pys_tiempos.idFase <> 'FS0012'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$inicioPeriodoA' AND '$finPeriodo'
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
                echo '          <th>'.$meses[$y+1].' REC</th>';
                echo '          <th>'.$meses[$y+1].' GES</th>';
                echo '          <th>'.$meses[$y+1].' DIS</th>';
                echo '          <th>'.$meses[$y+1].' MON</th>';
                echo '          <th class="teal white-text">'.$meses[$y+1].' TOT</th>';
            }
            echo '              <th>PROMEDIO</th>
                                <th>HORAS REGISTRADAS</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT CONCAT (apellido1, ' ', apellido2, ' ', nombres) AS 'profesor' 
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est <> '0'
                    AND pys_personas.est = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = (!empty($datos3['profesor'])) ? $datos3['profesor'] : '';
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
                    $anio2 = $anio;
                    if($i2 == 13){
                        $i2 = 1;
                        $anio2 = $anio+1;
                    }
                    $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$y-01' AND '$anio-$y-30') AND (`finPeriodo` BETWEEN '$anio2-$y2-01' AND '$anio2-$y2-30');";
                    $resultadoPer = mysqli_query($connection, $consultaPer);
                    $datosPer = mysqli_fetch_array($resultadoPer);
                    $inicioPeriodo =$datosPer['inicioPeriodo'];
                    $finPeriodo =$datosPer['finPeriodo'];
                    $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$inicioPeriodo' AND finPeriodo = '$finPeriodo';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    $hrsPeriodo = ($hrsPeriodo == 0) ? 160 : $hrsPeriodo;
                    $consulta2 = "SELECT pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_servicios.idEqu
                        FROM pys_tiempos 
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig AND pys_asignados.est <> '0' AND pys_asignados.idPersona = '".$datos['idPersona']."' AND pys_asignados.idProy = '".$datos['idProy']."'
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1'
                        INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                        WHERE pys_tiempos.fechTiempo BETWEEN '".$inicioPeriodo."' AND '".$finPeriodo."' AND pys_tiempos.estTiempo = '1' AND pys_tiempos.idFase <> 'FS0012' ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $minutos = $minutosRealizacion = $minutosDiseno = $minutosMontaje = $minutosGestoria = 0;
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $minutos += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                        switch ($datos2['idEqu']) {
                            case 'EQU001':
                                $minutosRealizacion += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU002':
                                $minutosDiseno += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU003':
                                $minutosMontaje += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU004':
                                $minutosGestoria += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                        }
                    }
                    $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                    $porcentaje = ($minutos / $minutosPeriodo) * 100;
                    $porcentajeRecursos = ($minutosRealizacion / $minutosPeriodo) * 100;
                    $porcentajeGestoria = ($minutosGestoria / $minutosPeriodo) * 100;
                    $porcentajeDiseno = ($minutosDiseno / $minutosPeriodo) * 100;
                    $porcentajeMontaje = ($minutosMontaje / $minutosPeriodo) * 100;
                    $porcentajeAcum += $porcentaje;
                    echo '  <td class="center-align">'.round($porcentajeRecursos, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeGestoria, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeDiseno, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeMontaje, 0).' %</td>';
                    echo '  <td class="center-align teal white-text">'.round($porcentaje, 0).' %</td>';

                }
                $promedio = ($porcentajeAcum/$mes);
                echo '          <td class="center-align">'.round($promedio, 0).' %</td>';
                echo '          <td>'.($minutos/60).'</td>
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

    public static function informePreliminar ($anio, $mes) {
        require('../Core/connection.php');
        $i2 = $mes + 1;
        $anio2 = $anio;
        if($i2 == 13){
            $i2 = 1;
            $anio2 = $anio+1;
        }
        $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$mes-01' AND '$anio-$mes-30') AND (`finPeriodo` BETWEEN '$anio2-$i2-01' AND '$anio2-$i2-30');";
        $resultadoPer = mysqli_query($connection, $consultaPer);
        $datosPer = mysqli_fetch_array($resultadoPer);
        $inicioPeriodo =$datosPer['inicioPeriodo'];
        $finPeriodo =$datosPer['finPeriodo'];
        $consultaPerIniA = "SELECT inicioPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-01-01' AND '$anio-1-30');";
        $resultadoPerIniA = mysqli_query($connection, $consultaPerIniA);
        $datosPerA = mysqli_fetch_array($resultadoPerIniA);
        $inicioPeriodoA =$datosPerA['inicioPeriodo'];
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
            AND pys_asignados.est <> '0'
            AND pys_tiempos.estTiempo = '1'
            AND pys_tiempos.idFase <> 'FS0012'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$inicioPeriodoA' AND '$finPeriodo'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        if ($registros = mysqli_num_rows($resultado) > 0) {
            echo '  <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>PROGRAMA / CONVOCATORIA</th>
                                <th>SIGLA</th>
                                <th>PROYECTO</th>
                                <th>NOMBRE</th>';
            for ($y=1; $y <= $mes ; $y++) {
                echo '          <th>'.$meses[$y+1].' REC</th>';
                echo '          <th>'.$meses[$y+1].' GES</th>';
                echo '          <th>'.$meses[$y+1].' DIS</th>';
                echo '          <th>'.$meses[$y+1].' MON</th>';
                echo '          <th class="teal white-text">'.$meses[$y+1].' TOT</th>';
            }
            echo '              <th>PROMEDIO</th>
                                <th>HORAS REGISTRADAS</th>
                            </tr>
                        </thead>
                        <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT CONCAT (apellido1, ' ', apellido2, ' ', nombres) AS 'profesor' 
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est <> '0'
                    AND pys_personas.est = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = (!empty($datos3['profesor'])) ? $datos3['profesor'] : '';
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
                                <td>'.$datos['nombreConvocatoria'].'</td>
                                <td>'.$datos['codProy'].'</td>
                                <td>'.$datos['nombreProy'].'</td>
                                <td>'.$nombreCompleto.'</td>';
                $porcentajeAcum = 0;
                for ($y=1; $y <= $mes; $y++) {
                    $y2 = $y + 1;
                    $anio2 = $anio;
                    if($i2 == 13){
                        $i2 = 1;
                        $anio2 = $anio+1;
                    }
                    $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$y-01' AND '$anio-$y-30') AND (`finPeriodo` BETWEEN '$anio2-$y2-01' AND '$anio2-$y2-30');";
                    $resultadoPer = mysqli_query($connection, $consultaPer);
                    $datosPer = mysqli_fetch_array($resultadoPer);
                    $inicioPeriodo =$datosPer['inicioPeriodo'];
                    $finPeriodo =$datosPer['finPeriodo'];
                    $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$inicioPeriodo' AND finPeriodo = '$finPeriodo';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    $hrsPeriodo = ($hrsPeriodo == 0) ? 160 : $hrsPeriodo;
                    $consulta2 = "SELECT pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_servicios.idEqu
                        FROM pys_tiempos 
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig AND pys_asignados.est <> '0' AND pys_asignados.idPersona = '".$datos['idPersona']."' AND pys_asignados.idProy = '".$datos['idProy']."'
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1'
                        INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                        WHERE pys_tiempos.fechTiempo BETWEEN '".$inicioPeriodo."' AND '".$finPeriodo."' AND pys_tiempos.estTiempo = '1' AND pys_tiempos.idFase <> 'FS0012' ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $minutos = $minutosRealizacion = $minutosDiseno = $minutosMontaje = $minutosGestoria = 0;
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $minutos += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                        switch ($datos2['idEqu']) {
                            case 'EQU001':
                                $minutosRealizacion += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU002':
                                $minutosDiseno += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU003':
                                $minutosMontaje += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU004':
                                $minutosGestoria += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                        }
                    }
                    $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                    $porcentaje = ($minutos / $minutosPeriodo) * 100;
                    $porcentajeRecursos = ($minutosRealizacion / $minutosPeriodo) * 100;
                    $porcentajeGestoria = ($minutosGestoria / $minutosPeriodo) * 100;
                    $porcentajeDiseno = ($minutosDiseno / $minutosPeriodo) * 100;
                    $porcentajeMontaje = ($minutosMontaje / $minutosPeriodo) * 100;
                    $porcentajeAcum += $porcentaje;
                    echo '  <td class="center-align">'.round($porcentajeRecursos, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeGestoria, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeDiseno, 0).' %</td>';
                    echo '  <td class="center-align">'.round($porcentajeMontaje, 0).' %</td>';
                    echo '  <td class="center-align teal white-text">'.round($porcentaje, 0).' %</td>';

                }
                $promedio = ($porcentajeAcum/$mes);
                echo '          <td class="center-align">'.round($promedio, 0).' %</td>';
                echo '          <td>'.($minutos/60).'</td>
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

    public static function descarga_old($anio, $mes) {
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
        $anio2 = $anio;
        if($i2 == 13){
            $i2 = 1;
            $anio2 = $anio+1;
        }
        $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$mes-01' AND '$anio-$mes-30') AND (`finPeriodo` BETWEEN '$anio2-$i2-01' AND '$anio2-$i2-30');";
        $resultadoPer = mysqli_query($connection, $consultaPer);
        $datosPer = mysqli_fetch_array($resultadoPer);
        $inicioPeriodo =$datosPer['inicioPeriodo'];
        $finPeriodo =$datosPer['finPeriodo'];
        $consultaPerIniA = "SELECT inicioPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-01-01' AND '$anio-1-30');";
        $resultadoPerIniA = mysqli_query($connection, $consultaPerIniA);
        $datosPerA = mysqli_fetch_array($resultadoPerIniA);
        $inicioPeriodoA =$datosPerA['inicioPeriodo'];
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
            AND pys_asignados.est <> '0'
            AND pys_tiempos.estTiempo = '1'
            AND pys_tiempos.idFase <> 'FS0012'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$inicioPeriodoA' AND '$finPeriodo'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            $titulos = ['FR', 'PROG', 'SIGLA', 'PROYECTO', 'CÉLULA', 'SEM', 'PROFESOR', 'FF', 'CECO', 'NOMBRE PEP', 'ELEMENTO PEP', 'NOMBRE', 'POSPRE'];
            for ($y=1; $y <= $mes ; $y++) {
                array_push($titulos,($meses[$y + 1]).' REC');
                array_push($titulos,($meses[$y + 1]).' GES');
                array_push($titulos,($meses[$y + 1]).' DIS');
                array_push($titulos,($meses[$y + 1]).' MON');
                array_push($titulos,($meses[$y + 1]).' TOT');
            }
            array_push($titulos,'PROMEDIO');
            array_push($titulos,'HORAS REGISTRADAS');
            $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A3');
            $fila = 4;
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT CONCAT (apellido1, ' ', apellido2, ' ', nombres) AS 'profesor'
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est <> '0'
                    AND pys_personas.est = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = (!empty($datos3['profesor'])) ? $datos3['profesor'] : '';
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
                    $anio2 = $anio;
                    if($i2 == 13){
                        $i2 = 1;
                        $anio2 = $anio+1;
                    }
                    $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$y-01' AND '$anio-$y-30') AND (`finPeriodo` BETWEEN '$anio2-$y2-01' AND '$anio2-$y2-30');";
                    $resultadoPer = mysqli_query($connection, $consultaPer);
                    $datosPer = mysqli_fetch_array($resultadoPer);
                    $inicioPeriodo =$datosPer['inicioPeriodo'];
                    $finPeriodo =$datosPer['finPeriodo'];
                    $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$inicioPeriodo' AND finPeriodo = '$finPeriodo';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $per1 = $datos5[0];
                    $per2 = $datos5[1];
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    $hrsPeriodo = ($hrsPeriodo == 0) ? 160 : $hrsPeriodo;
                    $consulta2 = "SELECT pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_servicios.idEqu
                        FROM pys_tiempos 
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig AND pys_asignados.est <> '0' AND pys_asignados.idPersona = '".$datos['idPersona']."' AND pys_asignados.idProy = '".$datos['idProy']."'
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1'
                        INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                        WHERE pys_tiempos.fechTiempo BETWEEN '".$inicioPeriodo."' AND '".$finPeriodo."' AND pys_tiempos.estTiempo = '1' AND pys_tiempos.idFase <> 'FS0012' ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $minutos = $minutosRealizacion = $minutosDiseno = $minutosMontaje = $minutosGestoria = 0;
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $minutos += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                        switch ($datos2['idEqu']) {
                            case 'EQU001':
                                $minutosRealizacion += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU002':
                                $minutosDiseno += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU003':
                                $minutosMontaje += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU004':
                                $minutosGestoria += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                        }
                    }
                    $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                    $porcentaje = ($minutos / $minutosPeriodo);
                    $porcentajeRecursos = ($minutosRealizacion / $minutosPeriodo);
                    $porcentajeGestoria = ($minutosGestoria / $minutosPeriodo);
                    $porcentajeDiseno = ($minutosDiseno / $minutosPeriodo);
                    $porcentajeMontaje = ($minutosMontaje / $minutosPeriodo);
                    $porcentajeAcum += $porcentaje;
                    array_push($informacion, number_format($porcentajeRecursos,2));
                    array_push($informacion, number_format($porcentajeGestoria,2));
                    array_push($informacion, number_format($porcentajeDiseno,2));
                    array_push($informacion, number_format($porcentajeMontaje,2));
                    array_push($informacion, number_format($porcentaje,2));
                }
                $promedio = (number_format($porcentajeAcum/$mes,2));
                $totalHoras = $minutos / 60;
                array_push($informacion,$promedio);
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
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(67);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(14);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(33);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(33);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);
            for ($i = 13; $i < count($informacion)-2; $i++ ){
                $spreadsheet->getActiveSheet()->getColumnDimension(chr($i+65))->setWidth(6);
            }
            $spreadsheet->getActiveSheet()->getColumnDimension(chr($col-1))->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension(chr($col))->setWidth(13);
            $spreadsheet->getActiveSheet()->getStyle('A3:'.chr($col).($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
            $spreadsheet->getActiveSheet()->getStyle('N2:'.chr($col-1).$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
                                
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformeNomina '.gmdate(' d M Y ').'.xlsx"');
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
        $anio2 = $anio;
        if($i2 == 13){
            $i2 = 1;
            $anio2 = $anio+1;
        }
        $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$mes-01' AND '$anio-$mes-30') AND (`finPeriodo` BETWEEN '$anio2-$i2-01' AND '$anio2-$i2-30');";
        $resultadoPer = mysqli_query($connection, $consultaPer);
        $datosPer = mysqli_fetch_array($resultadoPer);
        $inicioPeriodo =$datosPer['inicioPeriodo'];
        $finPeriodo =$datosPer['finPeriodo'];
        $consultaPerIniA = "SELECT inicioPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-01-01' AND '$anio-1-30');";
        $resultadoPerIniA = mysqli_query($connection, $consultaPerIniA);
        $datosPerA = mysqli_fetch_array($resultadoPerIniA);
        $inicioPeriodoA =$datosPerA['inicioPeriodo'];
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
            AND pys_asignados.est <> '0'
            AND pys_tiempos.estTiempo = '1'
            AND pys_tiempos.idFase <> 'FS0012'
            AND pys_frentes.est = '1'
            AND pys_convocatoria.est = '1'
            AND pys_personas.est = '1'
            AND pys_tiempos.fechTiempo BETWEEN '$inicioPeriodoA' AND '$finPeriodo'
            GROUP BY pys_actualizacionproy.codProy, pys_frentes.descripcionFrente, pys_convocatoria.nombreConvocatoria, 
            pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actualizacionproy.nombreProy, pys_personas.idPersona, pys_actualizacionproy.idProy,
            pys_actualizacionproy.semAcompanamiento, pys_personas.categoriaCargo;";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0) {
            $titulos = ['PROGRAMA / CONVOCATORIA', 'SIGLA', 'PROYECTO', 'NOMBRE'];
            for ($y=1; $y <= $mes ; $y++) {
                array_push($titulos,($meses[$y + 1]).' REC');
                array_push($titulos,($meses[$y + 1]).' GES');
                array_push($titulos,($meses[$y + 1]).' DIS');
                array_push($titulos,($meses[$y + 1]).' MON');
                array_push($titulos,($meses[$y + 1]).' TOT');
            }
            array_push($titulos,'PROMEDIO');
            array_push($titulos,'HORAS REGISTRADAS');
            $spreadsheet->getActiveSheet()->fromArray($titulos,null,'A3');
            $fila = 4;
            while ($datos = mysqli_fetch_array($resultado)) {
                $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                /** Consulta del nombre del profesor asignado al proyecto "ROL018" */
                $consulta3 = "SELECT CONCAT (apellido1, ' ', apellido2, ' ', nombres) AS 'profesor'
                    FROM pys_asignados 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idRol = 'ROL018' AND pys_asignados.idProy = '".$datos['idProy']."'
                    AND pys_asignados.est <> '0'
                    AND pys_personas.est = '1';";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $nombreProfesor = (!empty($datos3['profesor'])) ? $datos3['profesor'] : '';
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
                $informacion = [$datos['nombreConvocatoria'], $datos['codProy'], $datos['nombreProy'], $nombreCompleto];
                $porcentajeAcum = 0;
                for ($y=1; $y <= $mes; $y++) {
                    $y2 = $y + 1;
                    $anio2 = $anio;
                    if($i2 == 13){
                        $i2 = 1;
                        $anio2 = $anio+1;
                    }
                    $consultaPer = "SELECT inicioPeriodo, finPeriodo FROM `pys_periodos` WHERE (`inicioPeriodo` BETWEEN '$anio-$y-01' AND '$anio-$y-30') AND (`finPeriodo` BETWEEN '$anio2-$y2-01' AND '$anio2-$y2-30');";
                    $resultadoPer = mysqli_query($connection, $consultaPer);
                    $datosPer = mysqli_fetch_array($resultadoPer);
                    $inicioPeriodo =$datosPer['inicioPeriodo'];
                    $finPeriodo =$datosPer['finPeriodo'];
                    $consulta5 = "SELECT pys_periodos.diasSegmento1, pys_periodos.diasSegmento2, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                        FROM pys_periodos 
                        INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo 
                        WHERE pys_periodos.estadoPeriodo = '1' AND pys_dedicaciones.estadoDedicacion = '1' 
                        AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."' AND inicioPeriodo = '$inicioPeriodo' AND finPeriodo = '$finPeriodo';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);
                    $per1 = $datos5[0];
                    $per2 = $datos5[1];
                    $diasPer1 = ($datos5['diasSegmento1'] * $datos5['porcentajeDedicacion1'] / 100) ;
                    $diasPer2 = ($datos5['diasSegmento2'] * $datos5['porcentajeDedicacion2'] / 100) ;
                    $hrsPeriodo = ($diasPer1 + $diasPer2) * 8;
                    $hrsPeriodo = ($hrsPeriodo == 0) ? 160 : $hrsPeriodo;
                    $consulta2 = "SELECT pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_servicios.idEqu
                        FROM pys_tiempos 
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig AND pys_asignados.est <> '0' AND pys_asignados.idPersona = '".$datos['idPersona']."' AND pys_asignados.idProy = '".$datos['idProy']."'
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol AND pys_actsolicitudes.est = '1'
                        INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                        WHERE pys_tiempos.fechTiempo BETWEEN '".$inicioPeriodo."' AND '".$finPeriodo."' AND pys_tiempos.estTiempo = '1' AND pys_tiempos.idFase <> 'FS0012' ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $minutos = $minutosRealizacion = $minutosDiseno = $minutosMontaje = $minutosGestoria = 0;
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $minutos += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                        switch ($datos2['idEqu']) {
                            case 'EQU001':
                                $minutosRealizacion += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU002':
                                $minutosDiseno += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU003':
                                $minutosMontaje += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                            case 'EQU004':
                                $minutosGestoria += ($datos2['horaTiempo'] * 60) + $datos2['minTiempo'];
                                break;
                        }
                    }
                    $minutosPeriodo = $hrsPeriodo * 60; // Total de minutos a trabajar en un periodo (160 horas)
                    $porcentaje = ($minutos / $minutosPeriodo);
                    $porcentajeRecursos = ($minutosRealizacion / $minutosPeriodo);
                    $porcentajeGestoria = ($minutosGestoria / $minutosPeriodo);
                    $porcentajeDiseno = ($minutosDiseno / $minutosPeriodo);
                    $porcentajeMontaje = ($minutosMontaje / $minutosPeriodo);
                    $porcentajeAcum += $porcentaje;
                    array_push($informacion, number_format($porcentajeRecursos,2));
                    array_push($informacion, number_format($porcentajeGestoria,2));
                    array_push($informacion, number_format($porcentajeDiseno,2));
                    array_push($informacion, number_format($porcentajeMontaje,2));
                    array_push($informacion, number_format($porcentaje,2));
                }
                $promedio = (number_format($porcentajeAcum/$mes,2));
                $totalHoras = $minutos / 60;
                array_push($informacion,$promedio);
                array_push($informacion,number_format($totalHoras, 2));
                $spreadsheet->getActiveSheet()->fromArray($informacion,null,'A'.$fila);
                $fila += 1;
            }
            $col = count($informacion)+64;
            $sheet->setCellValue('A1', 'Informe de Nomina');
            $sheet->mergeCells("A1:".chr($col)."1");
        
            $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray(STYLETABLETI);
            $spreadsheet->getActiveSheet()->getStyle('A3:'.chr($col).'3')->applyFromArray(STYLETABLETITLE);    
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(14);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(67);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(33);
            for ($i = 4; $i < count($informacion)-2; $i++ ){
                $spreadsheet->getActiveSheet()->getColumnDimension(chr($i+65))->setWidth(6);
            }
            $spreadsheet->getActiveSheet()->getColumnDimension(chr($col-1))->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension(chr($col))->setWidth(13);
            $spreadsheet->getActiveSheet()->getStyle('A3:'.chr($col).($fila-1))->getBorders()->applyFromArray(STYLEBORDER);
            $spreadsheet->getActiveSheet()->getStyle('E4:'.chr($col-1).$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
                                
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformeNomina '.gmdate(' d M Y ').'.xlsx"');
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