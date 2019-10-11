<?php

    Class Inventario {

        Public static function onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip){
            require('../Core/connection.php');
            $string = "";
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct 
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu 
            INNER JOIN pys_productos ON pys_actsolicitudes.idSol = pys_productos.idSol ";
            $where = "WHERE pys_solicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_servicios.est = 1 AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = 1 AND pys_actsolicitudes.est = 1 AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_productos.est = 1 ";
            if ($persona != null){
                $consulta .= "INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona ";
                $where .= "AND pys_asignados.est = 1 AND pys_personas.est = 1 AND pys_personas.idPersona ='$persona' ";
            } if ($proyecto != null){
                $where .= "AND pys_actualizacionproy.idProy ='$proyecto' ";
            } if ($equipo != null){
                $where .= "AND pys_equipos.idEqu ='$equipo' ";
            } if ($idSol != null){
                $where .= "AND pys_actsolicitudes.idSol ='$idSol' ";
            } if ($descrip != null){
                $where .= "AND pys_actsolicitudes.ObservacionAct  LIKE '%$descrip%' ";
            }
            $consulta .= $where;
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left">
                                <thead>
                                    <tr>
                                        <th>Código solicitud</th>
                                        <th>Producto</th>
                                        <th>Cód. proyecto en Conecta-TE</th>
                                        <th>Proyecto</th>
                                        <th>Equipo -- Servicio</th>
                                        <th>Descripción Producto</th>
                                        <th>Asignados</th>
                                        <th>Estado inventario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Agregar Información" onclick="envioData(\''.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">group</i></a></td>
                                        <td>----</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Agregar Información" onclick="envioData(\''.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">description</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            }
            echo $string;
            mysqli_close($connection);
        }

        public static function onLoadUsuario($usuario){
            require('../Core/connection.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct 
            FROM pys_actsolicitudes
            INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy
            INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol
            INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona
            WHERE pys_solicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_servicios.est = 1 AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = 1 AND pys_actsolicitudes.est = 1 AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_asignados.est = 1 AND pys_personas.est = 1 AND pys_login.est = 1 AND pys_login.usrLogin ='$usuario'";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left">
                                <thead>
                                    <tr>
                                        <th>Código solicitud</th>
                                        <th>Producto/Servicio</th>
                                        <th>Cód. proyecto en Conecta-TE</th>
                                        <th>Proyecto</th>
                                        <th>Equipo -- Servicio</th>
                                        <th>Descripción Producto/Servicio</th>
                                        <th>Asignados</th>
                                        <th>Estado inventario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Agregar Información" onclick="envioData(\''.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">group</i></a></td>
                                        <td>----</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="---" onclick="envioData(\''.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">description</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            }
            echo $string;
            mysqli_close($connection);
        }

        public static function selectPersona($busqueda) {
            require('../Core/connection.php');
            $string ="";
            $consulta = "SELECT idPersona, apellido1, apellido2, nombres
                FROM pys_personas
                WHERE pys_personas.est = '1' AND (pys_personas.apellido1 LIKE '%$busqueda%' OR pys_personas.apellido2 LIKE '%$busqueda%' OR pys_personas.nombres LIKE '%$busqueda%');";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                $string .='  <select name="sltPersona" id="sltPersona">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                    $string .= '  <option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                }
                
            } else {
                $string .=  '  <select name="sltPersona" id="sltPersona" >
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        ';
            }
            echo $string;
            mysqli_close($connection);
        }

        public static function selectEquipo($busqueda) {
            require('../Core/connection.php');
            $string ="";
            $consulta = "SELECT idEqu, nombreEqu
                FROM pys_equipos
                WHERE est = '1' AND nombreEqu LIKE '%$busqueda%';";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                $string .='  <select name="sltEquipo" id="sltEquipo">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreEquipo = $datos['nombreEqu'];
                    $string .= '  <option value="'.$datos['idEqu'].'">'.$nombreEquipo.'</option>';
                }
                
            } else {
                $string .=  '  <select name="sltEquipo" id="sltEquipo" >
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        ';
            }
            echo $string;
            mysqli_close($connection);
        }
        public static function selectProyecto($busqueda) {
            require('../Core/connection.php');
            $string = "";
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.idFrente, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_actualizacionproy.descripcionProy
                FROM pys_actualizacionproy
                WHERE pys_actualizacionproy.est = '1' AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                $string .='  <select name="sltProyecto" id="sltProyecto" >';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                    $string .= '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
                }
            } else {
                $string .= '  <select name="sltProyecto" id="sltProyecto" >
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        ';
            }
            echo $string;
            mysqli_close($connection);
        }
        
        public static function selectProducto($busqueda) {
            require('../Core/connection.php');
            $string ="";
            echo $consulta = "SELECT idSol FROM pys_actproductos
                INNER JOIN pys_productos ON pys_actproductos.idProd = pys_productos.idProd
                WHERE pys_actproductos.est = '1' AND pys_productos.est = '1' AND idSol LIKE '%$busqueda%';";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                $string .='  <select name="sltProducto" id="sltProducto">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idsol = $datos['idSol'];
                    $string .= '  <option value="'.$datos['idSol'].'">P'.$idsol.'</option>';
                }
                
            } else {
                $string .=  '  <select name="sltProducto" id="sltProducto" >
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        ';
            }
            echo $string;
            mysqli_close($connection);
        }
    }



?>