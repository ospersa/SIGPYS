<?php

    class EliminadosEquipos {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_Equipos WHERE est = 0;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del Equipo</th>
                            <th>Descripción del Equipo</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idEquipo = $datos['idEqu'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreEqu'].'</td>
                            <td>'.$datos['descripcionEqu'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idEquipo.'\', \'../Controllers/ctrl_eliminadosEquipos.php\', \'el Equipo\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Equipos Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarEquipo($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_equipos SET est = 1 WHERE idEqu = '$id';";
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