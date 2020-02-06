<?php
    class Home{

        public static function agendaDia($user){
            require('../Core/connection.php');
            $string = "";
            $fecha = date("Y-m-d"); 
            $consulta ="SELECT pys_agenda.idAsig , pys_agenda.horaAgenda, pys_agenda.minAgenda, pys_agenda.notaAgenda, pys_agenda.estAgenda 
            FROM pys_agenda 
            INNER JOIN pys_asignados ON pys_asignados.idAsig =pys_agenda.idAsig
            INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
            WHERE pys_agenda.estAgenda <> 3 AND pys_asignados.est = 1 AND pys_login.est = 1 AND pys_login.usrLogin = '$user' AND pys_agenda.fechAgenda ='$fecha'";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado)>0){
                while ($datos = mysqli_fetch_array($resultado)){
                    $idAsig = $datos['idAsig'];
                    $notaAgenda = $datos['notaAgenda'];
                    $horaAgenda = $datos['horaAgenda'];
                    $minAgenda = $datos['minAgenda'];
                    $consulta2 = "SELECT pys_solicitudes.idSol, pys_solicitudes.descripcionSol, pys_actualizacionproy.nombreProy, pys_actualizacionproy.codProy FROM pys_asignados
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_asignados.idSol
                    WHERE pys_asignados.est = 1 AND pys_actualizacionproy.est = 1 AND pys_solicitudes.est = 1 AND pys_asignados.idAsig = $idAsig";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $idSol = $datos2['idSol'];
                    $descripcionSol = $datos2['descripcionSol'];
                    $nombreProy = $datos2['nombreProy'];
                    $codProy = $datos2['codProy'];
                    $string .='
                    <div class="card">
                        <div class="card-content ">
                            <div class="row">
                                <div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                                    <p class="left-center teal-text">
                                        <h6>'.$codProy.' -- '.$nombreProy.'</h6>
                                    </p>
                                </div>
                                <div class="trunca input-field col l10 m10 s12  offset-l1 offset-m1">
                                    <p class="left-align black-text truncate">P'.$idSol.' '.$descripcionSol.'</p>
                                </div>
                                <div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                                    <p class="left-align black-text">Tiempo: '.$horaAgenda.' Horas '.$minAgenda.' Minutos  </p>
                                </div>
                                <div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                                    <p class="left-align black-text">Actividad:'.$notaAgenda.'</p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                        ';
                }
            } else {
                $string ='<div class="card">
                <div class="card-content ">
                    <div class="row">
                        <div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                            <p class="left-center teal-text">
                                <h6>No hay agenda</h6>
                            </p>
                        </div>                
                        </div>                
                    </div>
                </div>';
            }

            return $string;
            mysqli_close($connection);
        }

        public static function solicitudes ($user){
            require('../Core/connection.php');
            $json = array();
            $consulta = "SELECT COUNT(pys_actsolicitudes.idSol), pys_estadosol.nombreEstSol FROM pys_actsolicitudes 
            INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
            INNER JOIN pys_asignados ON pys_asignados.idSol = pys_actsolicitudes.idSol 
            INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona 
            WHERE pys_actsolicitudes.est = 1 AND pys_asignados.est = 1 AND pys_login.est = 1 AND pys_login.usrLogin = '$user' GROUP by pys_actsolicitudes.idEstSol";
            $resultado = mysqli_query($connection, $consulta);
            while($data = mysqli_fetch_array($resultado) ){
                $json[]     = array(
                    'estado' => $data['nombreEstSol'],
                    'cantidad'     => $data['COUNT(pys_actsolicitudes.idSol)'],
                );
            }
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 

        }

        public static function tiempo($user){
            require('../Core/connection.php');
            $json = array();
            $fecha = date("Y-m-d");
            $consultaPerAct ="SELECT idPeriodo, inicioPeriodo, finPeriodo FROM pys_periodos WHERE inicioPeriodo <= '$fecha' AND finPeriodo >= '$fecha'";
            $resultadoP = mysqli_query($connection, $consultaPerAct);
            $datosP = mysqli_fetch_array($resultadoP);
            $idPeriodo = $datosP['idPeriodo'];
            $fechIni = $datosP['inicioPeriodo'];
            $fechaFin= $datosP['finPeriodo'];
            $consulta = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona
            WHERE pys_tiempos.estTiempo= 1 AND pys_login.usrLogin ='$user' AND fechTiempo >= '$fechIni' AND fechTiempo <='$fechaFin'";
            $resultado = mysqli_query($connection, $consulta);
            $data = mysqli_fetch_array($resultado);
            $consulta1 ="SELECT totalHoras FROM pys_dedicaciones  
            INNER JOIN pys_login ON pys_login.idPersona = pys_dedicaciones.persona_IdPersona
            WHERE periodo_IdPeriodo = $idPeriodo AND  pys_login.usrLogin = '$user' ";
            $resultado1 = mysqli_query($connection, $consulta1);
            $data1 = mysqli_fetch_array($resultado1);
            $horas = (($data['SUM(horaTiempo)']*60)+$data['SUM(minTiempo)'])/60;
            $horasTotal = (int) $data1['totalHoras']-$horas;
            $json[]     = array(
                'label' => 'Horas registradas',
                'cant'     => $horas,
            );
            $json[]     = array(
                'label' => 'Horas por registrar',
                'cant'     => $horasTotal,
            );
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 
        } 

        public static function solicitudesAsig($user){
            require('../Core/connection.php');
            $string ='<table class="responsive-table  left">
            <thead>
                <tr>
                    <th>Producto/Servicio</th>
                    <th>Cód. proyecto en Conecta-TE</th>
                    <th>Proyecto</th>
                    <th>Descripción Producto/Servicio</th>
                    <th>Fecha prevista entrega</th>
                </tr>
            </thead>';
            $consulta = "SELECT pys_actsolicitudes.idSol,pys_solicitudes.descripcionSol, pys_actsolicitudes.fechPrev, pys_actualizacionproy.nombreProy, pys_actualizacionproy.codProy  FROM pys_actsolicitudes 
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_asignados ON pys_asignados.idSol = pys_actsolicitudes.idSol 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona 
            WHERE pys_actsolicitudes.est = 1 AND pys_solicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_asignados.est = 1 AND pys_login.est = 1 AND pys_login.usrLogin = '$user'
            ORDER BY `pys_asignados`.`fechAsig` DESC LIMIT 5";
            $resultado = mysqli_query($connection, $consulta);
            while($datos = mysqli_fetch_array($resultado) ){
                $idSol = $datos['idSol'];
                $descripcionSol = $datos['descripcionSol'];
                $fechPrev = $datos['fechPrev'];
                $nombreProy = $datos['nombreProy'];
                $codProy = $datos['codProy'];
                $string .='
                <tbody >    
                <tr>
                        <td>P'.$idSol.'</td>
                        <td>'.$codProy.'</td>
                        <td>'.$nombreProy.'</td>
                        <td><p class = "truncate">'.$descripcionSol.'</p></td>
                        <td>'.$fechPrev.'</td> 
                </tr>
                </tbody> ';
            }
            $string .= '</table>';
			$string .= '<div class="col s12 m3 l3 offset-m9 offset-l9"><br><a href="misproductosservicios.php" class="teal-text right-align">+ Ver todos mís Productos/Servicios</a> </div>';
            return $string;  
            mysqli_close($connection);  
        }

        public static function solIniSinEsp(){
            require('../Core/connection.php');
            $count = 0;
            $json = array();
            $consulta = "SELECT * FROM pys_solicitudes WHERE idTSol = 'TSOL01' AND est = 1";
            $resultado = mysqli_query($connection, $consulta);
            while($datos = mysqli_fetch_array($resultado) ){
                $idSol = $datos['idSol'];
                $consulta2 = "SELECT idSol FROM pys_solicitudes WHERE idSolIni = '$idSol' AND est = 1; ";
                $resultado2 = mysqli_query($connection, $consulta2);
                if (mysqli_num_rows($resultado2)<=0){
                    $count += 1;
                }
            }
            $conSolEs =mysqli_num_rows($resultado)-$count;
            $json[]     = array(
                'label' => 'Con Producto/Servicio',
                'cant'     => $conSolEs,
            );
            $json[]     = array(
                'label' => 'Sin Producto/Servicio',
                'cant'     => $count,
            );
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 
        }

        public static function productosInventario(){
            require('../Core/connection.php');
            $json = array();
            $consulta = "SELECT SUM(idInventario), estadoInv FROM pys_actinventario WHERE est = 1 GROUP by estadoInv ";
            $resultado = mysqli_query($connection, $consulta);
            while($datos = mysqli_fetch_array($resultado) ){
                $cant = $datos['SUM(idInventario)'];
                $estadoInv = $datos['estadoInv'];
                $json[]     = array(
                    'label' => $estadoInv,
                    'cant'     => $cant,
                );
            }
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 
        }
        
        public static function productoSinCotizacion(){
            require('../Core/connection.php');
            $json = array();
            $consulta = "SELECT COUNT(pys_actsolicitudes.idSol)
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            WHERE pys_solicitudes.idTSol = 'TSOL02'
            AND pys_actsolicitudes.est = 1
            AND pys_solicitudes.est = 1
            AND pys_actualizacionproy.est = 1
            AND (pys_actsolicitudes.idEstSol = 'ESS002'
            OR pys_actsolicitudes.idEstSol = 'ESS003'
            OR pys_actsolicitudes.idEstSol = 'ESS004' 
            OR pys_actsolicitudes.idEstSol = 'ESS005')
            AND pys_solicitudes.idSol NOT IN (SELECT pys_cotizaciones.idSolEsp FROM pys_cotizaciones)";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $cant = $datos['COUNT(pys_actsolicitudes.idSol)'];
            $json[]     = array(
                'label' => 'Sin Presupuesto',
                'cant'     => $cant,
            );
            $consulta2 ="SELECT COUNT(pys_actsolicitudes.idSol)
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            WHERE pys_solicitudes.idTSol = 'TSOL02'
            AND pys_actsolicitudes.est = 1
            AND pys_solicitudes.est = 1
            AND pys_actualizacionproy.est = 1
            AND (pys_actsolicitudes.idEstSol = 'ESS002'
            OR pys_actsolicitudes.idEstSol = 'ESS003'
            OR pys_actsolicitudes.idEstSol = 'ESS004' 
            OR pys_actsolicitudes.idEstSol = 'ESS005')";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $cant2 = $datos2['COUNT(pys_actsolicitudes.idSol)']-$cant;
            $json[]     = array(
                'label' => 'Con Presupuesto',
                'cant'     => $cant2,
            );
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 

        }

        public static function presupuestoProyect($user, $cod){
            require('../Core/connection.php');
            $json = array();
            if ($cod == 1){
                $consulta = "SELECT pys_cursosmodulos.idProy, pys_actualizacionproy.codProy
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                AND pys_actualizacionproy.est = '1'
                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                AND pys_solicitudes.fechSol >= '2019/01/01'
                GROUP BY pys_actualizacionproy.codProy;";
            } else if ($cod == 2){
                $consulta = "SELECT pys_cursosmodulos.idProy, pys_actualizacionproy.codProy
                FROM pys_actsolicitudes
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                INNER JOIN pys_asignados ON pys_asignados.idProy = pys_actualizacionproy.idProy
                INNER JOIN pys_login ON pys_login.idPersona = pys_asignados.idPersona
                WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                AND pys_actualizacionproy.est = '1'
                AND pys_asignados.est = '1'
                AND pys_login.est = '1'
                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                AND pys_solicitudes.fechSol >= '2019/01/01'
                AND (idRol= 'ROL024' OR idRol= 'ROL025')
                AND pys_login.usrLogin = '$user'
                GROUP BY pys_actualizacionproy.codProy;";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                while($datos = mysqli_fetch_array($resultado) ){
                    $cod = 0;
                    $acumuladoTotalPS = 0;
                    $acumuladoTotalPresupuesto = 0;
                    $idProy = $datos['idProy'];
                    $codProy = $datos['codProy'];
                    $consulta2 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                    INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                    AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' AND pys_estadosol.est = '1' AND (pys_solicitudes.fechSol >= '2019/01/01') AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND pys_actsolicitudes.presupuesto <> '0'
                    ORDER BY pys_actsolicitudes.idSol ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $registros2 = mysqli_num_rows($resultado2);
                    $consulta3 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                    INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                    AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' AND pys_estadosol.est = '1' AND (pys_solicitudes.fechSol >= '2019/01/01') AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND pys_actsolicitudes.presupuesto = '0'
                    ORDER BY pys_actsolicitudes.idSol;";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $registros3 = mysqli_num_rows($resultado3);
                    if ($registros2 > 0) {
                        while($datos2 = mysqli_fetch_array($resultado2) ){
                            $valorPS = 0;
                            $salarioHor = 0;
                            $salarioMin = 0;
                            $idSol = $datos2['idSol'];
                            $consulta4 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_actsolicitudes.idSol, pys_salarios.salario
                                FROM pys_tiempos 
                                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                                INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                                WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') 
                                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                                AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol = '$idSol'  AND pys_salarios.estSal = '1' AND pys_actsolicitudes.est = '1'
                                ORDER BY pys_asignados.idSol;";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            while ($datos4 = mysqli_fetch_array($resultado4)) {
                                $salarioHor = $datos4['salario'];
                                $salarioMin = $salarioHor / 60;
                                $valorPS += (($datos4['horas'] * 60) + $datos4['minutos']) * $salarioMin;
                            }
                            $acumuladoTotalPS += $valorPS;
                            $acumuladoTotalPresupuesto += $datos2['presupuesto'];
                            $diferencia = $datos2['presupuesto'] - $valorPS;
                        }
                    }
                    if ($registros3 > 0) {
                        while($datos3 = mysqli_fetch_array($resultado3) ){
                            $valorPS = 0;
                            $salarioHor = 0;
                            $salarioMin = 0;
                            $idSol = $datos3['idSol'];
                            $consulta4 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_actsolicitudes.idSol, pys_salarios.salario
                                FROM pys_tiempos 
                                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                                INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                                WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') 
                                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                                AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol = '$idSol'  AND pys_salarios.estSal = '1' AND pys_actsolicitudes.est = '1'
                                ORDER BY pys_asignados.idSol;";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            while ($datos4 = mysqli_fetch_array($resultado4)) {
                                $salarioHor = $datos4['salario'];
                                $salarioMin = $salarioHor / 60;
                                $valorPS += (($datos4['horas'] * 60) + $datos4['minutos']) * $salarioMin;
                            }
                            $acumuladoTotalPS += $valorPS;
                            $acumuladoTotalPresupuesto += $datos3['presupuesto'];
                            $diferencia = $datos3['presupuesto'] - $valorPS;
                        }
                    }
                    if ($acumuladoTotalPS != 0 || $acumuladoTotalPresupuesto != 0){
                        $json[]     = array(
                            'proyecto' => $codProy,
                            'ejecutado'     => $acumuladoTotalPS,
                            'presupuesto'     => $acumuladoTotalPresupuesto,
                        );
                    }
                }
            }
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 
        }
    }
?>
