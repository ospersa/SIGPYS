<?php

Class InformeProductosCelulas {

    public static function busqueda ($celula, $proyecto) {
        require('../Core/connection.php');
        if ($celula == "none" && $proyecto == "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.idCelula = '' AND pys_actualizacionproy.est = '1';";
        } else if ($celula == "none" && $proyecto != "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.idCelula = '' AND pys_actualizacionproy.est = '1' AND pys_actualizacionproy.idProy = '$proyecto';";
        } else if ($celula == "all" && $proyecto == "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.est = '1';";
        } else if ($celula == "all" && $proyecto != "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.est = '1' AND pys_actualizacionproy.idProy = '$proyecto';";
        } else if ($celula != "none" && $celula != "all" && $proyecto == "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.idCelula = '$celula' AND pys_actualizacionproy.est = '1';";
        } else if ($celula != "none" && $celula != "all" && $proyecto != "all") {
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idCelula, pys_celulas.nombreCelula
                FROM pys_actualizacionproy 
                LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
                WHERE pys_actualizacionproy.idCelula = '$celula' AND pys_actualizacionproy.est = '1' AND pys_actualizacionproy.idProy = '$proyecto';";
        }
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        $tabla = '     <table class="responsive-table" border="1">
                            <thead>
                                <tr class="teal lighten-4">
                                    <th class="row-teal3">Célula</th>
                                    <th class="row-teal3">Proyecto</th>
                                    <th class="row-teal3">Asesor/Gestor RED</th>
                                    <th class="row-teal3">Producto/Servicio</th>
                                    <th class="row-teal3">Descripción P/S</th>
                                    <!--<th class="row-teal3">Estado P/S</th>
                                    <th class="row-teal3">Presupuesto</th>-->
                                    <th class="row-teal3">Asignados</th>
                                    <th class="row-teal3">Tiempo a Invertir</th>
                                    <th class="row-teal3">Tiempo Invertido</th>
                                    <th class="row-teal3">Ejecutado</th>
                                </tr>
                            </thead>
                            <tbody>';
        if ($registros > 0) {
            while ($datos = mysqli_fetch_array($resultado)) {
                $asesor = "";
                $idProy = $datos['idProy'];
                $nombreCelula = ($datos['nombreCelula'] == "") ? "Sin Célula" : $datos['nombreCelula'] ;
                $nombreProyecto = $datos['codProy']. " - ".$datos['nombreProy'];
                /** Consulta para obtener los asesores/gestores red del proyecto */
                $consulta2 = "SELECT pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
                    FROM pys_asignados
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.est = '1' AND pys_personas.est = '1' AND (pys_asignados.idRol = 'ROL024' OR pys_asignados.idRol = 'ROL025')
                    AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol = '';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registros2 = mysqli_num_rows($resultado2);
                if ($registros2 > 0) {
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $asesor .= $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres']." / ";
                    }
                }
                $asesor = substr($asesor, 0, -3);
                /** Consulta para obtener los productos/servicios del proyecto */
                $consulta3 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_actsolicitudes.idSolicitante, pys_estadosol.nombreEstSol
                    FROM pys_actsolicitudes
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = '1' AND pys_estadosol.est = '1' AND pys_solicitudes.est = '1' AND pys_solicitudes.idTSol = 'TSOL02' AND pys_cursosmodulos.idProy = '$idProy'
                    ORDER BY pys_actsolicitudes.idSol;";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datosProy = false;
                $presupuestoProy = 0;
                $ejecutadoProy = 0;
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    $acumuladoPS = 0;
                    $acumuladoTiempoPS = 0;
                    $acumuladoTiempoInvertir = 0;
                    $ps = $datos3['idSol'];
                    $minutosInvertidos = 0;
                    $consulta4 = "SELECT pys_solicitudes.idSol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idAsig
                        FROM pys_solicitudes
                        INNER JOIN pys_asignados ON pys_asignados.idSol = pys_solicitudes.idSol
                        INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                        WHERE pys_solicitudes.est = '1' AND pys_solicitudes.idTSol = 'TSOL02' 
                        AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_solicitudes.idSol = '$ps'
                        ORDER BY pys_solicitudes.idSol;";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    while ($datos4 = mysqli_fetch_array($resultado4)) {
                        $idAsignado = $datos4['idAsig'];
                        $asignado = $datos4['apellido1']." ".$datos4['apellido2']." ".$datos4['nombres'];
                        $tiempoInvertir = ($datos4['maxhora'] * 60) + $datos4['maxmin'];
                        $totalTiempoInvertir = number_format(($tiempoInvertir / 60), 2, ",", ".");
                        $acumuladoTiempoInvertir += $tiempoInvertir;
                        $presupuesto = $datos3['presupuesto'];
                        $descripcion = $datos3['ObservacionAct'];
                        $estadoPS = $datos3['nombreEstSol'];
                        /** Consulta para obtener el tiempo registrado y el costo respectivo*/
                        $consulta5 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_actsolicitudes.idSol, pys_salarios.salario
                            FROM pys_tiempos 
                            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                            INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                            WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') 
                            AND pys_asignados.idAsig = '$idAsignado'  AND pys_salarios.estSal = '1' AND pys_actsolicitudes.est = '1';";
                        $resultado5 = mysqli_query($connection, $consulta5);
                        $registros5 = mysqli_num_rows($resultado5);
                        if ($registros5 > 0) {
                            $totalAsignado = 0;
                            $totalTiempoInvertido = 0;
                            while ($datos5 = mysqli_fetch_array($resultado5)) {
                                $salarioMinuto = $datos5['salario'] / 60;
                                $minutosInvertidos = ($datos5['horas'] * 60) + $datos5['minutos'];
                                $totalAsignado += $salarioMinuto * $minutosInvertidos;
                                $totalTiempoInvertido += $minutosInvertidos;
                            }
                            $acumuladoPS += $totalAsignado;
                            $acumuladoTiempoPS += $totalTiempoInvertido;
                            if ($minutosInvertidos > 0) {
                                $totalTiempoInvertido = $totalTiempoInvertido / 60;
                                $tabla .= '
                                    <tr class="row60">
                                        <td class="middle">'.$nombreCelula.'</td>
                                        <td class="middle">'.$nombreProyecto.'</td>
                                        <td class="middle">'.$asesor.'</td>
                                        <td class="middle">P'.$ps.'</td>
                                        <td class="middle" style="width:500px;">'.strip_tags($descripcion).'</td>
                                        <!--<td class="middle">'.$estadoPS.'</td>
                                        <td class="middle">$'.number_format($presupuesto, 2, ",", ".").'</td>-->
                                        <td class="middle">'.$asignado.'</td>
                                        <td class="middle">'.$totalTiempoInvertir.'</td>
                                        <td class="middle">'.number_format($totalTiempoInvertido, 2, ",", ".").'</td>
                                        <td class="middle">$'.number_format($totalAsignado, 2, ",", ".").'</td>
                                    </tr>';
                            }
                        }
                    }
                    if ($minutosInvertidos > 0) {
                        $datosProy = true;
                        $tabla .= ' <tr class="grey lighten-2">
                                        <td class="row-total" colspan="2">Resumen producto: <strong>P'.$ps.'</strong></td>
                                        <td class="row-total" colspan="2">Estado P/S: <strong>'.$estadoPS.'</strong></td>
                                        <td class="row-total">Presupuesto: <strong>$'.number_format($presupuesto, 2, ",", ".").'</strong></td>
                                        <td class="row-total" colspan="2">Ejecutado: <strong> $'.number_format($acumuladoPS, 2, ",", ".").'</strong></td>';
                        $diferencia = $presupuesto - $acumuladoPS;
                        if ($diferencia >= 0) {
                            $tabla .= '     
                                        <td class="row-total" colspan="2">Diferencia: <strong><span class="teal-text">$ '.number_format(($presupuesto - $acumuladoPS), 2, ",", ".").'</span></strong></td>
                                    </tr>';
                        } else {
                            $tabla .= '     
                                        <td class="row-total" colspan="2">Diferencia: <strong><span class="red-text">$ '.number_format(($presupuesto - $acumuladoPS), 2, ",", ".").'</span></strong></td>
                                    </tr>';
                        }
                        $presupuestoProy += $presupuesto;
                        $ejecutadoProy += $acumuladoPS;
                    }
                }
                $diferenciaProy = $presupuestoProy - $ejecutadoProy;
                if ($datosProy) {
                    $tabla .= ' <tr class="teal lighten-5">
                                    <td colspan="3" class="row-teal4">Total proyecto: <strong>'.$nombreProyecto.'</strong></td>
                                    <td colspan="2" class="row-teal4">Presupuesto: <strong>$ '.number_format($presupuestoProy, 2, ",", ".").'</strong></td>
                                    <td colspan="2" class="row-teal4">Ejecutado: <strong>$ '.number_format($ejecutadoProy, 2, ",", ".").'</strong></td>';
                    if ($diferenciaProy >= 0) {
                        $tabla .= ' <td colspan="2" class="row-teal4">Diferencia: <strong><span class="teal-text">$ '.number_format($diferenciaProy, 2, ",", ".").'<span></strong></td>
                                </tr>';
                    } else {
                        $tabla .= ' <td colspan="2" class="row-teal4">Diferencia: <strong><span class="red-text">$ '.number_format($diferenciaProy, 2, ",", ".").'<span></strong></td>
                                </tr>';
                    }
                    
                }
            }
            $tabla .= '     </tbody>
                        </table>';
        }
        return $tabla;
        mysqli_close($connection);
    }

    public static function descarga ($celula, $proyecto) {
        require('../Core/connection.php');
        $tabla = InformeProductosCelulas::busqueda($celula, $proyecto);
        if ($tabla != "") {
            header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=inf-celulas-productos-servicios.xls");
                header('Cache-Control: max-age=0');
                header("Pragma: no-cache");
                header("Expires: 0");
                echo '  <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                                <style>
                                    .row-teal3 {
                                        background-color: #80cbc4;
                                        height: 30px;
                                        vertical-align: middle;
                                    }
                                    .row-teal4 {
                                        background-color: #e0f2f1;
                                        height: 30px;
                                        vertical-align: middle;
                                    }
                                    .row-resumen {
                                        text-align: center;
                                        height: 40px;
                                        vertical-align: middle;
                                        background-color: #b2dfdb;
                                    }
                                    .row-total {
                                        text-align: left;
                                        vertical-align: middle;
                                        background-color: #e0e0e0;
                                        height: 30px;
                                    }
                                    .middle {
                                        vertical-align: middle;
                                        text-align: left;
                                    }
                                    .row60 {
                                        height: 60px;
                                    }
                                    .red-text {
                                        color: red;
                                    }
                                    .teal-text {
                                        color:teal;
                                    }
                                </style>
                            </head>
                            <body>';
                echo $tabla;
                echo '      </body>
                        </html>';
        }
        mysqli_close($connection);
    }

    public static function selectCelula () {
        require('../Core/connection.php');
        $consulta = "SELECT pys_celulas.nombreCelula, pys_celulas.idCelula
            FROM pys_actualizacionproy
            LEFT JOIN pys_celulas ON pys_celulas.idCelula = pys_actualizacionproy.idCelula
            WHERE pys_actualizacionproy.est = '1' AND pys_celulas.estado = '1'
            GROUP BY pys_celulas.nombreCelula;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $string = ' <select name="sltCelula" id="sltCelula" onchange="cargaSelect(\'#sltCelula\',\'../Controllers/ctrl_infProductosCelulas.php\',\'#sltProyecto\');">
                            <option value="all" selected>Todas</option>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $string .= '<option value="'.$datos['idCelula'].'">'.$datos['nombreCelula'].'</option>';
            }
            $string .= '    <option value="none">Sin Célula</option>
                        </select>
                        <label>Célula</label>';
        } else {
            $string = "No hay celulas creadas en el sistema";
        }
        return $string;
        mysqli_close($connection);
    }

    public static function selectProyecto ($idCelula) {
        require('../Core/connection.php');
        if ($idCelula == "all") {
            $consulta = "SELECT idProy, codProy, nombreProy FROM pys_actualizacionproy WHERE est = '1' ORDER BY codProy;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <select name="sltProyecto" id="sltProyecto">
                                <option value="all">Todos</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '<option value="'.$datos['idProy'].'">'.$datos['codProy'].' - '.$datos['nombreProy'].'</option>';
                }
                $string .= '</select>
                            <label for="sltProyecto">Proyecto</label>';
            } else {
                echo "No hay proyectos";
            }
        } else {
            if ($idCelula == "none") {
                $consulta = "SELECT idProy, codProy, nombreProy FROM pys_actualizacionproy WHERE est = '1' AND idCelula = '' ORDER BY codProy;";
            } else {
                $consulta = "SELECT idProy, codProy, nombreProy FROM pys_actualizacionproy WHERE est = '1' AND idCelula = '$idCelula' ORDER BY codProy; ";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <select name="sltProyecto" id="sltProyecto">
                                <option value="all">Todos</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .= '<option value="'.$datos['idProy'].'">'.$datos['codProy'].' - '.$datos['nombreProy'].'</option>';
                }
                $string .= '</select>
                            <label for="sltProyecto">Proyecto</label>';
            }
        }
        return $string;
        mysqli_close($connection);
    }

}

?>