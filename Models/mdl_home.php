<?php
    class Home{

        public static function agendaDia($user){
            require('../Core/connection.php');
            $string = "";
            $fecha = '2019-11-28';/* date("Y-m-d"); */
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
            $fecha = '2019-1-22';/* date("Y-m-d"); */
            $consultaPerAct ="SELECT idPeriodo, inicioPeriodo, finPeriodo FROM pys_periodos WHERE inicioPeriodo < '$fecha' AND finPeriodo > '$fecha'";
            $resultadoP = mysqli_query($connection, $consultaPerAct);
            $datosP = mysqli_fetch_array($resultadoP);
            $idPeriodo = $datosP['idPeriodo'];
            $fechIni = $datosP['inicioPeriodo'];
            $fechaFin= $datosP['finPeriodo'];
            $consulta = "SELECT SUM(horaTiempo), SUM(minTiempo) FROM pys_tiempos 
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_login ON pys_login.idPersona =pys_asignados.idPersona
            WHERE pys_tiempos.estTiempo= 1 AND pys_login.usrLogin ='$user' AND fechTiempo > '$fechIni' AND fechTiempo <'$fechaFin'";
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
            ORDER BY `pys_asignados`.`fechAsig` DESC LIMIT 4";
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
                'label' => 'Con Solicitud Especifica',
                'cant'     => $conSolEs,
            );
            $json[]     = array(
                'label' => 'Sin Solicitud Especifica',
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
                'label' => 'Sin cotización',
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
                'label' => 'Con cotización',
                'cant'     => $cant2,
            );
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection); 

        }

        public static function presupuestoProyect($user, $cod){
            require('../Core/connection.php');
            $json = array();
            $consulta = "SELECT * FROM pys_actualizacionproy ";
            $where = "WHERE idEstProy ='ESP001' OR idEstProy ='ESP003'";
            if ($cod == 2){
                $consulta .= "";
                $where .="";
            }
            $consulta1 ="SELECT SUM(pys_actsolicitudes.presupuesto) FROM `pys_actsolicitudes` INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy WHERE pys_actsolicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_actualizacionproy.idProy= '$idProy'";
            
            mysqli_close($connection); 
        }
    }
?>
