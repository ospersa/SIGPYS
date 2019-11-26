<?php

class Departamento {

    public static function selectFacultad($idEntidad){
        require('../Core/connection.php');
        $consulta = "SELECT idFac, facDeptoFacultad from pys_facdepto  where estFacdeptoFac = '1' and idFac != 'FAC014' and idEnt = '$idEntidad'
            GROUP BY idFac, facDeptoFacultad
            ORDER BY facDeptoFacultad;";
        $resultado = mysqli_query($connection, $consulta);
        $rowcount=mysqli_num_rows($resultado);
        if($rowcount != 0){
            echo '
                <div id="" class="input-field col l12 m12 s12">
                    <select name="selFacultad" id="selFacultad" required>
                        <option value="" disabled selected>Seleccione</option>
            ';
                        while ($datos = mysqli_fetch_array($resultado)) {
                            echo '<option value="'.$datos['idFac'].'">'.$datos['facDeptoFacultad'].'</option>';
                        }
            echo '
                    </select>
                    <label for="selFacultad">Facultad</label>
                </div>
            ';
        }else{
            echo '
            <div id="" class="input-field col l12 m12 s12 ">
                <select name="selFacultad" id="selFacultad" required>
                    <option value="" disabled selected>Seleccione</option>
                </select>
                <label for="selFacultad">Facultad</label>
            </div>
            ';
        }
        mysqli_close($connection);
    }

