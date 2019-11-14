<?php

class Personas {

    public static function idPersona ($usuario){
        require('../Core/connection.php');
        $consulta = "SELECT idPersona FROM pys_login WHERE usrLogin = '$usuario' AND est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $usuario = $datos['idPersona'];
        return $usuario;
        mysqli_close($connection);
    }

    public static function consultaPeriodo () {
        require('../Core/connection.php');
        $fecha = date("y-m-d");
        $consulta = "SELECT idPeriodo FROM pys_periodos WHERE ('$fecha' >= inicioPeriodo AND '$fecha' <= finPeriodo) AND estadoPeriodo = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos['idPeriodo'];
        mysqli_close($connection);
    }

    public static function selectPeriodo () {
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_periodos WHERE estadoPeriodo = '1' ORDER BY inicioPeriodo DESC;";
        $resultado = mysqli_query($connection, $consulta);
        $count = mysqli_num_rows($resultado);
        if ($count == 0){
            echo "<script> alert ('No hay periodos registrados en la base de datos');</script>";
        } else {
            $string = '     <select name="sltPeriodo2" id="sltPeriodo2">
                                <option value="" disabled selected>Seleccionar</option>';
            while ($datos =mysqli_fetch_array($resultado)){
                $string .= "    <option value='". $datos["idPeriodo"] ."'> Periodo del: ". $datos["inicioPeriodo"].' al '. $datos["finPeriodo"].' '."</option>";
            }
            $string .= '    </select>
                            <label for="periodo">Periodo</label>';
        }
        return $string;
        mysqli_close($connection);
    }
    
    public static function listPersonas($idperiodo){
        $i = 0;
        require('../Core/connection.php');
        $consulta = "SELECT * 
            FROM pys_personas 
            WHERE est = '1' 
            AND ((idCargo = 'CAR010') OR (idCargo = 'CAR012') OR (idCargo = 'CAR013') OR (idCargo = 'CAR014') OR (idCargo = 'CAR015') OR (idCargo = 'CAR016') OR (idCargo = 'CAR017') OR (idCargo = 'CAR019') OR (idCargo = 'CAR027') OR (idCargo = 'CAR033') OR (idCargo = 'CAR035') OR (idCargo = 'CAR036'))
            ORDER BY apellido1;";
        $resultado = mysqli_query($connection,$consulta);
        echo'   <table class="centered responsive-table">
                    <thead>
                        <tr>
                            <th hidden>ID Persona</th>
                            <th>Nombre Persona</th>
                            <th>% Dedicación Seg. 1</th>
                            <th>Días Seg. 1</th>
                            <th>Horas Seg. 1</th>
                            <th>% Dedicación Seg. 2</th>
                            <th>Días Seg. 2</th>
                            <th>Horas Seg. 2</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDedicaciones">';
        $consulta2 = "SELECT * FROM pys_periodos WHERE idPeriodo='$idperiodo';";
        $resultado2 = mysqli_query($connection,$consulta2);
        $datos2 = mysqli_fetch_array($resultado2);
        while ($datos = mysqli_fetch_array($resultado)){
            echo'       <tr>
                            <td hidden><input  class="center-align" type="hidden" name="idPersona['.$i.']" value="'.$datos[0].'"></input></td>
                            <td>'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</td>
                            <td><input required class="center-align teal lighten-4" type="number" min="0" max="100" id="dedicacion1Seg'.$i.'" name="dedicacion1Seg['.$i.']" oninput="horasReales1Seg'.$i.'.value = dedicacion1Seg'.$i.'.value * '.(($datos2['diasSegmento1'] * 8) / 100).'"></input></td>
                            <td>'.$datos2['diasSegmento1'].'</td>
                            <td><input readonly class="center-align"  type="number" name="horasReales1Seg['.$i.']" id="horasReales1Seg'.$i.'"></input></td>
                            <td><input required class="center-align teal lighten-4" type="number" min="0" max="100" id="dedicacion2Seg'.$i.'" name="dedicacion2Seg['.$i.']" oninput="horasReales2Seg'.$i.'.value = dedicacion2Seg'.$i.'.value * '.(($datos2['diasSegmento2'] * 8) / 100).'"></input></td>
                            <td>'.$datos2['diasSegmento2'].'</td>
                            <td><input readonly class="center-align"  type="number" name="horasReales2Seg['.$i.']" id="horasReales2Seg'.$i.'"></input></td>
                        </tr>';
            $i = $i+1;
        }
        echo "      </tbody>
                </table>
            <div class='input-field center-align'>
                        <button class='btn waves-effect waves-light' type='submit' name='enviar'>Guardar</button>
            </div>";
        mysqli_close($connection);
    }

