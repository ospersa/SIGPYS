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
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Personas Asignadas" onclick="envioData(\'ASI'.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">group</i></a></td>
                                        <td>----</td>
                                        <td><a href="#modalInventario" class="modal-trigger tooltipped" data-position="right" data-tooltip="Entrega de inventario" onclick="envioData(\'INF'.$idSol.'\',\'modalInventario.php\');"><i class="material-icons teal-text">description</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            }
            echo $string;
            mysqli_close($connection);
        }

        public static function formularioInventario($id){
            require('../Core/connection.php');
            $string ="";
            $consulta = "SELECT * FROM pys_solicitudes";
            return $string;
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

        public static function selectEstadoInv($id) {
            require('../Core/connection.php');
            $consulta = "SELECT estadoInv FROM pys_inventario 
            INNER JOIN  pys_productos  ON  pys_productos.idProd = pys_inventario.idProd
            WHERE pys_actproductos.est = '1' AND pys_productos.idSol = $id;";
            $resultado = mysqli_query($connection, $consulta);
            $string = '  <select name="sltEstadoInv">
            <option value="" disabled selected>Seleccione</option>';
            if ($resultado && mysqli_num_rows($resultado) > 0){
                while ($datos = mysqli_fetch_array($resultado)) {
                    if ($datos['estadoInv'] == 'Sin inventario'){
                        $string .='
                        <option value="Sin inventario" selected>Sin inventario</option>
                        <option value="Proceso de inventario">Proceso de inventario</option>
                        <option value="Terminado">Terminado</option>';
                    }  else if ($datos['estadoInv'] == 'Proceso de inventario'){
                        $string .='
                        <option value="Sin inventario" >Sin inventario</option>
                        <option value="Proceso de inventario" selected>Proceso de inventario</option>
                        <option value="Terminado">Terminado</option>';
                    } else if ($datos['estadoInv'] == 'Terminado'){
                        $string .='
                        <option value="Sin inventario" >Sin inventario</option>
                        <option value="Proceso de inventario">Proceso de inventario</option>
                        <option value="Terminado" selected>Terminado</option>';
                    } else{
                        $string .='
                        <option value="Sin inventario">Sin inventario</option>
                        <option value="Proceso de inventario">Proceso de inventario</option>
                        <option value="Terminado">Terminado</option>';
                    }
                }
            } else {
                $string .='
                        <option value="Sin inventario">Sin inventario</option>
                        <option value="Proceso de inventario">Proceso de inventario</option>
                        <option value="Terminado">Terminado</option>';
            }
            $string .= '  </select>
                    <label for="sltEstadoInv">Estado del inventario*</label>';
        

            
        
            return $string;
            
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
    }



?>