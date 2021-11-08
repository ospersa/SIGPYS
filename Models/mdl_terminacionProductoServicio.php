<?php

    Class Terminar {

        public static function selectProyectoUsuario ($busqueda, $user){
            require('../Core/connection.php');
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta = "SELECT pys_actualizacionproy.idProy,pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                FROM pys_asignados 
                INNER JOIN pys_proyectos ON pys_proyectos.idProy = pys_asignados.idProy AND pys_proyectos.est = '1'
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy AND pys_actualizacionproy.est = '1' 
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona 
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
                WHERE pys_login.usrLogin = '$user' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND (pys_actualizacionproy.codProy LIKE '%$busqueda%' OR pys_actualizacionproy.nombreProy LIKE '%$busqueda%') 
                GROUP BY pys_actualizacionproy.idProy;";
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
            // Valida los proyectos en los cuales es asignado como gestor o asesor RED.
            $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.idSol, pys_solicitudes.idSolIni, pys_solicitudes.fechSol, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_asignados.est AS 'estado', pys_estadosol.nombreEstSol, pys_actproductos.nombreProd
                FROM pys_actualizacionproy
                INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
                INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer
                INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
                INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona 
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                LEFT JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol AND pys_productos.est = '1'
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                WHERE pys_login.usrLogin = '$user' AND pys_actualizacionproy.est = '1' AND (idRol= 'ROL024' OR idRol= 'ROL025') AND pys_actsolicitudes.est = '1' AND pys_solicitudes.idTSol = 'TSOL02' AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idEstSol != 'ESS001' AND pys_actsolicitudes.idEstSol != 'ESS007' AND pys_actsolicitudes.idEstSol != 'ESS006' AND pys_equipos.est = '1' AND pys_servicios.est = '1' ";
            if ($cod == 1 ) {
                $consulta .= "AND pys_actualizacionproy.idProy = '$busProy' ";
            } else if($cod == 3) {
                $consulta .= "AND pys_actualizacionproy.idProy = '$busProy' AND pys_actsolicitudes.fechPrev <= '$fechFin' ";
            } else if ($cod == 2 ) {
                $consulta .= "AND pys_actsolicitudes.fechPrev <= '$fechFin' ";
            } 
            $consulta .= "GROUP BY pys_solicitudes.idSol ORDER BY pys_solicitudes.fechSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            if (mysqli_num_rows($resultado) > 0 ){
                $string = ' <table class="responsive-table left" id="terminar" style="font-size: 0.9em;">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Producto/Servicio</th>
                                        <th>Descripción Producto/Servicio</th>
                                        <th>Nombre Producto</th>
                                        <th>Estado</th>
                                        <th>Fecha prevista entrega</th>
                                        <th>Fecha creación</th>
                                        <th>Metadata y cierre</th>
                                        <!--<th>Terminar y enviar correo</th>-->
                                    </tr>
                                </thead>
                                <tbody>';
                while ($data = mysqli_fetch_array($resultado)){
                    $codProy = $data['codProy'];
                    $nombreProy = $data['nombreProy'];
                    $idSol = $data['idSol'];
                    $idSolIni = $data['idSolIni'];
                    $estadoSol = $data['nombreEstSol'];
                    $fechSol = $data['fechSol'];
                    $nombreEqu = $data['nombreEqu'];
                    $nombreSer = $data['nombreSer'];
                    $ObservacionAct = $data['ObservacionAct'];
                    $nombreProducto = ( isset ( $data['nombreProd'] ) ) ? $data['nombreProd'] : null;
                    $fechPrev = $data['fechPrev'];
                    $consulta2 = "SELECT est FROM pys_asignados WHERE idSol = '$idSol' AND est != '0' ;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $pendientes = 0;
                    while ($data2 = mysqli_fetch_array($resultado2)) {
                        if ($data2['est'] == 1) {
                            $pendientes++;
                        }
                    }
                   $consultaProd = "SELECT plataforma_producto.nombrePlt AS plat_producto, pys_actproductos.nombreProd, pys_actproductos.fechEntregaProd, pys_actproductos.descripcionProd, pys_claseproductos.nombreClProd, pys_tiposproductos.nombreTProd, pys_actproductos.urlservidor, pys_actproductos.observacionesProd, pys_actproductos.urlVimeo, pys_actproductos.duracionmin, pys_actproductos.duracionseg, pys_actproductos.sinopsis, pys_actproductos.autorExterno, idiomas.idiomaNombre, formatos.formatoNombre, tiposcontenido.tipoContenidoNombre, pys_actproductos.palabrasClave 
                        FROM pys_productos
                        INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
                        LEFT JOIN idiomas ON idiomas.idIdiomas = pys_actproductos.idioma
                        LEFT JOIN formatos ON formatos.idFormatos = pys_actproductos.formato
                        LEFT JOIN tiposcontenido ON tiposcontenido.idtiposContenido = pys_actproductos.tipoContenido
                        LEFT JOIN pys_plataformas AS plataforma_producto ON plataforma_producto.idPlat = pys_actproductos.idPlat AND plataforma_producto.est = '1'
                        LEFT JOIN pys_claseproductos ON pys_claseproductos.idClProd = pys_actproductos.idClProd AND pys_claseproductos.est = '1'
                        LEFT JOIN pys_tiposproductos ON pys_tiposproductos.idTProd = pys_actproductos.idTProd AND pys_tiposproductos.est = '1'
                        WHERE idSol = '$idSol' AND pys_actproductos.est = '1' AND pys_productos.est = '1';";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $dataProd = mysqli_fetch_array($resultadoProd);
                    $metadataRealizacion = ['nombreProd', 'fechEntregaProd', 'descripcionProd', 'nombreClProd', 'nombreTProd', 'urlservidor', 'urlVimeo', 'duracionmin', 'duracionseg', 'sinopsis', 'autorExterno', 'idiomaNombre', 'formatoNombre', 'tipoContenidoNombre', 'palabrasClave'];
                    $metadataDiseno = ['nombreProd', 'fechEntregaProd', 'descripcionProd', 'plat_producto', 'nombreClProd', 'nombreTProd', 'idiomaNombre', 'formatoNombre', 'tipoContenidoNombre', 'palabrasClave', 'urlservidor'];
                    $metapendiente = 0;
                    if ( $nombreEqu == 'Realización' ) {
                        foreach ( $metadataRealizacion as $meta ) {
                            if ( ! isset ( $dataProd[$meta] ) || $dataProd[$meta] == '' ) {
                                $metapendiente ++;
                            }
                        }
                    } else if ( $nombreEqu == 'Diseño Gráfico' ) {
                        foreach ( $metadataDiseno as $meta ) {
                            if ( ! isset ($dataProd[$meta] ) || $dataProd[$meta] == '' ) {
                                $metapendiente ++;
                            }
                        }
                    }
                    if ( $metapendiente > 0 ) {
                        $colorMeta = "red";
                        $color = "red";
                        $modal = "!";
                        $onclick = "";
                        $mjsTooltip ="Metadata incompleta";
                    } else {
                        $colorMeta = "teal";
                        if ( $pendientes > 0 ) {
                            $color = "red";
                            $modal = "modalTerminarProSer";
                            $onclick = 'onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')"';
                            $mjsTooltip ="Hay $pendientes personas que no han terminado la labor";
                        } else {
                            $color = "teal";
                            $modal = "modalTerminarProSer";
                            $onclick = 'onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')"';
                            $mjsTooltip = "Terminar Producto o Servicio";
                        }
                    }
                    $string .= '    <tr>
                                        <td>'.$codProy.' - '.$nombreProy.'</td>
                                        <td>P'.$idSol.'</td>
                                        <td>'.$ObservacionAct.'</p></td>
                                        <td>'.$nombreProducto.'</td>
                                        <td>'.$estadoSol.'</td>
                                        <td>'.$fechPrev.'</td>
                                        <td>'.$fechSol.'</td>
                                        <td class="center-align"><a href="#modalTerminarProSer" data-position="left" class="modal-trigger tooltipped" data-tooltip="Más información del Producto/Servicio" onclick="envioData(\'INF'.$idSol.'\',\'modalTerminarProSer.php\')"><i class="material-icons '.$colorMeta.'-text">info_outline</i></a></td>
                                        <!--<td><a href="#'.$modal.'" data-position="left" class="modal-trigger tooltipped" data-tooltip="'.$mjsTooltip.'" '.$onclick.'><i class="material-icons '.$color.'-text">done_all</i></a></td>-->
                                    </tr>'; 
                    }    
                $string .= '    </tbody>
                            </table>';
            } else {
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda</h6></div>';
            }
            mysqli_close($connection);
            return $string;
        }

        public static function informacionProdSer ($idSol) {
            require('../Core/connection.php');
            require('../Models/mdl_solicitudEspecifica.php');
            $string = $disabled = "";
            $vacio = '<p class="left-align red-text">Información pendiente</p>';
            $consulta = "SELECT plataforma_producto.nombrePlt AS plat_producto, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_servicios.productoOservicio, pys_actproductos.nombreProd, pys_actproductos.fechEntregaProd, pys_actproductos.descripcionProd, pys_claseproductos.nombreClProd, pys_tiposproductos.nombreTProd, pys_tiposproductos.descripcionTProd, pys_actproductos.urlservidor, pys_actproductos.observacionesProd, pys_actproductos.urlVimeo, pys_actproductos.duracionmin, pys_actproductos.duracionseg, pys_actproductos.sinopsis, pys_actproductos.autorExterno, idiomas.idiomaNombre, formatos.formatoNombre, tiposcontenido.tipoContenidoNombre, pys_actproductos.palabrasClave, pys_estadosol.nombreEstSol
                FROM pys_actsolicitudes 
                INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer 
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
                INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_proyectos.idProy AND pys_actualizacionproy.est = '1'
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                LEFT JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol AND pys_productos.est = '1'
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                LEFT JOIN pys_resultservicio ON pys_resultservicio.idSol = pys_actsolicitudes.idSol AND pys_resultservicio.est = '1'
                LEFT JOIN pys_plataformas AS plataforma_producto ON plataforma_producto.idPlat = pys_actproductos.idPlat AND plataforma_producto.est = '1'
                LEFT JOIN pys_plataformas AS plataforma_servicio ON plataforma_servicio.idPlat = pys_resultservicio.idPlat AND plataforma_servicio.est = '1'
                LEFT JOIN idiomas ON idiomas.idIdiomas = pys_actproductos.idioma
                LEFT JOIN formatos ON formatos.idFormatos = pys_actproductos.formato
                LEFT JOIN tiposcontenido ON tiposcontenido.idtiposContenido = pys_actproductos.tipoContenido
                LEFT JOIN pys_claseproductos ON pys_claseproductos.idClProd = pys_actproductos.idClProd AND pys_claseproductos.est = '1'
                LEFT JOIN pys_tiposproductos ON pys_tiposproductos.idTProd = pys_actproductos.idTProd AND pys_tiposproductos.est = '1'
                WHERE pys_actsolicitudes.idSol = '$idSol' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1' AND pys_proyectos.est = '1' AND pys_actsolicitudes.est = '1' AND pys_equipos.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $idEqu              = $datos['idEqu'];
            $nombreEqu          = $datos['nombreEqu'];
            $nombreSer          = $datos['nombreSer'];
            $observacionAct     = $datos['ObservacionAct'];
            $estado             = $datos['nombreEstSol'];
            $fechPrev           = $datos['fechPrev'];
            $idProy             = $datos['codProy'];
            $nombreProy         = $datos['nombreProy'];
            $nomProduc          = (empty($datos['nombreProd'])) ? $vacio : '<p class="left-align">'.$datos['nombreProd'].' </p>' ;
            $fechaEntre         = (empty($datos['fechEntregaProd'])) ? $vacio : '<p class="left-align">'.$datos['fechEntregaProd'].' </p>' ;
            $RED                = (empty($datos['descripcionProd'])) ? $vacio : '<p class="left-align">'.$datos['descripcionProd'].' </p>' ;
            $plataformaProducto = (empty($datos['plat_producto'])) ? $vacio : '<p class="left-align">'.$datos['plat_producto'].' </p>' ;
            $clase              = (empty($datos['nombreClProd'])) ? $vacio : '<p class="left-align">'.$datos['nombreClProd'].' </p>' ;
            $tipo               = (empty($datos['nombreTProd'])) ? $vacio : '<p class="left-align">'.$datos['nombreTProd'].' - '.$datos['descripcionTProd'].' </p>' ;
            $url                = (empty($datos['urlservidor'])) ? $vacio : '<p class="left-align">'.$datos['urlservidor'].' </p>' ;
            $labor              = (empty($datos['observacionesProd'])) ? '<p class="left-align" style="color:transparent;">Observación</p>' : '<p class="left-align">'.$datos['observacionesProd'].' </p>' ;
            $urlVimeo           = (empty($datos['urlVimeo'])) ? $vacio : '<p class="left-align">'.$datos['urlVimeo'].' </p>' ;
            $minDura            = (empty($datos['duracionmin']) && empty($datos['duracionseg']) ) ? $vacio : '<p class="left-align">'.$datos['duracionmin'].' m '.$datos['duracionseg'].' s </p>' ;
            $sinopsis           = (empty($datos['sinopsis'])) ? $vacio : '<p class="left-align">'.$datos['sinopsis'].' </p>' ;
            $autores            = (empty($datos['autorExterno'])) ? $vacio : '<p class="left-align">'.$datos['autorExterno'].' </p>' ;
            $idioma             = (empty($datos['idiomaNombre'])) ? $vacio : '<p class="left-align">'.$datos['idiomaNombre'].' </p>' ;
            $formato            = (empty($datos['formatoNombre'])) ? $vacio : '<p class="left-align">'.$datos['formatoNombre'].' </p>' ;
            $tipoContenido      = (empty($datos['tipoContenidoNombre'])) ? $vacio : '<p class="left-align">'.$datos['tipoContenidoNombre'].' </p>' ;
            $palabrasClave      = (empty($datos['palabrasClave'])) ? $vacio : '<p class="left-align">'.$datos['palabrasClave'].' </p>' ;
            $tiempoTotal        = SolicitudEspecifica::totalTiempo ($idSol);
            $hora               = $tiempoTotal[0];
            $min                = $tiempoTotal[1];
            if ($idEqu == 'EQU001') {
                $disabled = ($nomProduc == $vacio || $sinopsis == $vacio || $clase == $vacio || $tipo == $vacio || $idioma == $vacio || $formato == $vacio || $tipoContenido == $vacio || $RED == $vacio || $palabrasClave == $vacio || $url == $vacio || $urlVimeo == $vacio || $autores == $vacio || $minDura == $vacio || $fechaEntre == $vacio) ? "disabled" : "";
            } else if ($idEqu == 'EQU002') {
                $disabled = ($nomProduc == $vacio || $clase == $vacio || $tipo == $vacio || $idioma == $vacio || $formato == $vacio || $tipoContenido == $vacio || $RED == $vacio || $palabrasClave == $vacio || $url == $vacio || $fechaEntre == $vacio ) ? "disabled" : "";
            }
            $string .= '    <div class="col l12 m12 s12">
                                <div class="input-field col l2 m12 s12 ">
                                    <label for="idSol" class="active">Código PS:</label>
                                    <p class="left-align">P'.$idSol.'</p>
                                </div>
                                <div class="input-field col l7 m12 s12 ">
                                    <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                                    <p class="left-align">'.$idProy.' - '.$nombreProy.'</p>
                                </div>
                                <div class="input-field col l3 m12 s12 ">
                                    <label for="descSol" class="active">Estado:</label>
                                    <p class="left-align">'.$estado.'</p>
                                </div>
                                <div class="input-field col l12 m12 s12 ">
                                    <label for="descSol" class="active">Descripción Solicitud Específica:</label>
                                    <p class="left-align">'.$observacionAct.'</p>
                                </div>
                                <div class="input-field col l3 m12 s12">
                                    <label for="duraSer" class="active">Fecha prevista de entrega al cliente:</label>
                                    <p class="left-align">'.$fechPrev.'</p>
                                </div>
                                <div class="input-field col l6 m12 s12 ">
                                    <label for="monEqu" class="active">Equipo - Servicio:</label>
                                    <p class="left-align">'.$nombreEqu.' - '.$nombreSer.'</p>
                                </div>
                                <div class="input-field col l3 m12 s12">
                                    <label for="duraSer" class="active">Duración del Servicio:</label>
                                    <p class="left-align">'.$hora.' h '.$min.' m</p>
                                </div>';
            if ($datos['productoOservicio'] == 'SI') { 
                $string .='     <div class="input-field col l12 m12 s12 ">
                                    <label for="nomProd" class="active">Nombre Producto:</label>
                                    '.$nomProduc.'
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .=' <div class="input-field col l12 m12 s12  left-align">
                                    <label for="sinopsis" class="active">Sinopsis:</label>
                                    '.$sinopsis.'
                                </div>';
                }
                $string .='     <div class="input-field col l6 m12 s12">
                                    <label for="clase" class="active">Clase de Producto:</label>
                                    '.$clase.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    <label for="tipo" class="active">Tipo de Producto:</label>
                                    '.$tipo.'
                                </div>
                                <div class="input-field col l6 m12 s12">
                                    <label for="clase" class="active">Idioma:</label>
                                    '.$idioma.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    <label for="tipo" class="active">Formato:</label>
                                    '.$formato.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    <label for="tipo" class="active">Tipo Contenido:</label>
                                    '.$tipoContenido.'
                                </div>
                                <div class="input-field col l6 m12 s12 ">
                                    <label for="txtRED" class="active">¿Es un RED?:</label>
                                    '.$RED.'
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <label for="labor" class="active">Palabras clave:</label>
                                    '.$palabrasClave.'
                                </div>
                                <div class="input-field col l12 m12 s12 ">
                                    <label for="url" class="active">Enlace para inventario:</label>
                                    '.$url.'
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .= '<div class="input-field col l12 m12 s12 ">
                                    <label for="urlVimeo" class="active">Enlace Vimeo:</label>
                                    '.$urlVimeo.'
                                </div>
                                <div class="input-field col l12 m12 s12  left-align">
                                    <label for="autores" class="active">Autores:</label>
                                    '.$autores.'
                                </div>';
                }
                $string .= '    <div class="input-field col l12 m12 s12  left-align">
                                    <label for="labor" class="active">Observaciones:</label>
                                    '.$labor.'
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .=' <div class="input-field col l6 m12 s12 ">
                                    <label for="urlVimeo" class="active">Duración Video:</label>
                                    '.$minDura.'
                                </div>';
                } else if ($idEqu == 'EQU002') {
                    $string .= '<div class="input-field col l6 m12 s12">
                                    <label for="plat" class="active">Plataforma:</label>
                                    '.$plataformaProducto.'
                                </div>';
                }
                $string .= '    <div class="input-field col l6 m12 s12">
                                    <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente:</label>
                                    '.$fechaEntre.'
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <a href="#modalTerminarProSer" class="btn modal-trigger" data-modal="#modalTerminarProSer" '.$disabled.' onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')">Terminar producto</a>
                                </div>';
            } else if ($datos['productoOservicio'] == 'NO'){
                $consulta2 = "SELECT * FROM pys_resultservicio 
                INNER JOIN pys_plataformas ON pys_resultservicio.idPlat = pys_plataformas.idPlat
                INNER JOIN pys_claseproductos ON pys_resultservicio.idClProd = pys_claseproductos.idClProd
                INNER JOIN pys_tiposproductos ON pys_resultservicio.idTProd = pys_tiposproductos.idTProd 
                WHERE pys_resultservicio.idSol = '$idSol' AND pys_resultservicio.est = 1 AND pys_plataformas.est = 1 AND pys_claseproductos.est= 1 AND pys_tiposproductos.est = 1;";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registro2 =  mysqli_num_rows($resultado2);
                /* if ($resultado2 == TRUE && $registro2 > 0){ */
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
                    <div class="input-field col l12 m12 s12">
                        <a href="#modalTerminarProSer" class="btn modal-trigger" data-modal="#modalTerminarProSer" '.$disabled.' onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')">Terminar producto</a>
                    </div>';
                /* } else{
                    $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No se ha completado la información de Terminación del servicio </h6></div>';
                } */
            }
            $string .= '</div>';
            return $string;
            mysqli_close($connection);
        }

        public static function infoEmail ($idSol){
            require('../Core/connection.php');
            $consulta = "SELECT idEqu, codProy,nombreProy, productoOservicio, idSolIni, pys_personas.correo 
                FROM pys_actsolicitudes 
                INNER JOIN pys_servicios ON pys_actsolicitudes.idSer= pys_servicios.idSer
                INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
                INNER JOIN pys_proyectos ON pys_cursosmodulos.idProy = pys_proyectos.idProy
                INNER JOIN pys_solicitudes ON pys_actsolicitudes.idSol = pys_solicitudes.idSol
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idPersona
                WHERE pys_actsolicitudes.idSol = '$idSol' AND pys_actsolicitudes.est = '1' AND pys_servicios.est = '1' AND pys_proyectos.est = '1' AND  pys_actsolicitudes.est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            mysqli_close($connection);
            return $datos;
        }

        public static function infoEmailPro($idSol, $idEqu){
            require('../Core/connection.php');
            $consulta1 = "SELECT nombreProd, urlVimeo FROM pys_productos WHERE pys_productos.idSol = '$idSol' AND pys_productos.est = '1';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $datos1 = mysqli_fetch_array($resultado1);
            $nomProduc  = isset ( $datos1['nombreProd'] ) ? $datos1['nombreProd'] : null;
            $string = ' <strong>Nombre del producto: </strong>'.$nomProduc.'<br>
                        <strong>Código Producto:</strong> P'.$idSol.'<br>';
            if ( $idEqu == 'EQU001' ) {
                $urlVimeo = isset ( $datos1['urlVimeo'] ) ? $datos1['urlVimeo'] : null;
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
            $observacion = isset($datos2['observacion']) ? $datos2['observacion'] : '';
            $string =' <strong>Descripción del servicio: </strong>'.$observacion.'<br />
            <strong>Código Servicio:</strong> P'.$idSol.'<br />';
            mysqli_close($connection);
            return $string;

        }
        
        public static function infoSolicitante($idSolIni){
            require('../Core/connection.php');
            $consulta = "SELECT correo FROM pys_solicitudes 
                INNER JOIN pys_personas ON pys_personas.idPersona = pys_solicitudes.idSolicitante
                WHERE idSol = '$idSolIni' AND pys_personas.est = '1' AND pys_solicitudes.est = '1' AND idTSol='TSOL01';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $correo = $datos['correo'];
            mysqli_close($connection);
            return $correo;
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
            INNER JOIN pys_personas ON pys_asignados.idPersona= pys_personas.idPersona 
            INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona 
            INNER JOIN pys_roles ON  pys_asignados.idRol= pys_roles.idRol
            WHERE pys_login.usrLogin = '$usuario' AND (pys_asignados.idRol= 'ROL024' OR pys_asignados.idRol= 'ROL025') AND pys_actualizacionproy.est=1 AND pys_actsolicitudes.est=1 AND pys_solicitudes.idTSol= 'TSOL02' AND pys_actsolicitudes.est=1 AND pys_actsolicitudes.idEstSol !='ESS001' AND pys_actsolicitudes.idEstSol !='ESS007' AND pys_actsolicitudes.idEstSol !='ESS006' AND pys_equipos.est = 1 AND pys_servicios.est = 1  AND pys_solicitudes.idSol ='$id'";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            return $datos;      
            mysqli_close($connection);      
        }

        public static function terminarProducto($idSol) {
            require('../Core/connection.php');
            $consulta = "SELECT * FROM pys_actsolicitudes WHERE idSol = '$idSol' AND est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $idEstSol       = $datos ['idEstSol'];
            $idSol          = $datos ['idSol']; 
            $idCM           = $datos ['idCM'];
            $idSer          = $datos ['idSer'];
            $idPersona      = $datos ['idPersona'];
            $idSolicitante  = $datos ['idSolicitante']; 
            $fechPrev       = ($datos ['fechPrev'] == '0000-00-00') ? 'NULL' : "'". $datos ['fechPrev']."'";
            $fechAct        = $datos ['fechAct'];
            $ObservacionAct = $datos ['ObservacionAct'];
            $presupuesto    = $datos ['presupuesto'];
            $horas          = $datos ['horas'];
            $registrar      = $datos['registraTiempo'];
            mysqli_query($connection, "BEGIN;");
            $consulta1 = "UPDATE pys_actsolicitudes SET est = '2' WHERE idSol = '$idSol' AND est = '1';";
            $resultado1 = mysqli_query($connection, $consulta1);
            $consulta2 = "INSERT INTO pys_actsolicitudes VALUES (NULL, 'ESS006', '$idSol', '$idCM', '$idSer', '$idPersona', '$idSolicitante', $fechPrev, now(), '$ObservacionAct', $presupuesto, '$horas', '$registrar', '1');";
            $resultado2 = mysqli_query($connection, $consulta2);
            $consulta3 = "SELECT idAsig FROM pys_asignados WHERE idSol = '$idSol' AND est = '1';";
            $resultado3 = mysqli_query($connection, $consulta3);
            $asignados = mysqli_num_rows($resultado3);
            if ($asignados > 0) {
                while ($datos3 = mysqli_fetch_array($resultado3)) {
                    $idAsig = $datos3['idAsig'];
                    $consulta4 = "UPDATE pys_asignados SET est = '2' WHERE idAsig = '$idAsig' AND est = '1';";
                    $resultado4 = mysqli_query($connection, $consulta4);
                    if ( $resultado4 ) {
                        $asignados--;
                    }
                }
            }
            if ( $resultado1 && $resultado2 && $asignados == 0 ) {
                mysqli_query($connection, "COMMIT;");
                echo '<script>alert("Se terminó el producto/Servicio correctamente.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/terminacionServiciosProductos.php">';
            } else {
                mysqli_query($connection, "ROLLBACK;");
                echo '<script>alert("No se terminó el producto/Servicio correctamente.")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/terminacionServiciosProductos.php">';
            }
            mysqli_close($connection);
        }
        
}
?>
