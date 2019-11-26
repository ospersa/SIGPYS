<?php

    class EliminadosRoles {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_roles.idRol, pys_roles.nombreRol, pys_roles.descripcionRol, pys_tiproles.nombreTipRol FROM pys_tiproles 
            INNER JOIN pys_roles ON pys_tiproles.idTipRol = pys_roles.tiproles_idTipRol
            WHERE pys_roles.est='0' ORDER BY pys_roles.nombreRol";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th>Descripción del Rol</th>
                            <th>Tipo de Rol</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idRol = $datos['idRol'];
                    echo'
                        <tr>
                            <td>'.$datos['nombreRol'].'</td>
                            <td>'.$datos['descripcionRol'].'</td>
                            <td>'.$datos['nombreTipRol'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idRol.'\', \'../Controllers/ctrl_eliminadosRoles.php\', \'el Rol\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Roles Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarRol($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_roles SET est = 1 WHERE idRol = '$id';";
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