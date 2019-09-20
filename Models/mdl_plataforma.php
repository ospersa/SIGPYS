<?php
    class Plataforma{

        public static function onLoad($idPlataforma){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_plataformas WHERE est ='1' AND idPlat = '$idPlataforma';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }


        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_plataformas WHERE est ='1' ORDER BY nombrePlt ";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Plataforma</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombrePlt'].'</td>
                        <td><a href="#modalPlataforma" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPlataforma.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_plataformas WHERE est ='1' AND nombrePlt LIKE '%".$busqueda."%' ORDER BY nombrePlt;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Plataforma</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombrePlt'].'</td>
                            <td><a href="#modalPlataforma" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalPlataforma.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarPlataforma($nomPlataforma){
            require('../Core/connection.php');
            /** Verificación de campos vacíos */
            if ($nomPlataforma == null || $nomPlataforma == "" || $nomPlataforma == " ") {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
            } else {
                /** Varificación de información existente en la tabla para evitar duplicidad de información */
                $consulta = "SELECT * FROM pys_plataformas WHERE nombrePlt = '$nomPlataforma' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    echo "<script> alert('Ya existe un registro con el nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
                } else {
                    $consulta = "SELECT COUNT(idPlat), MAX(idPlat) FROM pys_plataformas;";
                    $resultado = mysqli_query($connection, $consulta);
                    while ($datos =mysqli_fetch_array($resultado)){
                        $count=$datos[0];
                        $max=$datos[1];
                    }
                    if ($count==0){
                        $codPlataforma="PLT001";	
                    }
                    else {
                        $codPlataforma='PLT'.substr((substr($max,3)+1001),1);	
                    }		
                    $sql = "INSERT INTO pys_plataformas VALUES ('$codPlataforma', '$nomPlataforma', '1');";
                    $resultado = mysqli_query($connection, $sql);
                    if ($resultado){
                        echo "<script> alert ('Se guardó correctamente la información');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
                    }else{
                        echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
                    }
                }   
            }
            mysqli_close($connection);
        }

        public static function actualizarPlataforma($idPlataforma2, $nomPlataforma){
            require('../Core/connection.php');
            $consulta = "update pys_plataformas set nombrePlt='$nomPlataforma' where idPlat = '$idPlataforma2'";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
            } else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
            }
            mysqli_close($connection);
            
        }

        public static function suprimirPlataforma($idPlataforma2){
            require('../Core/connection.php');
            $consulta = "update pys_plataformas set est = '0' where idPlat = '$idPlataforma2';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/plataforma.php">';
            }
            mysqli_close($connection);
        }

        public static function selectPlataforma($cod){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_plataformas WHERE est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0) {
                $string = '  <select name="sltPlataforma" id="sltPlataforma" class="asignacion">
                            <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if( $datos['idPlat'] == $cod){
                        $string .= '  <option selected value="'.$datos['idPlat'].'">'.$datos['nombrePlt'].'</option>';
                    }
                    $string .= '  <option value="'.$datos['idPlat'].'">'.$datos['nombrePlt'].'</option>';
                }
                $string .= '  </select>
                        <label for="sltPlataforma">Plataforma*</label>';
            } else {
                echo "";
            }
            return $string;
            mysqli_close($connection);
        }
    }

