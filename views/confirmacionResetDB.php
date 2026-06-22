<div class="contenedor-centrado">
    <div class="card" style="max-width: 600px; text-align: center;">
        <h1 style="font-size: var(--font-size-lg); margin-bottom: var(--spacing-md); color: var(--color-danger);">¿Está seguro de reiniciar la base de datos a su estado de fábrica?</h1>
        <p style="color: var(--color-danger); font-weight: 700; margin-bottom: var(--spacing-lg);">¡ADVERTENCIA: SE BORRARÁN PARA SIEMPRE TODOS LOS DATOS. SE SUGIERE CREAR UN RESPALDO ANTES!</p>

        <div style="display: flex; gap: var(--spacing-sm); justify-content: center; flex-wrap: wrap;">
            <button class="btn btn--danger" onclick="respaldoReinicio();">Crear respaldo y reiniciar</button>
            <button class="btn btn--warning" onclick="reinicio();">Reiniciar base de datos</button>
            <button class="btn btn--ghost" onclick="history.back()">Cancelar</button>
        </div>
    </div>
</div>

<script>
    function respaldoReinicio() {
        window.location.href = "controllers/actions/resetDb.php?code=1";
    }
    function reinicio() {
        window.location.href = "controllers/actions/resetDb.php";
    }
</script>
