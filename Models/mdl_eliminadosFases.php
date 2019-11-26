<?php

    class EliminadosFases {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_fases WHERE est = 0;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la fase</th>
                            <th>Descripción de la fase</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idFase = $datos['idFase'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreFase'].'</td>
                            <td>'.$datos['descripcionFase'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idFase.'\', \'../Controllers/ctrl_eliminadosFases.php\', \'la Fase\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Fases Eliminadas</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarFase($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_Fases SET est = 1 WHERE idFase = '$id';";
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