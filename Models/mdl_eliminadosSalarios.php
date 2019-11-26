<?php

    class EliminadosSalarios {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_salarios.idSalarios, pys_salarios.mes, pys_salarios.anio, pys_salarios.salario
            FROM pys_salarios
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_salarios.idPersona
            WHERE pys_salarios.estSal = 0
            ORDER BY pys_personas.apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Salario</th>
                            <th>Vigente desde</th>
                            <th>Vigente hasta</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idSalario = $datos['idSalarios'];
                    echo'
                        <tr>
                            <td>'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</td>
                            <td>$ '.number_format($datos['salario'], 2,",",".").'</td>
                            <td>'.$datos['mes'].'</td>
                            <td>'.$datos['anio'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idSalario.'\', \'../Controllers/ctrl_eliminadosSalarios.php\', \'el Salario\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Salarios Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarSalario($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_salarios SET estSal = 1 WHERE idSalarios = $id;";
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