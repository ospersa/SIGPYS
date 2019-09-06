<?php

    class InformePlaneacion {

        public static function informePeriodo($periodo){
            require('../Core/connection.php');
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=inf-planeacion-periodo.xls");
            header('Cache-Control: max-age=0');
            header("Pragma: no-cache");
            header("Expires: 0");
            $consulta = "SELECT * FROM pys_periodos WHERE idPeriodo = $periodo;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $fecIni = $datos['inicioPeriodo'];
            $fecFin = $datos['finPeriodo'];
            $diasSeg1 = $datos['diasSegmento1'];
            $diasSeg2 = $datos['diasSegmento2'];
            echo '
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <html>
                <head>
                    <title>INFORME PLANEACIÓN POR PERIODO</title>
                </head>
                <body>
                <h1 align="center">Informe Planeación por Periodo</h1>
                <h3>Desde: '.$fecIni.'. Hasta: '.$fecFin.'.</h3>
                <table border="1">
                    <thead>
                        <tr bgcolor="#e0f2f1">
                            <th rowspan="3">Persona</th>
                            <th colspan="8">Asignaciones</th>
                        </tr>
                        <tr bgcolor="#e0f2f1">
                            <th rowspan="2">Cod. Proyecto</th>
                            <th rowspan="2">Proyecto</th>
                            <th rowspan="2">Prod. / Servicio</th>
                            <th rowspan="2">Detalle Solicitud Prod. / Servicio</th>
                            <th colspan="2">Planeación</th>
                            <th rowspan="2">Porcentaje</th>
                            <th rowspan="2">Observación</th>
                        </tr>
                        <tr>
                            <th>Horas</th>
                            <th>Minutos</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            $consulta2 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_personas.idPersona, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                FROM pys_asignaciones 
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado 
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona 
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion 
                WHERE pys_dedicaciones.periodo_IdPeriodo = '$periodo' 
                GROUP BY apellido1, apellido2, nombres, idPersona, porcentajeDedicacion1, porcentajeDedicacion2 
                ORDER BY apellido1;";
            $resultado2 = mysqli_query($connection, $consulta2);
            
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                $totMin = (((($diasSeg1 * 8) * 60) * $datos2['porcentajeDedicacion1']) / 100) + (((($diasSeg2 * 8) * 60) * $datos2['porcentajeDedicacion2']) / 100);
                $totHrs = floor($totMin / 60);
                $totMin1 = (($totMin / 60) - $totHrs) * 60;
                $nombreCompleto = $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'];
                $consulta3 = "SELECT pys_asignaciones.horasInvertir, pys_asignaciones.minutosInvertir, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_asignaciones.observacion
                    FROM pys_asignaciones
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                    INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_actualizacionproy.est = 1
                    AND pys_actsolicitudes.est = 1
                    AND pys_asignados.idPersona = '".$datos2['idPersona']."'
                    ORDER BY pys_actualizacionproy.idProy;";
                $resultado3 = mysqli_query($connection, $consulta3);
                echo '<table border="1">';
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    $acumHrs += $datos3['horasInvertir'];
                    $acumMin += $datos3['minutosInvertir'];
                    $totMinPS = ($datos3['horasInvertir'] * 60) + $datos3['minutosInvertir'];
                    $porcentaje = ($totMinPS / $totMin) * 100;
                    $acumPorcen += $porcentaje;
                    echo '
                    <tr>
                        <td style="width: auto; vertical-align: middle; text-align: left;">'.$nombreCompleto.'</td>
                        <td style="width: auto; vertical-align: middle; text-align: left;">'.$datos3['codProy'].'</td>
                        <td style="width: auto; vertical-align: middle; text-align: left;">'.$datos3['nombreProy'].'</td>
                        <td style="width: auto; vertical-align: middle; text-align: center;">P'.$datos3['idSol'].'</td>
                        <td style="width: auto; vertical-align: middle;">'.$datos3['ObservacionAct'].'</td>
                        <td style="width: auto; vertical-align: middle; text-align: center;">'.$datos3['horasInvertir'].'</td>
                        <td style="width: auto; vertical-align: middle; text-align: center;">'.$datos3['minutosInvertir'].'</td>
                        <td style="width: auto; vertical-align: middle; text-align: center;">'.round($porcentaje, 2).'%</td>
                        <td style="width: auto; vertical-align: middle; text-align: center;">'.$datos3['observacion'].'</td>
                    </tr>';
                }
                $totMin2 = ($acumHrs * 60) + $acumMin;
                if ($acumMin >= 60) {
                    $acumHrs = floor($totMin2 / 60);
                    $acumMin = round(((($totMin2 / 60) - $acumHrs) * 60), 0);
                }
                $tiemDisp = ($totMin - $totMin2) / 60;
                $minDisp = round((($tiemDisp - floor($tiemDisp)) * 60), 0);
                $porcenDisp = ABS(round(((($totMin2 / $totMin) - 1) * 100), 2));
                echo '
                    <tr>
                        <td bgcolor="C4C4C4" style="vertical-align:middle; font-weight: bold;" rowspan="3" colspan="4">Distribución tiempos</td>
                        <td bgcolor="C4C4C4" align="right"><strong>Tiempo total planeado:</strong></td>
                        <td bgcolor="C4C4C4" align="center"><strong>'.$acumHrs.'</strong></td>
                        <td bgcolor="C4C4C4" align="center"><strong>'.$acumMin.'</strong></td>
                        <td bgcolor="C4C4C4" align="center"><strong>'.round($acumPorcen, 2).'%</strong></td>
                        <td bgcolor="C4C4C4" align="center" rowspan="3"></td>
                    </tr>
                    <tr>
                        <td bgcolor="C2C2C2" align="right"><strong>Tiempo a dedicar en el periodo:</strong></td>
                        <td bgcolor="C2C2C2" align="center"><strong>'.$totHrs.'</strong></td>
                        <td bgcolor="C2C2C2" align="center"><strong>'.$totMin1.'</strong></td>
                        <td bgcolor="C2C2C2" align="center"></td>
                    </tr>
                    <tr>
                        <td bgcolor="C2C2C2" align="right"><strong>Tiempo disponible para asignar:</strong></td>
                        <td bgcolor="C2C2C2" align="center"><strong>'.floor($tiemDisp).'</strong></td>
                        <td bgcolor="C2C2C2" align="center"><strong>'.$minDisp.'</strong></td>
                        <td bgcolor="C2C2C2" align="center"><strong>'.$porcenDisp.'%</strong></td>
                    </tr>';
                $acumHrs = 0;
                $acumMin = 0;
                $acumPorcen = 0;
            }
            echo '</tbody>
            </table>';
            mysqli_close($connection);
        }

        /*public static function informePeriodoPersona($periodo, $persona) {
            require('../Core/connection.php');
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=inf-planeacion-persona.xls");
            header("Cache-Control: max-age=0");
            header("Pragma: no-cache");
            header("Expires: 0");
            $consulta = "SELECT apellido1, apellido2, nombres, inicioPeriodo, finPeriodo, idPersona, diasSegmento1, diasSegmento2, porcentajeDedicacion1, porcentajeDedicacion2 FROM pys_periodos
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona
                WHERE pys_periodos.idPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '$persona';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $fecIni = $datos['inicioPeriodo'];
            $fecFin = $datos['finPeriodo'];
            $persona = $datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'];
            $totMin = (((($datos['diasSegmento1'] * 8) * 60) * $datos['porcentajeDedicacion1']) / 100) + (((($datos['diasSegmento2'] * 8) * 60) * $datos['porcentajeDedicacion2']) / 100);
            echo '
                <meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
                <html>
                <head>
                    <title>INFORME PLANEACION POR PERSONA</title>
                </head>
                <body>
                <h1 align="center">Informe Planeaci&oacute;n por Persona</h1>
                <h4>Persona: '.$persona.'</h4>
                <h4>Desde: '.$fecIni.'. Hasta: '.$fecFin.'.</h4>
                <table border="1">
                    <thead>
                        <tr bgcolor="#e0f2f1">
                            <th colspan="6">Asignaciones</th>
                        </tr>
                        <tr bgcolor="#e0f2f1">
                            <th rowspan="2">Proyecto</th>
                            <th rowspan="2">Prod. / Servicio</th>
                            <th rowspan="2">Solicitud</th>
                            <th colspan="2">Planeaci&oacute;n</th>
                            <th rowspan="2">Porcentaje</th>
                        </tr>
                        <tr>
                            <th>Horas</th>
                            <th>Minutos</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            $consulta2 = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy  FROM pys_asignaciones
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."'
                AND pys_actsolicitudes.est = 1
                AND pys_actualizacionproy.est = 1
                GROUP BY codProy, nombreProy, idProy;";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                echo '<tr>
                    <td style="vertical-align: middle; text-align: left;"><strong>'.$datos2['codProy'].'</strong> - '.$datos2['nombreProy'].'</td>
                    <td colspan="5">';
                echo '<table border="1">';
                $consulta3 = "SELECT * FROM pys_asignaciones
                    INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_actsolicitudes.est = 1
                    AND pys_asignados.idProy = '".$datos2['idProy']."'
                    AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."';";
                $resultado3 = mysqli_query($connection, $consulta3);
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    $acumHrs += $datos3['horasInvertir'];
                    $acumMin += $datos3['minutosInvertir'];
                    $totMinPS = ($datos3['horasInvertir'] * 60) + $datos3['minutosInvertir'];
                    $porcentaje = ($totMinPS / $totMin) * 100;
                    $acumPorcen += $porcentaje;
                    echo '
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">P'.$datos3['idSol'].'</td>
                        <td style="vertical-align: middle;">'.$datos3['ObservacionAct'].'</td>
                        <td style="vertical-align: middle; text-align: center;">'.$datos3['horasInvertir'].'</td>
                        <td style="vertical-align: middle; text-align: center;">'.$datos3['minutosInvertir'].'</td>
                        <td style="vertical-align: middle; text-align: center;">'.round($porcentaje, 2).'%</td>
                    </tr>
                    ';
                }
                echo '</table>
                </tr>';
                if ($acumMin >= 60) {
                    $totMin2 = ($acumHrs * 60) + $acumMin;
                    $acumHrs = floor($totMin2 / 60);
                    $acumMin = round(((($totMin2 / 60) - $acumHrs) * 60), 0);
                }
            }
            echo '<tr>
                    <td bgcolor="C4C4C4" colspan="3" align="right"><strong>Tiempo total asignado:</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.$acumHrs.'</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.$acumMin.'</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.round($acumPorcen, 2).'%</strong></td>
                </tr>';
            echo '</tbody>
                </table>';
            mysqli_close($connection);

            *** CAMBIO SOLICITADO POR MARIELA 28/01/2018 ****
            ** Archivo de Excel sin combinaciones de celdas y código y nombre de proyecto separados

        }*/

        public static function informePeriodoPersona($periodo, $persona) {
            require('../Core/connection.php');
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=inf-planeacion-persona.xls");
            header("Cache-Control: max-age=0");
            header("Pragma: no-cache");
            header("Expires: 0");
            $consulta = "SELECT apellido1, apellido2, nombres, inicioPeriodo, finPeriodo, idPersona, diasSegmento1, diasSegmento2, porcentajeDedicacion1, porcentajeDedicacion2 
                FROM pys_periodos
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona
                WHERE pys_periodos.idPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '$persona';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $fecIni = $datos['inicioPeriodo'];
            $fecFin = $datos['finPeriodo'];
            $persona = utf8_encode($datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres']);
            $totMin = (((($datos['diasSegmento1'] * 8) * 60) * $datos['porcentajeDedicacion1']) / 100) + (((($datos['diasSegmento2'] * 8) * 60) * $datos['porcentajeDedicacion2']) / 100);
            $totHrs = floor($totMin / 60);
            $totMin1 = (($totMin / 60) - $totHrs) * 60;
            echo '
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <html>
                <head>
                    <title>INFORME PLANEACIÓN POR PERSONA</title>
                </head>
                <body>
                <h1 align="center">Informe planeación por persona</h1>
                <h4>Persona: '.$persona.'</h4>
                <h4>Desde: '.$fecIni.'. Hasta: '.$fecFin.'.</h4>
                <table border="1">
                    <thead>
                        <tr bgcolor="#e0f2f1">
                            <th colspan="8">Asignaciones</th>
                        </tr>
                        <tr bgcolor="#e0f2f1">
                            <th rowspan="2">Cod. Proyecto</th>
                            <th rowspan="2">Proyecto</th>
                            <th rowspan="2">Prod. / Servicio</th>
                            <th rowspan="2">Solicitud</th>
                            <th colspan="2">Planeación</th>
                            <th rowspan="2">Porcentaje</th>
                            <th rowspan="2">Observación</th>
                        </tr>
                        <tr>
                            <th>Horas</th>
                            <th>Minutos</th>
                        </tr>
                    </thead>
                    <tbody>';
            $consulta2 = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy  
                FROM pys_asignaciones
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."'
                AND pys_actsolicitudes.est = 1
                AND pys_actualizacionproy.est = 1
                GROUP BY codProy, nombreProy, idProy;";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                $consulta3 = "SELECT pys_asignaciones.horasInvertir, pys_asignaciones.minutosInvertir, pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_asignaciones.observacion FROM pys_asignaciones
                    INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_actsolicitudes.est = 1
                    AND pys_asignados.idProy = '".$datos2['idProy']."'
                    AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."';";
                $resultado3 = mysqli_query($connection, $consulta3);
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    echo '  
                        <tr>
                            <td style="vertical-align: middle; text-align: left;">'.$datos2['codProy'].'</td>
                            <td style="vertical-align: middle; text-align: left;">'.$datos2['nombreProy'].'</td>';
                    $acumHrs += $datos3['horasInvertir'];
                    $acumMin += $datos3['minutosInvertir'];
                    $totMinPS = ($datos3['horasInvertir'] * 60) + $datos3['minutosInvertir'];
                    $porcentaje = ($totMinPS / $totMin) * 100;
                    $acumPorcen += $porcentaje;
                    echo '  <td style="vertical-align: middle; text-align: center;">P'.$datos3['idSol'].'</td>
                            <td style="vertical-align: middle;">'.$datos3['ObservacionAct'].'</td>
                            <td style="vertical-align: middle; text-align: center;">'.$datos3['horasInvertir'].'</td>
                            <td style="vertical-align: middle; text-align: center;">'.$datos3['minutosInvertir'].'</td>
                            <td style="vertical-align: middle; text-align: center;">'.round($porcentaje, 2).'%</td>
                            <td style="vertical-align: middle; text-align: center;">'.$datos3['observacion'].'</td>
                        </tr>';
                }
                $totMin2 = ($acumHrs * 60) + $acumMin;
                if ($acumMin >= 60) {
                    $acumHrs = floor($totMin2 / 60);
                    $acumMin = round(((($totMin2 / 60) - $acumHrs) * 60), 0);
                }
                $tiemDisp = ($totMin - $totMin2) / 60;
                $minDisp = round((($tiemDisp - floor($tiemDisp)) * 60), 0);
                $porcenDisp = ABS(round(((($totMin2 / $totMin) - 1) * 100), 2));
            }
            echo '
                <tr>
                    <td bgcolor="C4C4C4" style="vertical-align:middle; font-weight: bold;" rowspan="3" colspan="3">Distribución tiempos</td>
                    <td bgcolor="C4C4C4" align="right"><strong>Tiempo total planeado:</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.$acumHrs.'</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.$acumMin.'</strong></td>
                    <td bgcolor="C4C4C4" align="center"><strong>'.round($acumPorcen, 2).'%</strong></td>
                    <td bgcolor="C4C4C4" align="center" rowspan="3"></td>
                </tr>
                <tr>
                    <td bgcolor="C2C2C2" align="right"><strong>Tiempo a dedicar en el periodo:</strong></td>
                    <td bgcolor="C2C2C2" align="center"><strong>'.$totHrs.'</strong></td>
                    <td bgcolor="C2C2C2" align="center"><strong>'.$totMin1.'</strong></td>
                    <td bgcolor="C2C2C2" align="center"></td>
                </tr>
                <tr>
                    <td bgcolor="C2C2C2" align="right"><strong>Tiempo disponible para asignar:</strong></td>
                    <td bgcolor="C2C2C2" align="center"><strong>'.floor($tiemDisp).'</strong></td>
                    <td bgcolor="C2C2C2" align="center"><strong>'.$minDisp.'</strong></td>
                    <td bgcolor="C2C2C2" align="center"><strong>'.$porcenDisp.'%</strong></td>
                </tr>';
            echo '  </tbody>
                </table>';
            mysqli_close($connection);
        }

        public static function consultaDatosPeriodo($periodo, $persona) {
            require('../Core/connection.php');
            if ($periodo == null) {
                echo "<script> alert ('Por favor seleccione un periodo válido.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/infPlaneacion.php">';
            } else if ($periodo != null && $persona == null){
                $consulta = "SELECT * FROM pys_dedicaciones
                    INNER JOIN pys_asignaciones ON pys_asignaciones.idDedicacion = pys_dedicaciones.idDedicacion
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo;";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_num_rows($resultado);
                if ($datos > 0) {
                    $informe = InformePlaneacion::informePeriodo($periodo);
                } else {
                    echo "<script> alert ('No se ha realizado planeación en el periodo seleccionado.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/infPlaneacion.php">';
                }
            } else if ($periodo != null && $persona != null) {
                $consulta = "SELECT * FROM pys_dedicaciones
                    INNER JOIN pys_asignaciones ON pys_asignaciones.idDedicacion = pys_dedicaciones.idDedicacion
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_dedicaciones.persona_IdPersona = '$persona';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_num_rows($resultado);
                if ($datos > 0) {
                    $informe = InformePlaneacion::informePeriodoPersona($periodo, $persona);
                } else {
                    echo "<script> alert ('No se ha realizado planeación para la persona seleccionada.');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/infPlaneacion.php">';
                }
            }   
            mysqli_close($connection);
        }

        public static function listarPlaneacionPeriodo ($periodo) {
            require('../Core/connection.php');
            $consulta = "SELECT inicioPeriodo, finPeriodo, diasSegmento1, diasSegmento2 FROM pys_periodos WHERE idPeriodo = $periodo;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $fecIni = $datos['inicioPeriodo'];
            $fecFin = $datos['finPeriodo'];
            $diasSeg1 = $datos['diasSegmento1'];
            $diasSeg2 = $datos['diasSegmento2'];
            echo '
                <h4>Informe planeación por periodo</h4>
                <h5>Desde: '.$fecIni.'. Hasta: '.$fecFin.'.</h5>
                <div class="row col l12 m12 s12">
                    <table class="responsive-table striped teal lighten-3 tdl-inf">
                        <thead>
                            <tr>
                                <th class="center-align">Persona</th>
                                <th colspan="6" class="center-align">Planeación</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
            $consulta2 = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_personas.idPersona, pys_dedicaciones.porcentajeDedicacion1, pys_dedicaciones.porcentajeDedicacion2 
                FROM pys_asignaciones 
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado 
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona 
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion 
                WHERE pys_dedicaciones.periodo_IdPeriodo = '$periodo' 
                GROUP BY apellido1, apellido2, nombres, idPersona, porcentajeDedicacion1, porcentajeDedicacion2 
                ORDER BY apellido1;";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                $totMin = (((($diasSeg1 * 8) * 60) * $datos2['porcentajeDedicacion1']) / 100) + (((($diasSeg2 * 8) * 60) * $datos2['porcentajeDedicacion2']) / 100);
                $totHrs = floor($totMin / 60);
                $totMin1 = (($totMin / 60) - $totHrs) * 60;
                $nombreCompleto = $datos2['apellido1'].' '.$datos2['apellido2'].' '.$datos2['nombres'];
                echo '      <tr>
                                <td><strong>'.$nombreCompleto.'</strong></td>
                                <td colspan="6">
                                    <table class="responsive-table striped teal lighten-4 tdl-inf2">
                                        <thead>
                                            <tr>
                                                <th class="center-align">Proyecto</th>
                                                <th class="center-align">Prod. / Servicio</th>
                                                <th class="center-align">Detalle Solicitud Prod. / Servicio</th>
                                                <th class="center-align">Horas</th>
                                                <th class="center-align">Minutos</th>
                                                <th class="center-align">Porcentaje</th>
                                                <th class="center-align">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                $consulta3 = "SELECT pys_asignaciones.horasInvertir, pys_asignaciones.minutosInvertir, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_asignaciones.observacion
                    FROM pys_asignaciones
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                    INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_actualizacionproy.est = 1
                    AND pys_actsolicitudes.est = 1
                    AND pys_asignados.idPersona = '".$datos2['idPersona']."'
                    ORDER BY pys_actualizacionproy.idProy;";
                $resultado3 = mysqli_query($connection, $consulta3);
                while ($datos3 = mysqli_fetch_array($resultado3)){
                    $acumHrs += $datos3['horasInvertir'];
                    $acumMin += $datos3['minutosInvertir'];
                    $totMinPS = ($datos3['horasInvertir'] * 60) + $datos3['minutosInvertir'];
                    $porcentaje = ($totMinPS / $totMin) * 100;
                    $acumPorcen += $porcentaje;
                    echo '                  <tr>
                                                <td><strong>'.$datos3['codProy'].'</strong> - '.$datos3['nombreProy'].'</td>
                                                <td>P'.$datos3['idSol'].'</td>
                                                <td>'.$datos3['ObservacionAct'].'</td>
                                                <td class="center-align">'.$datos3['horasInvertir'].'</td>
                                                <td class="center-align">'.$datos3['minutosInvertir'].'</td>
                                                <td class="center-align">'.round($porcentaje, 2).' %</td>
                                                <td class="center-align">'.$datos3['observacion'].'</td>
                                            </tr>';
                }
                $totMin2 = ($acumHrs * 60) + $acumMin;
                if ($acumMin >= 60) {
                    $totMin2 = ($acumHrs * 60) + $acumMin;
                    $acumHrs = floor($totMin2 / 60);
                    $acumMin = round(((($totMin2 / 60) - $acumHrs) * 60), 0);
                }
                $minDisp = round((($tiemDisp - floor($tiemDisp)) * 60), 0);
                $porcenDisp = ABS(round(((($totMin2 / $totMin) - 1) * 100), 2));
                echo '                      <tr>
                                                <td class="right-align grey lighten-3" colspan="3"><strong>Tiempo total planeado:</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.$acumHrs.'</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.$acumMin.'</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.round($acumPorcen, 2).'%</strong></td>
                                                <td class="center-align grey lighten-3"></td>
                                            </tr>
                                            <tr>
                                                <td class="right-align grey lighten-2" colspan="3"><strong>Tiempo a dedicar en el periodo:</strong></td>
                                                <td class="center-align grey lighten-2"><strong>'.$totHrs.'</strong></td>
                                                <td class="center-align grey lighten-2"><strong>'.$totMin1.'</strong></td>
                                                <td class="center-align grey lighten-2"></td>
                                                <td class="center-align grey lighten-2"></td>
                                            </tr>
                                            <tr>
                                                <td class="right-align grey lighten-3" colspan="3"><strong>Tiempo disponible para asignar:</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.floor($tiemDisp).'</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.$minDisp.'</strong></td>
                                                <td class="center-align grey lighten-3"><strong>'.$porcenDisp.'%</strong></td>
                                                <td class="center-align grey lighten-3"></td>
                                            </tr>';
                $acumHrs = 0;
                $acumMin = 0;
                $acumPorcen = 0;
                echo '                  </tbody>
                                    </table>
                                </td>
                            </tr>';
            }
            echo '      </tbody>
                    </table>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves-light col l2 m2 s12 offset-l5" type="submit" name="consultar">Descargar Excel</button>
                </div>';
                
            mysqli_close($connection);
        }

        public static function listarPlaneacionPeriodoPersona ($periodo, $persona) {
            require('../Core/connection.php');
            $consulta = "SELECT apellido1, apellido2, nombres, inicioPeriodo, finPeriodo, idPersona, diasSegmento1, diasSegmento2, porcentajeDedicacion1, porcentajeDedicacion2 
                FROM pys_periodos
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.periodo_IdPeriodo = pys_periodos.idPeriodo
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona
                WHERE pys_periodos.idPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '$persona';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $fecIni = $datos['inicioPeriodo'];
            $fecFin = $datos['finPeriodo'];
            $persona = $datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'];
            $totMin = (((($datos['diasSegmento1'] * 8) * 60) * $datos['porcentajeDedicacion1']) / 100) + (((($datos['diasSegmento2'] * 8) * 60) * $datos['porcentajeDedicacion2']) / 100);
            $totHrs = floor($totMin / 60);
            $totMin1 = (($totMin / 60) - $totHrs) * 60;
            echo '
                <h4>Informe planeación por periodo y persona</h4>
                <h5>Desde: '.$fecIni.'. Hasta: '.$fecFin.'.</h5>
                <h5>'.$persona.'</h5>
                <div class="row col l12 m12 s12">
                    <table class="responsive-table striped teal lighten-4 tdl-inf2">
                        <thead>
                            <tr>
                                <th colspan="7" class="center-align">Planeación</th>
                            </tr>
                            <tr>
                                <th class="center-align">Proyecto</th>
                                <th class="center-align">Prod. / Servicio</th>
                                <th class="center-align">Detalle Solicitud Prod. / Servicio</th>
                                <th class="center-align">Horas</th>
                                <th class="center-align">Minutos</th>
                                <th class="center-align">Porcentaje</th>
                                <th class="center-align">Observación</th>
                            </tr>
                        </thead>
                        <tbody>';
            $consulta2 = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idProy  FROM pys_asignaciones
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."'
                AND pys_actsolicitudes.est = 1
                AND pys_actualizacionproy.est = 1
                GROUP BY codProy, nombreProy, idProy;";
            $resultado2 = mysqli_query($connection, $consulta2);
            while ($datos2 = mysqli_fetch_array($resultado2)) {
                $consulta3 = "SELECT pys_asignaciones.horasInvertir, pys_asignaciones.minutosInvertir, pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_asignaciones.observacion
                    FROM pys_asignaciones
                    INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
                    INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                    WHERE pys_dedicaciones.periodo_IdPeriodo = $periodo
                    AND pys_actsolicitudes.est = 1
                    AND pys_asignados.idProy = '".$datos2['idProy']."'
                    AND pys_dedicaciones.persona_IdPersona = '".$datos['idPersona']."';";
                $resultado3 = mysqli_query($connection, $consulta3);
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    $acumHrs += $datos3['horasInvertir'];
                    $acumMin += $datos3['minutosInvertir'];
                    $totMinPS = ($datos3['horasInvertir'] * 60) + $datos3['minutosInvertir'];
                    $porcentaje = ($totMinPS / $totMin) * 100;
                    $acumPorcen += $porcentaje;
                        echo '      
                            <tr>
                                <td><strong>'.$datos2['codProy'].'</strong> - '.$datos2['nombreProy'].'</td>
                                <td>P'.$datos3['idSol'].'</td>
                                <td>'.$datos3['ObservacionAct'].'</td>
                                <td class="center-align">'.$datos3['horasInvertir'].'</td>
                                <td class="center-align">'.$datos3['minutosInvertir'].'</td>
                                <td class="center-align">'.round($porcentaje, 2).' %</td>
                                <td>'.$datos3['observacion'].'</td>
                            </tr>';
                }
                $totMin2 = ($acumHrs * 60) + $acumMin;
                if ($acumMin >= 60) {
                    $acumHrs = floor($totMin2 / 60);
                    $acumMin = round(((($totMin2 / 60) - $acumHrs) * 60), 0);
                }
                $tiemDisp = ($totMin - $totMin2) / 60;
                $minDisp = round((($tiemDisp - floor($tiemDisp)) * 60), 0);
                $porcenDisp = ABS(round(((($totMin2 / $totMin) - 1) * 100), 2));
            }
            echo '          <tr>
                                <td class="right-align grey lighten-3" colspan="3"><strong>Tiempo total planeado:</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.$acumHrs.'</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.$acumMin.'</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.round($acumPorcen, 2).'%</strong></td>
                                <td class="center-align grey lighten-3"></td>
                            </tr>
                            <tr>
                                <td class="right-align grey lighten-2" colspan="3"><strong>Tiempo a dedicar en el periodo:</strong></td>
                                <td class="center-align grey lighten-2"><strong>'.$totHrs.'</strong></td>
                                <td class="center-align grey lighten-2"><strong>'.$totMin1.'</strong></td>
                                <td class="center-align grey lighten-2"><strong></strong></td>
                                <td class="center-align grey lighten-2"></td>
                            </tr>
                            <tr>
                                <td class="right-align grey lighten-3" colspan="3"><strong>Tiempo disponible para asignar:</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.floor($tiemDisp).'</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.$minDisp.'</strong></td>
                                <td class="center-align grey lighten-3"><strong>'.$porcenDisp.'%</strong></td>
                                <td class="center-align grey lighten-3"></td>
                            </tr>';
            $acumHrs = 0;
            $acumMin = 0;
            $acumPorcen = 0;
            echo '      </tbody>
                    </table>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves-light col l2 m2 s12 offset-l5" type="submit" name="consultar">Descargar Excel</button>
                </div>';
            mysqli_close($connection);
        }

    }
?>