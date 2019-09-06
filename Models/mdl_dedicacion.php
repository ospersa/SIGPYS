<?php
    class Dedicacion {

        public static function onLoad($idDedicacion){
            require('../Core/connection.php');
            $consulta = "SELECT * 
                FROM ((dbpys.pys_dedicaciones 
                INNER JOIN dbpys.pys_periodos ON pys_periodos.idPeriodo = pys_dedicaciones.periodo_IdPeriodo)
                INNER JOIN dbpys.pys_personas ON pys_personas.idPersona = pys_dedicaciones.persona_IdPersona)
                WHERE estadoDedicacion='1' and idDedicacion ='".$idDedicacion."';";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $fetch[] = $datos;
            }
            return $fetch;
            mysqli_close($connection);
        }

        public static function guardarDedicacion ($periodo, $persona, $porcenDedica1, $porcenDedica2){
            require ('../Core/connection.php');
            $consulta1 = "SELECT COUNT(idDedicacion) FROM pys_dedicaciones;";
            $resultado1 = mysqli_query($connection, $consulta1);
            while ($datos = mysqli_fetch_array($resultado1)){
                $count=$datos[0];
            }
            if ($count==0){
                $idDedicacion = 1;
            }
            else if ($count > 0) {
                $idDedicacion = $count + 1;
            }
            $consulta2 = "SELECT * FROM pys_periodos WHERE idPeriodo = $periodo";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $horasS1 = $datos2['diasSegmento1'] * 8;
            $horasS2 = $datos2['diasSegmento2'] * 8;
            $query = "INSERT INTO pys_dedicaciones (idDedicacion, periodo_IdPeriodo, persona_IdPersona, porcentajeDedicacion1, porcentajeDedicacion2, totalHoras, estadoDedicacion) 
                VALUES ";
            for ($i=0; $i < count($persona); $i++) {
                $totalHoras = (($horasS1 * $porcenDedica1[$i]) / 100) + (($horasS2 * $porcenDedica2[$i]) / 100);
                $query .= "($idDedicacion, $periodo, '$persona[$i]', $porcenDedica1[$i], $porcenDedica2[$i], $totalHoras, 1),";
                $idDedicacion++;
            }
            $query_final = substr($query, 0, -1);
            $query_final .=";";
            $consulta2 = $query_final;
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado2) {
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo "<meta http-equiv='Refresh' content='0;url=../Views/planeacion.php?cod=".$periodo."'>";
            } else {
                echo "<script> alert ('No se pudo guardar la información');</script>";
            }
            mysqli_close($connection);
        }

        public static function actualizarDedicacion ($idDedicacion, $porcentaje1, $porcentaje2, $horasDedica){
            require ('../Core/connection.php');
            $consulta = "UPDATE pys_dedicaciones SET porcentajeDedicacion1 = '$porcentaje1', porcentajeDedicacion2 = '$porcentaje2', totalHoras = '$horasDedica' WHERE idDedicacion = '$idDedicacion';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection);
            echo "<script> alert ('Se actualizó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/dedicacion.php">';
        }

        public static function suprimirDedicacion ($idDedicacion){
            require ('../Core/connection.php');
            $consulta = "UPDATE pys_dedicaciones SET estadoDedicacion = '0' WHERE idDedicacion = '$idDedicacion';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close ($connection);
            echo "<script> alert ('Se eliminó correctamente la información');</script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/dedicacion.php">';
        }

    }


?>