    public static function completarInfo($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT * 
            FROM ((dbpys.pys_dedicaciones 
            INNER JOIN dbpys.pys_periodos ON pys_periodos.idPeriodo = pys_dedicaciones.periodo_IdPeriodo)
            INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
            WHERE periodo_IdPeriodo = $idPeriodo 
            AND estadoDedicacion = '1';";
        $resultado = mysqli_query($connection, $consulta);
        echo'   <table class="centered responsive-table">
                    <thead>
                        <tr>
                            <th hidden>ID Persona</th>
                            <th>Nombre Persona</th>
                            <th>% Dedicación Seg. 1</th>
                            <th>Días Seg. 1</th>
                            <th>Horas Seg. 1</th>
                            <th>% Dedicación Seg. 2</th>
                            <th>Días Seg. 2</th>
                            <th>Horas Seg. 2</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDedicaciones">';
        while ($datos = mysqli_fetch_array($resultado)){
            $hrsSegmento1 = (($datos['diasSegmento1'] * 8) * $datos['porcentajeDedicacion1']) / 100;
            $hrsSegmento2 = (($datos['diasSegmento2'] * 8) * $datos['porcentajeDedicacion2']) / 100;
            echo'       <tr>
                            <td hidden><input  class="center-align" type="hidden" name="idPersona['.$datos['persona_IdPersona'].']" value="'.$datos['persona_IdPersona'].'"></input></td>
                            <td>'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</td>
                            <td><input disabled require class="center-align" type="number" min="0" max="100" id="dedicacion" name="dedicacion[]" value="'.$datos['porcentajeDedicacion1'].'"></input></td>
                            <td>'.$datos['diasSegmento1'].'</td>
                            <td><input disabled class="center-align"  type="number" name="horasReales[]" id="horasReales" value="'.$hrsSegmento1.'"></input></td>
                            <td><input disabled require class="center-align" type="number" min="0" max="100" id="dedicacion" name="dedicacion[]" value="'.$datos['porcentajeDedicacion2'].'"></input></td>
                            <td>'.$datos['diasSegmento2'].'</td>
                            <td><input disabled class="center-align"  type="number" name="horasReales[]" id="horasReales" value="'.$hrsSegmento2.'"></input></td>
                            <td><a href="#modalDedicacion" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalDedicacion.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                        </tr>';
        }
        mysqli_close($connection);
    }

    public static function validarDatos($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_dedicaciones WHERE periodo_IdPeriodo = $idPeriodo;";
        $resultado = mysqli_query($connection, $consulta);
        $count = mysqli_num_rows($resultado);
        if ($count == 0){
            return false;
        } else {
            return true;
        }
        mysqli_close($connection);
    }

    public static function selectPersonas($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT idPersona, apellido1, apellido2, nombres
            FROM (dbpys.pys_dedicaciones 
            INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
            WHERE periodo_IdPeriodo = '$idPeriodo' AND estadoDedicacion = '1' AND totalHoras != '0';";
        $resultado = mysqli_query($connection, $consulta);
        echo '  <select name="sltPersona" id="sltPersona">
                    <option value="" disabled selected>Seleccionar</option>
        ';
        while ($datos = mysqli_fetch_array($resultado)) {
            echo '  <option value="'.$datos['idPersona'].'">'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</option>';
        }
        echo '  </select>
                <label for="sltPersona">Persona</label>';
        mysqli_close($connection);
    }

    public static function selectPersonas2($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT idPersona, apellido1, apellido2, nombres
            FROM (dbpys.pys_dedicaciones 
            INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
            WHERE periodo_IdPeriodo = '$idPeriodo' AND estadoDedicacion = '1' AND totalHoras != '0';";
        $resultado = mysqli_query($connection, $consulta);
        $string ='  <select name="sltPersona" class="inactivate" searchable="Ingrese un término de búsqueda" id="sltPersona" >
                    <option value="">Seleccionar</option>
        ';
        while ($datos = mysqli_fetch_array($resultado)) {
            $string .= '  <option value="'.$datos['idPersona'].'">'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</option>';
        }
        $string .= '  </select>
                <label for="sltPersona">Persona</label>';
        return $string;
        mysqli_close($connection);
    }

    public static function selectPersonasPlaneacion($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT idPersona, apellido1, apellido2, nombres
            FROM (dbpys.pys_dedicaciones 
            INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
            WHERE periodo_IdPeriodo = '$idPeriodo' AND estadoDedicacion = '1';";
        $resultado = mysqli_query($connection, $consulta);
        echo '  <div class="input-field select-plugin">
                    <select name="sltPersonaPlan" id="sltPersonaPlan">
                        <option value="" disabled selected>Seleccionar</option>
        ';
        while ($datos = mysqli_fetch_array($resultado)) {
            echo '      <option value="'.$datos['idPersona'].'">'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</option>';
        }
        echo '      </select>
                    <label for="sltPersonaPlan">Persona</label>
                </div>';
        mysqli_close($connection);
    }

    public static function validarPersonas($idPeriodo){
        require('../Core/connection.php');
        $consulta = "SELECT count(idDedicacion)
            FROM (dbpys.pys_dedicaciones 
            INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
            WHERE periodo_IdPeriodo = '$idPeriodo' AND estadoDedicacion = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $count = mysqli_num_rows($resultado);
        if ($count == 0){
            return false;
        } else {
            return true;
        }
        mysqli_close($connection);
    }

}

?>