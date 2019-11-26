<?php

    class EliminadosEntidades {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_entidades WHERE est = 0;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la Entidad/Empresa</th>
                            <th>Nombre corto de la Entidad/Empresa</th>
                            <th>Descripción de la Entidad/Empresa</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idEntidad = $datos['idEnt'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreEnt'].'</td>
                            <td>'.$datos['nombreCortoEnt'].'</td>
                            <td>'.$datos['descripcionEnt'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idEntidad.'\', \'../Controllers/ctrl_eliminadosEntidades.php\', \'la Entidad\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Entidades Eliminadas</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarEntidad($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_entidades SET est = 1 WHERE idEnt = '$id';";
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