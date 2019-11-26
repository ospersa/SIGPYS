<?php

    class EliminadosServicios {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_servicios.idSer, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_servicios.nombreCorto, pys_servicios.descripcionSer, pys_servicios.productoOservicio, pys_costos.costo FROM pys_servicios
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_costos ON pys_costos.idSer = pys_servicios.idSer
            WHERE pys_servicios.est = '0' AND pys_equipos.est = '1' AND pys_costos.est = '1' AND pys_costos.idClProd = '' AND pys_costos.idTProd = '' 
            GROUP BY pys_servicios.idSer
            ORDER BY pys_equipos.nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Servicio</th>
                            <th>Nombre corto</th>
                            <th>Descripción de servicio</th>
                            <th>¿Genera producto?</th>
                            <th>Costo $</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idServicio = $datos['idSer'];
                    $costo = number_format((integer)$datos['costo'],1,",","."); 
                    echo'
                        <tr>
                            <td>'.$datos['nombreEqu'].'</td>
                            <td>'.$datos['nombreSer'].'</td>
                            <td>'.$datos['nombreCorto'].'</td>
                            <td>'.$datos['descripcionSer'].'</td>
                            <td>'.$datos['productoOservicio'].'</td>
                            <td>$'.$costo.'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idServicio.'\', \'../Controllers/ctrl_eliminadosServicios.php\', \'el Servicio\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Servicios Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarServicio($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_servicios SET est = 1 WHERE idSer = '$id';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                $string = 'Se actualizó correctamente la información';
            }else{
               $string = 'Ocurrió un error al intentar actualizar el registro';              
            }
            echo $string;
            mysqli_close($connection);
        }

       
        
    }

?>