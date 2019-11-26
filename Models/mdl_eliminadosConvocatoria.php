<?php

    class EliminadosConvocatoria {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_convocatoria WHERE est = 0;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la convocatoria</th>
                            <th>Descripción de la convocatoria</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idConvocatoria = $datos['idConvocatoria'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreConvocatoria'].'</td>
                            <td>'.$datos['descrConvocatoria'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idConvocatoria.'\', \'../Controllers/ctrl_eliminadosConvocatoria.php\', \'la Convocatoria\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Convocatorias Eliminadas</h6></div>';

            }
            mysqli_close($connection);
        }

        public static function  activarConvocatoria($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_convocatoria SET est = 1 WHERE idConvocatoria = '$id';";
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