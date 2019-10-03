<?php

    Class Terminar {

        public static function selectProyectoUsuario ($busqueda, $user){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy,pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy  FROM pys_asignados 
            INNER JOIN pys_proyectos on pys_proyectos.idProy = pys_asignados.idProy 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy =pys_proyectos.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND pys_actualizacionproy.est=1 AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_proyectos.est=1 AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
            echo '  <select name="sltProy" id="sltProy" >';
            while ($datos = mysqli_fetch_array($resultado)) {
                $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                echo '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
            }
            echo '  </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        } else {
            echo '  <select name="sltProy" id="sltProy" required>
                        <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        }
        mysqli_close($connection);
        }

        public static function cargarProyectosUser ($user, $cod, $busProy, $fechIni, $fechFin){
            require('../Core/connection.php');
            //valida los proyectos en los cuales es agigando como gestor o asesor RED.
            if ($cod == 1){
                $resultado = Terminar::BuscarProyecto($user, $busProy);
            } else if ($cod == 2){
                $resultado = Terminar::buscarFecha($user, $fechIni, $fechFin);
            } else if ($cod == 3){
                $resultado = Terminar::buscarUsuario($user);
            } else if ($cod == 4){
                //busqueda de las tres opciones
            }
            if (mysqli_num_rows($resultado) > 0 ){
                $string = ' <table class="responsive-table left" id="terminar">
                <thead>
                    <tr>
                        <th>Cód. proyecto en Conecta-TE</th>
                        <th>Proyecto</th>
                        <th>Código solicitud</th>
                        <th>Producto/Servicio</th>
                        <th>Equipo -- Servicio</th>
                        <th>Descripción Producto/Servicio</th>
                        <th>Fecha prevista entrega</th>
                        <th>Fecha creación</th>
                        <th>Terminar y enviar correo</th>
                    </tr>
                </thead>
                <tbody>';
                while ($data = mysqli_fetch_array($resultado)){
                    $idProy = $data['idProy']; 
                    $codProy = $data['codProy'];
                    $nombreProy = $data['nombreProy'];
                    // consulta listado de Productos/servicios del proyecto
                    $consulta1 = "SELECT pys_solicitudes.idSol, pys_solicitudes.idSolIni, pys_solicitudes.fechSol,pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev FROM pys_proyectos 
                    INNER JOIN pys_cursosmodulos ON pys_proyectos.idProy = pys_cursosmodulos.idProy 
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
                    INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu            
                    WHERE pys_proyectos.idProy = '$idProy' AND pys_actsolicitudes.est=1 AND pys_solicitudes.idTSol= 'TSOL02' AND pys_actsolicitudes.est=1 AND pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS007' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_equipos.est = 1 AND pys_servicios.est = 1 ORDER BY pys_solicitudes.fechSol  ASC";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    if (mysqli_num_rows($resultado1) > 0){
                        while ($data1 = mysqli_fetch_array($resultado1)){
                            $idSol = $data1['idSol'];
                            $idSolIni = $data1['idSolIni'];
                            $fechSol = $data1['fechSol'];
                            $nombreEqu = $data1['nombreEqu'];
                            $nombreSer = $data1['nombreSer'];
                            $ObservacionAct = $data1['ObservacionAct'];
                            $fechPrev = $data1['fechPrev'];
                            $consulta2 = "SELECT est FROM pys_asignados WHERE idSol= '$idSol' and est !=0";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            $data2 = mysqli_fetch_array($resultado2);
                            if ($data2['est'] == 1){
                                $color = "red";
                                $mjsTooltip ="Faltan requisitos para terminar el Producto o Servicio";
                            } else if ($data2['est'] == 2){
                                $color = "teal";
                                //abrir modal
                                $mjsTooltip = "Terminar Producto o Servicio";
                            }
                            /*cambiar por == */
                            $string .= '<tr>
                                <td>'.$codProy.'</td>
                                <td>'.$nombreProy.'</td>
                                <td>'.$idSolIni.'</td>
                                <td>P'.$idSol.'</td>
                                <td>'.$nombreEqu.' -- '.$nombreSer.'</td>
                                <td><p class="truncate">'.$ObservacionAct.'</p></td>
                                <td>'.$fechPrev.'</td>
                                <td>'.$fechSol.'</td>
                                <td><a href="#!" data-position="botton" class="modal-trigger tooltipped" data-tooltip="'.$mjsTooltip.'"><i class="material-icons '.$color.'-text">done_all</i></a></td>
                                </tr>';     
                        }
                    } else if ($cod != 3){
                        $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda1</h6></div>';
                    }   
                }
                    $string .= '    </tbody>
                                </table>';
            } else{
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda</h6></div>';
            }
            return $string;
            mysqli_close($connection);
        }

        public static function buscarProyecto($user, $codProy){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy FROM pys_actualizacionproy
            INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_actualizacionproy.est=1  AND pys_asignados.idSol='' AND pys_actualizacionproy.idProy ='$codProy' ";
            $resultado = mysqli_query($connection, $consulta);
            return $resultado;
        }

        public static function buscarUsuario($user){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy FROM pys_actualizacionproy
            INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_actualizacionproy.est=1  AND pys_asignados.idSol=''  ";
            $resultado = mysqli_query($connection, $consulta);
            return $resultado;
        }

        public static function buscarFecha($user, $fechIni, $fechFin){
            require('../Core/connection.php');
            $consulta = "SELECT pys_proyectos.idProy FROM pys_asignados 
            INNER JOIN pys_proyectos on pys_proyectos.idProy = pys_asignados.idProy 
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_proyectos.est=1 AND (pys_proyectos.fechIniProy >= $fechIni OR  pys_proyectos.fechIniProy <= $fechFin) "; /*fecha*/ 
            $resultado = mysqli_query($connection, $consulta);
            return $resultado;
        }

    }
?>
