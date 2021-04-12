<?php

    Class Inventario {

        Public static function onLoadAdmin($persona, $proyecto, $equipo, $idSol, $descrip){
            require('../Core/connection.php');
            $string = "";
            $modal = "";
            $estado="";
            $descrip = mysqli_real_escape_string($connection, $descrip);
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct 
                FROM pys_actsolicitudes
                INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_actualizacionproy ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy
                INNER JOIN pys_servicios ON  pys_solicitudes.idSer = pys_servicios.idSer
                INNER JOIN pys_equipos ON  pys_servicios.idEqu = pys_equipos.idEqu 
                INNER JOIN pys_productos ON pys_actsolicitudes.idSol = pys_productos.idSol ";
            $where = "WHERE pys_solicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_productos.est = '1' ";
            if ($persona != null) {
                $consulta .= "INNER JOIN pys_asignados on pys_actsolicitudes.idSol = pys_asignados.idSol
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona ";
                $where .= "AND pys_asignados.est = 1 AND pys_personas.est = 1 AND pys_personas.idPersona ='$persona' ";
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
                                        <th>Nombre Producto</th>
                                        <th>Asignados</th>
                                        <th>Estado inventario</th>
                                        <th>Completar inventario</th>
                                    </tr>
                                </thead>
                                <tbody id="misSolicitudes">';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    require('../Core/connection.php');
                    $consultaE = "SELECT idEqu FROM pys_actsolicitudes INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
                    $resultadoE = mysqli_query($connection, $consultaE);
                    $datosE = mysqli_fetch_array($resultadoE);
                    $equipo = $datosE['idEqu'];
                    if ($equipo == 'EQU001'){//realizar
                        $modal = "REA";
                    }else if($equipo == 'EQU002'){//diseno
                        $modal = "DIS";
                    }else if($equipo == 'EQU003'){
                        $modal = "SOP";
                    }
                    $validar = Inventario::validarInventario($idSol);
                    if($validar != null){
                        $estado= $validar['estadoInv'];
                    } else {
                        $estado = 'Sin inventario';
                    }
                    if ($estado == "Terminado"){
                        $color = "teal";
                    } else {
                        $color = "red";
                    }
                    $consultaProd = "SELECT pys_actproductos.nombreProd FROM pys_actproductos 
                    INNER JOIN pys_productos ON pys_actproductos.idProd = pys_productos.idProd
                    INNER JOIN pys_actsolicitudes ON pys_productos.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = 1 AND pys_actproductos.est =1 AND pys_productos.est =1 AND pys_actsolicitudes.idSol = '$idSol'";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $datosP = mysqli_fetch_array($resultadoProd);
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datosP['nombreProd'].'</td>
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

        public static function onLoadUsuario($usuario){
            require('../Core/connection.php');
            $modal = "";
            $string = "";
            $color = "";
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
            WHERE pys_solicitudes.est = 1 AND pys_actualizacionproy.est = 1 AND pys_servicios.est = 1 AND pys_servicios.productoOservicio = 'SI' AND pys_equipos.est = 1 AND pys_actsolicitudes.est = 1 AND pys_actsolicitudes.idEstSol = 'ESS006' AND pys_personas.est = 1  AND pys_asignados.est = 2 AND pys_login.est = 1 AND pys_login.usrLogin ='$usuario'";
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
                    require('../Core/connection.php');
                    $consultaE = "SELECT idEqu FROM pys_actsolicitudes 
                    INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer WHERE idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
                    $resultadoE = mysqli_query($connection, $consultaE);
                    $datosE = mysqli_fetch_array($resultadoE);
                    $equipo = $datosE['idEqu'];
                    if ($equipo == 'EQU001'){//realizar
                        $modal = "REA";
                    }else if($equipo == 'EQU002'){//diseno
                        $modal = "DIS";
                    }else if($equipo == 'EQU003'){
                        $modal = "SOP";
                    }
                    $validar = Inventario::validarInventario($idSol);
                    if($validar != null){
                        $estado= $validar['estadoInv'];
                    } else {
                        $estado = 'Sin inventario';
                    }
                    if ($estado == "Terminado"){
                        $color = "teal";
                    } else {
                        $color = "red";
                    }
                    $consultaProd = "SELECT pys_actproductos.nombreProd FROM `pys_actproductos` 
                    INNER JOIN pys_productos ON pys_actproductos.idProd = pys_productos.idProd
                    INNER JOIN pys_actsolicitudes ON pys_productos.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = 1 AND pys_actproductos.est =1 AND pys_productos.est =1 AND pys_actsolicitudes.idSol = '$idSol'";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $datosP = mysqli_fetch_array($resultadoProd);
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$datosP['nombreProd'].'</td>
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
            $consulta = "SELECT  pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_roles.nombreRol, pys_fases.nombreFase, pys_asignados.est
            FROM pys_asignados
            inner join pys_solicitudes on pys_asignados.idSol = pys_solicitudes.idSol
            inner join pys_actsolicitudes on pys_actsolicitudes.idSol = pys_solicitudes.idSol
            inner join pys_cursosmodulos on pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            inner join pys_proyectos on pys_cursosmodulos.idProy = pys_proyectos.idProy
            inner join pys_actualizacionproy on pys_actualizacionproy.idProy = pys_proyectos.idProy
            inner join pys_frentes on pys_proyectos.idFrente = pys_frentes.idFrente
            inner join pys_personas on pys_asignados.idPersona = pys_personas.idPersona
            inner join pys_roles on pys_asignados.idRol = pys_roles.idRol
            inner join pys_fases on pys_asignados.idFase = pys_fases.idFase
            inner join pys_convocatoria on pys_actualizacionproy.idConvocatoria = pys_convocatoria.idConvocatoria

            where pys_asignados.est != '0' and pys_actsolicitudes.est = '1' and pys_solicitudes.est = '1' and pys_cursosmodulos.estProy = '1' and pys_cursosmodulos.estCurso = '1' and pys_actualizacionproy.est = '1' and pys_proyectos.est = '1' and pys_frentes.est = '1' and ((pys_personas.est = '1') or (pys_personas.est = '0')) and pys_convocatoria.est = '1' and pys_roles.est = '1' and pys_fases.est = '1' and pys_actsolicitudes.idSol = '$codsol'";
            $resultado = mysqli_query($connection, $consulta);
            $string = '
            <table class="left responsive-table">
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
                $est = $datos['est'];
                if ($est == 1 ){
                    $msjTool = "Tarea no terminada";
                    $color = "red";
                } else {
                    $msjTool = "Tarea terminada";
                    $color = "teal";
                }
                $string .= '
                <tr>
                <td>'.$datos['nombres'].' '.$datos['apellido1'].' '.$datos['apellido2'].'</td>
                <td>'.$datos['nombreRol'].'</td>
                <td>'.$datos['nombreFase'].'</td>
                <td><a class=" tooltipped" data-tooltip="'.$msjTool.'" ><i class="material-icons '.$color.'-text">done</i></a></td>
                </tr>';
            }    
            
            $string .= "
            </tbody>
            </table>";
            mysqli_close($connection);               
            return $string;    
        }

        public static function ingresarInventario ($id, $crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $soporteCarp, $soportePeso, $observaciones, $idPerEnt, $idPerRec, $estadoInv){
            require('../Core/connection.php');
            $validacion = 0;
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
            $consultaE = "SELECT idEqu FROM pys_actsolicitudes 
                INNER JOIN pys_servicios ON pys_actsolicitudes.idSer= pys_servicios.idSer 
                WHERE idSol='$id' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1';";
            $resultadoE = mysqli_query($connection, $consultaE);
            $datos2 = mysqli_fetch_array($resultadoE);
            $equipo = $datos2['idEqu'];
            $consultaP = "SELECT pys_actproductos.idProd FROM pys_actproductos
                INNER JOIN pys_productos ON pys_productos.idProd = pys_actproductos.idProd 
                WHERE  pys_productos.idSol = '$id' AND pys_actproductos.est = '1' AND pys_productos.est = '1';";
            $resultadoP = mysqli_query($connection, $consultaP);
            $datosP = mysqli_fetch_array($resultadoP);
            $idProd = $datosP['idProd'];
            $consultaI = "SELECT MAX(idInventario) FROM pys_inventario;";
            $resultadoI = mysqli_query($connection, $consultaI);
            $datosI = mysqli_fetch_array($resultadoI);
            $idInv = $datosI['MAX(idInventario)'];
            if ($idInv != null) {
                $idInv += 1;
            } else {
                $idInv = 1;
            }
            $consulta = "";
            $consulta2 = "";
            if ($estadoInv != "Terminado") {
                $validacion = 1;
            } else if ($idPerRec != null && $idPerEnt != null && $observaciones != null && $rutaServidor != null && $idProd!= null){
                if ($equipo == 'EQU001' && $crudoCarp != null && $crudoPeso != null && $proyectoCarp != null && $proyectoPeso != null && $finalCarp != null && $finalPeso != null && $recursoCarp != null && $recursoPeso != null && $documCarp != null && $documPeso !=null ) { // Realización
                    $validacion = 1;
                }else if($equipo == 'EQU002' && $disenoCarp!= null && $disenoPeso != null && $desarrolloCarpeta != null && $desarrolloPeso != null ) { // Diseño
                    $validacion = 1;
                }else if($equipo == 'EQU003'&& $soporteCarp != null && $soportePeso != null) { // Soporte
                    $validacion = 1;
                }
            }

            if ($equipo == 'EQU001' && $validacion == 1) { // Realización
                $consulta = "INSERT INTO pys_inventario VALUES ($idInv, '$idProd', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '', '', '', '', '', '', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
                $consulta2 = "INSERT INTO pys_actinventario VALUES (null, '$idInv', '$idProd', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '', '', '', '', '', '', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
            }else if ($equipo == 'EQU002' && $validacion == 1) { // Diseño
                $consulta = "INSERT INTO pys_inventario VALUES ($idInv, '$idProd', '$estadoInv', '', '', '', '', '', '', '', '', '', '', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '', '', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');"; 
                $consulta2 = "INSERT INTO pys_actinventario VALUES (null, $idInv, '$idProd', '$estadoInv', '', '', '', '', '', '', '', '', '', '', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '', '', '$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
            }else if ($equipo == 'EQU003' && $validacion == 1) { // Soporte
                $consulta = "INSERT INTO pys_inventario VALUES ('$idInv', '$idProd', '$estadoInv', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$soporteCarp', '$soportePeso','$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
                $consulta2 = "INSERT INTO pys_actinventario VALUES (null, $idInv', '$idProd', '$estadoInv', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$soporteCarp', '$soportePeso','$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";   
            } else if ($validacion == 0) {
                echo '<script>alert("Se presentó un error y el registro inventario no puede ser guardado con estado terminado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            }
            //$consulta. $consulta2;
            $resultado = mysqli_query($connection, $consulta);
            $resultado2 = mysqli_query($connection, $consulta2);
            if ($resultado && $resultado2){
                echo '<script>alert("Se guardó correctamente la información.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            } else {
                echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
            }
            mysqli_close($connection); 
        }

        public static function validarInventario($id){
            require('../Core/connection.php');
            $consulta ="SELECT pys_inventario.idInventario, pys_actinventario.estadoInv, pys_actinventario.crudoPeso, pys_actinventario.crudoCarpeta, pys_actinventario.proyectoPeso, pys_actinventario.proyectoCarpeta, pys_actinventario.finalesPeso, pys_actinventario.finalesCarpeta, pys_actinventario.recursosPeso, pys_actinventario.recursosCarpeta, pys_actinventario.documentosPeso, pys_actinventario.documentosCarpeta, pys_actinventario.rutaServidor, pys_actinventario.disenoCarpeta, pys_actinventario.disenoPeso, pys_actinventario.desarrolloCarpeta, pys_actinventario.desarrolloPeso, pys_actinventario.soporteCarpeta, pys_actinventario.soportePeso, pys_actinventario.observacion, pys_actinventario.idPersonaRecibe, pys_actinventario.idPersonaEntrega, pys_actinventario.estadoInv
                FROM pys_inventario 
                INNER JOIN pys_productos ON pys_productos.idProd = pys_inventario.idProd 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_productos.idSol 
                INNER JOIN pys_actinventario ON pys_actinventario.idInventario = pys_inventario.idInventario 
                WHERE pys_solicitudes.idSol = '$id' AND pys_inventario.est = '1' AND pys_productos.est = '1' AND pys_actinventario.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            mysqli_close($connection); 
            if ($resultado) {
                return $datos = mysqli_fetch_array($resultado);
            } else {
                return null;
            }
        }
            
        public static function actualizarInventario ($id, $crudoCarp, $crudoPeso, $proyectoCarp, $proyectoPeso, $finalCarp, $finalPeso, $recursoCarp, $recursoPeso, $documCarp, $documPeso, $rutaServidor, $disenoCarp, $disenoPeso, $desarrolloCarpeta, $desarrolloPeso, $soporteCarp, $soportePeso, $observaciones, $idPerEnt, $idPerRec, $estadoInv) {
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
            $consultaI ="SELECT pys_inventario.idInventario,  pys_actinventario.idProd
                FROM pys_inventario 
                INNER JOIN pys_productos ON pys_productos.idProd = pys_inventario.idProd 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_productos.idSol 
                INNER JOIN pys_actinventario ON pys_actinventario.idInventario = pys_inventario.idInventario 
                WHERE pys_solicitudes.idSol='$id' AND pys_inventario.est = '1' AND pys_productos.est = '1' AND pys_actinventario.est = '1';";
            $resultadoI = mysqli_query($connection, $consultaI);
            $datosI = mysqli_fetch_array($resultadoI);
            $idInv =  $datosI['idInventario'];
            $idProd = $datosI['idProd'];
            $validacion = 0;
            $consultaE = "SELECT idEqu FROM pys_actsolicitudes 
                INNER JOIN pys_servicios ON pys_actsolicitudes.idSer= pys_servicios.idSer 
                WHERE idSol = '$id' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1';";
            $resultadoE = mysqli_query($connection, $consultaE);
            $datos2 = mysqli_fetch_array($resultadoE);
            $equipo = $datos2['idEqu'];
            if ($estadoInv != "Terminado") {
                $validacion = 1;
            } else if ($idPerRec != null && $idPerEnt != null && $observaciones != null && $rutaServidor != null && $idProd!= null) {
                if ($equipo == 'EQU001' && $crudoCarp != null && $crudoPeso != null && $proyectoCarp != null && $proyectoPeso != null && $finalCarp != null && $finalPeso != null && $recursoCarp != null && $recursoPeso != null && $documCarp != null && $documPeso != null ) { // Realización
                    $validacion = 1;
                } else if ($equipo == 'EQU002' && $disenoCarp != null && $disenoPeso != null && $desarrolloCarpeta != null && $desarrolloPeso != null ) { // Diseño
                    $validacion = 1;
                } else if($equipo == 'EQU003'&& $soporteCarp != null && $soportePeso != null) { //Soporte
                    $validacion = 1;
                }
            } else {
                $validacion = 0;
            }
            if ($validacion == 1) {
                $consulta = "UPDATE pys_actinventario SET est = '2' WHERE idInventario = $idInv AND est = '1';";
                $consulta1 = "INSERT INTO pys_actinventario VALUES (null, $idInv, '$idProd', '$estadoInv', '$crudoCarp', '$crudoPeso', '$proyectoCarp', '$proyectoPeso', '$finalCarp', '$finalPeso', '$recursoCarp', '$recursoPeso', '$documCarp', '$documPeso', '$disenoCarp', '$disenoPeso', '$desarrolloCarpeta', '$desarrolloPeso', '$soporteCarp', '$soportePeso','$idPerRec', '$idPerEnt', '$observaciones', '$rutaServidor', now(), '1');";
                $resultado = mysqli_query($connection, $consulta);
                $resultado1 = mysqli_query($connection, $consulta1);
                if ($resultado && $resultado1){
                    echo '<script>alert("Se actualizó correctamente la información.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
                } else {
                    echo '<script>alert("Ha ocurrido un error y el registro no pudo ser guardado.")</script>';
                    echo '<meta http-equiv="Refresh" content="0;url= '.$_SERVER["HTTP_REFERER"].'">';
                }
            } else {
                echo '<script>alert("Se presentó un error y el registro inventario no puede ser guardado con estado terminado. Verifique que todos los campos esten diligenciados")</script>';
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

        public static function selectPersona($cod,$busqueda) {
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $string = '';
            $consulta = "SELECT idPersona, apellido1, apellido2, nombres
                FROM pys_personas
                WHERE pys_personas.est = '1' ";
            if ($cod == 1) {
                $consulta .= "AND (pys_personas.apellido1 LIKE '%$busqueda%' OR pys_personas.apellido2 LIKE '%$busqueda%' OR pys_personas.nombres LIKE '%$busqueda%')";
                $string .=' <select name="sltPersona" id="sltPersona">';
            } 
            #cod =3 filtar solo los cargos que tienen personas del equipo de produccion y soporte P&S
            #Utilizado para select en el modal inventario
            else if ($cod == 3) {
                $consulta .=  " AND (idCargo = 'CAR019' OR idCargo = 'CAR017' OR idCargo = 'CAR012' OR idCargo = 'CAR014' OR idCargo = 'CAR015' OR idCargo = 'CAR016' OR idCargo = 'CAR035' OR idCargo = 'CAR036' OR idCargo = 'CAR037' OR idCargo = 'CAR038' OR idCargo = 'CAR039' OR idCargo = 'CAR041' OR idCargo = 'CAR045');";
            }

            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 ) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    if( $busqueda == $datos['idPersona'] && ($cod == 2 || $cod == 3) ){
                        $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                        $string .= '  <option value="'.$datos['idPersona'].'" selected>'.$nombreCompleto.'</option>';
                    } else {
                        $nombreCompleto = $datos['apellido1']." ".$datos['apellido2']." ".$datos['nombres'];
                        $string .= '  <option value="'.$datos['idPersona'].'">'.$nombreCompleto.'</option>';
                    }
                }
            } else {
                $string .=  '  
                            <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                        ';
            }
            mysqli_close($connection);
            if($cod == 1)  {
                $string .='</select>';
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