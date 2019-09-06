<?php

    Class InformeTiemposProductoServicio {

        public static function busqueda ($fechaInicial, $fechaFinal) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_asignados.idSol, pys_solicitudes.fechSol, pys_actsolicitudes.fechPrev, pys_actsolicitudes.ObservacionAct, pys_actualizacionproy.idCelula, pys_estadosol.nombreEstSol, pys_servicios.nombreSer, pys_equipos.nombreEqu
                FROM pys_tiempos 
                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_asignados.idProy 
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
                WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_tiempos.estTiempo = '1' 
                AND pys_actualizacionproy.est = '1' AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' 
                AND pys_solicitudes.idTSol = 'TSOL02' AND pys_servicios.est = '1' AND pys_equipos.est = '1'
                AND (pys_tiempos.fechTiempo >= '$fechaInicial' AND pys_tiempos.fechTiempo <= '$fechaFinal') 
                GROUP BY pys_asignados.idSol 
                ORDER BY pys_actualizacionproy.codProy, pys_asignados.idSol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $tabla = '  <table class="responsive-table" border="1">
                            <thead>
                                <tr class="row-teal3">
                                    <th>Frente</th>
                                    <th>Cod. Proyecto</th>
                                    <th>Nombre Proyecto</th>
                                    <th>Célula</th>
                                    <th>Cod. Producto/Servicio</th>
                                    <th>Estado Producto/Servicio</th>
                                    <th>Equipo</th>
                                    <th>Servicio</th>
                                    <th>Descripción Producto/servicio</th>
                                    <th>Fecha Creación</th>
                                    <th>Fecha Estimada entrega</th>
                                    <th>Tiempo Invertido en el Periodo</th>
                                    <th>Tiempo Total a Dedicar P/S</th>
                                    <th>Tiempo Total Invertido P/S</th>
                                </tr>
                            </thead>
                            <tbody>';
                while($datos = mysqli_fetch_array($resultado)){
                    $equipo = $datos['nombreEqu'];
                    $servicio = $datos['nombreSer'];
                    $nombreEstado = $datos['nombreEstSol'];
                    $idCelula = $datos['idCelula'];
                    $idSol = $datos['idSol'];
                    $codProy = $datos['codProy'];
                    $frente = substr($codProy, 0, 2);
                    $nombreProy = $datos['nombreProy'];
                    $observacion = $datos['ObservacionAct'];
                    $fecha = date_create($datos['fechSol']);
                    $fechaCreacion = date_format($fecha, 'Y-m-d');
                    $fechaEsperada = $datos['fechPrev'];
                    $consulta2 = "SELECT SUM(pys_asignados.maxhora), SUM(pys_asignados.maxmin)
                        FROM pys_asignados
                        WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $datos2 = mysqli_fetch_array($resultado2);
                    $consulta3 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                        FROM pys_asignados
                        RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                        WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1'
                        AND (pys_tiempos.fechTiempo >= '$fechaInicial' AND pys_tiempos.fechTiempo <= '$fechaFinal');";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $datos3 = mysqli_fetch_array($resultado3);

                    $consulta4 = "SELECT nombreCelula FROM pys_celulas WHERE idCelula = '$idCelula';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    $datos4 = mysqli_fetch_array($resultado4);
                    $nombreCelula = $datos4['nombreCelula'];

                    $consulta5 = "SELECT SUM(pys_tiempos.horaTiempo), SUM(pys_tiempos.minTiempo)
                        FROM pys_asignados
                        RIGHT JOIN pys_tiempos ON pys_tiempos.idAsig = pys_asignados.idAsig
                        WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol = '$idSol' AND pys_tiempos.estTiempo = '1';";
                    $resultado5 = mysqli_query($connection, $consulta5);
                    $datos5 = mysqli_fetch_array($resultado5);

                    $horasDedicar = $datos2[0];
                    $minutosDedicar = $datos2[1];
                    $totalDedicar = ($horasDedicar * 60) + $minutosDedicar;


                    $horasInvertidas = $datos3[0];
                    $minutosInvertidos = $datos3[1];
                    $totalInvertido = ($horasInvertidas * 60) + $minutosInvertidos;

                    $horasTotalesPS = $datos5[0];
                    $minutosTotalesPS = $datos5[1];
                    $totalPS = ($horasTotalesPS * 60) + $minutosTotalesPS;
                    $tabla .= ' <tr class="middle">
                                    <td>'.$frente.'</td>
                                    <td>'.$codProy.'</td>
                                    <td>'.$nombreProy.'</td>
                                    <td>'.$nombreCelula.'</td>
                                    <td>P'.$idSol.'</td>
                                    <td>'.$nombreEstado.'</td>
                                    <td>'.$equipo.'</td>
                                    <td>'.$servicio.'</td>
                                    <td>'.$observacion.'</td>
                                    <td class="center">'.$fechaCreacion.'</td>
                                    <td class="center">'.$fechaEsperada.'</td>
                                    <td class="center">'.number_format(($totalInvertido / 60), 2, ",", ".").'</td>
                                    <td class="center">'.number_format(($totalDedicar / 60), 2, ",", ".").'</td>
                                    <td class="center">'.number_format(($totalPS / 60), 2, ",", ".").'</td>
                                </tr>';
                }
                $tabla .= '      </tbody>
                        </table>';
            }
            return $tabla;
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