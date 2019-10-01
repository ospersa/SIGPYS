<a href="#" data-target="slide-out" class="teal white-text sidenav-trigger show-on-large menu">
    <i class="material-icons">menu</i>
</a>

<ul id="slide-out" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background teal darken-1"></div>
            <span class="white-text name">
                <?php echo $usserName; ?>
            </span>
            <span class="email">
                <?php echo $salir; ?>
            </span>
        </div>
    </li>
    <li>
        <a href="home.php">Home</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a href="misproductosservicios.php">Mis Productos/Servicios</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="colciencias">Colciencias
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="cotizador">Cotizaciones
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="eliminados">Eliminados
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="entidades">Entidades
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="informes">Informes
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a href="#!">Inventario</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="nuevo">Nuevo
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="planeacion">Planeación
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="productos">Productos
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="proyectos">Proyectos
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="solicitudes">Solicitudes
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li>
        <a class="dropdown-trigger" href="#!" data-target="usuarios">Usuarios
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
</ul>



<ul id="colciencias" class="dropdown-content teal darken-1">
    <li>
        <a href="#!">Productos en colciencias</a>
    </li>
    <li>
        <a href="colcienciasProy.php">Proyectos en colciencias</a>
    </li>
</ul>
<ul id="entidades" class="dropdown-content teal darken-1">
    <li>
        <a href="entidad.php">Nueva entidad</a>
    </li>
    <li>
        <a href="facultad.php">Nueva facultad</a>
    </li>
    <li>
        <a href="departamento.php">Nuevo departamento</a>
    </li>
</ul>
<ul id="informes" class="dropdown-content teal darken-1">
    <li>
        <a href="#!">Ejecuciones</a>
    </li>
    <li>
        <a href="#!">Ejecuciones Productos/Servicios</a>
    </li>
    <li>
        <a href="infNomina.php">Nómina</a>
    </li>
    <li>
        <a href="#!">Personas</a>
    </li>
    <li>
        <a href="infPlaneacion.php">Planeación</a>
    </li>
    <li>
        <a href="infProductosConSaldo.php">Productos/Servicios - Ejecución</a>
    </li>
    <li>
        <a href="infProductosCelulas.php">Productos/Servicios - Células</a>
    </li>
    <li>
        <a href="infTiempos.php">Productos/Servicios - Tiempos</a>
    </li>
    <!--<li>
        <a href="#!">P/S con Saldo Negativo</a>
    </li>-->
    <li>
        <a href="#!">Proyectos</a>
    </li>
    <li>
        <a href="#!">Supervisión</a>
    </li>
    <li>
        <a href="infMisTiempos.php">Mis Tiempos</a>
    </li>
</ul>
<ul id="productos" class="dropdown-content teal darken-1">
    <li>
        <a href="#!">Productos de diseño</a>
    </li>
    <li>
        <a href="#!">Productos de realización</a>
    </li>
    <li>
        <a href="#!">Productos de soporte</a>
    </li>
    <li>
        <a href="claProductos.php">Clases de productos</a>
    </li>
    <li>
        <a href="#!">Resultados de servicio</a>
    </li>
    <li>
        <a href="servicios.php">Servicios</a>
    </li>
    <li>
        <a href="tipProductos.php">Tipos de productos </a>
    </li>
</ul>
<ul id="proyectos" class="dropdown-content teal darken-1">
    <li>
        <a href="proyecto.php">Nuevo proyecto</a>
    </li>
    <li>
        <a href="estProyecto.php">Estados de proyectos</a>
    </li>
    <li>
        <a href="etaProyecto.php">Etapa de proyectos</a>
    </li>
    <li>
        <a href="tipProyecto.php">Tipo de proyectos</a>
    </li>
</ul>
<ul id="solicitudes" class="dropdown-content teal darken-1">
    <li>
        <a href="estSolicitud.php">Estados de solicitud</a>
    </li>
    <li>
        <a href="solicitudEspecifica.php">Productos/Servicios</a>
    </li>
    <li>
        <a href="solicitudInicial.php">Solicitud Inicial</a>
    </li>
    <li>
        <a href="tipSolicitud.php">Tipo de solicitud</a>
    </li>
</ul>
<ul id="usuarios" class="dropdown-content teal darken-1">
    <li>
        <a href="cargo.php">Cargos</a>
    </li>
    <li>
        <a href="equipo.php">Equipos</a>
    </li>
    <li>
        <a href="password.php">Login</a>
    </li>
    <li>
        <a href="usuario.php">Nuevo usuario</a>
    </li>
    <li>
        <a href="perfil.php">Perfiles</a>
    </li>
    <li>
        <a href="salarios.php">Salarios</a>
    </li>
</ul>
<ul id="nuevo" class="dropdown-content teal darken-1">
    <li>
        <a href="celula.php">Célula</a>
    </li>
    <li>
        <a href="centroCosto.php">Centro de Costos</a>
    </li>
    <li>
        <a href="convocatoria.php">Convocatorias</a>
    </li>
    <li>
        <a href="elementosPep.php">Elemento PEP</a>
    </li>
    <li>
        <a href="fases.php">Fases</a>
    </li>
    <li>
        <a href="frente.php">Frentes</a>
    </li>
    <li>
        <a href="fuenteFinanciamiento.php">Fuente Financiación</a>
    </li>
    <li>
        <a href="plataforma.php">Plataformas</a>
    </li>
    <li>
        <a href="rol.php">Roles</a>
    </li>
</ul>


<ul id="eliminados" class="dropdown-content teal darken-1">
    <li>
        <a href="#!">Convocatorias</a>
    </li>
    <li>
        <a href="#!">Fases</a>
    </li>
    <li>
        <a href="#!">Frentes</a>
    </li>
    <li>
        <a href="#!">Plataformas</a>
    </li>
    <li>
        <a href="#!">Roles</a>
    </li>
</ul>

<ul id="planeacion" class="dropdown-content teal darken-1">
    <li>
        <a href="periodo.php">Periodos</a>
    </li>
    <li>
        <a href="dedicacion.php">Dedicaciones</a>
    </li>
    <li>
        <a href="planeacion.php">Planear Tiempos</a>
    </li>
</ul>

<ul id="cotizador" class="dropdown-content teal darken-1">
    <li>
        <a href="busCotizacion.php">Buscar Cotización</a>
    </li>
    <li>
        <a href="cotizacion.php?cod=1">Nueva Cotización</a>
    </li>
</ul>