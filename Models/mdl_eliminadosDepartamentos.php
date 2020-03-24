<?php

    class EliminadosDepartamentos {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_entidades.nombreEnt, pys_facultades.nombreFac, pys_departamentos.idDepto, pys_departamentos.nombreDepto, pys_departamentos.coordDepto, pys_departamentos.cargoCoordDepto, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
            FROM pys_departamentos
            INNER JOIN pys_facdepto ON pys_facdepto.idDepto = pys_departamentos.idDepto
            INNER JOIN pys_facultades ON pys_facdepto.idFac = pys_facultades.idFac
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_personas ON pys_departamentos.coordDepto = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_departamentos.cargoCoordDepto = pys_cargos.idCargo
            WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_departamentos.est = '0' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '0' AND pys_personas.est = '1' AND pys_cargos.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Facultad</th>
                            <th>Departamento</th>
                            <th>Director Departamento</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idDepartamento = $datos['idDepto'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreEnt'].'</td>
                            <td>'.$datos['nombreFac'].'</td>
                            <td>'.$datos['nombreDepto'].'</td>
                            <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idDepartamento.'\', \'../Controllers/ctrl_eliminadosDepartamentos.php\', \'el Departamento\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Departamentos Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarDepartamento($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_departamentos SET est = '1' WHERE idDepto='$id';";
            $resultado = mysqli_query($connection, $consulta);
            $consulta = "UPDATE pys_facdepto SET estFacdeptoDepto = '1' WHERE idDepto='$id';";
            $resultado2 = mysqli_query($connection, $consulta);
            if ($resultado && $resultado2){
                $string = 'Se actualizó correctamente la información';
            }else{
               $string = 'Ocurrió un error al intentar actualizar el registro';              
            }
            echo $string;
            mysqli_close($connection);
        }

       
        
    }

?>