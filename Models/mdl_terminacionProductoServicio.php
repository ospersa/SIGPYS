<?php

    Class Terminar {

        public static function selectProyectoUsuario ($busqueda, $user){
            require('../Core/connection.php');
            $consulta = "SELECT pys_actualizacionproy.idProy,pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy  FROM pys_asignados 
            INNER JOIN pys_proyectos on pys_proyectos.idProy = pys_asignados.idProy 
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy =pys_proyectos.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND pys_actualizacionproy.est=1 AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_proyectos.est=1 AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%');";
        $resultado = mysqli_query($connection, $consulta);
        if (mysqli_num_rows($resultado) > 0 && $busqueda != null) {
            echo '  <select name="sltProy" id="sltProy" >';
            while ($datos = mysqli_fetch_array($resultado)) {
                $proyecto = $datos['codProy']." - ".$datos['nombreProy'];
                echo '  <option value="'.$datos['idProy'].'">'.$proyecto.'</option>';
            }
            echo '  </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        } else {
            echo '  <select name="sltProy" id="sltProy" >
                        <option value="" disabled>No hay resultados para la busqueda: '.$busqueda.'</option>
                    </select>
                    <label for="sltProy">Seleccione un proyecto</label>';
        }
        mysqli_close($connection);
        }

        public static function cargarProyectosUser ($user, $cod, $busProy, $fechFin){
            require('../Core/connection.php');
            $resultado = "";
            $string = "";
            //valida los proyectos en los cuales es agigando como gestor o asesor RED.
            $consulta = "SELECT  pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.idSol, pys_solicitudes.idSolIni, pys_solicitudes.fechSol, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev FROM pys_actualizacionproy
            INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
            INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu        
            INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_actualizacionproy.est=1  AND pys_asignados.idSol='' AND pys_actsolicitudes.est=1 AND pys_solicitudes.idTSol= 'TSOL02' AND pys_actsolicitudes.est=1 AND pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS007' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_equipos.est = 1 AND pys_servicios.est = 1  ";
            if ($cod == 1 ){
                $consulta .= "AND pys_actualizacionproy.idProy ='$busProy' ";
            } else if($cod == 3){
                $consulta .= "AND pys_actualizacionproy.idProy ='$busProy' AND pys_actsolicitudes.fechPrev <='$fechFin' ";
            }else if ($cod == 2 ){
                $consulta .= "AND pys_actsolicitudes.fechPrev <='$fechFin' ";
            } 
            $consulta .= "ORDER BY pys_solicitudes.fechSol  ASC";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 ){
                $string = ' <table class="responsive-table left" id="terminar">
                <thead>
                    <tr>
                        <th>Cód. proyecto en Conecta-TE</th>
                        <th>Proyecto</th>
                        <th>Código solicitud</th>
                        <th>Producto/Servicio</th>
                        <th>Equipo -- Servicio</th>
                        <th>Descripción Producto/Servicio</th>
                        <th>Fecha prevista entrega</th>
                        <th>Fecha creación</th>
                        <th>Terminar y enviar correo</th>
                        <th>Información</th>
                    </tr>
                </thead>
                <tbody>';
                while ($data = mysqli_fetch_array($resultado)){
                    $codProy = $data['codProy'];
                    $nombreProy = $data['nombreProy'];
                    $idSol = $data['idSol'];
                    $idSolIni = $data['idSolIni'];
                    $fechSol = $data['fechSol'];
                    $nombreEqu = $data['nombreEqu'];
                    $nombreSer = $data['nombreSer'];
                    $ObservacionAct = $data['ObservacionAct'];
                    $fechPrev = $data['fechPrev'];
                    $consulta2 = "SELECT est FROM pys_asignados WHERE idSol= '$idSol' and est !=0";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $data2 = mysqli_fetch_array($resultado2);
                    if ($data2['est'] == 1){
                        $color = "red";
                        $mjsTooltip ="Faltan requisitos para terminar el Producto o Servicio";
                    } else if ($data2['est'] == 2){
                        $color = "teal";
                        //abrir modal
                        $mjsTooltip = "Terminar Producto o Servicio";
                    }
                    $string .= '<tr>
                            <td>'.$codProy.'</td>
                            <td>'.$nombreProy.'</td>
                            <td>'.$idSolIni.'</td>
                            <td>P'.$idSol.'</td>
                            <td>'.$nombreEqu.' -- '.$nombreSer.'</td>
                            <td><p class="truncate">'.$ObservacionAct.'</p></td>
                            <td>'.$fechPrev.'</td>
                            <td>'.$fechSol.'</td>
                            <td><a href="#modalTerminarProSer" data-position="right" class="modal-trigger tooltipped" data-tooltip="Mas información del Producto/Servicio"onclick="envioData(\'INF'.$idSol.'\',\'modalTerminarProSer.php\')"><i class="material-icons '.$color.'-text">info_outline</i></a></td>
                            <td><a href="#!" data-position="right" class="modal-trigger tooltipped" data-tooltip="'.$mjsTooltip.'"><i class="material-icons '.$color.'-text">done_all</i></a></td>
                        </tr>';     
                }
                $string .= '    </tbody>
                                </table>';
            } else{
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda</h6></div>';
            }
            return $string;
            mysqli_close($connection);
        }

        public static function informacionProdSer($idSol){
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_actsolicitudes INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer where idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            if ($datos['productoOservicio'] == 'SI') {
                $consulta1 = "SELECT * FROM pys_productos
                INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
                WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
                $resultado1 = mysqli_query($connection, $consulta1);
                if ($resultado1 == TRUE ){
                    $datos1 = mysqli_fetch_array($resultado1);
                    $nomProduc = $datos1['nombreProd'];
                    $fechaEntre = $datos1['fechEntregaProd'];
                    $RED = $datos1['descripcionProd'];
                    $plat = $datos1['idPlat']; 
                    $clase = $datos1['idClProd']; 
                    $tipo = $datos1['idTProd'];
                    $url = $datos1['urlservidor'];  
                    $labor = $datos1['observacionesProd']; 
                    if ($datos2['idEqu'] == 'EQU001') {
                        $urlY = $datos['urlyoutubeOficial'];     
                        $urlVimeo = $datos['urlVimeo']; 
                        $minDura = $datos['duracionmin'];  
                        $segDura = $datos['duracionseg'];  
                        $sinopsis = $datos['sinopsis'];
                        $autores = $datos['autorExterno'];
                    } 
                } else {
                    $string ="";
                } 
            } else if ($datos['productoOservicio'] == 'NO'){
                $consulta2 = "SELECT * FROM pys_resultservicio WHERE idSol = '$idSol' AND est = 1;";
                $resultado2 = mysqli_query($connection, $consulta2);
                if ($resultado2 == TRUE ){
                    $datos2 = mysqli_fetch_array($resultado);
                    $idPlat = $datos2['idPlat'];
                    $idClProd = $datos2['idClProd'];
                    $idTProd = $datos2['idTProd'];
                    $observacion = $datos2['observacion'];
                    $estudiantesImpac = $datos2['estudiantesImpac'];
                    $docentesImpac = $datos2['docentesImpac'];
                    $urlResultado  = $datos2['urlResultado'];
                } else{
                    $string = "";
                }
            }
        }
    }
?>
