<?php

    Class Tiempos {

        public static function Provisional () {//Cambiar nombre, antes no estaba definido 
            require('../Core/connection.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_solicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_equipos.idEqu, pys_servicios.nombreSer, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct, pys_tipossolicitud.nombreTSol, pys_tipossolicitud.idTSol, pys_actsolicitudes.idEstSol, pys_actsolicitudes.idCM, pys_actsolicitudes.presupuesto, pys_actsolicitudes.horas, pys_solicitudes.idSer
                FROM pys_solicitudes
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idPersona
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_solicitudes.idSer
                INNER JOIN pys_equipos ON pys_equipos.idEqu = pys_servicios.idEqu
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                INNER JOIN pys_tipossolicitud ON pys_tipossolicitud.idTSol = pys_solicitudes.idTSol
                WHERE pys_solicitudes.est = '1' AND pys_solicitudes.idSol = '$id' AND pys_personas.est = '1' AND pys_equipos.est = '1' AND pys_servicios.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_tipossolicitud.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }
    
    }
?>
