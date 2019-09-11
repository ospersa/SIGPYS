<?php
    class Facultad {

        public static function onLoad($idFacultad){
            require('../Core/connection.php');
            $consulta="SELECT pys_entidades.idEnt, pys_entidades.nombreEnt, pys_facultades.idFac, pys_facultades.nombreFac, pys_facultades.coordFac, pys_facultades.cargoCoordFac,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
                FROM pys_facultades
                INNER JOIN pys_facdepto ON pys_facdepto.idFac = pys_facultades.idFac 
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_personas ON pys_facultades.coordFac = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_facultades.cargoCoordFac = pys_cargos.idCargo
                WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.idDepto = '' AND pys_personas.est = '1' AND pys_cargos.est = '1' AND pys_facultades.idFac = '$idFacultad';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }


        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT pys_entidades.nombreEnt, pys_facultades.idFac, pys_facultades.nombreFac, pys_facultades.coordFac, pys_facultades.cargoCoordFac,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
                FROM pys_facultades
                INNER JOIN pys_facdepto ON pys_facdepto.idFac = pys_facultades.idFac 
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_personas ON pys_facultades.coordFac = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_facultades.cargoCoordFac = pys_cargos.idCargo
                WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.idDepto = '' AND pys_personas.est = '1' AND pys_cargos.est = '1' 
                ORDER BY pys_facultades.nombreFac;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Facultad</th>
                        <th>Decano</th>
                        <th>Cargo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[0].'</td>
                        <td>'.$datos[2].'</td>
                        <td>'.$datos[5].' '.$datos[6].' '.$datos[7].'</td>
                        <td>'.$datos[8].'</td>
                        <td><a href="#modalFacultad" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[1]'".','."'modalFacultad.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";

            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT pys_entidades.nombreEnt, pys_facultades.idFac, pys_facultades.nombreFac, pys_facultades.coordFac, pys_facultades.cargoCoordFac,  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
                FROM pys_facultades
                INNER JOIN pys_facdepto ON pys_facdepto.idFac = pys_facultades.idFac 
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_personas ON pys_facultades.coordFac = pys_personas.idPersona
                INNER JOIN pys_cargos ON pys_facultades.cargoCoordFac = pys_cargos.idCargo
                WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.idDepto = '' AND pys_personas.est = '1' AND pys_cargos.est = '1' AND pys_facultades.nombreFac LIKE '%".$busqueda."%' 
                ORDER BY pys_facultades.nombreFac;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Facultad</th>
                        <th>Decano</th>
                        <th>Cargo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos =mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreEnt'].'</td>
                        <td>'.$datos['nombreFac'].'</td>
                        <td>'.$datos["apellido1"].' '.$datos["apellido2"].' '.$datos["nombres"].'</td>
                        <td>'.$datos['nombreCargo'].'</td>
                        <td><a href="#modalFacultad" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[1]'".','."'modalFacultad.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function registrarFacultad($entidad, $nomFacultad){
            require('../Core/connection.php');
            /** Verificación de campos vacíos */
            if ($entidad == null || $nomFacultad == null || $nomFacultad == " ") {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
            } else {
                /** Verificación de información existente en la tabla, para evitar duplicidad */
                $consulta = "SELECT * FROM pys_facdepto WHERE facDeptofacultad = '$nomFacultad' AND idEnt = '$entidad' AND estFacdeptoFac = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    echo "<script> alert('Ya existe un registro con el código o nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
                } else {
                    $consulta = "SELECT COUNT(idFac), MAX(idFac) FROM pys_facultades;";
                    $resultado = mysqli_query($connection, $consulta);
                    while ($datos = mysqli_fetch_array($resultado)){
                        $count = $datos[0];
                        $max = $datos[1];
                    }
                    if ($count == 0){
                        $countFac = "FAC001";	
                    }
                    else {
                        $countFac = 'FAC'.substr((substr($max,3)+1001),1);
                    }		
                    /* contador para tabla facdepto */
                    $sql1 = "SELECT COUNT(idFacDepto), MAX(idFacDepto) FROM pys_facdepto;";
                    $cs1 = mysqli_query($connection,$sql1);
                    while ($result = mysqli_fetch_array($cs1)) {
                        $count = $result[0];
                        $max = $result[1];
                    }
                    if ($count == 0){
                        $countFacDepto = "FD0001";	
                    } else {
                        $countFacDepto = 'FD'.substr((substr($max,2)+10001),1);		
                    }
                    /** Preparación de la información antes de realizar procesos de inserción en las diferentes tablas */
                    mysqli_query($connection, "BEGIN;");
                    /*Código de inserción en la tabla pys_facultades*/	
                    $sql = "INSERT INTO pys_facultades VALUES ('$countFac', '$nomFacultad', 'PR0042', 'CAR032', '1');";
                    $resultado = mysqli_query($connection, $sql);
                    /*Código de inserción en la tabla pys_facDepto*/	
                    $sql1 = "INSERT INTO pys_facdepto VALUES ('$countFacDepto', '$entidad', '$countFac', '', '$nomFacultad', '', '1', '1', '1');";
                    $resultado1 = mysqli_query($connection, $sql1);
                    if ($resultado && $resultado1) {
                        /** Si las inserciones son correctas, se hace COMMIT para guardar la información en BD */
                        mysqli_query($connection, "COMMIT;");
                        echo "<script> alert ('Se guardó correctamente la información');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
                    } else {
                        /** Si se presentan errores, se realiza ROLLBACK para evitar daño en la información */
                        mysqli_query($connection, "ROLLBACK;");
                        echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
                    }
                }
                mysqli_close($connection);
            }
        }

        public static function actualizarFacultad($idFacultad2, $entidad, $nomFacultad){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_facultades SET nombreFac='$nomFacultad' WHERE idFac='$idFacultad2';";
            $resultado = mysqli_query($connection, $consulta);
            /*Código de actualización nombre facultad en la tabla pys_facdepto*/			
            $consulta2="UPDATE pys_facdepto SET idEnt = '$entidad', facDeptoFacultad = '$nomFacultad' WHERE idFac = '$idFacultad2';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if($resultado && $resultado2){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
            }else{
                echo "<script> alert('Ocurrió un error al intentar actualizar el registro.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
            }
            mysqli_close($connection);
        }

        public static function suprimirFacultad($idFacultad2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_facdepto SET estFacdeptoFac = '0' WHERE pys_facdepto.idFac = '$idFacultad2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
            }else{
                echo "<script> alert('Ocurrió un error al intentar eliminar la informacion.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/facultad.php">';
            }
                mysqli_close($connection);
        }

        public static function selectEntidad($idEntidad) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_entidades WHERE est = '1' ORDER BY nombreEnt;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $select = '     <select name="selEntidad" id="selEntidad">
                                    <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idEnt'] == $idEntidad) {
                        $select .= '<option value="'.$datos['idEnt'].'" selected>'.$datos['nombreEnt'].'</option>';
                    } else {
                        $select .= '<option value="'.$datos['idEnt'].'">'.$datos['nombreEnt'].'</option>';
                    }
                }
                $select .= '    </select>
                                <label for="selEntidad">Entidad/Empresa</label>';
            } else {
                $select = '     <select name="selEntidad" id="selEntidad">
                                    <option value="" selected disabled>No hay entidades creadas</option>
                                </select>
                                <label for="selEntidad">Entidad/Empresa</label>';
            }
            return $select;
            mysqli_close($connection);
        }

    }