    public static function onLoad($idDepartamento){
        require('../Core/connection.php');
        $consulta = "SELECT pys_entidades.idEnt, pys_facultades.idFac, pys_departamentos.idDepto, pys_entidades.nombreEnt, pys_facultades.nombreFac, pys_departamentos.nombreDepto, pys_departamentos.coordDepto, pys_departamentos.cargoCoordDepto, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo 
            FROM pys_departamentos
            INNER JOIN pys_facdepto ON pys_facdepto.idDepto = pys_departamentos.idDepto
            INNER JOIN pys_facultades ON pys_facdepto.idFac = pys_facultades.idFac
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_personas ON pys_departamentos.coordDepto = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_departamentos.cargoCoordDepto = pys_cargos.idCargo
            WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_departamentos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1'  and pys_personas.est = '1' and pys_cargos.est = '1' and pys_departamentos.idDepto = '$idDepartamento';";
        $resultado = mysqli_query($connection, $consulta);
        $datos =mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaTotal(){
        require('../Core/connection.php');
        $consulta = "SELECT pys_entidades.nombreEnt, pys_facultades.nombreFac, pys_departamentos.idDepto, pys_departamentos.nombreDepto, pys_departamentos.coordDepto, pys_departamentos.cargoCoordDepto, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
            FROM pys_departamentos
            INNER JOIN pys_facdepto ON pys_facdepto.idDepto = pys_departamentos.idDepto
            INNER JOIN pys_facultades ON pys_facdepto.idFac = pys_facultades.idFac
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_personas ON pys_departamentos.coordDepto = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_departamentos.cargoCoordDepto = pys_cargos.idCargo
            WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_departamentos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_personas.est = '1' AND pys_cargos.est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        echo'
        <table class="left responsive-table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Facultad</th>
                    <th>Departamento</th>
                    <th>Director Departamento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        ';
        while ($datos = mysqli_fetch_array($resultado)){
            echo'
                <tr>
                    <td>'.$datos['nombreEnt'].'</td>
                    <td>'.$datos['nombreFac'].'</td>
                    <td>'.$datos['nombreDepto'].'</td>
                    <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                    <td><a href="#modalDepartamento" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[2]'".','."'modalDepartamento.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                </tr>';
        }
        echo "
            </tbody>
        </table>";
        mysqli_close($connection);
    }

    public static function busqueda($busqueda){
        require('../Core/connection.php');
        $consulta="SELECT pys_entidades.nombreEnt, pys_facultades.nombreFac, pys_departamentos.idDepto, pys_departamentos.nombreDepto, pys_departamentos.coordDepto, pys_departamentos.cargoCoordDepto, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_cargos.nombreCargo, pys_cargos.idCargo 
            FROM pys_departamentos
            INNER JOIN pys_facdepto ON pys_facdepto.idDepto = pys_departamentos.idDepto
            INNER JOIN pys_facultades ON pys_facdepto.idFac = pys_facultades.idFac
            INNER JOIN pys_entidades ON pys_facdepto.idEnt = pys_entidades.idEnt
            INNER JOIN pys_personas ON pys_departamentos.coordDepto = pys_personas.idPersona
            INNER JOIN pys_cargos ON pys_departamentos.cargoCoordDepto = pys_cargos.idCargo
            WHERE pys_entidades.est = '1' AND pys_facultades.est = '1' AND pys_departamentos.est = '1' AND pys_facdepto.estFacdeptoEnt = '1' AND pys_facdepto.estFacdeptoFac = '1' AND pys_facdepto.estFacdeptoDepto = '1' AND pys_personas.est = '1' AND pys_cargos.est = '1' AND ((pys_facultades.nombreFac LIKE '%".$busqueda."%') OR (pys_departamentos.nombreDepto LIKE '%".$busqueda."%'))
            ORDER BY pys_facdepto.facDeptoFacultad;";
        $resultado = mysqli_query($connection, $consulta);
        $count=mysqli_num_rows($resultado);
        if($count > 0){
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Facultad</th>
                        <th>Departamento</th>
                        <th>Director Departamento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos =mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreEnt'].'</td>
                        <td>'.$datos['nombreFac'].'</td>
                        <td>'.$datos['nombreDepto'].'</td>
                        <td>'.$datos['apellido1'].' '.$datos['apellido2'].' '.$datos['nombres'].'</td>
                        <td><a href="#modalDepartamento" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[2]'".','."'modalDepartamento.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
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

    public static function registrarDepartamento($idEntidad, $idFacultad, $nomDepartamento){
        require('../Core/connection.php');
        /* contador para tabla departamentos */
        $consulta = "SELECT COUNT(idDepto),MAX(idDepto) FROM pys_departamentos;";
        $resulta = mysqli_query($connection, $consulta);
        while ($dato = mysqli_fetch_array($resulta)){
            $count=$dato[0];
            $max=$dato[1];
        }
        if ($count==0){
            $codDepto="DP0001";
        }
        else {
            $codDepto='DP'.substr((substr($max,2)+10001),1);	
        }	
        /* contador para tabla facdepto */
		$sql1="SELECT COUNT(idFacDepto),MAX(idFacDepto) FROM pys_facdepto;";
        $cs1 = mysqli_query($connection, $sql1);
        while ($result = mysqli_fetch_array($cs1)){
			$count=$result[0];
			$max=$result[1];
		}
		if ($count==0){
			$codFacDepto="FD0001";	
		}
		else{
			$codFacDepto='FD'.substr((substr($max,2)+10001),1);		
		}
        /*Código de inserción en la tabla pys_departamentos*/	
        $sql="INSERT INTO pys_departamentos VALUES ('$codDepto', '$nomDepartamento', 'PR0042', 'CAR032', '1');";
        $resultado = mysqli_query($connection, $sql);
        /*Código para buscar el nombre de la  facultad*/
        $sql2="SELECT nombreFac FROM pys_facultades WHERE idFac = '$idFacultad';";
        $resultado2 = mysqli_query($connection, $sql2);
        while ($datos2 =mysqli_fetch_array($resultado2)){
           $nomFacultad = $datos2[0];
        }
        /*Código de inserción en la tabla pys_facdepto*/
        echo $sql3="INSERT INTO pys_facdepto VALUES ('$codFacDepto', '$idEntidad', '$idFacultad', '$codDepto', '$nomFacultad', '$nomDepartamento', '1', '1', '1');";
        $resultado3 = mysqli_query($connection, $sql3);

        if($resultado && $resultado2 && $resultado3){
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar guardar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }
        mysqli_close($connection);
    }

    public static function actualizarDepartamento($idDepartamento2, $idEntidad, $idFacultad, $nomFacultad, $nomDepartamento){
        require('../Core/connection.php');
        $consulta = "UPDATE pys_departamentos SET nombreDepto = '$nomDepartamento' WHERE idDepto = '$idDepartamento2';";
        $resultado = mysqli_query($connection, $consulta);
        echo $consulta = "UPDATE pys_facdepto SET idEnt = '$idEntidad', idFac = '$idFacultad', idDepto = '$idDepartamento2', facDeptoFacultad = '$nomFacultad', facDeptoDepartamento = '$nomDepartamento' WHERE idDepto = '$idDepartamento2';";
        $resultado2 = mysqli_query($connection, $consulta);
        if($resultado && $resultado2){
            echo "<script> alert ('Se guardó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }
        mysqli_close($connection);
    }

    public static function suprimirDepartamento($idDepartamento2){
        require('../Core/connection.php');
        $consulta = "UPDATE pys_departamentos SET est = '0' WHERE idDepto='$idDepartamento2';";
        $resultado = mysqli_query($connection, $consulta);
        $consulta = "UPDATE pys_facdepto SET estFacdeptoDepto = '0' WHERE idDepto='$idDepartamento2';";
        $resultado2 = mysqli_query($connection, $consulta);
        if($resultado && $resultado2){
            echo "<script> alert ('Se eliminó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }else{
            echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/departamento.php">';
        }
        mysqli_close($connection);
    }
    
}

?>