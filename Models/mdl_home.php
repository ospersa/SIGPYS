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
                            <div class="input-field col l10 m10 s12  offset-l1 offset-m1">
                                <p class="left-align black-text">P'.$idSol.' '.$descripcionSol.'</p>
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
            WHERE pys_actsolicitudes.est = 1 AND pys_asignados.est = 1 AND pys_login.est = 1 AND pys_login.usrLogin = '$user' GROUP by pys_actsolicitudes.idEstSol
                ";
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
    }
?>
