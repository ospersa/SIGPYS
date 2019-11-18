<?php
    class Usuario {

        public static function onLoad ($idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres,
                pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, 
                pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_entidades.idEnt, pys_facdepto.idFac
                FROM pys_personas               
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_equipos ON pys_personas.idEquipo = pys_equipos.idEqu
                WHERE pys_personas.est = '1' AND pys_cargos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND
                pys_facdepto.estFacdeptoDepto = '1' AND pys_personas.idPersona = '$idPersona';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal () {
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres,
                pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, 
                pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo, pys_equipos.idEqu, pys_equipos.nombreEqu
                FROM pys_personas               
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_equipos ON pys_personas.idEquipo = pys_equipos.idEqu
                WHERE pys_personas.est = '1' AND pys_cargos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND
                pys_facdepto.estFacdeptoDepto = '1'
                ORDER BY pys_personas.apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            
            echo "
                <table class='left responsive-table' style='font-size:.85em;'>
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
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '
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
                            <td><a href="#modalUsuario" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalUsuario.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>
                ';
            }
            echo "  </tbody>
                </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda) {
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.idPersona, pys_personas.tipoPersona, pys_personas.identificacion, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres,
                pys_personas.correo, pys_personas.telefono, pys_personas.extension, pys_personas.celular, pys_entidades.nombreEnt, pys_facdepto.idFacDepto, pys_facdepto.facDeptoFacultad, 
                pys_facdepto.facDeptoDepartamento, pys_cargos.idCargo, pys_cargos.nombreCargo, pys_equipos.idEqu, pys_equipos.nombreEqu
                FROM pys_personas               
                INNER JOIN pys_cargos ON pys_personas.idCargo = pys_Cargos.idCargo
                INNER JOIN pys_facdepto ON pys_personas.idFacDepto=pys_facdepto.idFacDepto
                INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
                INNER JOIN pys_equipos ON pys_personas.idEquipo = pys_equipos.idEqu
                WHERE pys_personas.est = '1' AND pys_cargos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND
                pys_facdepto.estFacdeptoDepto = '1' AND
                ((pys_facdepto.facDeptoFacultad LIKE'%$busqueda%') OR 
                (pys_facdepto.facDeptoDepartamento LIKE '%$busqueda%') OR
                (pys_personas.nombres LIKE '%$busqueda%') OR
                (pys_personas.apellido1 LIKE '%$busqueda%') OR
                (pys_personas.apellido2 LIKE '%$busqueda%') OR
                (pys_cargos.nombreCargo LIKE '%$busqueda%') OR
                (pys_personas.correo LIKE '%$busqueda%'))
                ORDER BY pys_personas.apellido1;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if($registros){
                echo "
                    <table class='left responsive-table' style='font-size:.85em;'>
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
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '
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
                                <td><a href="#modalUsuario" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalUsuario.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                            </tr>
                    ';
                }
                echo "  </tbody>
                        </table>";
            }else{
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function selectCargo ($idPersona){
            require('../Core/connection.php');
            $consulta = "SELECT idCargo, nombreCargo FROM pys_cargos ORDER BY nombreCargo;";
            $resultado = mysqli_query($connection, $consulta);
            if ($idPersona == null) {
                if (($registros = mysqli_num_rows($resultado)) > 0) {
                    echo '
                        <select name="sltCargo" id="sltCargo">
                            <option value="" disabled selected>Seleccione</option>
                    ';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '<option value="'.$datos['idCargo'].'">'.$datos['nombreCargo'].'</option>';
                    }
                    echo '
                        </select>
                        <label for="sltCargo">Cargo*</label>';
                } else {
                    echo "<script> alert ('No hay cargos registrados en la base de datos');</script>";
                }
            } else {
                $consulta2 = "SELECT idCargo FROM pys_personas WHERE idPersona = '$idPersona';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                echo '<select name="sltCargo2" id="sltCargo2">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['idCargo'] == $datos2['idCargo']) {
                        echo '<option value="'.$datos['idCargo'].'" selected>'.$datos['nombreCargo'].'</option>';
                    } else {
                        echo '<option value="'.$datos['idCargo'].'">'.$datos['nombreCargo'].'</option>';
                    }
                }
                echo '</select>
                    <label for="sltCargo2">Cargo*</label>';
            }
            mysqli_close($connection);
        }

        public static function selectTipo ($idPersona, $label) {
            require('../Core/connection.php');
            if ($idPersona == null) {
                echo '  <select name="sltTipoIntExt" id="sltTipoIntExt">
                            <option value="" disabled selected>Seleccione</option>
                            <option value="Interno">Interno</option>
                            <option value="Externo">Externo</option>
                        </select>
                        <label for="sltTipoIntExt">'.$label.'</label>';
            } else {
                $consulta = "SELECT tipoPersona FROM pys_personas WHERE idPersona = '$idPersona';";
                $resultado = mysqli_query($connection, $consulta);
                if ($registros = mysqli_num_rows($resultado) > 0) {
                    echo '<select name="sltTipoIntExt" id="sltTipoIntExt">';
                    $datos = mysqli_fetch_array($resultado);
                    if ($datos[0] == "Interno") {
                        echo '  <option value="Interno" selected>Interno</option>
                                <option value="Externo">Externo</option>';
                    } else {
                        echo '  <option value="Interno">Interno</option>
                                <option value="Externo" selected>Externo</option>';
                    }
                    echo '  </select>
                            <label for="sltTipoIntExt">'.$label.'</label>';
                }
            }
            mysqli_close($connection);
        }

        public static function selectPais ($idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT Codigo, Nombre FROM paises ORDER BY Nombre;";
            $resultado = mysqli_query($connection, $consulta);
            if ($idPersona == null) {
                if (($registros = mysqli_num_rows($resultado)) > 0) {
                    echo '
                        <select name="sltPais" id="sltPais" onchange="cargaSelect(\'#sltPais\',\'../Controllers/ctrl_usuario.php\',\'#sltCiudad\')">
                            <option value="" disabled selected>Seleccione</option>
                    ';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '<option value="'.$datos['Codigo'].'">'.$datos['Nombre'].'</option>';
                    }
                    echo '
                        </select>
                        <label for="sltPais">País</label>';
                } else {
                    echo "<script> alert ('No hay países registrados en la base de datos');</script>";
                }
            } else {
                $consulta2 = "SELECT paises.Codigo, ciudades.Ciudad FROM dbpys.pys_personas
                    INNER JOIN ciudades ON ciudades.idCiudades = pys_personas.Ciudades_idCiudades
                    INNER JOIN paises ON paises.Codigo = ciudades.Paises_Codigo
                    WHERE pys_personas.idPersona = '$idPersona';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros = mysqli_num_rows($resultado2) > 0) {
                    $datos2 = mysqli_fetch_array($resultado2);
                    echo '
                        <select name="sltPais2" id="sltPais2" onchange="cargaSelect(\'#sltPais2\',\'../Controllers/ctrl_usuario.php\',\'#sltCiudad2\')">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['Codigo'] == $datos2['Codigo']) {
                            echo '<option value="'.$datos['Codigo'].'" selected>'.$datos['Nombre'].'</option>';
                        } else {
                            echo '<option value="'.$datos['Codigo'].'">'.$datos['Nombre'].'</option>';
                        }
                    }
                    echo '
                        </select>
                        <label for="sltPais2">País</label>';
                }
            }
            mysqli_close($connection);
        }

        public static function selectEquipo ($idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT idEqu, nombreEqu FROM pys_equipos WHERE est = '1' ORDER BY nombreEqu;";
            $resultado = mysqli_query($connection, $consulta);
            if ($idPersona == null) {
                if (($registros = mysqli_num_rows($resultado)) > 0) {
                    echo '
                        <select name="sltEquipo" id="sltEquipo">
                            <option value="" disabled selected>Seleccione</option>
                    ';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '<option value="'.$datos['idEqu'].'">'.$datos['nombreEqu'].'</option>';
                    }
                    echo '
                        </select>
                        <label for="sltEquipo">Equipo de trabajo</label>';
                } else {
                    echo "<script> alert ('No hay equipos registrados en la base de datos');</script>";
                }
            } else {
                $consulta2 = "SELECT idEquipo FROM pys_personas WHERE idPersona = '$idPersona';";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros2 = mysqli_num_rows($resultado2) > 0) {
                    $datos2 = mysqli_fetch_array($resultado2);
                    echo '
                        <select name="sltEquipo2" id="sltEquipo2">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idEqu'] == $datos2['idEquipo']) {
                            echo '<option value="'.$datos['idEqu'].'" selected>'.$datos['nombreEqu'].'</option>';
                        } else {
                            echo '<option value="'.$datos['idEqu'].'">'.$datos['nombreEqu'].'</option>';
                        }
                    }
                    echo '
                        </select>
                        <label for="sltEquipo2">Equipo de trabajo</label>';
                }
            }
            
            mysqli_close($connection);
        }

        public static function selectEntidad ($idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT idEnt, nombreEnt FROM pys_entidades WHERE est = '1' ORDER BY nombreEnt;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                if ($idPersona == null) {
                    echo '
                        <select name="sltEntidad" id="sltEntidad" onchange="cargaSelect(\'#sltEntidad\',\'../Controllers/ctrl_usuario.php\',\'#sltFacultad\');">
                            <option value="" disabled selected>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '<option value="'.$datos['idEnt'].'">'.$datos['nombreEnt'].'</option>';
                    }
                    echo '
                        </select>
                        <label for="sltEntidad">Empresa*</label>';
                } else {
                    echo '
                        <select name="sltEntidad2" id="sltEntidad2" onchange="cargaSelect(\'#sltEntidad2\',\'../Controllers/ctrl_usuario.php\',\'#sltFacultad2\');">';
                    $consulta2 = "SELECT pys_facdepto.idEnt AS idEnt1, pys_entidades.nombreEnt, pys_entidades.idEnt FROM pys_personas
                        INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_personas.idFacDepto
                        INNER JOIN pys_entidades ON pys_entidades.idEnt = pys_facdepto.idEnt
                        WHERE pys_personas.idPersona = '$idPersona';";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if (($registros2 = mysqli_num_rows($resultado2)) > 0) {
                        $datos2 = mysqli_fetch_array($resultado2);
                        while ($datos = mysqli_fetch_array($resultado)) {
                            if ($datos['idEnt'] == $datos2['idEnt1']) {
                                echo '<option value="'.$datos['idEnt'].'" selected>'.$datos['nombreEnt'].'</option>';
                            } else {
                                echo '<option value="'.$datos['idEnt'].'">'.$datos['nombreEnt'].'</option>';
                            }
                        }
                    }
                    echo '
                        </select>
                        <label for="sltEntidad2">Empresa*</label>';
                }
                
            } else {
                echo "<script> alert ('No hay entidades registradas en la base de datos');</script>";
            }
            mysqli_close($connection);
        }

        public static function selectFacultades ($idEnt, $idPersona, $accion) {
            require('../Core/connection.php');
            $consulta = "SELECT idFac, facDeptoFacultad FROM pys_facdepto WHERE estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idFac != 'FAC014' AND idEnt = '$idEnt' ORDER BY facDeptoFacultad;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($idPersona == null && $accion == null) {
                if ($registros > 0) {
                    echo '  
                                <select id="sltFacul" name="sltFacul" onchange="cargaSelect(\'#sltFacul\',\'../Controllers/ctrl_usuario.php\',\'#sltDepartamento\')">
                                    <option value="" selected disabled>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '      <option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                    }
                    echo '      </select>
                                <label for="sltFacul">Facultad</label>';
                }
            } else if ($idPersona == null && $accion == "1") {
                if ($registros > 0) {
                    echo '
                        <select id="sltFacul2" name="sltFacul" onchange="cargaSelect(\'#sltFacul2\',\'../Controllers/ctrl_usuario.php\',\'#sltDepartamento2\')">
                            <option value="" selected disabled>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '<option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                    }
                    echo '      
                        </select>
                        <label for="sltFacul2">Facultad</label>';    
                }
            } else {
                $consulta2 = "SELECT idFac, facDeptoFacultad FROM pys_personas
                    INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_personas.idFacDepto
                    INNER JOIN pys_entidades ON pys_entidades.idEnt = pys_facdepto.idEnt
                    WHERE pys_personas.idPersona = '$idPersona';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                if ($datos2[0] != "" && $datos2[1] != "") {
                    echo '
                        <select id="sltFacul2" name="sltFacul" onchange="cargaSelect(\'#sltFacul2\',\'../Controllers/ctrl_usuario.php\',\'#sltDepartamento2\')">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos2[0] == $datos['idFac']) {
                            echo '<option value="'.$datos['idFac'].'" selected>'.$datos['facDeptoFacultad'].'</option>';
                        } else {
                            echo '<option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                        }
                    }
                    echo '      
                        </select>
                        <label for="sltFacul2">Facultad</label>';
                }
            }
            
            mysqli_close($connection);
        }

        public static function selectDepartamentos ($idFac, $idPersona) {
            require('../Core/connection.php');
            $consulta = "SELECT idFacDepto, facDeptoDepartamento, idDepto FROM pys_facdepto WHERE estFacdeptoDepto = '1' AND pys_facdepto.idFac != 'FAC014' AND pys_facdepto.idDepto != 'DP0027' AND idFac = '$idFac';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($idPersona == null) {
                if ($registros > 0) {
                    echo '  
                                <select id="sltDepto" name="sltDepto">
                                    <option value="" selected disabled>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '      <option value="'.$datos['idFacDepto'].'">'.$datos['facDeptoDepartamento'].'</option>';
                    }
                    echo '      </select>
                                <label for="sltDepto">Departamento</label>
                            ';
                }
            } else if ($idPersona == '1') {
                if ($registros > 0) {
                    echo '  
                                <select id="sltDepto" name="sltDepto">
                                    <option value="" selected disabled>Seleccione</option>';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        echo '      <option value="'.$datos['idFacDepto'].'">'.$datos['facDeptoDepartamento'].'</option>';
                    }
                    echo '      </select>
                                <label for="sltDepto">Departamento</label>
                            ';
                }
            } else {
                $consulta2 = "SELECT idDepto, facDeptoDepartamento FROM pys_personas
                    INNER JOIN pys_facdepto ON pys_facdepto.idFacDepto = pys_personas.idFacDepto
                    INNER JOIN pys_entidades ON pys_entidades.idEnt = pys_facdepto.idEnt
                    WHERE pys_personas.idPersona = '$idPersona';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                if ($datos2[0] != "" && $datos2[1] != "") {
                    echo '  <select id="sltDepto2" name="sltDepto">';
                    while ($datos = mysqli_fetch_array($resultado)) {
                        if ($datos['idDepto'] == $datos2['idDepto']) {
                            echo '<option value="'.$datos[0].'" selected>'.$datos[1].'</option>';
                        } else {
                            echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
                        }
                    }
                    echo '  </select>
                            <label for="sltDepto2">Departamento</label>';
                }
            }
            
            mysqli_close($connection);
        }

        public static function selectCiudades ($idPais) {
            require('../Core/connection.php');
            if (substr($idPais, 0, 2) == "PR") {
                $consulta = "SELECT ciudades.idCiudades, ciudades.Paises_Codigo FROM dbpys.pys_personas
                    INNER JOIN ciudades ON ciudades.idCiudades = pys_personas.Ciudades_idCiudades
                    WHERE pys_personas.idPersona = '$idPais';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $pais = $datos[1];
                $consulta2 = "SELECT * FROM ciudades WHERE Paises_Codigo = '$pais' ORDER BY Ciudad;";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($registros = mysqli_num_rows($resultado) > 0) {
                    echo '<select id="sltCiudad2" name="sltCiudad2">';
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        if ($datos2['idCiudades'] == $datos['idCiudades']) {
                            echo '<option value="'.$datos2['idCiudades'].'" selected>'.$datos2['Ciudad'].'</option>';
                        } else {
                            echo '<option value="'.$datos2['idCiudades'].'">'.$datos2['Ciudad'].'</option>';
                        }
                    }
                    echo '</select>
                        <label for="sltCiudad2">Ciudad orígen</label>';
                }
            } else {
                $consulta = "SELECT idCiudades, Ciudad FROM ciudades WHERE Paises_Codigo = '$idPais' ORDER BY Ciudad;";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                echo '  
                            <select id="sltCiudad" name="sltCiudad">
                                <option value="" selected disabled>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    echo '      <option value="'.$datos['idCiudades'].'">'.$datos['Ciudad'].'</option>';
                }
                echo '      </select>
                            <label for="sltCiudad">Ciudad orígen</label>
                        ';
                }
            }
            mysqli_close($connection);
        }

        public static function selectCategoriaCargo ($idPersona) {
            if ($idPersona != null) {
                require('../Core/connection.php');
                $consulta = "SELECT categoriaCargo FROM pys_personas WHERE idPersona = '$idPersona' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                echo '  <select name="sltCategoriaCargo" id="sltCategoriaCargo" required>
                            <option value="" disabled selected>Seleccione</option>';
                if ($datos['categoriaCargo'] == '41027') {
                    echo '  <option value="41012">Profesional</option>
                            <option value="41027" selected>No Profesional</option>';
                } else if ($datos['categoriaCargo'] == '41012') {
                    echo '  <option value="41012" selected>Profesional</option>
                            <option value="41027">No Profesional</option>';
                } else {
                    echo '  <option value="41012">Profesional</option>
                            <option value="41027">No Profesional</option>';
                }
                echo '  </select>
                        <label for="sltCategoriaCargo">Categoría del Cargo*</label>';
                mysqli_close($connection);
            }
            
        }

        public static function registrarUsuario ($entidad, $facultad, $departamento, $cargo, $tipo, $identificacion, $nombres, $apellido1, $apellido2, $ciudad, $equipo, $mail, $fijo, $extension, $celular, $categoriaCargo) {
            require('../Core/connection.php');
            // Determinar el ID de persona a registrar en la tabla pys_personas
            $consulta1 = "SELECT count(idPersona), max(idPersona) FROM pys_personas;"; 
            $resultado1 = mysqli_query($connection, $consulta1);
            $datos1 = mysqli_fetch_array($resultado1);
            $count = $datos1[0];
            $max = $datos1[1];
            if ($count == 0) {
                $idPer = "PR0001";
            } else {
                $idPer = 'PR'.substr((substr($max, 2) + 10001), 1);
            }
            // Validar si el usuario ya está creado en la tabla y se evita la duplicidad de registros
            $consulta2 = "SELECT identificacion, correo, est FROM pys_personas WHERE (est = '0' OR est = '1') AND correo = '$mail';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if (mysqli_num_rows($resultado2) > 0) {
                echo '<script>alert("El registro ya existe. Registro no válido.");</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
            } else { // Si el usuario no está creado en la tabla procedemos a realizar el registro de los datos
                /** Consultamos el ID de la tabla pys_facdepto respecto a la facultad y el departamento*/
                if ($departamento == null) {
                    $consulta3 = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idFac = '$facultad' AND idEnt = '$entidad';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    while ($datos3 = mysqli_fetch_array($resultado3)) {
                        $departamento = $datos3[0];
                    }
                }
                /** Actualización de representante y cargo en la tabla pys_departamentos*/
                if ($departamento != null) {
                    $consulta4 = "SELECT idDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND idFacDepto = '$departamento';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    while ($datos4 = mysqli_fetch_array($resultado4)) {
                        $codDepto = $datos4[0];
                    }
                    /** CAR029 - Director Departamento */
                    if ($cargo == "CAR029") {
                        $consulta5 = "UPDATE pys_departamentos SET coordDepto = '$idPer', cargoCoordDepto = '$cargo' WHERE idDepto = '$codDepto';";
                        $resultado5 = mysqli_query($connection, $consulta5);
                    }
                /** Actualización de coordinador y cargo en la tabla pys_facultades */
                } else if ($facultad != null && $departamento == null) {
                    /** CAR003 - Decano */
                    if ($cargo == "CAR003") {
                        $consulta6 = "UPDATE pys_facultades SET coordFac='$idPer', cargoCoordFac = '$cargo' WHERE idFac='$facultad';";
                        $resultado6 = mysqli_query($connection, $consulta6);
                    }
                /** Actualización de coordinador y cargo en la tabla pys_entidades */
                } else {
                    $consulta7 = "UPDATE pys_entidades SET coordEnt = '$idPer', cargoCoordEnt = '$cargo' WHERE idEnt='$entidad';";
                    $resultado7 = mysqli_query($connection, $consulta7);
                }
                $consulta8 = "INSERT INTO pys_personas VALUES ('$idPer', '$departamento', '$cargo', '$tipo', '$identificacion', '$apellido1', '$apellido2', '$nombres', '$mail' , '$fijo', '$extension', '$celular', '$ciudad', '$equipo', '$categoriaCargo', '1');";
                $resultado8 = mysqli_query($connection, $consulta8);
                echo $consulta8;
                if ($resultado8) {
                    echo "<script> alert ('El registro se insertó correctamente');</script>";
			        echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
                } else {
                    echo "<script> alert ('Ocurrió un error y el registro no pudo ser guardado.');</script>";
			        echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarUsuario($idPersona, $identificacion, $nombres, $apellido1, $apellido2, $entidad, $facultad, $departamento, $cargo, $tipo, $equipo, $ciudad, $correo, $telefono, $extension, $celular, $categoriaCargo) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_personas WHERE idPersona = '$idPersona';";
            $resultado = mysqli_query($connection, $consulta);
            if ($departamento == "") {
                if ($facultad != null) {
                    $consulta3 = "SELECT idFacDepto FROM pys_facdepto WHERE estFacdeptoEnt = '1' AND estFacdeptoFac = '1' AND idDepto = 'DP0027' AND idFac = '$facultad' AND idEnt = '$entidad';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                } else {
                    $consulta3 = "SELECT idFacDepto FROM pys_facdepto WHERE idEnt = '$entidad';";
                    $resultado3 = mysqli_query($connection, $consulta3);
                }
                $depto = mysqli_fetch_array($resultado3);
                $departamento = $depto['idFacDepto'];
            }
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $datosBD = mysqli_fetch_array($resultado);
                if ($identificacion == $datosBD['identificacion'] && 
                        $nombres == $datosBD['nombres'] && 
                            $apellido1 == $datosBD['apellido1'] && 
                                $apellido2 == $datosBD['apellido2'] &&
                                    $departamento == $datosBD['idFacDepto'] &&
                                        $cargo == $datosBD['idCargo'] &&
                                            $tipo == $datosBD['tipoPersona'] &&
                                                $equipo == $datosBD['idEquipo'] &&
                                                    $ciudad == $datosBD['Ciudades_idCiudades'] &&
                                                        $correo == $datosBD['correo'] &&
                                                            $telefono == $datosBD['telefono'] &&
                                                                $extension == $datosBD['extension'] &&
                                                                    $celular == $datosBD['celular'] &&
                                                                        $categoriaCargo == $datosBD['categoriaCargo']) {
                    echo "<script> alert ('No se actualizó el registro.');</script>";
			        echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
                } else {
                    $consulta2 = "UPDATE pys_personas 
                        SET idFacDepto = '$departamento', idCargo = '$cargo', tipoPersona = '$tipo', identificacion = '$identificacion', apellido1 = '$apellido1', apellido2 = '$apellido2', 
                        nombres = '$nombres', correo = '$correo', telefono = '$telefono', extension = '$extension', celular = '$celular', Ciudades_idCiudades = '$ciudad', idEquipo = '$equipo', categoriaCargo = '$categoriaCargo'
                        WHERE (idPersona = '$idPersona');";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($resultado2) {
                        echo "<script> alert ('Se actualizó correctamente el registro.');</script>";
			            echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
                    } else {
                        echo "<script> alert ('Ocurrió un error, no se pudo actualizar el registro.');</script>";
			            echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function eliminarUsuario($idPersona) {
            require('../Core/connection.php');
            $consulta = "UPDATE pys_personas SET est = '0' WHERE (`idPersona` = '$idPersona');";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado) {
                echo "<script> alert ('Se eliminó correctamente el registro.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
            } else {
                echo "<script> alert ('Ocurrió un error, no se pudo actualizar el registro.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/usuario.php">';
            }
            mysqli_close($connection);
        }
    }
    
?>