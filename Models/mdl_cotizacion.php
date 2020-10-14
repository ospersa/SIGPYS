<?php

Class Cotizacion {

    public static function onLoad($idAsig){
        require('../Core/connection.php');
        $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idSol, pys_asignados.est, pys_actsolicitudes.idSolicitante
            FROM pys_asignados 
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_asignados.idSol
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSolIni
            WHERE idAsig = '$idAsig'
            AND pys_personas.est = 1
            AND pys_solicitudes.est = 1
            AND (pys_asignados.est = 1 OR pys_asignados.est = 2);";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;        
        mysqli_close($connection);
    }

    public static function onLoad2($idCot){
        require('../Core/connection.php');
        $consulta="SELECT *
            FROM pys_cotizaciones 
            WHERE idCotizacion = '".$idCot."'
            AND pys_cotizaciones.estado = 1;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;     
        mysqli_close($connection);
    }

    public static function busqueda($busqueda) {
        require ('../Core/connection.php');
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        $consulta = "SELECT idSolIni, idSolEsp, codProy, nombreProy, ObservacionAct, idCotizacion FROM pys_actsolicitudes 
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_cotizaciones ON pys_cotizaciones.idSolEsp = pys_actsolicitudes.idSol
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            WHERE pys_actsolicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_cotizaciones.estado = 1 AND pys_solicitudes.est = 1
            AND (pys_cotizaciones.valorPresupuesto LIKE '%$busqueda%' OR pys_cotizaciones.idSolEsp LIKE '%$busqueda%' OR pys_cotizaciones.fechaRegistro LIKE '%$busqueda%' OR pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%' OR pys_actsolicitudes.ObservacionAct LIKE '%$busqueda%' OR pys_solicitudes.idSolIni LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            echo '
            <h6>Resultados para la busqueda: <strong>'.$busqueda.'</strong></h6>
            <table class="responsive-table striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cód. Proyecto</th>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Solicitante</th>
                        <th>Consultar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $consulta2 = "SELECT apellido1, apellido2, nombres, idSolicitante FROM pys_actsolicitudes 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_actsolicitudes.idSolicitante
                    WHERE pys_actsolicitudes.idSol = '$datos[idSolIni]' AND pys_actsolicitudes.est = 1;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $nombre = strtoupper($datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres']);
                echo '
                        <tr>
                            <td>P'.$datos["idSolEsp"].'</td>
                            <td>'.$datos["codProy"].'</td>
                            <td>'.$datos["nombreProy"].'</td>
                            <td>'.$datos["ObservacionAct"].'</td>
                            <td>'.$nombre.'</td>
                            <td><a href="cotizador.php?cod='.$datos['idSolEsp'].'&val='.$datos2['idSolicitante'].'&cod2='.$datos['idCotizacion'].'" class="waves-effect waves-light btn" title="Editar"><i class="material-icons">visibility</i></a></td>
                        </tr>
                ';
            }
            echo '
                    </tbody>
                </table>';    
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
        mysqli_close($connection);
    }

    public static function busquedaPendientes($busqueda) {
        require ('../Core/connection.php');        
        $busqueda = mysqli_real_escape_string($connection, $busqueda);
        echo "<script>$('#tblCotizaciones').empty();</script>";
        $consulta = "SELECT pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.ObservacionAct, pys_solicitudes.idSolIni
        FROM pys_actsolicitudes
        INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
        INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
        WHERE pys_solicitudes.idTSol = 'TSOL02'
        AND pys_actsolicitudes.est = 1
        AND pys_solicitudes.est = 1
        AND pys_actualizacionproy.est = 1
        AND (pys_actsolicitudes.idEstSol = 'ESS002'
        OR pys_actsolicitudes.idEstSol = 'ESS003'
        OR pys_actsolicitudes.idEstSol = 'ESS004' 
        OR pys_actsolicitudes.idEstSol = 'ESS005')
        AND pys_solicitudes.idSol NOT IN (SELECT pys_cotizaciones.idSolEsp FROM pys_cotizaciones)
        AND (pys_actsolicitudes.idSol LIKE '%$busqueda%' OR pys_solicitudes.idSolIni LIKE '%$busqueda%' OR pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%' OR pys_actsolicitudes.ObservacionAct LIKE '%$busqueda%')
        ORDER BY pys_actsolicitudes.idSol DESC;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            echo '
            <h6>('.$registros.') Resultados para la busqueda: <strong>"'.$busqueda.'"</strong></h6>
            <table class="responsive-table striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cód. Proyecto</th>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Solicitante</th>
                        <th>Cotizar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $consulta2 = "SELECT apellido1, apellido2, nombres, idSolicitante FROM pys_actsolicitudes 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_actsolicitudes.idSolicitante
                    WHERE pys_actsolicitudes.idSol = '$datos[idSolIni]' AND pys_actsolicitudes.est = 1;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $nombre = strtoupper($datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres']);
                echo '
                        <tr>
                            <td>P'.$datos["idSol"].'</td>
                            <td>'.$datos["codProy"].'</td>
                            <td>'.$datos["nombreProy"].'</td>
                            <td>'.$datos["ObservacionAct"].'</td>
                            <td>'.$nombre.'</td>
                            <td><a href="cotizador.php?cod='.$datos['idSol'].'&val='.$datos2['idSolicitante'].'" class="waves-effect waves-light btn" title="Cotizar"><i class="material-icons">attach_money</i></a></td>
                        </tr>
                ';
            }
            echo '
                    </tbody>
                </table>';    
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
        mysqli_close($connection);
    }

    public static function listarSolicitudes() {
        require('../Core/connection.php');
        echo "<h6>Listado solicitudes pendientes por cotización</h6>";
        $consulta = "SELECT pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_actsolicitudes.ObservacionAct, pys_solicitudes.idSolIni
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            WHERE pys_solicitudes.idTSol = 'TSOL02'
            AND pys_actsolicitudes.est = 1
            AND pys_solicitudes.est = 1
            AND pys_actualizacionproy.est = 1
            AND (pys_actsolicitudes.idEstSol = 'ESS002'
            OR pys_actsolicitudes.idEstSol = 'ESS003'
            OR pys_actsolicitudes.idEstSol = 'ESS004' 
            OR pys_actsolicitudes.idEstSol = 'ESS005')
            AND pys_solicitudes.idSol NOT IN (SELECT pys_cotizaciones.idSolEsp FROM pys_cotizaciones)
            ORDER BY pys_actsolicitudes.idSol DESC;";
        $resultado = mysqli_query($connection, $consulta);
        echo'
        <div class="container" id="tblCotizaciones">
        <div class="col l12 m12 s12">
            <table class="responsive-table">
                <thead>
                    <tr class="left-align">
                        <th>Producto</th>
                        <th>Cód. Proyecto</th>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Solicitante</th>
                        <th>Cotizar</th>
                    </tr>
                </thead>
                <tbody id="cotizacion">
        ';
        while ($datos = mysqli_fetch_array($resultado)) {
            $consulta2 = "SELECT apellido1, apellido2, nombres, idSolicitante FROM pys_actsolicitudes 
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_actsolicitudes.idSolicitante
            WHERE pys_actsolicitudes.idSol = '".$datos['idSolIni']."'
            AND pys_actsolicitudes.est =  '1'
            AND pys_personas.est =  '1';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $nombreCompleto = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
            echo '
                <tr>
                    <td>P'.$datos['idSol'].'</td>
                    <td>'.$datos['codProy'].'</td>
                    <td>'.$datos['nombreProy'].'</td>
                    <td>'.$datos['ObservacionAct'].'</td>
                    <td>'.$nombreCompleto.'</td>
                    <td><a href="cotizador.php?cod='.$datos['idSol'].'&val='.$datos2['idSolicitante'].'" class="waves-effect waves-light btn" title="Cotizar"><i class="material-icons">attach_money</i></a></td>
                </tr>';
        }
        echo "
                </tbody>
            </table>
        </div>
        </div>";
        mysqli_close($connection);
    }

    public static function infoSolicitud($idSol) {
        require('../Core/connection.php');
        $consulta = "SELECT *
            FROM pys_actsolicitudes
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_solicitudes on pys_solicitudes.idSol = pys_actsolicitudes.idSol
            WHERE pys_actsolicitudes.idSol = '$idSol'
            AND pys_actsolicitudes.est = '1'
            AND pys_solicitudes.est = '1'
            AND pys_actualizacionproy.est = '1'";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        mysqli_close($connection);
        return $datos;
    }

    public static function infoSolicitudInicial($idSol) {
        require('../Core/connection.php');
        $consulta = "SELECT *
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            WHERE pys_actsolicitudes.idSol = '$idSol'
            AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        mysqli_close($connection);
        return $datos;
    }

    public static function consultaSolicitante($idSolicitante) {
        require('../Core/connection.php');
        $consulta = "SELECT apellido1, apellido2, nombres FROM pys_personas WHERE pys_personas.idPersona = '$idSolicitante';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
        mysqli_close($connection);
        return $nombreCompleto;
    }

    public static function guardarCotizacion($solEsp, $valCot, $obsSol, $obsPys, $obsProd, $solicitante) {
        require('../Core/connection.php');
        mysqli_query($connection, "BEGIN;");
        $valCot = str_replace(".","",$valCot);
        $valCot = str_replace("$ ","",$valCot);
        $solEsp = substr($solEsp, 1);
        $consulta2 = "SELECT idActSol FROM pys_actsolicitudes WHERE idSol = '$solEsp' AND est = '1';"; //Obtenemos el ID de la solicitud a la que será agregado el presupuesto
        $resultado2 = mysqli_query($connection, $consulta2);
        $datos2 = mysqli_fetch_array($resultado2);
        $idAct = $datos2[0];
        if ($valCot == "0,00") {
            echo "<script> alert ('El valor de la cotización debe ser mayor de $0.00. Por favor intente nuevamente.'); </script>";
            echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$solEsp.'&val='.$solicitante.'">';
        } else {
            $obsSol = mysqli_real_escape_string($connection, $obsSol);
            $obsPys = mysqli_real_escape_string($connection, $obsPys);
            $obsProd = mysqli_real_escape_string($connection, $obsProd);
            $consulta = "INSERT INTO pys_cotizaciones (idSolEsp, valorPresupuesto, obsSolicitante, obsPyS, obsProducto, fechaRegistro, estado) 
                    VALUES ('$solEsp', '$valCot', '$obsSol', '$obsPys', '$obsProd', now(), '1');";
            $resultado = mysqli_query($connection, $consulta);
            $consulta3 = "UPDATE pys_actsolicitudes SET presupuesto = '$valCot' WHERE idActSol = '$idAct'";
            $resultado3 = mysqli_query($connection, $consulta3);
            if ($resultado && $resultado3) {
                mysqli_query($connection, "COMMIT;");
                $cotizacion = Cotizacion::datosCotizacion($solEsp);
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$solEsp.'&val='.$solicitante.'&cod2='.$cotizacion[0].'">';
            } else {
                mysqli_query($connection, "ROLLBACK;");
                echo "<script> alert ('Ocurrió un error, por favor intente nuevamente.'); </script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$solEsp.'&val='.$solicitante.'">';
            }
        } 
        mysqli_close($connection);
    }

    public static function actualizarCotizacion($idCot, $valCot, $obsSol, $obsPys, $obsProd, $solicitante) {
        require('../Core/connection.php');
        mysqli_query($connection, "BEGIN;");
        $obsSol = mysqli_real_escape_string($connection, $obsSol); //Limpiamos caracteres especiales introducidos en los campos para evitar errores
        $obsPys = mysqli_real_escape_string($connection, $obsPys);
        $obsProd = mysqli_real_escape_string($connection, $obsProd);
        $idCot = substr($idCot, 2);
        $consulta = "SELECT * FROM pys_cotizaciones WHERE idCotizacion = $idCot;";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $consulta4 = "SELECT pys_actsolicitudes.idSolicitante 
            FROM pys_solicitudes 
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSolIni
            WHERE pys_solicitudes.idSol = '".$datos['idSolEsp']."'
            AND pys_actsolicitudes.est = '1'
            AND pys_solicitudes.est = '1';";
        $resultado4 = mysqli_query($connection, $consulta4);
        $solicitante = mysqli_fetch_array($resultado4);
        if ($datos['idCotizacion'] == $idCot && $datos['valorPresupuesto'] != $valCot) {
            $consulta2 = "UPDATE pys_cotizaciones SET estado = '2' WHERE idCotizacion = '$idCot';";
            $resultado2 = mysqli_query($connection, $consulta2);
            $consulta3 = "INSERT INTO pys_cotizaciones (idSolEsp, valorPresupuesto, obsSolicitante, obsPyS, obsProducto, fechaRegistro, estado) VALUES ('".$datos['idSolEsp']."', '$valCot', '$obsSol', '$obsPys', '$obsProd', now(), '1');";
            $resultado3 = mysqli_query($connection, $consulta3);
            $idSol = $datos['idSolEsp'];
            $consultaIdSol = "SELECT idActSol FROM pys_actsolicitudes WHERE idSol = '$idSol' AND est = 1";
            $resultadoIdSol = mysqli_query($connection, $consultaIdSol);
            $datosIdSol = mysqli_fetch_array($resultadoIdSol);
            $idAct = $datosIdSol['idActSol'];
            $consulta5 = "UPDATE pys_actsolicitudes SET presupuesto = '$valCot' WHERE idActSol = '$idAct'";
            $resultado5 = mysqli_query($connection, $consulta5);
            if ($resultado2 && $resultado3 && $resultado5) {
                mysqli_query($connection, "COMMIT;");
                $cotizacion = Cotizacion::datosCotizacion($datos['idSolEsp']);
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$datos['idSolEsp'].'&val='.$solicitante[0].'&cod2='.$cotizacion[0].'#infCotizacion">';
            } else {
                mysqli_query($connection, "ROLLBACK;");
            }
        } else if ($datos['idCotizacion'] == $idCot && $datos['valorPresupuesto'] == $valCot && ($datos['obsSolicitante'] != $obsSol || $datos['obsPyS'] != $obsPys || $datos['obsProducto'] != $obsProd)) { //Cuando la información es diferente a la registrada en la BD se realiza el UPDATE de los datos, se agrega marca de tiempo en la actualización y se cambia estado.
            $consulta2 = "UPDATE pys_cotizaciones SET obsSolicitante = '$obsSol', obsPyS = '$obsPys', obsProducto = '$obsProd' WHERE (idCotizacion = '$idCot');";
            $resultado2 = mysqli_query($connection, $consulta2);
            $cotizacion = Cotizacion::datosCotizacion($datos['idSolEsp']);
            if ($resultado2) {
                mysqli_query($connection, "COMMIT;");
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$datos['idSolEsp'].'&val='.$solicitante[0].'&cod2='.$cotizacion[0].'#infCotizacion">';
            } else {
                mysqli_query($connection, "ROLLBACK;");
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cotizador.php?cod='.$datos['idSolEsp'].'&val='.$solicitante[0].'&cod2='.$cotizacion[0].'#infCotizacion">';
            }
        } else {
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'#infCotizacion">'; //Se realiza redirección a la página anterior
        }
        mysqli_close($connection);
    }

    public static function enviarCorreoCotizacion($solEsp, $solIni, $codProy, $nomProy, $obsProd, $obsProdCot, $valCot, $obsSol, $idProy, $idCot, $totRec, $accion) {
        require('../Core/connection.php');
        require('../Models/mdl_enviarEmail.php');
        $consulta = "SELECT * FROM pys_actsolicitudes 
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_actsolicitudes.idSolicitante
            WHERE idSol = '$solIni'
            AND pys_actsolicitudes.est = '1'
            AND pys_personas.est = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        $copia = "apoyoconectate@uniandes.edu.co;";
        $consulta2 = "SELECT pys_personas.correo FROM pys_asignados 
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            WHERE idProy = '$idProy' AND (idRol = 'ROL024' OR idRol = 'ROL025') AND idSol=''
            AND pys_personas.est = '1'
            AND pys_asignados.est = '1';";
        $resultado2 = mysqli_query($connection, $consulta2);
        while ($datos2 = mysqli_fetch_array($resultado2)) {
            $correo = $datos2[0];
            $copia .= "$correo;";
        }
        $consulta3 = "SELECT fechaRegistro FROM pys_cotizaciones WHERE idCotizacion = '$idCot';"; 
        $resultado3 = mysqli_query($connection, $consulta3);
        $datos3 = mysqli_fetch_array($resultado3);
        $fechaSolIni = Cotizacion::infoSolicitudInicial($solIni);
        $nombreCompleto = $datos['nombres'] ." ". $datos['apellido1'] ." ". $datos['apellido2'];
        $solicita = $datos['correo'];
        if ($accion == 1) {
            $titulo = "Presupuesto: Solicitud ".$solIni." / P".$solEsp." / ".$codProy." - ".$nomProy;
            $mail = "
            <p>Buen día $nombreCompleto,</p>
            <p>Informamos el valor presupuestado para el producto/servicio <strong>P$solEsp</strong>, (solicitud inicial $solIni realizada el día ".date("d/m/Y", strtotime($fechaSolIni['fechSol'])).") del proyecto $codProy - $nomProy.</p>
            <p>Si está de acuerdo, por favor a vuelta de correo aceptar el presupuesto para formalizar la asignación de los encargados y comenzar con el desarrollo del producto/servicio solicitado por usted.</p>
            <table style='background: #e0f2f1; border: 2px solid black;'>
                <tr style='background: #80cbc4;'>
                    <th colspan='3'>Información producto / servicio:</th>
                </tr>
                <tr style='background: #b2dfdb;'>
                    <th>Código producto / servicio</th>
                    <th colspan='2'>Producto con especificación:</th>
                </tr>
                <tr>
                    <td>P$solEsp</td>
                    <td colspan='2'>$obsProd -- $obsProdCot</td>
                </tr>
                <tr style='background: #80cbc4;'>
                    <th colspan='3'>Información presupuesto:</th>
                </tr>
                <tr style='background: #b2dfdb;'>
                    <th>Fecha</th>
                    <th>Valor(PyS)</th>
                    <th>Observación presupuesto</th>
                </tr>
                <tr>
                    <td>".date("d/m/Y",strtotime($datos3[0]))."</td>
                    <td><strong>$ ".number_format($valCot, 0, ",", ".")."</strong></td>
                    <td>$obsSol</td>
                </tr>
            </table>
            <p>Estaremos atentos a sus comentarios.</p>
            <p>Cordialmente,</p>
            <p>
                _________________________________________<br>
                <strong>Equipo de Producción y Soporte</strong><br>
                Centro de Innovación en Tecnología y Educación - Conecta-TE<br>
                Facultad de Educación<br>
                Universidad de los Andes<br>
                <a href='mailto:apoyoconectate@uniandes.edu.co'>apoyoconectate@uniandes.edu.co</a>
            </p>";
        } else {
            $titulo = "Nuevo presupuesto: solicitud ".$solIni." / P".$solEsp." / ".$codProy." - ".$nomProy;
            $mail = "
            <p>Buen día $nombreCompleto,</p>
            <p>Informamos el <strong>nuevo valor presupuestado</strong> para el producto/servicio <strong>P$solEsp</strong>, (solicitud inicial $solIni realizada el día ".date("d/m/Y", strtotime($fechaSolIni['fechSol'])).") del proyecto $codProy - $nomProy.</p>
            <p>Si está de acuerdo, por favor a vuelta de correo aceptar el presupuesto para formalizar la asignación de los encargados y comenzar con el desarrollo del producto/servicio solicitado por usted.</p>
            <table style='background: #e0f2f1; border: 2px solid black;'>
                <tr style='background: #80cbc4;'>
                    <th colspan='3'>Información producto / servicio:</th>
                </tr>
                <tr style='background: #b2dfdb;'>
                    <th>Código producto / servicio</th>
                    <th colspan='2'>Producto con Especificación:</th>
                </tr>
                <tr>
                    <td>P$solEsp</td>
                    <td colspan='2'>$obsProd -- $obsProdCot</td>
                </tr>
                <tr style='background: #80cbc4;'>
                    <th colspan='3'>Información nuevo presupuesto:</th>
                </tr>
                <tr style='background: #b2dfdb;'>
                    <th>Fecha</th>
                    <th>Valor(PyS)</th>
                    <th>Observación presupuesto</th>
                </tr>
                <tr>
                    <td>".date("d/m/Y",strtotime($datos3[0]))."</td>
                    <td><strong>$ ".number_format($valCot, 0, ",", ".")."</strong></td>
                    <td>$obsSol</td>
                </tr>
            </table>
            <p>Estaremos atentos a sus comentarios.</p>
            <p>Cordialmente,</p>
            <p>
                _________________________________________<br>
                <strong>Equipo de Producción y Soporte</strong><br>
                Centro de Innovación en Tecnología y Educación - Conecta-TE<br>
                Facultad de Educación<br>
                Universidad de los Andes<br>
                <a href='mailto:apoyoconectate@uniandes.edu.co'>apoyoconectate@uniandes.edu.co</a>
            </p>";
        }
        $bool = EnviarCorreo::enviarCorreos($solicita, "", $correo, $titulo, $mail);
        if(($bool != NULL) or ($bool != 0)){
            $consulta3 = "UPDATE pys_cotizaciones SET fechaCorreo = now() WHERE idCotizacion = '$idCot';";
            $resultado3 = mysqli_query($connection, $consulta3);
            echo "<script> alert ('El mensaje ha sido enviado correctamente.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        } else {
            echo "<script> alert ('El mensaje no pudo ser enviado.');</script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">';
        }
    }

    public static function verificarCorreoEnviado($idCot) {
        require('../Core/connection.php');
        $consulta = "SELECT fechaCorreo FROM pys_cotizaciones WHERE idCotizacion = '$idCot';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        if ($datos[0] == "") {
            return false;
        } else {
            return true;
        }
        mysqli_close($connection);
    }

    public static function aprobarCotizacion($idCot, $nota, $enlace) {
        require('../Core/connection.php');
        $consulta = "SELECT notaAprobacion, enlaceAprobacion FROM pys_cotizaciones WHERE idCotizacion = '$idCot';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        if ($nota == null && $enlace == null) {
            echo "<script> alert ('Para poder aprobar la cotización por favor agregue la nota de aprobación.'); </script>";
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; //Se realiza redirección a la página anterior
        } else if ($datos['notaAprobacion'] == $nota && $enlace == "") {
            echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; //Se realiza redirección a la página anterior
        } else {
            
            $nota = mysqli_real_escape_string($connection, $nota);
            $enlace = mysqli_real_escape_string($connection, $enlace);
            $consulta2 = "UPDATE pys_cotizaciones SET fechaAprobacion = now(), notaAprobacion = '$nota', enlaceAprobacion = '$enlace' WHERE idCotizacion = '$idCot';";
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado2) {
                echo "<script> alert ('Se ha realizado correctamente la aprobación de la cotización.'); </script>";
                echo '<meta http-equiv="Refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">'; //Se realiza redirección a la página anterior
            }
        }
        mysqli_close($connection);
    }

    public static function verificarAprobacion($idCot) {
        require('../Core/connection.php');
        $consulta = "SELECT notaAprobacion, enlaceAprobacion FROM pys_cotizaciones WHERE idCotizacion = '$idCot';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function obtenerPersonaRegistra($usuario) {
        require('../Core/connection.php');
        $consulta = "SELECT idPersona FROM pys_login WHERE usrLogin = '$usuario';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function mostrarAsignados ($solEsp) {
        include_once('mdl_asignados.php');
        echo $prueba = Asignados::mostrarAsignados("Cot", $solEsp);
    }

    public static function totalAsignaciones($solEsp) {
        require('../Core/connection.php');
        $totalHoras = 0;
        $totalMinutos = 0;
        $totalRecurso = 0;
        $consulta = "SELECT pys_asignados.fechAsig, pys_personas.idPersona, pys_roles.nombreRol, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_fases.nombreFase, pys_asignados.maxhora, pys_asignados.maxmin, pys_asignados.idAsig
            FROM pys_asignados 
            INNER JOIN pys_roles ON pys_roles.idRol = pys_asignados.idRol
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
            INNER JOIN pys_fases ON pys_fases.idFase = pys_asignados.idFase
            WHERE pys_asignados.est = '1' 
            AND pys_roles.est = '1'
            AND idSol = '$solEsp';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_num_rows($resultado);
        if ($datos > 0){
            while ($datos2 = mysqli_fetch_array($resultado)) {
                $fecha = $datos2['fechAsig']; // Tomamos la fecha de asignación al producto para calcular el salario
                if ($fecha == null) { // Si está en blanco procedemos a tomar la fecha de la Solicitud Específica
                    $consFechSolEsp = "SELECT fechSol FROM pys_solicitudes WHERE idSol = '$solEsp';";
                    $resFechSolEsp = mysqli_query($connection, $consFechSolEsp);
                    $fechSolEsp = mysqli_fetch_array($resFechSolEsp);
                    $fecha = date("Y-m-d",strtotime($fechSolEsp['fechSol']));
                } else { // De lo contrario traemos el valor almacenado en la tabla de pys_asignados (Este campo es nuevo en la tabla 21/11/2018)
                    $fecha = date("Y-m-d",strtotime($datos2['fechAsig']));
                }
                $idPersona = $datos2[0];
                $consulta3 = "SELECT * 
                    FROM pys_asignados
                    INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona
                    WHERE pys_salarios.estSal = 1
                    AND pys_asignados.idSol = '$solEsp'
                    AND pys_asignados.idPersona = '$idPersona'
                    AND '$fecha' >= mes 
                    AND '$fecha' <= anio;";
                $resultado3 = mysqli_query($connection, $consulta3);
                $datos3 = mysqli_fetch_array($resultado3);
                $salarioMinuto = $datos3['salario'] / 60;
                $minutosAsignados = ($datos2['maxhora'] * 60) + $datos2['maxmin'];
                $recurso = round($salarioMinuto * $minutosAsignados, 2);
                $totalHoras = $totalHoras + $datos2['maxhora'];
                $totalMinutos = $totalMinutos + $datos2['maxmin'];
                $totalRecurso = $totalRecurso + $recurso;
            }
            if ($totalMinutos > 60) {
                $totalHoras += floor($totalMinutos / 60);
                $totalMinutos = (($totalMinutos / 60) - floor($totalMinutos / 60)) * 60;
            }
        } 
        mysqli_close($connection);
        return $totalRecurso;
    }
    
    public static function listarCotizaciones () {
        require('../Core/connection.php');
        $consulta = "SELECT * FROM pys_cotizaciones
            INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_cotizaciones.idSolEsp
            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_cotizaciones.idSolEsp
            WHERE pys_cotizaciones.estado = 1
            AND pys_actualizacionproy.est = 1
            AND pys_actsolicitudes.est = 1
            ORDER BY pys_cotizaciones.idSolEsp DESC;";
        $resultado = mysqli_query($connection, $consulta);
        $registros = mysqli_num_rows($resultado);
        if ($registros > 0) {
            $tabla = '  <table class="responsive-table highlight">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cód. Proyecto</th>
                                    <th>Proyecto</th>
                                    <th>Descripción</th>
                                    <th>Solicitante</th>
                                    <th>Consultar</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($datos = mysqli_fetch_array($resultado)) {
                $consulta2 = "SELECT apellido1, apellido2, nombres, idSolicitante FROM pys_actsolicitudes 
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_actsolicitudes.idSolicitante
                    WHERE pys_actsolicitudes.idSol = '".$datos['idSolIni']."'
                    AND pys_actsolicitudes.est = '1';"; // Consultamos el solicitante que está activo actualmente en la solicitud inicial
                $resultado2 = mysqli_query($connection, $consulta2);
                $datos2 = mysqli_fetch_array($resultado2);
                $nombreCompleto = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                $tabla .= '     <tr>
                                    <td>P'.$datos['idSol'].'</td>
                                    <td>'.$datos['codProy'].'</td>
                                    <td>'.$datos['nombreProy'].'</td>
                                    <td>'.$datos['ObservacionAct'].'</td>
                                    <td>'.$nombreCompleto.'</td>
                                    <td><a href="cotizador.php?cod='.$datos['idSol'].'&val='.$datos2['idSolicitante'].'&cod2='.$datos['idCotizacion'].'" class="waves-effect waves-light btn" title="Consultar"><i class="material-icons">visibility</i></a></td>
                                </tr>';
            }
            $tabla .= '     </tbody>
                        </table>';
        } else {
            $tabla = "<h5 class='red-text darken-1'>No hay cotizaciones registradas en el sistema.</h5>";
        }
        return $tabla;
        mysqli_close($connection);
    }

    public static function datosCotizacion ($idCotizacion) {
        require('../Core/connection.php');
        $consulta = "SELECT * 
            FROM pys_cotizaciones 
            WHERE pys_cotizaciones.idCotizacion = '$idCotizacion' 
            OR pys_cotizaciones.idSolEsp = '$idCotizacion'
            AND pys_cotizaciones.estado = '1';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        return $datos;
        mysqli_close($connection);
    }

    public static function reCotizacion ($idSolEsp) {
        require('../Core/connection.php');
        $consulta = "SELECT COUNT(idCotizacion) FROM pys_cotizaciones WHERE idSolEsp = '$idSolEsp';";
        $resultado = mysqli_query($connection, $consulta);
        $datos = mysqli_fetch_array($resultado);
        if ($datos[0] == 1) {
            return false;
        } else {
            return true;
        }
        mysqli_close($connection);
    }

}


?>