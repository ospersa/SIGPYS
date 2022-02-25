<?php
require('../Controllers/ctrl_restriccionMenu.php');
?>
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
        <a href="infMisTiempos.php">Mis Tiempos</a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <?php
    if ($validar == true) {
        echo '
    <li>
        <a class="dropdown-trigger" href="#!" data-target="gestores">Gestor/Asesor RED
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>';
    }
    ?>

    <!--
    <li>
        <a class="dropdown-trigger" href="#!" data-target="colciencias">Colciencias
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
    </li> -->
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
    <!--
    <li>
        <a class="dropdown-trigger" href="#!" data-target="nuevo">Nuevo
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    -->
    <!--  <li>
        <a class="dropdown-trigger" href="#!" data-target="planeacion">Planeación
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li> 
    
    <li>
        <div class="divider"></div>
    </li>-->
    <li>
        <a class="dropdown-trigger" href="#!" data-target="productos">Productos
            <i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <!--
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
-->
    <?php
    if ($validar == true) {
        echo '<ul id="gestores" class="dropdown-content teal darken-1">
        <li>
            <a href="terminacionServiciosProductos.php">Terminación P/S</a>
        </li>
        <li>
            <a href="agendaAdmin.php">Todas las Agendas</a>
        </li>
        <li>
            <a href="solicitudEspecifica.php">Productos/Servicios</a>
        </li>
        <li>
            <a href="solicitudInicial.php">Solicitud inicial</a>
        </li>
        <li>
            <a href="proycontdigital.php">Mis Proyectos de contenido digital</a>
        </li>
    </ul>';
    }
    ?>

    <ul id="colciencias" class="dropdown-content teal darken-1">
        <li>
            <a href="#!">Productos en colciencias</a>
        </li>
        <li>
            <a href="#!">Proyectos en colciencias</a>
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
        <!-- <li>
        <a href="#!">Ejecuciones</a>
    </li>
    <li>
        <a href="#!">Ejecuciones Productos/Servicios</a>
    </li>
    <li>
        <a href="#!">Personas</a>
    </li>-->
        <li>
            <a href="infPlaneacion.php">Planeación</a>
        </li>
        <li>
            <a href="infProductosCelulas.php">Ejecución por célula</a>
        </li>
        <!--<li>
        <a href="#!">Productos/Servicios</a>
    </li>
    <li>
        <a href="#!">Proyectos</a>
    </li>
    <li>
        <a href="#!">Supervisión</a>
    </li>-->
        <li>
            <a href="infNotasTiempos.php">Proyecto/Producto - Notas</a>
        </li>
        <?php if ($validar): ?>
        <li>
            <a href="infSeguiminetoEstados.php">Seguimiento Estados/Metadata</a>
        </li>
        <?php endif; ?>
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
        <!-- <li>
        <a href="#!">Clases de productos</a>
    </li>
    <li>
        <a href="#!" >Resultados de servicio</a>
    </li> -->
        <li>
            <a href="resultadoServicio.php">Servicios</a>
        </li>
        <!-- <li>
        <a href="#!">Tipos de productos </a>
    </li> -->
    </ul>
    <ul id="proyectos" class="dropdown-content teal darken-1">
        <li>
            <a href="#!">Nuevo proyecto</a>
        </li>
        <li>
            <a href="#!">Estados de proyectos</a>
        </li>
        <li>
            <a href="#!">Etapa de proyectos</a>
        </li>
        <li>
            <a href="#!">Tipo de proyectos</a>
        </li>
    </ul>
    <ul id="solicitudes" class="dropdown-content teal darken-1">
        <li>
            <a href="#!">Estados de solicitud</a>
        </li>
        <li>
            <a href="#!">Nueva solicitud inicial</a>
        </li>
        <li>
            <a href="#!">Producto/Servicio</a>
        </li>
        <li>
            <a href="#!">Tipo de solicitud</a>
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
            <a href="#!">Nuevo usuario</a>
        </li>
        <li>
            <a href="perfil.php">Perfiles</a>
        </li>
        <li>
            <a href="#!">Salarios</a>
        </li>
    </ul>
    <ul id="nuevo" class="dropdown-content teal darken-1">
        <li>
            <a href="convocatoria.php">Convocatorias</a>
        </li>
        <li>
            <a href="fases.php">Fases</a>
        </li>
        <li>
            <a href="frente.php">Frentes</a>
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

    <!-- <ul id="planeacion" class="dropdown-content teal darken-1">
    <li>
        <a href="periodo.php">Periodos</a>
    </li>
    <li>
        <a href="dedicacion.php">Dedicaciones</a>
    </li>
    <li>
        <a href="planeacion.php">Planear Tiempos</a>
    </li>
</ul> -->