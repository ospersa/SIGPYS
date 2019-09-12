<?php
    class Fase{

        public static function onLoad ($idFase) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_fases WHERE est = '1' AND idFase = '$idFase';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_fases WHERE est='1' ORDER BY nombreFase;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="centered responsive-table">
                <thead>
                    <tr>
                        <th>Nombre de la fase</th>
                        <th>Descripción de la fase</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos[1].'</td>
                        <td>'.$datos[2].'</td>
                        <td><a href="#modalFase" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFase.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_fases WHERE est='1' AND nombreFase LIKE '%".$busqueda."%' ORDER BY nombreFase;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                    echo'
                <table class="centered responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre de la fase</th>
                            <th>Descripción de la fase</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos[1].'</td>
                            <td>'.$datos[2].'</td>
                            <td><a href="#modalFase" class="waves-effect waves-light btn modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalFase.php'".');" title="Editar"><i class="material-icons">edit</i></a></td>
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

        public static function registrarFase($nomFase, $descFase){
            require('../Core/connection.php');
            /** Verificación de campos vacíos */
            if ($nomFase == null || $nomFase == "" || $nomFase == " ") {
                echo "<script> alert('Existe algún campo vacío. Por favor intente nuevamente.');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
            } else {
                /** Verificación de información existente en la tabla, para evitar duplicidad */
                $consulta = "SELECT * FROM pys_fases WHERE nombreFase = '$nomFase' AND descripcionFase = '$descFase' AND est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $registros = mysqli_num_rows($resultado);
                if ($registros > 0) {
                    echo "<script> alert('Ya existe un registro con el nombre ingresado. La información no se modificó');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
                } else {
                    $consulta = "SELECT COUNT(idFase), MAX(idFase) FROM pys_fases;";
                    $resultado = mysqli_query($connection, $consulta);
                    while ($datos = mysqli_fetch_array($resultado)){
                        $count = $datos[0];
                        $max = $datos[1];
                    }
                    if ($count == 0){
                        $codFase = "FS0001";
                    }
                    else {
                        $codFase = 'FS'.substr((substr($max,2)+10001),1);	
                    }		
                    $consulta1 = "INSERT INTO pys_fases VALUES ('$codFase', '$nomFase',  '$descFase', '1');";
                    $resultado1 = mysqli_query($connection, $consulta1);
                    if ($resultado1) {
                        echo "<script> alert ('Se guardó correctamente la información');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
                    } else {
                        echo "<script> alert('Ocurrió un error al intentar guardar el registro.');</script>";
                        echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
                    }
                }
            }
            mysqli_close($connection);
        }

        public static function actualizarFase($idFase2, $nomFase, $descFase){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_fases SET nombreFase='$nomFase', descripcionFase='$descFase' WHERE idFase='$idFase2';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
            }
            mysqli_close($connection);
        }

        public static function suprimirFase($idFase2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_fases SET est='0' WHERE idFase='$idFase2';";
            $resultado = mysqli_query($connection, $consulta);
            if ($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
            }else{
                echo "<script> alert ('Ocurrió un error al intentar eliminar la informacion');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/fases.php">';
            }
            mysqli_close($connection);
        }

    }
?>