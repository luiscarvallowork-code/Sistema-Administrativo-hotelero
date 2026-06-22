<h1 class="menu-configuracion__titulo">Configuraciones</h1>

<div class="menu-configuracion__grilla">

    <a href="controllers/router.php?code=habMantenimiento&id=null" class="menu-configuracion__item menu-configuracion__item--mantenimiento">
        <div class="menu-configuracion__icono">🛠️</div>
        <span class="menu-configuracion__texto">Habitaciones en Reparacion</span>
    </a>

    <a href="controllers/router.php?code=actualizarPrecios" class="menu-configuracion__item menu-configuracion__item--precios">
        <div class="menu-configuracion__icono">💰</div>
        <span class="menu-configuracion__texto">Actualizar Precios</span>
        <span class="menu-configuracion__subtitulo">Cambiar tarifas de habitaciones</span>
    </a>

    <?php
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
        <a href="controllers/router.php?code=baseDatos" class="menu-configuracion__item menu-configuracion__item--mantenimiento">
            <div class="menu-configuracion__icono">🛄</div>
            <span class="menu-configuracion__texto">Administrador base de datos</span>
            <span class="menu-configuracion__subtitulo">!!</span>
        </a>

        <a href="controllers/router.php?code=administrdorPisos" class="menu-configuracion__item menu-configuracion__item--mantenimiento">
            <div class="menu-configuracion__icono">🧱</div>
            <span class="menu-configuracion__texto">Administrar pisos</span>
            <span class="menu-configuracion__subtitulo">!!</span>
        </a>

        <a href="controllers/router.php?code=ingresarNuevaHabitacion" class="menu-configuracion__item menu-configuracion__item--mantenimiento">
            <div class="menu-configuracion__icono">🛌</div>
            <span class="menu-configuracion__texto">Registrar Habitaciones Nuevas</span>
            <span class="menu-configuracion__subtitulo"></span>
        </a>
    <?php } ?>

    <a href="controllers/router.php?code=login" class="menu-configuracion__item menu-configuracion__item--mantenimiento">
        <div class="menu-configuracion__icono">👤</div>
        <span class="menu-configuracion__texto">Permisos de Administrador</span>
    </a>
</div>
