<?php
$paginaActual = $_GET['page'] ?? '';
$urlBase = "index.php?page=";

$nombreHolel="Hostal Canaima Suit";

function enlaceActivo($pagina, $actual) {
    return $pagina === $actual ? ' barra-lateral__enlace--activo' : '';
}
function submenuAbierto($paginas, $actual) {
    return in_array($actual, $paginas) ? ' barra-lateral__submenu--abierto' : '';
}
function flechaAbierto($paginas, $actual) {
    return in_array($actual, $paginas) ? ' barra-lateral__flecha--abierto' : '';
}
?>

<aside class="barra-lateral">
    <div class="barra-lateral__logo">
        <h1>Sistema Hotelero</h1>
        <span>Panel de gestión</span>
    </div>

    <button class="barra-lateral__toggle js-toggle-sidebar" title="Colapsar menú">◀</button>

    <nav class="barra-lateral__nav">
        <ul class="barra-lateral__lista">
            <li>
                <a href="<?= $urlBase ?>home" class="barra-lateral__enlace<?= enlaceActivo('home', $paginaActual) ?>">
                    <span class="barra-lateral__icono">🏠</span>
                    <span class="barra-lateral__texto">Inicio</span>
                </a>
            </li>
            <li>
                <a href="<?= $urlBase ?>ingresoHabitacion" class="barra-lateral__enlace<?= enlaceActivo('ingresoHabitacion', $paginaActual) ?>">
                    <span class="barra-lateral__icono">➕</span>
                    <span class="barra-lateral__texto">Ingreso Habitación</span>
                </a>
            </li>
            <li>
                <a href="<?= $urlBase ?>estadoHabitaciones" class="barra-lateral__enlace<?= enlaceActivo('estadoHabitaciones', $paginaActual) ?>">
                    <span class="barra-lateral__icono">🗺️</span>
                    <span class="barra-lateral__texto">Estado Habitaciones</span>
                </a>
            </li>

            <li>
                <a href="#" class="barra-lateral__enlace js-toggle-submenu" data-submenu="submenu-bd">
                    <span class="barra-lateral__icono">🗄️</span>
                    <span class="barra-lateral__texto">Base de Datos</span>
                    <span class="barra-lateral__flecha<?= flechaAbierto(['listaReservacion', 'listaIngresosHabitacion', 'listaClientes', 'listaHabitaciones', 'listaPagos', 'listaMantenimiento', 'listaTasas'], $paginaActual) ?>">▾</span>
                </a>
                <ul class="barra-lateral__submenu<?= submenuAbierto(['listaReservacion', 'listaIngresosHabitacion', 'listaClientes', 'listaHabitaciones', 'listaPagos', 'listaMantenimiento', 'listaTasas'], $paginaActual) ?>" id="submenu-bd">
                    <li><a href="<?= $urlBase ?>listaReservacion" class="barra-lateral__enlace<?= enlaceActivo('listaReservacion', $paginaActual) ?>">Historial de Reservaciones</a></li>
                    <li><a href="<?= $urlBase ?>listaIngresosHabitacion" class="barra-lateral__enlace<?= enlaceActivo('listaIngresosHabitacion', $paginaActual) ?>">Historial de Rentas</a></li>
                    <li><a href="<?= $urlBase ?>listaClientes" class="barra-lateral__enlace<?= enlaceActivo('listaClientes', $paginaActual) ?>">Lista de Clientes</a></li>
                    <li><a href="<?= $urlBase ?>listaHabitaciones" class="barra-lateral__enlace<?= enlaceActivo('listaHabitaciones', $paginaActual) ?>">Lista de Habitaciones</a></li>
                    <li><a href="<?= $urlBase ?>listaPagos" class="barra-lateral__enlace<?= enlaceActivo('listaPagos', $paginaActual) ?>">Historial de Pagos</a></li>
                    <li><a href="<?= $urlBase ?>listaMantenimiento" class="barra-lateral__enlace<?= enlaceActivo('listaMantenimiento', $paginaActual) ?>">Lista de Reparaciones</a></li>
                    <li><a href="<?= $urlBase ?>listaTasas" class="barra-lateral__enlace<?= enlaceActivo('listaTasas', $paginaActual) ?>">Historial tasa cambiaria</a></li>
                </ul>
            </li>

            <li>
                <a href="<?= $urlBase ?>informe" class="barra-lateral__enlace<?= enlaceActivo('informe', $paginaActual) ?>">
                    <span class="barra-lateral__icono">📄</span>
                    <span class="barra-lateral__texto">Informe</span>
                </a>
            </li>
            <li>
                <a href="<?= $urlBase ?>configuraciones" class="barra-lateral__enlace<?= enlaceActivo('configuraciones', $paginaActual) ?>">
                    <span class="barra-lateral__icono">⚙️</span>
                    <span class="barra-lateral__texto">Configuraciones</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="barra-lateral__footer">
        <?= $nombreHolel ?>
    </div>
</aside>

<script>
    document.querySelectorAll('.js-toggle-submenu').forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            var submenuId = this.dataset.submenu;
            var submenu = document.getElementById(submenuId);
            if (submenu) {
                submenu.classList.toggle('barra-lateral__submenu--abierto');
            }
            var flecha = this.querySelector('.barra-lateral__flecha');
            if (flecha) {
                flecha.classList.toggle('barra-lateral__flecha--abierto');
            }
        });
    });

    // Sidebar colapsable
    (function() {
        var sidebar = document.querySelector('.barra-lateral');
        var btn = document.querySelector('.js-toggle-sidebar');
        if (!btn) return;
        var saved = localStorage.getItem('sidebar-colapsada');
        if (saved === 'true') {
            sidebar.classList.add('barra-lateral--colapsada');
            btn.textContent = '▶';
            btn.title = 'Expandir menú';
        }
        btn.addEventListener('click', function() {
            sidebar.classList.toggle('barra-lateral--colapsada');
            var colapsada = sidebar.classList.contains('barra-lateral--colapsada');
            btn.textContent = colapsada ? '▶' : '◀';
            btn.title = colapsada ? 'Expandir menú' : 'Colapsar menú';
            localStorage.setItem('sidebar-colapsada', colapsada);
        });
    })();
</script>
