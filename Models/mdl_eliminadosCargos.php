<?php

    class EliminadosCargos {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_cargos WHERE est = 0;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del Cargo</th>
                            <th>Descripción del Cargo</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idCargo = $datos['idCargo'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td>'.$datos['descripcionCargo'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idCargo.'\', \'../Controllers/ctrl_eliminadosCargos.php\', \'el Cargo\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Cargos Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarCargo($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_cargos SET est = 1 WHERE idCargo = '$id';";
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