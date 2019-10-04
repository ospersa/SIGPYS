<?php

    Class InformeMisTiempos {

        public static function busqueda ($fechaInicial, $fechaFinal, $user) {
            require('../Core/connection.php');
            $fech == NULL;
            $consulta = "SELECT pys_asignados.idSol, pys_asignados.idProy, pys_proyectos.codProy, pys_proyectos.nombreProy, pys_tiempos.idTiempo, pys_tiempos.idAsig, pys_tiempos.fechTiempo,pys_tiempos.notaTiempo, pys_tiempos.horaTiempo, pys_tiempos.minTiempo, pys_tiempos.idFase, pys_fases.nombreFase
            FROM pys_asignados
            INNER JOIN pys_tiempos ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_proyectos ON pys_asignados.idProy = pys_proyectos.idProy
            INNER JOIN pys_fases ON pys_tiempos.idFase = pys_fases.idFase
            INNER JOIN pys_personas ON pys_asignados.idPersona =pys_personas.idPersona
            INNER JOIN pys_login on pys_personas.idPersona=pys_login.idPersona
            WHERE estTiempo = '1' AND fechTiempo >= '$fechaInicial' AND fechTiempo <= '$fechaFinal' AND pys_login.usrLogin = '$user' ORDER BY pys_tiempos.fechTiempo ASC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            $consulta2= "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo) FROM pys_tiempos
            INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
            INNER JOIN pys_personas ON pys_asignados.idPersona =pys_personas.idPersona
            INNER JOIN pys_login on pys_personas.idPersona=pys_login.idPersona
            INNER JOIN pys_proyectos ON pys_asignados.idProy = pys_proyectos.idProy
            INNER JOIN pys_fases ON pys_tiempos.idFase = pys_fases.idFase
            WHERE fechTiempo >= '$fechaInicial' AND fechTiempo <= '$fechaFinal' AND pys_login.usrLogin = '$user' AND pys_tiempos.estTiempo = '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2= mysqli_fetch_array($resultado2);
            $totalHora = $datos2["SUM(pys_tiempos.horaTiempo)"]; 
            $totalMin = $datos2["SUM(pys_tiempos.minTiempo)"];
            if($totalMin >= 60){
                $totalHora = ($totalMin/60)+$totalHora;
                $totalMin = $totalMin%60 ;
                $totalHora = intval($totalHora);
                $totalMin = intval($totalMin);
            }
            if ($registros > 0) {
                echo'<h5 class="black-text">Tiempo total trabajado: <strong>'.$totalHora.' horas y '.$totalMin.' minutos </strong> desde <strong>'.$fechaInicial.'</strong>, hasta <strong>'.$fechaFinal.'</strong></h5><br><br>';
                $tabla = '  <table id="infMisTiempos" class="responsive-table" border="1">
                            <thead>
                                <tr class="row-teal3">
                                    <th>Fecha</th>
                                    <th>Código solicitud</th>
                                    <th>Código Proyecto</th>
                                    <th>Nombre Proyecto</th>
                                    <th>Producto/Servicio</th>
                                    <th>Descripción Producto/Servicio</th>
                                    <th>Tiempo</th>
                                    <th>Nota</th>
                                    <th>Fase</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>';
                while($datos = mysqli_fetch_array($resultado)){
                    if ($fech == NULL){
                        $fech = $datos['fechTiempo'];
                        $tothor += $datos['horaTiempo'];
                        $totmin += $datos['minTiempo'];
                        if($totmin >= 60){
                            $tothor = $tothor + ($totmin/60);
                            $totmin = $totmin%60; 
                        }
                    } else if ($fech == $datos['fechTiempo']){
                        $tothor += $datos['horaTiempo'];
                        $totmin += $datos['minTiempo'];
                        if ($totmin >= 60){
                            $tothor = $tothor + ($totmin/60);
                            $totmin = $totmin%60; 
                        }
                  

                    } else {
                        $tabla .= "<tr>
                                <th class='teal lighten-4' colspan='6' style='text-align: right;'>Tiempo total trabajado el $fech</th>
                                <th class='teal lighten-4' colspan='5' style='text-align: center;'>$tothor Horas y $totmin minutos</th>
                            </tr>";
                        $fech = $datos['fechTiempo'];
                        $tothor = '';
                        $totmin = '';
                        $tothor += $datos['horaTiempo'];
                        $totmin += $datos['minTiempo'];
                        
                        if($totmin >= 60){
                            $tothor = $tothor + ($totmin/60);
                            $totmin = $totmin%60; 
                        }
                    }
                    
                        
                    $fecha = $datos['fechTiempo'];
                    $idSol = $datos['idSol'];
                    $codProy = $datos['codProy'];
                    $nombreProy = $datos['nombreProy'];
                    $horaTiem = $datos['horaTiempo'];
                    $minTiem = $datos['minTiempo'];
                    $fase = $datos['nombreFase'];
                    $notaTiempo = $datos['notaTiempo'];
                    $consulta1 = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.ObservacionAct
					FROM pys_actsolicitudes
					INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
					WHERE pys_actsolicitudes.est ='1' AND pys_actsolicitudes.idSol='$idSol';";
					$resultado1 = mysqli_query($connection, $consulta1);
					while($datos1 = mysqli_fetch_array($resultado1)){
                        $solIni=$datos1['idSolIni'];
                        $detSol=$datos1['ObservacionAct'];
                    }
                    $tabla .= ' <tr class="middle">
                                <td>'.$fecha.'</td>
                                <td>'.$solIni.'</td>
                                <td>'.$codProy.'</td>
                                <td>'.$nombreProy.'</td>
                                <td> P'.$idSol.'</td>
                                <td><p class="truncate">'.$detSol.'</p></td>
                                <td>'.$horaTiem.' Horas y '.$minTiem.' minutos </td>
                                <td><p class="truncate">'.$notaTiempo.'</p></td>
                                <td>'.$fase.'</td>
                                </tr>';
                }
                $tabla .= '<tr>
                                <th class="teal lighten-4" colspan="6" style="text-align: right;">Tiempo total trabajado el '.$fech.'</th>
                                <th class="teal lighten-4" colspan="5" style="text-align: center;">'.$tothor.' Horas y '.$totmin.' minutos</th>
                        </tr>
                        </tbody>
                        </table>';
            }else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No tiene tiempos registrados entre el '.$fechaInicial. ' y '.$fechaFinal.'</h6></div>';
            }

            echo $tabla;
            mysqli_close($connection);
        }

        public static function descarga ($fechaInicial, $fechaFinal) {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=inf-tiempos-producto-servicio.xls");
            header('Cache-Control: max-age=0');
            header("Pragma: no-cache");
            header("Expires: 0");
            echo '  <html>
                                <head>
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                                    <style>
                                        .row-teal3 {
                                            background-color: #80cbc4;
                                            height: 40px;
                                            position: fixed;
                                        }
                                        .middle {
                                            vertical-align: middle;
                                            height: 40px;
                                        }
                                        .center {
                                            width: 100px;
                                            text-align: center;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1 align="center">Informe de Tiempos - Productos/Servicios</h1>
                                    <h3>Mostrando resultados desde: '.$fechaInicial.' - hasta: '.$fechaFinal.'</h3>';
            $tabla = InformeTiemposProductoServicio::busqueda($fechaInicial, $fechaFinal);
            echo $tabla;
            echo '      </body>
                    </html>';
        }

    }
?>