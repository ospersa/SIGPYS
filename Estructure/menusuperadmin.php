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
        <a href="agenda.php">Mi Agenda</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>

    <li>
        <a class="dropdown-trigger" href="#!" data-target="gestores">Gestor/Asesor RED
            <i class="material-icons right">arrow_drop_down</i>
        </a>
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
        <a class="dropdown-trigger" href="#!" data-target="cotizador">Presupuestos
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
        <a href="inventario.php">Inventario</a>
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

<ul id="gestores" class="dropdown-content teal darken-1">
    <li>
        <a href="terminacionServiciosProductos.php">Terminación P/S</a>
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
        <a href="infEjecuciones.php">Ejecuciones</a>
    </li>
    <li>
        <a href="infEjecuProdSer.php">Ejecuciones Productos/Servicios</a>
    </li>
    <li>
        <a href="infNomina.php">Nómina</a>
    </li>
    <li>
        <a href="infPersonas.php">Personas</a>
    </li>
    <li>
        <a href="infPlaneacion.php">Planeación</a>
    </li>
    <li>
        <a href="infInventario.php">Inventario</a>
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
        <a href="infProyectos.php">Proyectos</a>
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
        <a href="resultadoDiseno.php">Productos de diseño</a>
    </li>
    <li>
        <a href="resultadoRealizacion.php">Productos de realización</a>
    </li>
    <li>
        <a href="ResultadoSoporte.php">Productos de soporte</a>
    </li>
    <li>
        <a href="claProductos.php">Clases de productos</a>
    </li>
    <li>
        <a href="resultadoServicio">Resultados de servicio</a>
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
        <a href="eliminadosConvocatoria.php">Convocatorias</a>
    </li>
    <li>
        <a href="eliminadosFases.php">Fases</a>
    </li>
    <li>
        <a href="eliminadosFrentes.php">Frentes</a>
    </li>
    <li>
        <a href="eliminadosPlataformas.php">Plataformas</a>
    </li>
    <li>
        <a href="eliminadosRoles.php">Roles</a>
    </li>
    <li>
        <a href="eliminadosEntidades.php">Entidades</a>
    </li>
    <li>
        <a href="eliminadosFacultades.php">Facultades</a>
    </li>
    <li>
        <a href="eliminadosDepartamentos.php">Departamentos</a>
    </li>

    <li>
        <a href="eliminadosCargos.php">Cargos</a>
    </li>
    <li>
        <a href="eliminadosEquipos.php">Equipos</a>
    </li>
    <li>
        <a href="eliminadosProyectos.php">Proyectos</a>
    </li>
    <li>
        <a href="eliminadosSalarios.php">Salarios</a>
    </li>
    <li>
        <a href="eliminadosServicios.php">Servicios</a>
    </li>
    <li>
        <a href="eliminadosUsuarios.php">Personas</a>
    </li>
    <li>
        <a href="eliminadosPassword.php">Password</a>
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
    <li>
        <a href="agendaAdmin.php">Todas las Agendas</a>
    </li>
</ul>

<ul id="cotizador" class="dropdown-content teal darken-1">
    <li>
        <a href="busCotizacion.php">Buscar Presupuesto</a>
    </li>
    <li>
        <a href="cotizacion.php?cod=1">Nuevo Presupuesto</a>
    </li>
</ul>