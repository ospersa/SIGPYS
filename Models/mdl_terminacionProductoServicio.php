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
            $consulta = "SELECT pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.idSol, pys_solicitudes.idSolIni, pys_solicitudes.fechSol, pys_equipos.nombreEqu, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_asignados.est AS 'estado', pys_estadosol.nombreEstSol, pys_actproductos.nombreProd, pys_servicios.productoOservicio
                FROM pys_actualizacionproy
                INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy 
                INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM AND pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idEstSol != 'ESS007' AND pys_actsolicitudes.idEstSol != 'ESS001' AND pys_actsolicitudes.idEstSol != 'ESS006'
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol AND pys_solicitudes.idTSol = 'TSOL02'
                INNER JOIN pys_servicios ON pys_servicios.idSer = pys_actsolicitudes.idSer AND pys_servicios.est = '1'
                INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu AND pys_equipos.est = '1'
                INNER JOIN pys_asignados ON pys_actualizacionproy.idProy = pys_asignados.idProy
                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona 
                INNER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona AND pys_login.usrLogin = '$user'
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol
                LEFT JOIN pys_productos ON pys_productos.idSol = pys_actsolicitudes.idSol AND pys_productos.est = '1'
                LEFT JOIN pys_actproductos ON pys_actproductos.idProd = pys_productos.idProd AND pys_actproductos.est = '1'
                WHERE pys_actualizacionproy.est = '1' AND (idRol= 'ROL024' OR idRol= 'ROL025') ";
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
                        if ($data['productoOservicio'] == 'SI') {
                            foreach ( $metadataRealizacion as $meta ) {
                                if ( ! isset ( $dataProd[$meta] ) || $dataProd[$meta] == '' ) {
                                    $metapendiente ++;
                                }
                            }
                        }
                    } else if ( $nombreEqu == 'Diseño Gráfico' ) {
                        if ($data['productoOservicio'] == 'SI') {
                            foreach ( $metadataDiseno as $meta ) {
                                if ( ! isset ($dataProd[$meta] ) || $dataProd[$meta] == '' ) {
                                    $metapendiente ++;
                                }
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
            include_once('../Models/mdl_solicitudEspecifica.php');
            $string = $disabled = "";
            /* $vacio = '<p class="left-align red-text">Información pendiente</p>'; */
            $vacio = '';
            $consulta = "SELECT plataforma_producto.nombrePlt AS plat_producto, plataforma_producto.idPlat, pys_equipos.idEqu, pys_equipos.nombreEqu, pys_actsolicitudes.idSer, pys_servicios.nombreSer, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_actualizacionproy.idProy, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_servicios.productoOservicio, pys_actproductos.nombreProd, pys_actproductos.fechEntregaProd, pys_actproductos.descripcionProd, pys_claseproductos.idClProd, pys_claseproductos.nombreClProd, pys_tiposproductos.idTProd, pys_tiposproductos.nombreTProd, pys_tiposproductos.descripcionTProd, pys_actproductos.urlservidor, pys_actproductos.observacionesProd, pys_actproductos.urlVimeo, pys_actproductos.duracionmin, pys_actproductos.duracionseg, pys_actproductos.sinopsis, pys_actproductos.autorExterno, idiomas.idIdiomas, idiomas.idiomaNombre, formatos.idFormatos, formatos.formatoNombre, tiposcontenido.idtiposContenido, tiposcontenido.tipoContenidoNombre, pys_actproductos.palabrasClave, pys_estadosol.nombreEstSol, pys_actproductos.idAreaConocimiento
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
            $idProy                 = $datos['codProy'];
            $nombreProy             = $datos['nombreProy'];
            $estado                 = $datos['nombreEstSol'];
            $observacionAct         = $datos['ObservacionAct'];
            $fechPrev               = $datos['fechPrev'];
            $idEqu                  = $datos['idEqu'];
            $nombreEqu              = $datos['nombreEqu'];
            $nombreSer              = $datos['nombreSer'];
            $tiempoTotal            = SolicitudEspecifica::totalTiempo ($idSol);
            $hora                   = $tiempoTotal[0];
            $min                    = $tiempoTotal[1];
            $nomProduc              = $datos['nombreProd'];
            $sinopsis               = $datos['sinopsis'];
            $idServicio             = $datos['idSer'];
            $idClase                = $datos['idClProd'];
            $idTipo                 = $datos['idTProd'];
            $clase                  = $datos['nombreClProd'];
            $sltClase               = SolicitudEspecifica::selectClaseConTipo ($idServicio, $idClase);
            $tipo                   = $datos['nombreTProd'];
            $selectTipoVacio        = ' <select>
                                            <option value="" disabled>Seleccione una clase de producto</option>
                                        </select>
                                        <label for="">Tipo de producto</label>';
            $sltTipo                = ($datos['idTProd'] != null) ? SolicitudEspecifica::selectTipoProducto ($idClase, $idServicio, $idTipo) : $selectTipoVacio;
            $idioma                 = $datos['idiomaNombre'];
            $sltIdioma              = SolicitudEspecifica::selectIdioma ($datos['idIdiomas']);
            $formato                = $datos['formatoNombre'];
            $sltFormato             = SolicitudEspecifica::selectFormato ($datos['idFormatos']);
            $tipoContenido          = $datos['tipoContenidoNombre'];
            $sltTipoContenido       = SolicitudEspecifica::selectTipoContenido ($datos['idtiposContenido']);
            $RED                    = $datos['descripcionProd'];
            $sltRED                 = SolicitudEspecifica::selectRED ($RED);
            $idAreaConocimiento     = $datos['idAreaConocimiento'];
            $sltAreaConocimiento    = SolicitudEspecifica::selectAreaConocimiento ($idProy, $idAreaConocimiento);
            $palabrasClave          = $datos['palabrasClave'];
            $url                    = $datos['urlservidor'];
            $urlVimeo               = $datos['urlVimeo'];
            $autores                = $datos['autorExterno'];
            $labor                  = $datos['observacionesProd'];
            $minDura                = $datos['duracionmin'];
            $segDura                = $datos['duracionseg'];
            $sltPlataforma          = SolicitudEspecifica::selectPlataforma ($datos['idPlat']);
            $fechaEntre             = $datos['fechEntregaProd'];

            if ($idEqu == 'EQU001') {
                if ($datos['productoOservicio'] == 'SI') {
                    $disabled = ( empty($nomProduc) || empty($sinopsis) || empty($clase) || empty($tipo) || empty($idioma) || empty($formato) || empty($tipoContenido) || empty($RED) || empty($palabrasClave) || empty($url) || empty($urlVimeo) || empty($autores) || empty($minDura) || empty($fechaEntre) ) ? "disabled" : "";
                }
            } else if ($idEqu == 'EQU002') {
                $disabled = ($nomProduc == $vacio || $clase == $vacio || $tipo == $vacio || $idioma == $vacio || $formato == $vacio || $tipoContenido == $vacio || $RED == $vacio || $palabrasClave == $vacio || $url == $vacio || $fechaEntre == $vacio ) ? "disabled" : "";
            }
            $string .= '<form id="actForm" method="post">
                            <input type="hidden" name="action" value="guardarMetadata">
                            <input type="hidden" name="idSol" value="'.$idSol.'">
                            <input type="hidden" name="idEquipo" value="'.$idEqu.'">
                            <input type="hidden" name="producto" value="'.$datos['productoOservicio'].'">
                            <input type="hidden" name="idServicio" value="'.$datos['idSer'].'">
                            <div class="">
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
                                    <input type="text" name="nomProd" id="nomProd" value="'.$nomProduc.'" >
                                    <label for="nomProd" class="active">Nombre Producto:</label>
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .=' <div class="input-field col l12 m12 s12  left-align">
                                    <textarea name="sinopsis" id="sinopsis" class="materialize-textarea">'.$sinopsis.'</textarea>
                                    <label for="sinopsis" class="active">Sinopsis:</label>
                                </div>';
                }
                $string .='     <div class="input-field col l6 m12 s12">
                                    '.$sltClase.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    '.$sltTipo.'
                                </div>
                                <div class="input-field col l6 m12 s12">
                                    '.$sltIdioma.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    '.$sltFormato.'
                                </div>
                                <div class="input-field col l6 m12 s12" id="sltModalTipo">
                                    '.$sltTipoContenido.'
                                </div>
                                <div class="input-field col l6 m12 s12 ">
                                    '.$sltRED.'
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    '.$sltAreaConocimiento.'
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <textarea id="palabrasClave" name="palabrasClave" class="materialize-textarea">'.$palabrasClave.'</textarea>
                                    <label for="labor" class="active">Palabras clave:</label>
                                </div>
                                <div class="input-field col l12 m12 s12 ">
                                    <input type="text" name="url" id="url" value="'.$url.'">
                                    <label for="url" class="active">Enlace para inventario:</label>
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .= '<div class="input-field col l12 m12 s12 ">
                                    <input type="text" name="urlV" id="urlV" value="'.$urlVimeo.'">
                                    <label for="urlVimeo" class="active">Enlace Vimeo:</label>
                                </div>
                                <div class="input-field col l12 m12 s12  left-align">
                                    <textarea name="autores" id="autores" class="materialize-textarea">'.$autores.'</textarea>
                                    <label for="autores" class="active">Autores:</label>
                                </div>';
                }
                $string .= '    <div class="input-field col l12 m12 s12  left-align">
                                    <textarea name="labor" id="labor" class="materialize-textarea">'.$labor.'</textarea>
                                    <label for="labor" class="active">Observaciones:</label>
                                </div>';
                if ($idEqu == 'EQU001') {
                    $string .=' <div class="input-field col l3 m12 s12 ">
                                    <input type="number" name="minutosDura" min="0" id="minutosDura" value="'.$minDura.'">
                                    <label for="minutosDura" class="active">Duración Min:</label>
                                </div>
                                <div class="input-field col l3 m12 s12">
                                    <input type="number" name="segundosDura" min="0" max="59" id="segundosDura" value="'.$segDura.'">
                                    <label for="segundosDura" class="active">Duración Seg:</label>
                                </div>';
                } else if ($idEqu == 'EQU002') {
                    $string .= '<div class="input-field col l6 m12 s12">
                                    '.$sltPlataforma.'
                                </div>';
                }
                $string .= '    <div class="input-field col l6 m12 s12">
                                    <input class="datepicker" type="text" name="txtfechEntr" id="txtfechEntr"  value="'.$fechaEntre.'" >
                                    <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente:</label>
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <a href="#" class="btn orange" style="margin-right: 30px;" onclick="guardarMetadata(\''.$idSol.'\',\'../Controllers/ctrl_terminacionProductoServicio.php\')">Guardar Metadata</a>
                                    <a href="#modalTerminarProSer" class="btn modal-trigger" data-modal="#modalTerminarProSer" '.$disabled.' onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')">Terminar producto</a>
                                </div>';
            } else if ($datos['productoOservicio'] == 'NO') {
                $consulta2 = "SELECT * 
                    FROM pys_resultservicio 
                    INNER JOIN pys_plataformas ON pys_resultservicio.idPlat = pys_plataformas.idPlat
                    INNER JOIN pys_claseproductos ON pys_resultservicio.idClProd = pys_claseproductos.idClProd
                    INNER JOIN pys_tiposproductos ON pys_resultservicio.idTProd = pys_tiposproductos.idTProd 
                    WHERE pys_resultservicio.idSol = '$idSol' AND pys_resultservicio.est = '1' AND pys_plataformas.est = '1' AND pys_claseproductos.est= '1' AND pys_tiposproductos.est = '1';";
                $resultado2 = mysqli_query($connection, $consulta2);
                $registros2 = mysqli_num_rows($resultado2);
                $datos2 = mysqli_fetch_array($resultado2);
                $plataforma         = (empty($datos2['idPlat'])) ? '' : $datos2['idPlat'];
                $sltPlataforma      = SolicitudEspecifica::selectPlataforma ($plataforma);
                $clase              = (empty($datos2['idClProd'])) ? '' : $datos2['idClProd'];
                $sltClase           = SolicitudEspecifica::selectClaseConTipo ($idServicio, $clase);
                $tipo               = (empty($datos2['idTProd'])) ? '' : $datos2['idTProd'];
                $sltTipo            = (empty($datos2['idTProd'])) ? $selectTipoVacio : SolicitudEspecifica::selectTipoProducto ($clase, $idServicio, $tipo);
                $observacion        = (empty($datos2['observacion'])) ? '' : $datos2['observacion'];
                $estudiantesImpac   = (empty($datos2['estudiantesImpac'])) ? '' : $datos2['estudiantesImpac'];
                $docentesImpac      = (empty($datos2['docentesImpac'])) ? '' : $datos2['docentesImpac'];
                $urlResultado       = (empty($datos2['urlResultado'])) ? '' : $datos2['urlResultado'];
                $string .= '    <div class="input-field col l4 m4 s12">
                                    '.$sltPlataforma.'
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    '.$sltClase.'
                                </div>
                                <div class="input-field col l4 m4 s12" id="sltModalTipo">
                                    '.$sltTipo.'
                                </div>
                                <div class="input-field col l12 m12 s12  left-align">
                                    <textarea name="labor" id="labor" class="materialize-textarea" >'.$observacion.'</textarea>
                                    <label for="labor" class="active">Descripción Servicio:</label>
                                </div>
                                <div class="input-field col l2 m12 s12">
                                    <input type="number" name="numEst" id="numEst" min = 0 value = "'.$estudiantesImpac.'">
                                    <label for="numEst" class="active">Numero de estudiantes:</label>
                                </div>
                                <div class="input-field col l2 m12 s12">
                                    <input type="number" name="numDoc" id="numDoc" min = 0 value = "'.$docentesImpac.'" > 
                                    <label for="numDoc" class="active">Numero de docentes:</label>
                                </div>
                                <div class="input-field col l6 m12 s12">
                                    <input type="text" name="url" id="url" value="'.$urlResultado.'" >
                                    <label for="url" class="active">URL store easy Conecta-TE :</label>
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <a href="#" class="btn orange" style="margin-right: 30px;" onclick="guardarMetadata(\''.$idSol.'\',\'../Controllers/ctrl_terminacionProductoServicio.php\')">Guardar Metadata</a>
                                    <a href="#modalTerminarProSer" class="btn modal-trigger" data-modal="#modalTerminarProSer" '.$disabled.' onclick="envioData(\'TER'.$idSol.'\',\'modalTerminarProSer.php\')">Terminar producto</a>
                                </div>';
            }
            $string .= '    </div>
                        </form>';
            mysqli_close($connection);
            return $string;
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
                $message = "El producto/servicio P$idSol fue cerrado correctamente.";
            } else {
                mysqli_query($connection, "ROLLBACK;");
                $message = "El producto/servicio P$idSol NO se pudo cerrar. Por favor intente nuevamente.";
            }
            $query = "SELECT pys_actualizacionproy.codProy
                FROM pys_cursosmodulos
                INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy AND pys_actualizacionproy.est = '1'
                WHERE idCM = '$idCM';";
            $result = mysqli_query($connection, $query);
            $data = mysqli_fetch_array($result);
            $proyecto = $data[0];
            $json[] = array (
                'message' => $message,
                'proyecto' => $proyecto
            );
            $jsonString = json_encode($json);
            echo $jsonString;
            mysqli_close($connection);
        }

        public static function guardarMetadata($idSol, $equipo, $nombreProducto, $sinopsis, $claseProducto, $tipoProducto, $idioma, $formato, $tipoContenido, $red, $palabrasClave, $urlServidor, $urlVimeo, $autores, $observaciones, $duracionMin, $duracionSeg, $plataforma, $fechaEntrega, $areaConocimiento, $productoServicio, $estudiantesImpac, $docentesImpac, $idServicio) {
            require('../Core/connection.php');
            include_once('../Models/mdl_solicitudEspecifica.php');
            $plataforma         = ($plataforma == '') ? "PLT009" : $plataforma;
            $nombreProducto     = mysqli_real_escape_string($connection, $nombreProducto);
            $red                = mysqli_real_escape_string($connection, $red);
            $palabrasClave      = mysqli_real_escape_string($connection, $palabrasClave);
            $fechaEntrega       = ($fechaEntrega != null) ? "'".$fechaEntrega."'" : "null";
            $urlVimeo           = mysqli_real_escape_string($connection, $urlVimeo);
            $urlServidor        = mysqli_real_escape_string($connection, $urlServidor);
            $observaciones      = mysqli_real_escape_string($connection, $observaciones);
            $usuario            = $_SESSION['usuario'];
            $idPersona          = SolicitudEspecifica::generarIdPersona($usuario);
            $duracionMin        = ($duracionMin == "") ? "null" : $duracionMin;
            $duracionSeg        = ($duracionSeg == "") ? "null" : $duracionSeg;
            $sinopsis           = mysqli_real_escape_string($connection, $sinopsis);
            $autores            = mysqli_real_escape_string($connection, $autores);
            $idioma             = ($idioma == null || $idioma == '') ? "null" : $idioma;
            $formato            = ($formato == null || $formato == '') ? "null" : $formato;
            $tipoContenido      = ($tipoContenido == null || $tipoContenido == '') ? "null" : $tipoContenido;
            $areaConocimiento   = ($areaConocimiento == null || $areaConocimiento == '' || $areaConocimiento == 'Seleccione...') ? '0' : $areaConocimiento;
            $estudiantesImpac   = ($estudiantesImpac == null || $estudiantesImpac == '') ? 0 : $estudiantesImpac;
            $estudiantesImpac   = ($docentesImpac == null || $docentesImpac == '') ? 0 : $docentesImpac;
            if ($productoServicio == 'SI') {
                /* Si el tipo de serivicio genera producto, se procede a realizar guardado/actualización en la tabla pys_productos y pys_actproductos */
                $producto           = SolicitudEspecifica::comprobraExisResultadoProductos($idSol);
                $countProd          = SolicitudEspecifica::generarCodigoProducto();
                if (!$producto) {
                    /* Si no existe el producto en el sistema, se procede a crear uno nuevo */
                    mysqli_query($connection, "BEGIN;");
                    if ($equipo == 'EQU001' || $equipo == 'EQU002') {
                        $consultaProducto       = "INSERT INTO pys_productos VALUES ('$countProd', '$idSol', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto','$nombreProducto','$red', '', $fechaEntrega, now(), '$urlVimeo','$urlServidor', '$observaciones', '', '$idPersona', '', $duracionMin, $duracionSeg, DEFAULT, '1')";
                        $consultaActProducto    = "INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto','$red', '$palabrasClave', $fechaEntrega, now(), '$urlVimeo', '$urlServidor', '$observaciones', '', '$idPersona', '', $duracionMin, $duracionSeg, DEFAULT, '$sinopsis', '$autores', '1', $idioma, $formato, $tipoContenido, $areaConocimiento)";
                    } else if ($equipo == 'EQU003') {
                        $consultaProducto       = "INSERT INTO pys_productos VALUES ('$countProd', '$idSol', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto', '$red', '', $fechaEntrega, now(), '', '$urlServidor', '$observaciones', '', '$idPersona', '', '0', '0', DEFAULT, '1');";
                        $consultaActProducto    = "INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto', '$red', '$palabrasClave', $fechaEntrega, now(), '', '$urlServidor', '$observaciones', '', '$idPersona', '', '0', '0', DEFAULT, '', '', '1', $idioma, $formato, $tipoContenido, $areaConocimiento);";
                    }
                    $resultadoProducto      = mysqli_query($connection, $consultaProducto);
                    $resultadoActProducto   = mysqli_query($connection, $consultaActProducto);
                    if ($resultadoProducto && $resultadoActProducto) {
                        echo "<script>alert('Registro guardado correctamente');</script>";
                        mysqli_query($connection, "COMMIT;");
                    } else {
                        echo "<script>alert('Se presentaron errores durante el guardado de la información. Por favor intente nuevamente.');</script>";
                        mysqli_query($connection, "ROLLBACK;");
                    }
                    echo "<h3>$consultaProducto</h3>";
                    echo "<h3>$consultaActProducto</h3>";
                    mysqli_close($connection);
                } else {
                    /* Si existe el producto, se procede a realizar la actualización */
                    mysqli_query($connection, "BEGIN;");
                    $consulta1      = "SELECT idProd FROM pys_productos WHERE idSol = '$idSol' AND est = '1';";
                    $resultado1     = mysqli_query($connection, $consulta1);
                    $datos          = mysqli_fetch_array($resultado1);
                    $countProd      = $datos['idProd'];
                    $consulta       = "UPDATE pys_actproductos SET est= 2 WHERE idProd = '$countProd' AND est = '1';";
                    $resultado      = mysqli_query($connection, $consulta);
                    if ($equipo == 'EQU001' || $equipo == 'EQU002') {
                        $consultaActProducto    = "INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto', '$red', '$palabrasClave', $fechaEntrega, now(), '$urlVimeo', '$urlServidor', '$observaciones', '', '$idPersona', '', $duracionMin, $duracionSeg, DEFAULT, '$sinopsis', '$autores', '1', $idioma, $formato, $tipoContenido, '$areaConocimiento');";
                    } else if ($idEqu == 'EQU003') {
                        $consultaActProducto    = "INSERT INTO pys_actproductos VALUES (NULL, '$countProd', 'TRC012', '$plataforma', '$claseProducto', '$tipoProducto', '$nombreProducto', '$red', '$palabrasClave', $fechaEntrega, now(), '', '$urlServidor', '$observaciones', '', '$idPersona', '', '0', '0', DEFAULT, '', '', '1', $idioma, $formato, $tipoContenido, $areaConocimiento);";
                    }
                    $resultadoActProducto   = mysqli_query($connection, $consultaActProducto);
                    if ($resultadoActProducto) {
                        echo "<script>alert('Registro actualizado correctamente');</script>";
                        mysqli_query($connection, "COMMIT;");
                    } else {
                        echo "<script>alert('Se presentaron errores durante la actualización de la información. Por favor intente nuevamente.');</script>";
                        mysqli_query($connection, "ROLLBACK;");
                    }
                }
            } else {
                /* Si no genera producto entonces se procede a realizar guardado/actualización en la tabla pys_resultservicio */
                $servicio = SolicitudEspecifica::comprobraExisResultadoServicio($idSol);
                if (!$servicio) {
                    /* Si no existe el registro en el sistema, se procede a registrar la información en la tabla pys_resultservicio */
                    $consulta = "SELECT COUNT(idResultServ), MAX(idResultServ) FROM pys_resultservicio;";
                    $resultado = mysqli_query($connection, $consulta);
                    while ($datos=mysqli_fetch_array($resultado)) {
                        $count = $datos[0];
                        $max = $datos[1];
                    }
                    $countResServ       = ($count == 0) ? "R00001" : 'R'.substr((substr($max, 3)+100001), 1);
                    $estudiantesImpac   = ($estudiantesImpac == "") ? 0 : $estudiantesImpac;
                    $docentesImpac      = ($docentesImpac == "") ? 0 : $docentesImpac;
                    $tiempoTotal        = SolicitudEspecifica::totalTiempo ($idSol);
                    $hora               = $tiempoTotal[0];
                    $min                = $tiempoTotal[1];
                    $consulta2 = "INSERT INTO pys_resultservicio VALUES ('$countResServ', '$idSol', '$plataforma', '$idServicio', '$claseProducto', '$tipoProducto', '$observaciones', '$estudiantesImpac', '$docentesImpac', 0, '$urlServidor', '', '$idPersona', '$hora', '$min', now(), '1')";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    if ($resultado && $resultado2) {
                        echo '<script>alert("Se guardó correctamente la información.")</script>';
                    } else {
                        echo '<script>alert("Se presentó un error y el registro no pudo ser guardado.")</script>';
                    }
                } else {
                    /* Si ya existe un registro, se procede con la actualización del mismo en la tabla pys_resultservicio */
                    $consulta = "UPDATE pys_resultservicio SET idPlat = '$plataforma',  idSer ='$idServicio', idClProd= '$claseProducto', idTProd ='$tipoProducto', observacion= '$observaciones', estudiantesImpac = '$estudiantesImpac', docentesImpac = '$docentesImpac', urlResultado = '$urlServidor', idResponRegistro = '$idPersona', fechaCreacion = now() WHERE idSol = '$idSol' AND est = '1';";
                    $resultado = mysqli_query($connection, $consulta);
                    if ($resultado) {
                        echo '<script>alert("Se actualizó correctamente la información.")</script>';
                    } else {
                        echo "<script> alert ('Ocurrió un error al intentar actualizar el registro');</script>";
                    }
                }
            }
            
            echo self::informacionProdSer ($idSol);
        }
        
    }
?>
