<?php

    class EliminadosPassword {

        public static function onLoadEliminados () {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_personas 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_perfil ON pys_login.idPerfil = pys_perfil.idPerfil
            WHERE pys_personas.est = 1 AND pys_login.est = 0
            ORDER BY nombres";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado && mysqli_num_rows($resultado)){
                
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Perfil</th>
                            <th>Activar</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
                while ($datos = mysqli_fetch_array($resultado)){
                    $idLogin = $datos['idLogin'];
                    echo'
                        <tr>
                            <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                            <td>'.$datos['usrLogin'].'</td>
                            <td>'.$datos['nombrePerfil'].'</td>
                            <td><a href="#!" class="waves-effect waves-light modal-trigger" onclick="activar(\''.$idLogin.'\', \'../Controllers/ctrl_eliminadosPassword.php\', \'el Password\')" title="Activar"><i class="material-icons teal-text">refresh</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay Password Eliminados</h6></div>';
            }
            mysqli_close($connection);
        }

        public static function  activarPassword($id) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_login SET est = 1 WHERE idLogin = '$id';";
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