<?php

    class EliminadosUsuarios {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres,
            pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, 
            pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo, pys_equipos.idEqu, pys_equipos.nombreEqu
            FROM pys_personas               
            INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
            INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_equipos ON pys_personas.idEquipo = pys_equipos.idEqu
            WHERE pys_personas.est = '0' AND pys_cargos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND
            pys_facdepto.estFacdeptoDepto = '1'
            ORDER BY pys_personas.apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Facultad - Departamento</th>
                            <th>Cargo</th>
                            <th>Tipo</th>
                            <th>Identificación</th>
                            <th>Apellidos y Nombres</th>
                            <th>E-Mail</th>
                            <th>Extensión</th>
                            <th>Celular</th>
                            <th>Equipo</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idUsuario = $datos['idPersona'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreEnt'].'</td>
                            <td>'.$datos['facDeptoFacultad'].' - '.$datos['facDeptoDepartamento'].'</td>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td>'.$datos['tipoPersona'].'</td>
                            <td>'.$datos['identificacion'].'</td>
                            <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                            <td>'.$datos['correo'].'</td>
                            <td>'.$datos['extension'].'</td>
                            <td>'.$datos['celular'].'</td>
                            <td>'.$datos['nombreEqu'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idUsuario.'\', \'../Controllers/ctrl_eliminadosUsuarios.php\', \'la Persona\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Personas Eliminadas</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarUsuario($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_personas SET est = 1 WHERE idPersona = '$id'";
            $resultado = mysqli_query($connection, $consulta);
            $consulta1 = "UPDATE pys_login SET est = 1 WHERE idLogin = '$id';";
            $resultado1 = mysqli_query($connection, $consulta1);
            if ($resultado && $resultado1) {
                $string = 'Se activo correctamente a la persona y el usuario';
            }else{
               $string = 'Ocurrió un error al intentar actualizar el registro';              
            }
            echo $string;
            mysqli_close($connection);
        }

       
        
    }

?>