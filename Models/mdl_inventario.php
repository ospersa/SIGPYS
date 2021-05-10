<?php

    Class Inventario {

        Public static function onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip, $estado) {
            require('../Core/connection.php');
            $string = "";
            $modal = "";
            $descrip = mysqli_real_escape_string($connection, $descrip);
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_servicios.idEqu, pys_actproductos.nombreProd, pys_actinventario.estadoInv
                FROM pys_actsolicitudes
                INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy
                INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer
                INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu 
                LEFT JOIN pys_productos ON pys_actsolicitudes.idSol = pys_productos.idSol
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                LEFT JOIN pys_inventario ON pys_inventario.idProd = pys_actproductos.idProd AND pys_inventario.est = '1'
                LEFT JOIN pys_actinventario ON pys_actinventario.idInventario = pys_inventario.idInventario AND pys_actinventario.est = '1' ";
            $where = "WHERE pys_solicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_productos.est = '1' ";
            if ($persona != null) {
                $consulta .= "INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona ";
                $where .= "AND pys_asignados.est = 1 AND pys_personas.est = 1 AND pys_personas.idPersona ='$persona' ";
            } 
            if ($estado == "Terminado" || $estado == "Proceso de inventario") {
                $where .= "AND pys_actinventario.estadoInv = '$estado' ";
            } else if ($estado == "Sin inventario") {
                $where .= "AND (pys_actinventario.estadoInv = '' OR pys_actinventario.estadoInv IS NULL OR pys_actinventario.estadoInv = '$estado') ";
            } else if ($estado == "") {
                $where .= "AND (pys_actinventario.estadoInv != 'Terminado' OR pys_actinventario.estadoInv IS NULL) ";
            }
            if ($proyecto != null) {
                $where .= "AND pys_actualizacionproy.idProy ='$proyecto' ";
            } 
            if ($equipo != null) {
                $where .= "AND pys_equipos.idEqu ='$equipo' ";
            } 
            if ($idSol != null) {
                $where .= "AND pys_actsolicitudes.idSol ='$idSol' ";
            } 
            if ($descrip != null) {
                $where .= "AND pys_actsolicitudes.ObservacionAct LIKE '%$descrip%' ";
            }
            $order = "ORDER BY pys_actsolicitudes.fechAct DESC ";
            $consulta .= $where . $order . ';';
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
                                        <th>Nombre Producto</th>
                                        <th>Asignados</th>
                                        <th>Estado inventario</th>
                                        <th>Completar inventario</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $modal = strtoupper ( substr( $datos['nombreEqu'], 0, 3) );
                    $color = ($datos['estadoInv'] == "Terminado") ? "teal" : "red";
                    $estadoInv = ($datos['estadoInv'] != null) ? $datos['estadoInv'] : "Sin inventario" ;
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datos['nombreProd'].'</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Personas Asignadas" onclick="envioData(\'ASI'.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">group</i></a></td>
                                        <td>'.$estadoInv.'</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Entrega de inventario" onclick="envioData(\''.$modal.$idSol.'\',\'modalInventario.php\');"><i class="material-icons '.$color.'-text">description</i></a></td>
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
            $modal = "";
            $string = "";
            $color = "";
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_servicios.idEqu, pys_actinventario.estadoInv, pys_equipos.nombreEqu, pys_actproductos.nombreProd
                FROM pys_actsolicitudes
                INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol AND pys_solicitudes.est = '1'
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy AND pys_actualizacionproy.est = '1' 
                INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer AND (pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'SI')
                INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu AND pys_equipos.est = '1'
                INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol AND (pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idEstSol = 'ESS006')
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona AND pys_personas.est = '1'
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona AND (pys_login.est = '1' AND pys_login.usrLogin = '$usuario')
                LEFT JOIN pys_productos ON pys_actsolicitudes.idSol = pys_productos.idSol
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                LEFT JOIN pys_inventario ON pys_inventario.idProd = pys_actproductos.idProd AND pys_inventario.est = '1'
                LEFT JOIN pys_actinventario ON pys_actinventario.idInventario = pys_inventario.idInventario AND pys_actinventario.est = '1'
                WHERE pys_asignados.est != '0'
                ORDER BY pys_actsolicitudes.fechAct DESC;";
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
                                        <th>Nombre Producto</th>
                                        <th>Asignados</th>
                                        <th>Estado inventario</th>
                                        <th>Completar inventario</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $equipo = $datos['idEqu'];
                    $modal = strtoupper ( substr( $datos['nombreEqu'], 0, 3) );
                    $estado = ($datos['estadoInv'] != null) ? $datos['estadoInv'] : "Sin inventario" ;
                    $color = ($datos['estadoInv'] == "Terminado") ? "teal" : "red";
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datos['nombreProd'].'</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Personas Asignadas" onclick="envioData(\'ASI'.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">group</i></a></td>
                                        <td>'.$estado.'</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Entrega de inventario" onclick="envioData(\''.$modal.$idSol.'\',\'modalInventario.php\');"><i class="material-icons '.$color.'-text">description</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            }
            echo $string;
            mysqli_close($connection);
        }

        public static function OnLoadAsignados($codsol){
            require('../Core/connection.php');
            $horasTotal1 = 0;
            $minTotal1 = 0;
            $horasTotal = 0;
            $minTotal = 0;
            $consulta = "SELECT pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.est
                FROM pys_asignados
                INNER JOIN pys_solicitudes ON pys_asignados.idSol = pys_solicitudes.idSol
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy
                INNER JOIN pys_frentes ON pys_proyectos.idFrente = pys_frentes.idFrente
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                INNER JOIN pys_roles ON pys_asignados.idRol = pys_roles.idRol
                INNER JOIN pys_fases ON pys_asignados.idFase = pys_fases.idFase
                INNER JOIN pys_convocatoria ON pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria
                WHERE pys_asignados.est != '0' AND pys_actsolicitudes.est = '1' AND pys_solicitudes.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_actualizacionproy.est = '1' AND pys_proyectos.est = '1' AND pys_frentes.est = '1' AND ((pys_personas.est = '1') OR (pys_personas.est = '0')) AND pys_convocatoria.est = '1' AND pys_roles.est = '1' AND pys_fases.est = '1' AND pys_actsolicitudes.idSol = '$codsol';";
            $resultado = mysqli_query($connection, $consulta);
            $string = ' <table class="left responsive-table">
                            <thead>
                                <tr>
                                    <th>Responsable</th>
                                    <th>Rol</th>
                                    <th>Fase</th>
                                    <th>Estado de tarea</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                $color = ($datos['est'] == 1) ? "red" : "teal";
                $msjTool = ($datos['est'] == 1) ? "Tarea no terminada" : "Tarea terminada";
                $string .= '    <tr>
                                    <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                                    <td>'.$datos['nombreRol'].'</td>
                                    <td>'.$datos['nombreFase'].'</td>
                                    <td><a class=" tooltipped" data-tooltip="'.$msjTool.'" ><i class="material-icons '.$color.'-text">done</i></a></td>
                                </tr>';
            }    
            
            $string .= "    </tbody>
                        </table>";
            mysqli_close($connection);               
            return $string;    
        }

        public static function ingresarInventario ($id, $crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $soporteCarp, $soportePeso, $observaciones, $idPerEnt, $idPerRec, $estadoInv){
            require('../Core/connection.php');
            $validacion = 0;
            $crudoCarp          = mysqli_real_escape_string($connection, $crudoCarp);
            $crudoPeso          = mysqli_real_escape_string($connection, $crudoPeso);
            $proyectoCarp       = mysqli_real_escape_string($connection, $proyectoCarp);
            $proyectoPeso       = mysqli_real_escape_string($connection, $proyectoPeso);
            $finalCarp          = mysqli_real_escape_string($connection, $finalCarp);
            $finalPeso          = mysqli_real_escape_string($connection, $finalPeso);
            $recursoCarp        = mysqli_real_escape_string($connection, $recursoCarp);
            $recursoPeso        = mysqli_real_escape_string($connection, $recursoPeso);
            $documCarp          = mysqli_real_escape_string($connection, $documCarp);
            $documPeso          = mysqli_real_escape_string($connection, $documPeso);
            $rutaServidor       = mysqli_real_escape_string($connection, $rutaServidor);
            $disenoCarp         = mysqli_real_escape_string($connection, $disenoCarp);
            $disenoPeso         = mysqli_real_escape_string($connection, $disenoPeso);
            $desarrolloCarpeta  = mysqli_real_escape_string($connection, $desarrolloCarpeta);
            $desarrolloPeso     = mysqli_real_escape_string($connection, $desarrolloPeso);
            $soporteCarp        = mysqli_real_escape_string($connection, $soporteCarp);
            $soportePeso        = mysqli_real_escape_string($connection, $soportePeso);
            $observaciones      = mysqli_real_escape_string($connection, $observaciones);
            $campos = [$crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $soporteCarp, $soportePeso, $observaciones];
            $realizacion = [$crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $idPerRec, $idPerEnt];
            $diseno = [$disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $rutaServidor, $idPerRec, $idPerEnt];
            $soporte = [$soporteCarp, $soportePeso, $rutaServidor, $idPerRec, $idPerEnt];
            $camposTotal = count($campos);
            $camposVacios = 0;
            foreach ($campos as $key) {
                if ($key == null) {
                    $camposVacios++;
                }
            }
            if ($camposVacios == $camposTotal) {
                echo '<script>alert("No ha ingresado información de inventario.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            } else {
                $consulta = "SELECT pys_servicios.idEqu, pys_actproductos.idProd 
                    FROM pys_actsolicitudes 
                    INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer 
                    INNER JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol
                    INNER JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                    WHERE pys_actsolicitudes.idSol = '$id' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1';";
                $resultado = mysqli_query($connection, $consulta);
                $datos = mysqli_fetch_array($resultado);
                $equipo = $datos['idEqu'];
                $idProd = $datos['idProd'];
                $consultaI = "SELECT MAX(idInventario) AS 'max' FROM pys_inventario;";
                $resultadoI = mysqli_query($connection, $consultaI);
                $datosI = mysqli_fetch_array($resultadoI);
                $idInv = ($datosI['max'] != null) ? $datosI['max'] += 1 : 1;
                $consulta = "";
                $consulta2 = "";
                $infoPendiente = 0;
                if ($equipo == 'EQU001') {
                    foreach ($realizacion as $key) {
                        if ($key == null) {
                            $infoPendiente++;
                        }
                    }
                } else if ($equipo == 'EQU002') {
                    foreach ($diseno as $key) {
                        if ($key == null) {
                            $infoPendiente++;
                        }
                    }
                } else if ($equipo == 'EQU003') {
                    foreach ($soporte as $key) {
                        if ($key == null) {
                            $infoPendiente++;
                        }
                    }
                }
                $estadoInv = ($infoPendiente > 0) ? 'Proceso de inventario' : 'Terminado';
                mysqli_query($connection, "BEGIN;");
                $consulta = "INSERT INTO pys_inventario VALUES ($idInv, '$idProd', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '$soporteCarp', '$soportePeso', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
                $consulta2 = "INSERT INTO pys_actinventario VALUES (null, '$idInv', '$idProd', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '$soporteCarp', '$soportePeso', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
                $resultado = mysqli_query($connection, $consulta);
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado && $resultado2){
                    mysqli_query($connection, "COMMIT;");
                    echo '<script>alert("Se guardó correctamente la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
                } else {
                    mysqli_query($connection, "ROLLBACK;");
                    echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
                } 
            }
            mysqli_close($connection); 
        }

        public static function validarInventario($id){
            require('../Core/connection.php');
            $consulta ="SELECT pys_inventario.idInventario, pys_actinventario.estadoInv, pys_actinventario.crudoPeso, pys_actinventario.crudoCarpeta, pys_actinventario.proyectoPeso, pys_actinventario.proyectoCarpeta, pys_actinventario.finalesPeso, pys_actinventario.finalesCarpeta, pys_actinventario.recursosPeso, pys_actinventario.recursosCarpeta, pys_actinventario.documentosPeso, pys_actinventario.documentosCarpeta, pys_actinventario.rutaServidor, pys_actinventario.disenoCarpeta, pys_actinventario.disenoPeso, pys_actinventario.desarrolloCarpeta, pys_actinventario.desarrolloPeso, pys_actinventario.soporteCarpeta, pys_actinventario.soportePeso, pys_actinventario.observacion, pys_actinventario.idPersonaRecibe, pys_actinventario.idPersonaEntrega, pys_productos.idProd, pys_inventario.idInventario, pys_servicios.idEqu
                FROM pys_inventario 
                INNER JOIN pys_productos ON pys_productos.idProd = pys_inventario.idProd 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_productos.idSol AND pys_solicitudes.est = '1'
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol AND pys_actsolicitudes.est = '1'
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                INNER JOIN pys_actinventario ON pys_actinventario.idInventario = pys_inventario.idInventario 
                WHERE pys_actsolicitudes.idSol = '$id' AND pys_inventario.est = '1' AND pys_productos.est = '1' AND pys_actinventario.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            mysqli_close($connection); 
            if ($resultado) {
                return $datos;
            } else {
                return null;
            }
        }
            
        public static function actualizarInventario ($id, $crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $soporteCarp, $soportePeso, $observaciones, $idPerEnt, $idPerRec, $estadoInv, $idProducto, $idInventario, $idEquipo) {
            require('../Core/connection.php');
            $crudoCarp = mysqli_real_escape_string($connection, $crudoCarp);
            $crudoPeso = mysqli_real_escape_string($connection, $crudoPeso);
            $proyectoCarp = mysqli_real_escape_string($connection, $proyectoCarp);
            $proyectoPeso = mysqli_real_escape_string($connection, $proyectoPeso);
            $finalCarp = mysqli_real_escape_string($connection, $finalCarp);
            $finalPeso = mysqli_real_escape_string($connection, $finalPeso);
            $recursoCarp = mysqli_real_escape_string($connection, $recursoCarp);
            $recursoPeso = mysqli_real_escape_string($connection, $recursoPeso);
            $documCarp = mysqli_real_escape_string($connection, $documCarp);
            $documPeso = mysqli_real_escape_string($connection, $documPeso);
            $rutaServidor = mysqli_real_escape_string($connection, $rutaServidor);
            $disenoCarp = mysqli_real_escape_string($connection, $disenoCarp);
            $disenoPeso = mysqli_real_escape_string($connection, $disenoPeso);
            $desarrolloCarpeta = mysqli_real_escape_string($connection, $desarrolloCarpeta);
            $desarrolloPeso = mysqli_real_escape_string($connection, $desarrolloPeso);
            $soporteCarp = mysqli_real_escape_string($connection, $soporteCarp);
            $soportePeso = mysqli_real_escape_string($connection, $soportePeso);
            $observaciones = mysqli_real_escape_string($connection, $observaciones);
            $realizacion = [$crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $idPerRec, $idPerEnt];
            $diseno = [$disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $rutaServidor, $idPerRec, $idPerEnt];
            $soporte = [$soporteCarp, $soportePeso, $rutaServidor, $idPerRec, $idPerEnt];
            $infoPendiente = 0;
            if ($idEquipo == 'EQU001') {
                foreach ($realizacion as $key) {
                    if ($key == null) {
                        $infoPendiente++;
                    }
                }
            } else if ($idEquipo == 'EQU002') {
                foreach ($diseno as $key) {
                    if ($key == null) {
                        $infoPendiente++;
                    }
                }
            } else if ($idEquipo == 'EQU003') {
                foreach ($soporte as $key) {
                    if ($key == null) {
                        $infoPendiente++;
                    }
                }
            }
            $estadoInv = ($infoPendiente > 0) ? 'Proceso de inventario' : 'Terminado';
            mysqli_query($connection, "BEGIN;");
            $consulta = "UPDATE pys_actinventario SET est = '2' WHERE idInventario = '$idInventario' AND est = '1';";
            $consulta1 = "INSERT INTO pys_actinventario VALUES (null, '$idInventario', '$idProducto', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '$soporteCarp', '$soportePeso','$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
            $resultado = mysqli_query($connection, $consulta);
            $resultado1 = mysqli_query($connection, $consulta1);
            if ($resultado && $resultado1){
                mysqli_query($connection, "COMMIT;");
                echo '<script>alert("Se actualizó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            } else {
                mysqli_query($connection, "ROLLBACK");
                echo '<script>alert("Ha ocurrido un error y el registro no pudo ser actualizado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            }
            mysqli_close($connection);
        }
        
        public static function tablaActualizaciones($idSol){
            require('../Core/connection.php');
            $string = "";
            $consultaE = "SELECT idEqu 
                FROM pys_actsolicitudes 
                INNER JOIN pys_servicios ON pys_actsolicitudes.idSer= pys_servicios.idSer 
                WHERE idSol = '$idSol' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1';";
            $resultadoE = mysqli_query($connection, $consultaE);
            $datos2 = mysqli_fetch_array($resultadoE);
            $equipo = $datos2['idEqu'];
            $consulta = "SELECT estadoInv, crudoCarpeta, crudoPeso, proyectoCarpeta, proyectoPeso, finalesCarpeta, finalesPeso, recursosCarpeta, recursosPeso, documentosCarpeta, documentosPeso, disenoCarpeta, disenoPeso, desarrolloCarpeta, desarrolloPeso, soporteCarpeta, soportePeso, idPersonaRecibe, idPersonaEntrega, observacion, rutaServidor, fechaActInventario 
                FROM pys_actinventario
                INNER JOIN pys_productos ON pys_productos.idProd = pys_actinventario.idProd
                WHERE pys_actinventario.est = '2' AND pys_productos.idSol = '$idSol';";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            $string = ' <table class="responsive-table  left">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Persona que Entrega</th>
                                    <th>Persona que Recibe</th>
                                    <th>Observaciones</th>
                                    <th>Url</th>
                                    <th>Fecha Actualización</th>';
            if ($registros > 0) {
                if ($equipo == 'EQU001') { // Realización
                    
                } else if ($equipo == 'EQU002'){ // Diseño
                    $string .= '    <th>Diseno Carpeta</th>
                                    <th>Diseno Peso</th>
                                    <th>Desarrollo Carpeta</th>
                                    <th>Desarrollo Peso</th>';
                } else if($equipo == 'EQU003'){ // Soporte   

                }
                                '   <th>Descripción Producto</th>
                                    <th>Asignados</th>
                                    <th>Estado inventario</th>
                                </tr>
                            </thead>
                            <tbody id="">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $string .=' <tr>';
                    $rutSer      = $datos['rutaServidor'];
                    $obs         = $datos['observacion'];
                    $idPerEnt    = $datos['idPersonaEntrega']; 
                    $idPerRec    = $datos['idPersonaRecibe'];
                    $estadoInv   = $datos['estadoInv'];
                    $fechaACt    = $datos['fechaActInventario'];
                    $personaEnt = Inventario::nombrePersona($idPerEnt,'ID');
                    $personaRec = Inventario::nombrePersona($idPerRec,'ID');
                    $string .='     <td>'.$estadoInv.'</td>  
                                    <td>'.$personaEnt['apellido1']." ".$personaEnt['apellido2']." ".$personaEnt['nombres'].'</td>  
                                    <td>'.$personaRec['apellido1']." ".$personaRec['apellido2']." ".$personaRec['nombres'].'</td>  
                                    <td>'.$obs.'</td>
                                    <td>'.$rutSer.'</td>  
                                    <td>'.$fechaACt.'</td>';
                    if ($equipo == 'EQU001') { // Realización
                        $crudosCarp  = $datos['crudoCarpeta'];
                        $crudosPes   = $datos['crudoPeso'];
                        $proyCarp    = $datos['proyectoCarpeta'];
                        $proyPeso    = $datos['proyectoPeso'];
                        $finCarp     = $datos['finalesCarpeta']; 
                        $finPeso     = $datos['finalesPeso'];
                        $recCarp     = $datos['recursosCarpeta'];
                        $recPeso     = $datos['recursosPeso'];
                        $docCarp     = $datos['documentosCarpeta'];
                        $docPeso     = $datos['documentosPeso'];
                        $string .= '<td>'.$crudosCarp.'</td>  
                                    <td>'.$crudosPes.'</td>  
                                    <td>'.$proyCarp.'</td>  
                                    <td>'.$proyPeso.'</td>  
                                    <td>'.$finCarp.'</td>  
                                    <td>'.$finPeso.'</td>  
                                    <td>'.$recCarp.'</td>  
                                    <td>'.$recPeso.'</td>  
                                    <td>'.$docCarp.'</td>  
                                    <td>'.$docPeso.'</td> '; 
                    } else if ($equipo == 'EQU002') { // Diseño
                        $disCarp    = $datos['disenoCarpeta'];
                        $disPeso    = $datos['disenoPeso'];
                        $desCarp    = $datos['desarrolloCarpeta'];
                        $desPeso    = $datos['desarrolloPeso'];
                        $string .= '<td>'.$disCarp.'</td>  
                                    <td>'.$disPeso.'</td>
                                    <td>'.$desCarp.'</td>
                                    <td>'.$desPeso.'</td> ';
                    } else if ($equipo == 'EQU003') { // Soporte
                        $sopCarp     = $datos['soporteCarpeta'];
                        $sopPeso     = $datos['soportePeso'];
                        $string .= '<th>'.$sopCarp.'</td>  
                                    <th>'.$sopPeso.'</td>  ';
                    }
                    $string .= '</tr>';
                }
                $string .= '</tbody>
                        </table>';
            }else{
                $string = "<h6> No hay Actualizaciones registradas</h6>";
            }   
            mysqli_close($connection); 
            return $string;
        }


        public static function nombrePersona($user,$tipo){
            require('../Core/connection.php');
            $string = '';
            $consulta = "SELECT pys_personas.idPersona, apellido1, apellido2, nombres
                FROM pys_personas
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona
                WHERE pys_personas.est = '1' AND pys_login.est = '1' ";
            if ($tipo == "US") {
                $where = "AND pys_login.usrLogin = '$user';";
            } else if ($tipo == "ID"){
                $where = "AND pys_personas.idPersona = '$user';";
            }
            $consulta .= $where;
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            mysqli_close($connection);
            return $datos;
        }

        public static function selectPersona($cod, $busqueda) {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $string = '';
            if ($cod == 1) {
                $consulta = "SELECT idPersona, apellido1, apellido2, nombres
                    FROM pys_personas
                    WHERE pys_personas.est = '1'
                    AND (pys_personas.apellido1 LIKE '%$busqueda%' OR pys_personas.apellido2 LIKE '%$busqueda%' OR pys_personas.nombres LIKE '%$busqueda%')";
                $string .='         <select name="sltPersona" id="sltPersona">';
            }
            /** Si $cod contiene una letra se realizará búsqueda de las personas asignadas al proyecto */
            else if ( is_string ( $cod ) ) {
                $consulta = "SELECT pys_asignados.idPersona, apellido1, apellido2, nombres
                    FROM pys_asignados
                    INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                    WHERE pys_asignados.idProy = '$cod' AND pys_asignados.est != '0'
                    GROUP BY pys_personas.idPersona
                    ORDER BY pys_personas.apellido1 ASC;";
            }
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 ) {
                $string .= '            <option value="" disabled selected>Seleccione</option>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreCompleto = strtoupper ( $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'] );
                    if( $busqueda == $datos['idPersona'] ){
                        $string .= '    <option value="'.$datos['idPersona'].'" selected>'.$nombreCompleto.'</option>';
                    } else {
                        $string .= '    <option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                    }
                }
            } else {
                $string .=  '           <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>';
            }
            mysqli_close($connection);
            if($cod == 1)  {
                $string .= '        </select>
                                    <label for="sltPersona">Seleccione una persona</label>';
                echo $string ;
            } else{
                return $string;

            }
        }

        public static function selectEquipo($busqueda) {
            require('../Core/connection.php');
            $string ="";
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT idEqu, nombreEqu
                FROM pys_equipos
                WHERE est = '1' AND (idEqu='EQU001' OR idEqu='EQU002' OR idEqu='EQU003' OR idEqu='EQU004') AND nombreEqu LIKE '%$busqueda%';";
            $resultado = mysqli_query($connection, $consulta);
            $string .= '        <select name="sltEquipo" id="sltEquipo">';
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $nombreEquipo = $datos['nombreEqu'];
                    $string .= '    <option value="'.$datos['idEqu'].'">'.$nombreEquipo.'</option>';
                }
            } else {
                $string .=  '       <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>';
            }
            $string .= '        </select>
                                <label for="sltEquipo">Seleccione un equipo</label>';
            echo $string;
            mysqli_close($connection);
        }
        public static function selectProyecto($busqueda) {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT pys_actualizacionproy.idProy, pys_actualizacionproy.idFrente, pys_actualizacionproy.nombreProy, pys_actualizacionproy.idConvocatoria, pys_actualizacionproy.codProy, pys_actualizacionproy.descripcionProy
                FROM pys_actualizacionproy
                WHERE pys_actualizacionproy.est = '1' AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
            $resultado = mysqli_query($connection, $consulta);
            $string ='          <select name="sltProyecto" id="sltProyecto" >';
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                    $string .= '    <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
                }
            } else {
                $string .= '        <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>';
            }
            $string .= '        </select>
                                <label for="sltProyecto">Seleccione un proyecto</label>';
            echo $string;
            mysqli_close($connection);
        }
        
        public static function selectProducto($busqueda) {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT idSol FROM pys_actproductos
                INNER JOIN pys_productos ON pys_actproductos.idProd = pys_productos.idProd
                WHERE pys_actproductos.est = '1' AND pys_productos.est = '1' AND idSol LIKE '%$busqueda%';";
            $resultado = mysqli_query($connection, $consulta);
            $string ='          <select name="sltProducto" id="sltProducto">';
            if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idsol = $datos['idSol'];
                    $string .= '    <option value="'.$datos['idSol'].'">P'.$idsol.'</option>';
                }
                
            } else {
                $string .=  '       <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>';
            }
            $string .= '        </select>
                                <label for="sltProducto">Seleccione un producto</label>';
            echo $string;
            mysqli_close($connection);
        }

        public static function selectEstadoInv($id) {
            $string = '     <select name="sltEstadoInv">
                                <option value="" disabled selected>Seleccione</option>';
            $estados = ['Sin inventario', 'Proceso de inventario', 'Terminado'];
            foreach ($estados as $estado) {
                if ($estado == $id) {
                    $string .= '<option value="' . $estado . '" selected>' . $estado . '</option>';
                } else {
                    $string .= '<option value="' . $estado . '">' . $estado . '</option>';
                }
            }
            $string .= '    </select>
                            <label for="sltEstadoInv">Estado del inventario*</label>';
            return $string;
        }
    }
?>