<?php

class Planeacion{

    public static function onLoad($idAsignado) {
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_asignaciones WHERE idAsignado = '$idAsignado';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function listarProyectos ($idPeriodo, $idPersona) {
        $i = 0;
        require('../Core/connection.php');
        $cons = "SELECT apellido1, apellido2, nombres FROM pys_personas WHERE idPersona = '$idPersona' AND est = '1';";
        $res = mysqli_query($connection, $cons);
        $data = mysqli_fetch_array($res);
        $nombreCompleto = strtoupper($data['apellido1']." ".$data['apellido2']." ".$data['nombres']);
        /*$consulta = "SELECT * 
            FROM pys_asignados 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
            INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
            WHERE pys_asignados.idPersona = '$idPersona'
            AND pys_actsolicitudes.idSolicitante = ''
            AND pys_asignados.est = '1'
            AND pys_actsolicitudes.est = '1'
            AND pys_actualizacionproy.est = '1'
            AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005')
            ORDER BY pys_actualizacionproy.codProy ASC;";*/
			$consulta = "SELECT pys_actualizacionproy.idCelula, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idSol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idAsig, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_roles.nombreRol, pys_actsolicitudes.ObservacionAct
            FROM pys_asignados 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
            INNER JOIN pys_solicitudes AS solicitudEspecifica ON solicitudEspecifica.idSol = pys_asignados.idSol
            INNER JOIN pys_solicitudes AS solicitudInicial ON solicitudInicial.idSol = solicitudEspecifica.idSolIni
            INNER JOIN pys_personas ON pys_personas.idPersona = solicitudInicial.idSolicitante
            INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
            WHERE pys_asignados.idPersona = '$idPersona'
            AND pys_actsolicitudes.idSolicitante = ''
            AND pys_asignados.est = '1'
            AND pys_personas.est = '1'
            AND pys_actsolicitudes.est = '1'
            AND pys_actualizacionproy.est = '1'
            AND solicitudInicial.est = '1'
            AND solicitudEspecifica.est = '1'
            AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005')
            ORDER BY pys_actualizacionproy.codProy ASC;";
        $resultado = mysqli_query($connection, $consulta);
        $consulta3 = "SELECT porcentajeDedicacion1, porcentajeDedicacion2, diasSegmento1, diasSegmento2
            FROM pys_dedicaciones
            INNER JOIN pys_periodos ON pys_periodos.idPeriodo = pys_dedicaciones.periodo_IdPeriodo
            WHERE pys_dedicaciones.periodo_IdPeriodo = '$idPeriodo'
            AND pys_dedicaciones.persona_IdPersona = '$idPersona';";
        $resultado3 = mysqli_query($connection, $consulta3);
        $datos3 = mysqli_fetch_array($resultado3);
        $hrsPeriodo = ((($datos3['diasSegmento1'] * 8) * $datos3['porcentajeDedicacion1']) / 100) + ((($datos3['diasSegmento2'] * 8) * $datos3['porcentajeDedicacion2']) / 100);
        echo "<input hidden type='text' name='txtHorasDisponibles' value='$hrsPeriodo'/>";
        echo "  <div class='row col l7 m7 s12'>
                        <div class='col l10 m10 s12 offset-l1 offset-m1'>
                            <div class='card blue-grey darken-1'>
                                <div class='card-content white-text'>
                                    <span class='card-title'><strong>$nombreCompleto</strong></span>
                                    <div class='divider'></div>
                                    <p>Aún no se ha realizado planeación de tiempos en el periodo seleccionado.</p>
                                    <p>Horas disponibles para asignar: <strong>$hrsPeriodo</strong> hrs.</p>
                                </div>
                            </div>
                        </div>
                    </div>";
        echo'
        <table class="responsive-table tdl-planeacion">
            <thead>
                <tr>
                    <th hidden>Id Asig</th>
                    <th class="center-align" rowspan="2">Cód. proyecto - Célula</th>
                    <th class="center-align" rowspan="2">Proyecto</th>
                    <th class="center-align" rowspan="2">Producto - Rol</th>
                    <th class="center-align" rowspan="2">Solicitud</th>
                    <th class="center-align" colspan="4">Tiempos</th>
                    <th class="center-align" rowspan="2">Observacion</th>
                </tr>
                <tr class="center-align">
                    <th class="center-align">Presupuestado</th>
                    <th class="center-align">Registrado</th>
                    <th class="center-align teal lighten-4">Disponible </th>
                    <th class="center-align">Dedicar</th>
                </tr>
            </thead>
            <tbody>';
        while ($datos = mysqli_fetch_array($resultado)){
            $idCelula = $datos['idCelula'];
            $tiempoPresupuestado = (($datos['maxhora'] * 60) + $datos['maxmin']) / 60;
            $tiempoPresupuestado = round($tiempoPresupuestado, 2);
            $idSol = $datos['idSol'];
            $consulta2 = "SELECT SUM(pys_tiempos.horaTiempo) AS Horas, SUM(pys_tiempos.minTiempo) AS Minutos
                FROM pys_asignados
                INNER JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                WHERE pys_asignados.idSol = '$idSol'
                AND pys_asignados.idPersona = '$idPersona'";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            /** Consulta para obtener el nombre de la celula con respecto al id que tiene en la tabla pys_actualizacionproy */
            $consulta4 = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula' AND estado = '1';";
            $resultado4 = mysqli_query($connection, $consulta4);
            $datos4 = mysqli_fetch_array($resultado4);
            $registros4 = mysqli_num_rows($resultado4);
            if ($registros4 > 0) {
                $nombreCelula = " - <strong>".$datos4['nombreCelula']."</strong>";
            }
            $minutosReg = ($datos2[0]*60)+$datos2[1];
            $horasReg = round($minutosReg/60, 2);
            $solicitante = '<strong class="teal-text">'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].':</strong><br>';
            echo '
                <tr>
                    <td hidden><input type="hidden" name="idAsignado['.$i.']" value="'.$datos['idAsig'].'"></td>
                    <td>'.$datos['codProy'].$nombreCelula.'</td>
                    <td>'.$datos['nombreProy'].'</td>
                    <td>P'.$datos['idSol'].' - '.$datos['nombreRol'].'</td>
                    <td>'.$solicitante.$datos['ObservacionAct'].'</td>
                    <td class="center-align"><input readonly class="center-align" type="text" id="tiempoPresupuestado['.$i.']" name="tiempoPresupuestado['.$i.']" value="'.$tiempoPresupuestado.' hrs"></td>
                    <td class="center-align"><input readonly class="center-align" type="text" id="tiempoRegistrado" name="tiempoRegistrado" value="'.$horasReg.' hrs"></td>';
            $disponible = $tiempoPresupuestado - $horasReg;
            if ($disponible < 0) {
                $class = "alert-input";
            } else {
                $class = "texto-resaltado";
            }
            echo '  <td class="center-align teal lighten-4"><input readonly class="'.$class.' center-align" type="text" id="tiempoDisponible['.$i.']" name="tiempoDisponible['.$i.']" value="'.$disponible.' hrs"></td>
                    <td>
                        <input placeholder="h" class="center-align texto-resaltado col l6 m6 s12 hrsInvertir" type="number" id="horasInvertir'.$i.'" name="horasInvertir['.$i.']" value="" step="any" onblur="validar('.$i.')">
                        <input placeholder="m" class="center-align texto-resaltado col l6 m6 s12 minInvertir" type="number" max="59" name="minutosInvertir['.$i.']" id="minutosInvertir'.$i.'" value="" step="any" onblur="validar('.$i.')"></td>
                    <td><textarea class="materialize-textarea textarea" name="observacion['.$i.']" id="observacion'.$i.'"></textarea></td>
                </tr>';
                $i = $i + 1;
            }
        echo "
        </tbody>
        </table>
        <div class='input-field center-align'>
            <button class='btn waves-effect waves-light' type='submit' name='enviar'>Guardar</button>
        </div>";
        mysqli_close($connection);
    }

