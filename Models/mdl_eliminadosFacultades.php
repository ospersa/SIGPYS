<?php

    class EliminadosFacultades {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_entidades.nombreEnt, pys_facultades.idFac, pys_facultades.nombreFac, pys_facultades.coordFac, pys_facultades.cargoCoordFac,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
            FROM pys_facultades
            INNER JOIN pys_facdepto ON pys_facdepto.idFac = pys_facultades.idFac 
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_personas ON pys_facultades.coordFac = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_facultades.cargoCoordFac = pys_cargos.idCargo
            WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '0'  AND pys_facdepto.facDeptoDepartamento ='No Aplica' AND pys_personas.est = '1' AND pys_cargos.est = '1' 
            ORDER BY pys_facultades.nombreFac;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Facultad</th>
                            <th>Decano</th>
                            <th>Cargo</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idFacultad = $datos['idFac'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreEnt'].'</td>
                            <td>'.$datos['nombreFac'].'</td>
                            <td>'.$datos["apellido1"].' '.$datos["apellido2"].' '.$datos["nombres"].'</td>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idFacultad.'\', \'../Controllers/ctrl_eliminadosFacultades.php\', \'la Facultad\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Facultades Eliminadas</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarFacultad($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_facdepto SET estFacdeptoFac = '1' WHERE pys_facdepto.idFac = '$id';";
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