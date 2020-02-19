<?php

    Class Terminar {

        public static function selectProyectoUsuario ($busqueda, $user){
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
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
            $busProy = mysqli_real_escape_string($connection, $busProy);
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
						<th>Nombre Producto</th>
                        <th>Fecha prevista entrega</th>
                        <th>Fecha creación</th>
                        <th>Información</th>
                        <th>Terminar y enviar correo</th>
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
                    $consulta2 = "SELECT est FROM pys_asignados WHERE idSol= '$idSol' and est !=0 ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $data2 = mysqli_fetch_array($resultado2);
                    $consultaProd = "SELECT * FROM pys_productos
                    INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
                    WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $nomProduc = "";
                    if ($resultadoProd == TRUE ){
                        $datosProd = mysqli_fetch_array($resultadoProd);
                        $nomProduc = $datosProd['nombreProd'];
                    }
                    if ($data2['est'] == 1){
                        $color = "red";
                        $modal = "!";
                        $onclick = "";
                        $mjsTooltip ="Faltan requisitos para terminar el Producto o Servicio";
                    } else if ($data2['est'] == 2){
                        $color = "teal";
                        $modal = "modalTerminarProSer";
                        $onclick ='onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')"';
                        $mjsTooltip = "Terminar Producto o Servicio";
                    }
                    $string .= '<tr>
                            <td>'.$codProy.'</td>
                            <td>'.$nombreProy.'</td>
                            <td>'.$idSolIni.'</td>
                            <td>P'.$idSol.'</td>
                            <td>'.$nombreEqu.' -- '.$nombreSer.'</td>
                            <td><p class="truncate">'.$ObservacionAct.'</p></td>
                            <td>'.$nomProduc.'</td>
                            <td>'.$fechPrev.'</td>
                            <td>'.$fechSol.'</td>
                            <td><a href="#modalTerminarProSer" data-position="right" class="modal-trigger tooltipped" data-tooltip="Mas información del Producto/Servicio" onclick="envioData(\'INF'.$idSol.'\',\'modalTerminarProSer.php\')"><i class="material-icons '.$color.'-text">info_outline</i></a></td>
                            <td><a href="#'.$modal.'" data-position="right" class="modal-trigger tooltipped" data-tooltip="'.$mjsTooltip.'" '.$onclick.'><i class="material-icons '.$color.'-text">done_all</i></a></td>
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
            require('../Models/mdl_solicitudEspecifica.php');
            $string ="";
            $vacio = '<p class="left-align red-text">Pendiente por diligenciar información </p>';
            $consulta = "SELECT * FROM pys_actsolicitudes 
            INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer 
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            WHERE idSol='".$idSol."' AND pys_actsolicitudes.est=1 AND pys_servicios.est=1 AND pys_proyectos.est = 1 AND  pys_actsolicitudes.est = 1 AND  pys_equipos.est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $idEqu = $datos['idEqu'];
            $nombreEqu = $datos['nombreEqu'];
            $nombreSer = $datos['nombreSer'];
            $observacionAct = $datos['ObservacionAct'];
            $fechPrev = $datos['fechPrev'];
            $idProy = $datos['idProy'];
            $nombreProy = $datos['nombreProy'];
            $tiempoTotal = SolicitudEspecifica::totalTiempo ($idSol);
            $hora = $tiempoTotal[0];
            $min = $tiempoTotal[1];
            $string .='
            <div class="row">
                <div class="input-field col l3 m12 s12 ">
                    <label for="idSol" class="active">Solicitud Específica No:</label>
                    <p class="left-align">P'.$idSol.'</p>
                </div>
                <div class="input-field col l8 m12 s12 ">
                    <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                    <p class="left-align">'.$idProy.' - '.$nombreProy.'</p>
                </div>
                <div class="input-field col l12 m12 s12 ">
                    <label for="descSol" class="active">Descripción Solicitud Específica:</label>
                    <p class="left-align">'.$observacionAct.'</p>
                </div>
                <div class="input-field col l3 m12 s12">
                    <label for="duraSer" class="active">Fecha prevista de entrega al cliente:</label>
                    <p class="left-align">'.$fechPrev.'</p>
                </div>
                <div class="input-field col l7 m12 s12 ">
                    <label for="monEqu" class="active">Equipo - Servicio:</label>
                    <p class="left-align">'.$nombreEqu.' - '.$nombreSer.'</p>
                </div>
                <div class="input-field col l2 m12 s12">
                    <label for="duraSer" class="active">Duración del Servicio:</label>
                    <p class="left-align">'.$hora.' h '.$min.' m</p>
                </div>';
            if ($datos['productoOservicio'] == 'SI') {
                $consulta1 = "SELECT * FROM pys_productos
                INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd   
                INNER JOIN pys_plataformas ON pys_actproductos.idPlat = pys_plataformas.idPlat
                INNER JOIN pys_claseproductos ON pys_actproductos.idClProd = pys_claseproductos.idClProd
                INNER JOIN pys_tiposproductos ON pys_actproductos.idTProd = pys_tiposproductos.idTProd 
                WHERE pys_productos.idSol = '$idSol' AND pys_productos.est = 1 AND pys_actproductos.est = 1 AND pys_plataformas.est = 1 AND pys_claseproductos.est= 1 AND pys_tiposproductos.est = 1 ";
                $resultado1 = mysqli_query($connection, $consulta1);
                $registro1 =  mysqli_num_rows($resultado1);
                $datos1 = mysqli_fetch_array($resultado1);
                if ($resultado1 == TRUE && $registro1 > 0 ){
                    $nomProduc  = (empty($datos1['nombreProd'])) ? $vacio : '<p class="left-align">'.$datos1['nombreProd'].' </p>' ;
                    $fechaEntre = (empty($datos1['fechEntregaProd'])) ? $vacio : '<p class="left-align">'.$datos1['fechEntregaProd'].' </p>' ;
                    $RED        = (empty($datos1['fechEntregaProd'])) ? $vacio : '<p class="left-align">'.$datos1['fechEntregaProd'].' </p>' ;
                    $plat       = (empty($datos1['nombrePlt'])) ? $vacio : '<p class="left-align">'.$datos1['nombrePlt'].' </p>' ;
                    $clase      = (empty($datos1['nombreClProd'])) ? $vacio : '<p class="left-align">'.$datos1['nombreClProd'].' </p>' ;
                    $tipo       = (empty($datos1['nombreTProd'])) ? $vacio : '<p class="left-align">'.$datos1['nombreTProd'].' </p>' ;
                    $url        = (empty($datos1['urlservidor'])) ? $vacio : '<p class="left-align">'.$datos1['urlservidor'].' </p>' ;
                    $labor      = (empty($datos1['observacionesProd'])) ? $vacio : '<p class="left-align">'.$datos1['observacionesProd'].' </p>' ;
                    $urlVimeo   = (empty($datos1['urlVimeo'])) ? $vacio : '<p class="left-align">'.$datos1['urlVimeo'].' </p>' ;
                    $minDura    = (empty($datos1['duracionmin']) && empty($datos1['duracionseg']) ) ? $vacio : '<p class="left-align">'.$datos1['duracionmin'].' m '.$datos1['duracionseg'].' s </p>' ;
                    $sinopsis   = (empty($datos1['sinopsis'])) ? $vacio : '<p class="left-align">'.$datos1['sinopsis'].' </p>' ;
                    $autores    = (empty($datos1['autorExterno'])) ? $vacio : '<p class="left-align">'.$datos1['autorExterno'].' </p>' ;
                    $string .='
                    <div class="input-field col l5 m12 s12 ">
                        <label for="nomProd" class="active">Nombre Producto:</label>
                        '.$nomProduc.'
                    </div>
                    <div class="input-field col l5 m12 s12 offset-l1 ">
                        <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente:</label>
                        '.$fechaEntre.'
                    </div>';
                    if ($idEqu == 'EQU001') {
                        $string .='
                    <div class="input-field col l11 m12 s12  left-align">
                        <label for="sinopsis" class="active">Sinopsis:</label>
                        '.$sinopsis.'
                    </div>';
                    }
                    $string .='
                    <div class="input-field col l5 m12 s12 ">
                        <label for="txtRED" class="active">¿Es una RED?:</label>
                        '.$RED.'
                    </div>
                    <div class="input-field col l5 m12 s12 offset-l1 ">
                        <label for="plat" class="active">Plataforma:</label>
                        '.$plat.'
                    </div>
                    <div class="input-field col l5 m12 s12">
                        <label for="clase" class="active">Clase de Producto:</label>
                        '.$clase.'
                    </div>
                    <div class="input-field col l5 m12 s12 offset-l1 " id="sltModalTipo">
                        <label for="tipo" class="active">Tipo de Producto:</label>
                        '.$tipo.'
                    </div>
                    <div class="input-field col l11 m12 s12 ">
                        <label for="url" class="active">URL store easy Conecta-TE :</label>
                        '.$url.'
                    </div>
                    <div class="input-field col l11 m12 s12  left-align">
                        <label for="labor" class="active">Labor :</label>
                        '.$labor.'
                    </div>
                    ';
                    if ($idEqu == 'EQU001') {
                        $string .='
                    <div class="input-field col l11 m12 s12  left-align">
                        <label for="autores" class="active">Autores:</label>
                        '.$autores.'
                    </div>
                    <div class="input-field col l11 m12 s12 ">
                        <label for="urlVimeo" class="active">URL álbum de Vimeo:</label>
                        '.$urlVimeo.'
                    </div>
                    <div class="input-field col l12 m12 s12 ">
                        <label for="urlVimeo" class="active">Duracion Video:</label>
                        '.$minDura.'
                        </div>
                       ';
                    } 
                    $string .= '</div>';
                } else {
                    $string ='<div class="card-panel teal darken-1"><h6 class="white-text">No se ha completado la información de Terminación del producto </h6></div>';
                } 
            } else if ($datos['productoOservicio'] == 'NO'){
                $consulta2 = "SELECT * FROM pys_resultservicio 
                INNER JOIN pys_plataformas ON pys_resultservicio.idPlat = pys_plataformas.idPlat
                INNER JOIN pys_claseproductos ON pys_resultservicio.idClProd = pys_claseproductos.idClProd
                INNER JOIN pys_tiposproductos ON pys_resultservicio.idTProd = pys_tiposproductos.idTProd 
                WHERE pys_resultservicio.idSol = '$idSol' AND pys_resultservicio.est = 1 AND pys_plataformas.est = 1 AND pys_claseproductos.est= 1 AND pys_tiposproductos.est = 1;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registro2 =  mysqli_num_rows($resultado2);
                if ($resultado2 == TRUE && $registro2 > 0){
                    $datos2 = mysqli_fetch_array($resultado2);
                    $plat               = (empty($datos2['nombrePlt'])) ? $vacio : '<p class="left-align">'.$datos2['nombrePlt'].' </p>' ;
                    $clase              = (empty($datos2['nombreClProd'])) ? $vacio : '<p class="left-align">'.$datos2['nombreClProd'].' </p>' ;
                    $tipo               = (empty($datos2['nombreTProd'])) ? $vacio : '<p class="left-align">'.$datos2['nombreTProd'].' </p>' ;
                    $observacion        = (empty($datos2['observacion'])) ? $vacio : '<p class="left-align">'.$datos2['observacion'].' </p>' ;
                    $estudiantesImpac   = (empty($datos2['estudiantesImpac'])) ? $vacio : '<p class="left-align">'.$datos2['estudiantesImpac'].' </p>' ;
                    $docentesImpac      = (empty($datos2['docentesImpac'])) ? $vacio : '<p class="left-align">'.$datos2['docentesImpac'].' </p>' ;
                    $urlResultado       = (empty($datos2['urlResultado'])) ? $vacio : '<p class="left-align">'.$datos2['urlResultado'].' </p>' ;
                    $string .= '
                    <div class="input-field col l3 m3 s12">
                        <label for="plat" class="active">Plataforma:</label>
                        '.$plat.'
                    </div>
                    <div class="input-field col l3 m3 s12">
                        <label for="clase" class="active">Clase de Producto:</label>
                        '.$clase.'
                    </div>
                    <div class="input-field col l3 m3 s12" id="sltModalTipo">
                        <label for="tipo" class="active">Tipo de Producto:</label>
                        '.$tipo.'
                    </div>
                    <div class="input-field col l12 m12 s12  left-align">
                        <label for="descripSer" class="active">Descripción Servicio:</label>
                        '.$observacion.'
                    </div>
                    <div class="input-field col l2 m12 s12">
                    <label for="numEst" class="active">Numero de estudiantes:</label>
                        '.$estudiantesImpac.'
                    </div>
                    <div class="input-field col l2 m12 s12 offset-l1">
                    <label for="numDoc" class="active">Numero de docentes:</label>
                        '.$docentesImpac.'
                    </div>
                    <div class="input-field col l6 m12 s12 offset-l1">
                        <label for="url" class="active">URL store easy Conecta-TE :</label>
                        '.$urlResultado.'
                    </div>
                </div>';
                } else{
                    $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No se ha completado la información de Terminación del servicio </h6></div>';
                }
            }
            return $string;
            mysqli_close($connection);
        }

        public static function infoEmail ($idSol){
            require('../Core/connection.php');
            $consulta = "SELECT idEqu, codProy,nombreProy, productoOservicio, idSolIni, pys_personas.correo FROM pys_actsolicitudes 
            INNER JOIN pys_servicios on pys_actsolicitudes.idSer= pys_servicios.idSer
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
            INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idPersona
            WHERE pys_actsolicitudes.idSol='".$idSol."' AND pys_actsolicitudes.est = 1 AND pys_servicios.est=1 AND pys_proyectos.est = 1 AND  pys_actsolicitudes.est = 1;";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function infoEmailPro($idSol, $idEqu){
            require('../Core/connection.php');
            $consulta1 = "SELECT nombreProd, urlVimeo FROM pys_productos WHERE pys_productos.idSol = '$idSol' AND pys_productos.est = 1 ";
            $resultado1 = mysqli_query($connection, $consulta1);
            $datos1 = mysqli_fetch_array($resultado1);
            $nomProduc  = $datos1['nombreProd'];
            $string ='<strong>Nombre del producto: </strong>'.$nomProduc.'<br>
            <strong>Código Producto:</strong> P'.$idSol.'<br>';
            if ($idEqu == 'EQU001'){
                $urlVimeo =$datos1['urlVimeo'];
                $string .= 'URL versión Final (Vimeo): '.$urlVimeo.'<br />';
            }
            return $string;
            mysqli_close($connection);
        }

        public static function infoEmailSer($idSol){
            require('../Core/connection.php');
            $consulta2 = "SELECT * FROM pys_resultservicio WHERE pys_resultservicio.idSol = '$idSol' AND pys_resultservicio.est = 1 ;";
            $resultado2 = mysqli_query($connection, $consulta2);
            $datos2 = mysqli_fetch_array($resultado2);
            $observacion = $datos2['observacion'];
            $string =' <strong>Descripción del servicio: </strong>'.$observacion.'<br />
            <strong>Código Servicio:</strong> P'.$idSol.'<br />';
            return $string;
            mysqli_close($connection);

        }
        
        public static function infoSolicitante($idSolIni){
            require('../Core/connection.php');
            $consulta = "SELECT correo FROM pys_solicitudes 
            INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idSolicitante
            WHERE idSol = '$idSolIni' AND pys_personas.est = 1 AND pys_solicitudes.est = 1 AND idTSol='TSOL01';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $correo = $datos['correo'];
            return $correo;
            mysqli_close($connection);
        }

        public static function infoUsuario($usuario,$id){
            require('../Core/connection.php');
            $consulta = "SELECT pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2 , pys_roles.nombreRol  FROM pys_actualizacionproy
            INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
            INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
            INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu        
            INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
            INNER JOIN pys_personas on pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_roles ON  pys_asignados.idRol= pys_roles.idRol
            WHERE pys_login.usrLogin = '$usuario' AND (pys_asignados.idRol= 'ROL024' OR pys_asignados.idRol= 'ROL025') AND pys_actualizacionproy.est=1  AND pys_asignados.idSol='' AND pys_actsolicitudes.est=1 AND pys_solicitudes.idTSol= 'TSOL02' AND pys_actsolicitudes.est=1 AND pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS007' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_equipos.est = 1 AND pys_servicios.est = 1  AND pys_solicitudes.idSol ='$id'";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;      
            mysqli_close($connection);      
        }

        public static function terminarProducto($idSol){
            require('../Core/connection.php');
            $consulta ="SELECT * FROM pys_actsolicitudes WHERE idSol='$idSol' AND est=1; ";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $idEstSol = $datos ['idEstSol'];
            $idSol= $datos ['idSol']; 
            $idCM= $datos ['idCM'];
            $idSer= $datos ['idSer'];
            $idPersona= $datos ['idPersona'];
            $idSolicitante= $datos ['idSolicitante'];
            $fechPrev= $datos ['fechPrev'];
            $fechAct= $datos ['fechAct'];
            $ObservacionAct= $datos ['ObservacionAct'];
            $presupuesto= $datos ['presupuesto'];
            $horas= $datos ['horas'];
            $est= $datos ['est'];
            $consulta1 = "UPDATE pys_actsolicitudes SET est=2 WHERE idSol='$idSol' AND est=1; ";
            $resultado1 = mysqli_query($connection, $consulta1);
            $consulta2 = "INSERT INTO pys_actsolicitudes VALUES (NULL,'ESS006', '$idSol','$idCM', '$idSer', '$idPersona', '$idSolicitante', '$fechPrev', now(), '$ObservacionAct', $presupuesto, '$horas', '$est')";
            $resultado2 = mysqli_query($connection, $consulta2);
            mysqli_close($connection);
            if ($resultado && $resultado1 && $resultado2){
                echo '<script>alert("Se terminó el producto/Servicio correctamente.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/terminacionServiciosProductos.php">';
            } else{
                echo '<script>alert("No se terminó el producto/Servicio correctamente.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/terminacionServiciosProductos.php">';
            }
        }
        
}
?>
