<?php

    class EliminadosFrentes {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
            FROM pys_frentes
            INNER JOIN pys_personas ON pys_frentes.coordFrente = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_personas.idCargo = pys_cargos.idCargo
            WHERE pys_frentes.est = '0' AND pys_personas.est = '1' AND pys_cargos.est = '1' 
            ORDER BY pys_frentes.idFrente;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Frente</th>
                            <th>Descripción</th>
                            <th>Coordinador de Frente</th>
                            <th>Cargo</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idFrente = $datos['idFrente'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreFrente'].'</td>
                            <td>'.$datos['descripcionFrente'].'</td>
                            <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idFrente.'\', \'../Controllers/ctrl_eliminadosFrentes.php\', \'el Frente\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Frentes Eliminados</h6></div>';

            }
            mysqli_close($connection);
        }

        public static function  activarFrente($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_Frentes SET est = 1 WHERE idFrente = '$id';";
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