    public static function listarProyectos2 ($idPeriodo, $idPersona) {
        $hrsAsig = 0;
        $mtsAsig = 0;
        $i = 0;
        require('../Core/connection.php');
        $cons = "SELECT apellido1, apellido2, nombres FROM pys_personas WHERE idPersona = '$idPersona' AND est = '1';";
        $res = mysqli_query($connection, $cons);
        $data = mysqli_fetch_array($res);
        $nombreCompleto = strtoupper($data['apellido1']." ".$data['apellido2']." ".$data['nombres']);
        $consulta = "SELECT pys_actualizacionproy.idCelula, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idSol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idAsig, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_roles.nombreRol, pys_actsolicitudes.ObservacionAct
            FROM pys_asignados 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
            INNER JOIN pys_solicitudes AS solicitudEspecifica ON solicitudEspecifica.idSol = pys_asignados.idSol
            INNER JOIN pys_solicitudes AS solicitudInicial ON solicitudInicial.idSol = solicitudEspecifica.idSolIni
            INNER JOIN pys_personas ON pys_personas.idPersona = solicitudInicial.idSolicitante
            INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
            WHERE pys_asignados.idPersona = '$idPersona'
            AND pys_actsolicitudes.idSolicitante = ''
            AND pys_asignados.est = '1'
            AND pys_personas.est = '1'
            AND pys_actsolicitudes.est = '1'
            AND pys_actualizacionproy.est = '1'
            AND solicitudInicial.est = '1'
            AND solicitudEspecifica.est = '1'
            AND (pys_actsolicitudes.idEstSol = 'ESS002' OR pys_actsolicitudes.idEstSol = 'ESS003' OR pys_actsolicitudes.idEstSol = 'ESS004' OR pys_actsolicitudes.idEstSol = 'ESS005')
            ORDER BY pys_actualizacionproy.codProy ASC;";
        $resultado = mysqli_query($connection, $consulta);
        $consulta4 = "SELECT horasInvertir, minutosInvertir, diasSegmento1, diasSegmento2, porcentajeDedicacion1, porcentajeDedicacion2, observacion
            FROM pys_asignaciones
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
            INNER JOIN pys_dedicaciones ON pys_dedicaciones.idDedicacion = pys_asignaciones.idDedicacion
            INNER JOIN pys_periodos ON pys_periodos.idPeriodo = pys_dedicaciones.periodo_IdPeriodo
            WHERE pys_dedicaciones.periodo_IdPeriodo = $idPeriodo
            AND pys_asignados.idPersona = '$idPersona';";
        $resultado4 = mysqli_query($connection, $consulta4);
        while ($datos4 = mysqli_fetch_array($resultado4)){
            $hrsAsig += $datos4['horasInvertir'];
            $mtsAsig += $datos4['minutosInvertir'];
            $diasS1 = $datos4['diasSegmento1'];
            $diasS2 = $datos4['diasSegmento2'];
            $porcen1 = $datos4['porcentajeDedicacion1'];
            $porcen2 = $datos4['porcentajeDedicacion2'];
        }
        $datos4 = mysqli_fetch_array($resultado4);
        $hrsPer = ((($diasS1 * 8) * $porcen1) / 100) + ((($diasS2 * 8) * $porcen2) / 100); //Horas totales del periodo registrado
        $minTot = ($hrsAsig * 60) + $mtsAsig;
        
        $hrsTot = round(($minTot / 60), 2);
        $hrsAsig = floor($hrsTot);
        $mtsAsig = round((($hrsTot - $hrsAsig) * 60),0);
        $hrsDisp = ($hrsPer - $hrsTot);
        $hrsDisp1 = floor($hrsDisp);
        $hrsDisp2 = round($hrsDisp, 0);
        $minDisp = $hrsDisp - $hrsDisp1;
        $minDisp = round(($minDisp * 60),0);
        if ($hrsDisp < 0) {
            $string = " <div class='row col l7 m7 s12'>
                            <div class='col l10 m10 s12 offset-l1 offset-m1'>
                                <div class='card red darken-2'>
                                    <div class='card-content white-text'>
                                        <span class='card-title'><strong>".$nombreCompleto."</strong></span>
                                        <div class='divider'></div>
                                        <p>Se han asignado <strong>".$hrsTot."</strong> hrs., de: <strong>".$hrsPer."</strong> horas del periodo seleccionado.</p>
                                        <p>Tiempo excedido en: <strong>".ABS($hrsDisp)."</strong> hrs.</p>
                                    </div>
                                </div>
                            </div>
                        </div>";
        } else {
            $string = " <div class='row col l7 m7 s12'>
                            <div class='col l10 m10 s12 offset-l1 offset-m1'>
                                <div class='card teal'>
                                    <div class='card-content white-text'>
                                        <span class='card-title'><strong>".$nombreCompleto."</strong></span>
                                        <div class='divider'></div>
                                        <p>Se han asignado <strong>".$hrsTot."</strong> hrs., de: <strong>".$hrsPer."</strong> horas del periodo seleccionado.</p>
                                        <p>Tiempo disponible para asignar: <strong>".$hrsDisp."</strong> hrs.</p>
                                    </div>
                                </div>
                            </div>
                        </div>";
        }
        
        echo "<input hidden type='text' name='txtHorasDisponibles' value='$hrsDisp'/>";
        echo $string;
        echo'
        <table class="responsive-table tdl-planeacion">
            <thead>
                <tr>
                    <th hidden>Id Asig</th>
                    <th class="center-align" rowspan="2">Cód. proyecto</th>
                    <th class="center-align" rowspan="2">Proyecto</th>
                    <th class="center-align" rowspan="2">Producto - Rol</th>
                    <th class="center-align" rowspan="2">Solicitud</th>
                    <th class="center-align" colspan="4">Tiempos</th>
                    <th class="center-align" rowspan="2">Observacion</th>
                </tr>
                <tr class="center-align">
                    <th class="center-align">Presupuestado</th>
                    <th class="center-align">Registrado</th>
                    <th class="center-align teal lighten-4">Disponible </th>
                    <th class="center-align">Dedicar</th>
                </tr>
            </thead>
            <tbody>';
        while ($datos = mysqli_fetch_array($resultado)){
            $idCelula = $datos['idCelula'];
            $tiempoPresupuestado = (($datos['maxhora'] * 60) + $datos['maxmin']) / 60;
            $tiempoPresupuestado = round($tiempoPresupuestado, 2);
            $idSol = $datos['idSol'];
            $consulta2 = "SELECT SUM(pys_tiempos.horaTiempo) AS Horas, SUM(pys_tiempos.minTiempo) AS Minutos
                FROM pys_asignados
                INNER JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                WHERE pys_asignados.idSol = '$idSol'
                AND pys_asignados.idPersona = '$idPersona'";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $minutosReg = ($datos2[0]*60)+$datos2[1];
            $horasReg = round($minutosReg/60, 2);
            $consulta3 = "SELECT * 
                FROM pys_dedicaciones 
                INNER JOIN pys_asignaciones ON pys_asignaciones.idDedicacion = pys_dedicaciones.idDedicacion
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_asignaciones.idAsignado
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                WHERE pys_dedicaciones.periodo_IdPeriodo = $idPeriodo 
                AND pys_asignados.idAsig = ".$datos['idAsig']."
                AND pys_actsolicitudes.est = 1
                AND pys_dedicaciones.persona_IdPersona = '$idPersona'";
            $resultado3 = mysqli_query($connection, $consulta3);
            $datos3 = mysqli_fetch_array($resultado3);
            $solicitante = '<strong class="teal-text">'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].':</strong><br>';
            /** Consulta para obtener el nombre de la celula con respecto al id que tiene en la tabla pys_actualizacionproy */
            $consulta4 = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula' AND estado = '1';";
            $resultado4 = mysqli_query($connection, $consulta4);
            $datos4 = mysqli_fetch_array($resultado4);
            $registros4 = mysqli_num_rows($resultado4);
            if ($registros4 > 0) {
                $nombreCelula = " - <strong>".$datos4['nombreCelula']."</strong>";
            }
            echo '
                <tr>
                    <td hidden><input type="hidden" name="idAsignado['.$i.']" value="'.$datos['idAsig'].'"></td>
                    <td>'.$datos['codProy'].$nombreCelula.'</td>
                    <td>'.$datos['nombreProy'].'</td>
                    <td>P'.$datos['idSol'].' - '.$datos['nombreRol'].'</td>
                    <td>'.$solicitante.$datos['ObservacionAct'].'</td>
                    <td class="center-align"><input readonly class="center-align" type="text" id="tiemPres['.$i.']" name="tiemPres['.$i.']" value="'.$tiempoPresupuestado.' hrs"></td>
                    <td class="center-align"><input readonly class="center-align" type="text" id="tiempoRegistrado" name="tiempoRegistrado" value="'.$horasReg.' hrs"></td>';
            $disponible = $tiempoPresupuestado - $horasReg;
            if ($disponible < 0) {
                $class = "alert-input";
            } else {
                $class = "texto-resaltado";
            }
            echo '  <td class="center-align teal lighten-4"><input readonly class="'.$class.' center-align" type="text" id="tiempoDisponible['.$i.']" name="tiempoDisponible['.$i.']" value="'.$disponible.' hrs"></td>
                    <td>
                        <input placeholder="h" class="center-align texto-resaltado col l6 m6 s12 hrsInvertir" type="number" id="horasInvertir'.$i.'" name="horasInvertir['.$i.']" value="'.$datos3['horasInvertir'].'" step="any" onblur="validar('.$i.')">
                        <input placeholder="m" class="center-align texto-resaltado col l6 m6 s12 minInvertir" type="number" max="59" name="minutosInvertir['.$i.']" id="minutosInvertir'.$i.'" value="'.$datos3['minutosInvertir'].'" step="any" onblur="validar('.$i.')"></td>
                    <td><textarea class="materialize-textarea textarea" name="observacion['.$i.']" id="observacion'.$i.'">'.$datos3['observacion'].'</textarea></td>
                </tr>';
            $i = $i + 1;
        }
        echo "
            </tbody>
        </table>
        <div class='input-field center-align'>
            <button class='btn waves-effect waves-light' type='submit' name='enviar'>Guardar</button>
        </div>";
        mysqli_close($connection);
    }

    public static function mostrarInfoPeriodo ($idPeriodo, $idPersona) {
        require('../Core/connection.php');
        $horasTotales = 0;
        $consulta = "SELECT idDedicacion, porcentajeDedicacion1, porcentajeDedicacion2, diasSegmento1, diasSegmento2, inicioPeriodo, finPeriodo
            FROM pys_dedicaciones
            INNER JOIN pys_periodos ON pys_periodos.idPeriodo = pys_dedicaciones.periodo_IdPeriodo
            WHERE pys_dedicaciones.persona_IdPersona = '$idPersona'
            AND pys_periodos.idPeriodo = $idPeriodo;";
        $resultado = mysqli_query($connection, $consulta);
        if(!empty($resultado)){
            $registros = mysqli_num_rows($resultado);
        }else{
            $registros = 0;
        }
        if ($registros > 0) {
            $datos = mysqli_fetch_array($resultado);
            $horasDedicacion1 = $datos['porcentajeDedicacion1'] * ($datos['diasSegmento1'] * 8) / 100;
            $horasDedicacion2 = $datos['porcentajeDedicacion2'] * ($datos['diasSegmento2'] * 8) / 100;
            $horasTotales = $horasDedicacion1 + $horasDedicacion2;
            echo "  <div class='row col l5 m5 s12'>
                        <div class='col l12 m12 s12'>
                            <div class='card blue-grey darken-1 left-align'>
                                <div class='card-content white-text'>
                                    <span class='card-title'>Información periodo:</span>
                                    <p style='display:inline-block;' class='col l6 m6 s12'>Fecha inicial: ".$datos['inicioPeriodo'].".</p>
                                    <p style='display:inline-block;' class='col l6 m6 s12 right-align'>Fecha final: ".$datos['finPeriodo'].".</p>
                                    <div class='divider col l12 m12 s12'></div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Porcentaje dedicación</th>
                                                <th>Horas a trabajar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Segmento 1</td>
                                                <td class='center-align'>".$datos['porcentajeDedicacion1']." %</td>
                                                <td class='center-align'>".($datos['porcentajeDedicacion1'] * ($datos['diasSegmento1'] * 8) / 100)."</td>
                                            </tr>
                                            <tr>
                                                <td>Segmento 2</td>
                                                <td class='center-align'>".$datos['porcentajeDedicacion2']." %</td>
                                                <td class='center-align'>".($datos['porcentajeDedicacion2'] * ($datos['diasSegmento2'] * 8) / 100)."</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='2' class='right-align'>Total horas a trabajar:</td>
                                                <td class='center-align'>".$horasTotales."</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input hidden readonly id='txtHorasMes' class='center-align' name='txtHorasMes' value='".$horasTotales."' type='number'/>
                    <input hidden readonly id='txtIdDedicacion' class='center-align' name='txtIdDedicacion' value='".$datos['idDedicacion']."' type='number'/>";
            $planear = true;
        } else {
            echo "  <div class='row'>
                        <div class='col l8 m8 s12 offset-l2 offset-m2'>
                            <div class='card red darken-2'>
                                <div class='card-content white-text'>
                                    <span class='card-title'><strong>Usted no tiene permitido realizar planeación en el periodo actual.</strong></span>
                                    <div class='divider col l12 m12 s12'></div>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td><i class='medium material-icons'>warning</i></td>
                                                <td class='center-align'><h6 class='white-text'>Por favor comuniquese con su coordinador.</h6></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input hidden readonly id='txtHorasMes' class='center-align' name='txtHorasMes' value='' type='number'/>
                    <input hidden readonly id='txtIdDedicacion' class='center-align' name='txtIdDedicacion' value='' type='number'/>";
            $planear = false;
        }
        return $planear;
        mysqli_close($connection);
    }

    public static function guardarPlaneacion($idDedicacion, $idAsignado, $hrsInvertir, $mtsInvertir, $hrsDispo, $observacion){
        require('../Core/connection.php');
        $acumHrs = 0;
        $acumMin = 0;
        $con1 = "SELECT * FROM pys_asignaciones;";
        $registros = mysqli_query($connection, $con1);
        $count = mysqli_num_rows($registros);
        if ($count == 0) {
            $idAsignacion = 1;
        } else if ($count > 0) {
            $idAsignacion = $count + 1;
        }
        $query = "INSERT INTO pys_asignaciones (idAsignacion, idDedicacion, idAsignado, horasInvertir, minutosInvertir, observacion, estadoAsignacion) VALUES ";
        for ($i = 0; $i < count($idAsignado); $i++) {
            $con2 = "SELECT * FROM pys_asignaciones WHERE idAsignado = $idAsignado[$i] AND idDedicacion = $idDedicacion;";
            $resultado = mysqli_query($connection, $con2);
            $infoDB = mysqli_fetch_array($resultado);
            if ($hrsInvertir[$i] != 0 && $infoDB['horasInvertir'] == null || $mtsInvertir[$i] != 0 && $infoDB['minutosInvertir'] == null){
                $acumHrs += $hrsInvertir[$i];
                $acumMin += $mtsInvertir[$i];
                if ($hrsInvertir[$i] != "" && $mtsInvertir[$i] == "") {
                    $mtsInvertir[$i] = 0;
                    $query .= "('$idAsignacion', '$idDedicacion', '$idAsignado[$i]', '$hrsInvertir[$i]', '$mtsInvertir[$i]', '$observacion[$i]', 1),";
                    $idAsignacion++;
                } else if ($hrsInvertir[$i] == "" && $mtsInvertir[$i] != "") {
                        $hrsInvertir[$i] = 0;
                        $query .= "('$idAsignacion', '$idDedicacion', '$idAsignado[$i]', '$hrsInvertir[$i]', '$mtsInvertir[$i]', '$observacion[$i]', 1),";
                        $idAsignacion++;
                } else if ($hrsInvertir[$i] != "" && $mtsInvertir[$i] != ""){
                        $query .= "('$idAsignacion', '$idDedicacion', '$idAsignado[$i]', '$hrsInvertir[$i]', '$mtsInvertir[$i]', '$observacion[$i]', 1),";
                        $idAsignacion++;
                }
            } else if ($hrsInvertir[$i] != $infoDB['horasInvertir'] || $mtsInvertir[$i] != $infoDB['minutosInvertir'] || $observacion[$i] != $infoDB['observacion']) {
                $result = Planeacion::actualizarRegistro($idAsignado[$i], $idDedicacion, $hrsInvertir[$i], $mtsInvertir[$i], $observacion[$i]);
                echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
            }
        }
        $query_final = substr($query, 0, -1);
        $consulta2 = $query_final;
        $resultado2 = mysqli_query($connection, $consulta2);
        if ($resultado2) {
            $acumTot = ($acumHrs * 60) + $acumMin;
            if ($acumTot >= 60) {
                $acumHrs = floor($acumTot / 60);
                $acumMin = round(((($acumTot / 60) - $acumHrs) * 60),0);
                $acumTot1 = round(($acumTot / 60),2);
                //echo "<script> alert ('Se agregaron ".$acumHrs." horas y ".$acumMin." minutos. Quedan ".($hrsDispo - $acumTot1)." Horas disponibles para asignar.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
            } else {
                //echo "<script> alert ('Se agregaron ".$acumMin." minutos. Quedan ".($hrsDispo - $acumTot1)." Horas disponibles para asignar.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
            }
        }
        mysqli_close($connection);
    }

    public static function actualizarRegistro($idAsignado, $idDedicacion, $hrsInvertir, $mtsInvertir, $observacion) {
        require('../Core/connection.php');
        if ($hrsInvertir == null && $mtsInvertir == null && $observacion != null) {
            echo "<script> alert ('No puede dejar observaciones en los PS sin haber asignado tiempos.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
        } else {
            $query = "UPDATE pys_asignaciones SET horasInvertir = '$hrsInvertir', minutosInvertir = '$mtsInvertir', observacion = '$observacion' WHERE idAsignado = '$idAsignado' AND idDedicacion = '$idDedicacion';";
            $resultado = mysqli_query($connection, $query);
        }
        mysqli_close($connection);
    }

    public static function verificarInfo($idPeriodo, $idPersona) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_dedicaciones.idDedicacion
        FROM pys_dedicaciones
        INNER JOIN pys_asignaciones ON pys_asignaciones.idDedicacion = pys_dedicaciones.idDedicacion
        WHERE pys_dedicaciones.periodo_IdPeriodo = '$idPeriodo'
        AND pys_dedicaciones.persona_IdPersona = '$idPersona';";
        $resultado = mysqli_query($connection, $consulta);
        $count = mysqli_num_rows($resultado);
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function actualizarTiempos ($idAsignado, $hrsInvertir, $mtsInvertir) {
        require('../Core/connection.php');
        $consulta = "UPDATE pys_asignaciones
            SET horasInvertir = '$hrsInvertir', minutosInvertir = '$mtsInvertir' 
            WHERE idAsignado = '$idAsignado';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado){
            echo "<script> alert ('Se actualizó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/planeacion.php">';
        }
        mysqli_close($connection);
    }

}
?>