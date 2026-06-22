<h1 class="lista-generica__titulo">Administrador base de datos</h1>

<div class="card" style="max-width: 500px;">
    <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
        <button class="btn btn--primary" onclick="exportarBaseDatos()">Crear copia base de datos</button>
        <button class="btn btn--danger" onclick="reiniciarBaseDatos()">Resetear base de datos</button>
        <button class="btn btn--ghost" onclick="importarBaseDatos()">Importar base de datos</button>
    </div>
</div>

<script>
    function exportarBaseDatos() {
        window.location.href = "controllers/actions/respaldoDb.php";
    }

    function reiniciarBaseDatos() {
        window.location.href = "controllers/router.php?code=confirmacionResetDB";
    }

    function importarBaseDatos() {
        window.location.href = "controllers/router.php?code=importarBaseDatos";
    }
</script>
