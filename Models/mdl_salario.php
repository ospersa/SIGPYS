<?php

Class Salarios {

    public static function onLoad ($idSalario) {
        require('../Core/connection.php');
        $consulta = "SELECT pys_salarios.idSalarios, pys_salarios.idPersona, pys_salarios.mes, pys_salarios.anio, pys_salarios.salario, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres
            FROM pys_salarios
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_salarios.idPersona
            WHERE pys_salarios.idSalarios = '$idSalario' AND pys_salarios.estSal = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function busquedaTotal () {
        require('../Core/connection.php');
        echo '
            <table class="responsive-table left">
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Salario</th>
                        <th>Vigente desde</th>
                        <th>Vigente hasta</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
        $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_salarios.idSalarios, pys_salarios.mes, pys_salarios.anio, pys_salarios.salario
            FROM pys_salarios
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_salarios.idPersona
            WHERE pys_salarios.estSal = '1'
            ORDER BY pys_personas.apellido1;";
        $resultado = mysqli_query($connection, $consulta);
        while ($datos = mysqli_fetch_array($resultado)) {
            echo '
                    <tr>
                        <td>'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</td>
                        <td>$ '.number_format($datos['salario'], 2,",",".").'</td>
                        <td>'.$datos['mes'].'</td>
                        <td>'.$datos['anio'].'</td>
                        <td><a href="#modalSalario" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[3]'".','."'modalSalario.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>
            ';
        }
        echo '  </tbody>
            </table>';
        mysqli_close($connection);
    }

    public static function busqueda ($busqueda) {
        require('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_salarios.idSalarios, pys_salarios.mes, pys_salarios.anio, pys_salarios.salario FROM pys_salarios
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_salarios.idPersona
            WHERE ((pys_personas.apellido1 LIKE '%$busqueda%') OR (pys_personas.apellido2 LIKE '%$busqueda%') OR (pys_personas.nombres LIKE '%$busqueda%') OR (pys_salarios.mes LIKE '%$busqueda%') OR (pys_salarios.anio LIKE '%$busqueda%')) AND pys_salarios.estSal='1'
            ORDER BY pys_personas.apellido1";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            echo '
            <h5 class="center-align">'.$registros.' Resultados para la busqueda: '.$busqueda.'</h5>
            <table class="responsive-table left">
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Salario</th>
                        <th>Vigente desde</th>
                        <th>Vigente hasta</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
            ';
            while ($datos = mysqli_fetch_array($resultado)) {
                echo '
                        <tr>
                            <td>'.$datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'].'</td>
                            <td>$ '.number_format($datos['salario'], 2,",",".").'</td>
                            <td>'.$datos['mes'].'</td>
                            <td>'.$datos['anio'].'</td>
                            <td><a href="#modalSalario" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[3]'".','."'modalSalario.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                        </tr>
                ';
            }
            echo '  </tbody>
                </table>';
        } else {
            echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
        }
        mysqli_close($connection);
    }

    public static function registrarSalario ($idPersona, $salario, $vigIni, $vigFin) {
        if (($idPersona && $salario && $vigIni && $vigFin) != null) {
            require('../Core/connection.php');
            $salario = mysqli_real_escape_string($connection, $salario);
            $consulta = "SELECT idSalarios FROM pys_salarios WHERE idPersona = '$idPersona' AND mes = '$vigIni' AND anio = '$vigFin' AND estSal = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros == 0) {
                $consulta2 = "INSERT INTO pys_salarios (idPersona, mes, anio, salario, estSal) VALUES ('$idPersona', '$vigIni', '$vigFin', '$salario', '1');";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2) {
                    echo '<script>alert("Se guardó correctamente la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
                } else {
                    echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
                }
                
            } else {
                echo '<script>alert("Ya existe un registro igual o similar a este. No se guardó la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
            }
            mysqli_close($connection);
        } else {
            echo '<script>alert("No se pudo guardar el registro porque hay algún campo vacío, por favor verifique.")</script>';
            echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
        }
    }

    public static function actualizarSalario ($idSalario, $salario, $vigIni, $vigFin) {
        require('../Core/connection.php');
        $salario = mysqli_real_escape_string($connection, $salario);
        $consulta = "SELECT pys_salarios.salario, pys_salarios.mes, pys_salarios.anio 
            FROM pys_salarios
            WHERE idSalarios = '$idSalario';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        if ($datos['salario'] == $salario && $datos['mes'] == $vigIni && $datos['anio'] == $vigFin) {
            echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
        } else {
            $consulta2 = "UPDATE pys_salarios SET salario = '$salario', mes = '$vigIni', anio = '$vigFin' WHERE idSalarios = '$idSalario';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado2) {
                echo '<script>alert("Se actualizó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
            } else {
                echo '<script>alert("Se presentó un error y el registro no pudo ser actualizado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
            }
        }
        mysqli_close($connection);
    }

    public static function eliminarSalario ($idSalario) {
        require('../Core/connection.php');
        $consulta = "UPDATE pys_salarios SET estSal = '0' WHERE idSalarios = '$idSalario';";
        $resultado = mysqli_query($connection, $consulta);
        if ($resultado) {
            echo '<script>alert("Se eliminó correctamente el registro.")</script>';
            echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
        } else {
            echo '<script>alert("Se presentó un error y el registro no pudo ser eliminado.")</script>';
            echo '<meta http-equiv="Refresh" content="0;url=../Views/salarios.php">';
        }
        mysqli_close($connection);
    }

    public static function selectPersonaConectate ($idPersona) {
        require('../Core/connection.php');
        $consulta = "SELECT idPersona, apellido1, apellido2, nombres FROM pys_personas WHERE est = '1' AND (idEquipo = 'EQU001' OR idEquipo = 'EQU002' OR idEquipo = 'EQU003' OR idEquipo = 'EQU005') ORDER BY apellido1;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $select = '     <select name="sltPersona" id="sltPersona" required>
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
                            <label for="sltPersona">Persona</label>';
        } else {
            $select = '     <select name="sltPersona" id="sltPersona" required>
                                <option value="" selected disabled>No hay personas registradas en el sistema</option>
                            </select>
                            <label for="sltPersona">Persona</label>';
        }
        return $select;
        mysqli_close($connection);
    }

}

?>