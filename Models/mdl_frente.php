<?php
    class Frente{

        public static function onLoad($idFrente){
            require('../Core/connection.php');
            $consulta = "SELECT pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_personas.idPersona, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
                FROM pys_frentes
                INNER JOIN pys_personas ON pys_frentes.coordFrente = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_cargos.idCargo
                WHERE pys_frentes.est = '1' AND pys_personas.est = '1' AND pys_cargos.est = '1' AND idFrente = '$idFrente';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }
        
        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta = "SELECT pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
                FROM pys_frentes
                INNER JOIN pys_personas ON pys_frentes.coordFrente = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_cargos.idCargo
                WHERE pys_frentes.est = '1' AND pys_personas.est = '1' AND pys_cargos.est = '1' 
                ORDER BY pys_frentes.idFrente;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Frente</th>
                        <th>Descripción</th>
                        <th>Coordinador de Frente</th>
                        <th>Cargo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreFrente'].'</td>
                        <td>'.$datos['descripcionFrente'].'</td>
                        <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                        <td>'.$datos['nombreCargo'].'</td>
                        <td><a href="#modalFrente" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFrente.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }

            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT pys_frentes.idFrente, pys_frentes.nombreFrente, pys_frentes.descripcionFrente, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
                FROM pys_frentes
                INNER JOIN pys_personas ON pys_frentes.coordFrente = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_cargos.idCargo
                WHERE pys_frentes.est = '1' AND pys_personas.est = '1' AND pys_cargos.est = '1' AND (nombreFrente LIKE '%".$busqueda."%' OR descripcionFrente LIKE '%".$busqueda."%') ORDER BY idFrente;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){ 
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Frente</th>
                            <th>Descripción</th>
                            <th>Coordinador de Frente</th>
                            <th>Cargo</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombreFrente'].'</td>
                            <td>'.$datos['descripcionFrente'].'</td>
                            <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td><a href="#modalFrente" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFrente.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            }else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';

            }
            mysqli_close($connection);
        }

        public static function registrarFrente($nomFrente, $descFrente, $coorFrente){
            require('../Core/connection.php');
            $count = 0;
            /** Verificación campos vacíos */
            if ($nomFrente == null || $nomFrente == "" || $nomFrente == " " || $coorFrente == null || $coorFrente == " ") {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/frentes.php">';
            } else {
                /** Verificación de información en la tabla para evitar duplicidad */
                $consulta = "SELECT * FROM pys_frentes WHERE nombreFrente = '$nomFrente' AND coordFrente = '$coorFrente' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    echo "<script> alert('Ya existe un registro con el nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
                } else {
                    $consulta1 = "SELECT COUNT(idFrente), MAX(idFrente) FROM pys_frentes;";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    while ($datos = mysqli_fetch_array($resultado1)){
                        $count = $datos['COUNT(idFrente)'];
                        $max = $datos['MAX(idFrente)'];
                    }
                    if ($count == 0){
                        $codFrente = "FRE001";	
                    } else {
                        $codFrente = 'FRE'.substr((substr($max,3)+1001),1);		
                    }	
                    $consulta2 = "INSERT INTO pys_frentes VALUES ('$codFrente', '$nomFrente',  '$descFrente', '$coorFrente', '', '1');";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($resultado2 && $resultado1) {
                        echo "<script> alert ('Se guardó correctamente la información');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
                    } else {
                        echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarFrente($idFrente2, $nomFrente, $descFrente, $coorFrente){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_frentes SET nombreFrente='$nomFrente', descripcionFrente='$descFrente', coordFrente='$coorFrente' WHERE idFrente='$idFrente2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
            }
            mysqli_close($connection);
            
        }

        public static function suprimirFrente($idFrente2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_frentes SET est = '0' WHERE idFrente = '$idFrente2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/frente.php">';}
            mysqli_close($connection);
        }

        public static function selectCoordinadorFrente ($idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_personas WHERE est = '1' AND idFacDepto = 'FD0034' ORDER BY apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $select = '     <select name="selCoorFrente" id="selCoorFrente">
                                    <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    if ($datos['idPersona'] == $idPersona) {
                        $select .= '<option value="'.$datos['idPersona'].'" selected>'.$nombreCompleto.'</option>';
                    } else {
                        $select .= '<option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                    }
                }
                $select .= '    </select>
                                <label for="selCoorFrente">Coordinador de Frente</label>';
            } else {
                $select = "  <select name='selCoorFrente' id='selCoorFrente'>
                            <option selected disabled>No hay personas creadas</option>
                        </select>
                        <label for='selCoorFrente'>Coordinador de Frente</label>";
            }
            return $select;
            mysqli_close($connection);
        }
    }

?>