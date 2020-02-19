<?php
    class Equipo{

        public static function onLoad($idEquipo){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_equipos WHERE est='1' AND idEqu ='".$idEquipo."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_equipos WHERE est='1' ORDER BY nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre del equipo</th>
                        <th>Descripción del equipo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreEqu'].'</td>
                        <td>'.$datos['descripcionEqu'].'</td>
                        <td><a href="#modalEquipo" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEquipo.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta="SELECT * FROM pys_equipos WHERE est='1' AND nombreEqu LIKE '%".$busqueda."%' ORDER BY nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){    
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del equipo</th>
                            <th>Descripción del equipo</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombreEqu'].'</td>
                            <td>'.$datos['descripcionEqu'].'</td>
                            <td><a href="#modalEquipo" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalEquipo.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }    
            mysqli_close($connection);
        }

        public static function registrarEquipo($nomEquipo, $descEquipo){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idEqu), MAX(idEqu) FROM pys_equipos;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos['COUNT(idEqu)'];
                $max=$datos['MAX(idEqu)'];
            }
            if ($count==0){
                $codEquipo="EQU001";
            }
            else {
                $codEquipo='EQU'.substr((substr($max,3)+1001),1);
            }		
            $nomEquipo = mysqli_real_escape_string($connection, $nomEquipo);
            $descEquipo = mysqli_real_escape_string($connection, $descEquipo);
            $sql="INSERT INTO pys_equipos VALUES ('$codEquipo', '$nomEquipo',  '$descEquipo', '1');";
            $resultado = mysqli_query($connection, $sql);
            if ($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            } else {
                echo "<script> alert ('No se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            }
            
            mysqli_close($connection);
        }

        public static function actualizarEquipo($idEquipo2, $nomEquipo, $descEquipo){
            require('../Core/connection.php');
            $nomEquipo = mysqli_real_escape_string($connection, $nomEquipo);
            $descEquipo = mysqli_real_escape_string($connection, $descEquipo);
            $consulta = "UPDATE pys_equipos SET nombreEqu='$nomEquipo', descripcionEqu='$descEquipo' WHERE idEqu='$idEquipo2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            if ($resultado){
                echo "<script> alert ('Se actualizó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            } else {
                echo "<script> alert ('No se actualizó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            }
        }

        public static function suprimirEquipo($idEquipo2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_equipos SET est = '0' WHERE idEqu='$idEquipo2';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            if ($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            } else {
                echo "<script> alert ('No se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/equipo.php">';
            }
        }

        public static function selectEquipo ($idEquipo, $modal) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_equipos WHERE est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                if ($idEquipo != null && $modal == null) {
                    $select = ' <select name="sltEquipo" id="sltEquipo" disabled>
                                    <option value="" selected disabled>Seleccione</option>';
                } else if ($idEquipo != null && $modal == "modal"){    
                    $select = ' <select name="sltEquipo" id="sltEquipo">
                                    <option value="" selected disabled>Seleccione</option>';
                
                } else {
                    $select = ' <select name="sltEquipo" id="sltEquipo" onchange="cargaSelect(\'#sltEquipo\',\'../Controllers/ctrl_productos.php\',\'#sltServicioLoad\')" required>
                                    <option value="" selected disabled>Seleccione</option>';
                }
                
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEqu'] == $idEquipo) {
                        $select .= '<option value="'.$datos['idEqu'].'" selected>'.$datos['nombreEqu'].'</option>';
                    } else {
                        $select .= '<option value="'.$datos['idEqu'].'">'.$datos['nombreEqu'].'</option>';
                    }
                }
                $select .= '    </select>
                                <label for="sltEquipo">Equipo*</label>';
            } else {
                $select = ' <select name="sltEquipo" id="sltEquipo" required>
                                <option value="">No hay equipos creados</option>
                            </select>
                            <label for="sltEquipo">Equipo*</label>';
            }
            return $select;
            mysqli_close($connection);
        }

    }