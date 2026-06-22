<div class="card" style="max-width: 600px; margin: 30px auto;">
    <h3 style="color: var(--color-danger); margin-bottom: var(--spacing-lg);">!ADVERTENCIA: IMPORTAR UNA BASE DE DATOS SUSTITUIRA LOS DATOS ACTUALES BORRANDOLOS PARA SIEMPRE!</h3>

    <div style="display: flex; gap: var(--spacing-sm); align-items: center; margin-bottom: var(--spacing-md);">
        <button onclick="history.back()" class="btn btn--danger">Cancelar</button>
        <input type="file" id="js-archivoDb" accept=".db" class="input" style="max-width: 300px;">
        <button onclick="subirArchivo()" class="btn btn--primary">Importar base de dato</button>
    </div>

    <p id="js-mensajeEstado" class="etiqueta"></p>
</div>

<script>
    async function subirArchivo() {
        const fileInput = document.getElementById('js-archivoDb');
        const mensaje = document.getElementById('js-mensajeEstado');

        if (fileInput.files.length === 0) {
            mensaje.innerText = "Por favor, selecciona un archivo primero.";
            return;
        }

        const archivo = fileInput.files[0];
        const formData = new FormData();
        formData.append('database', archivo);

        mensaje.innerText = "Subiendo y reemplazando...";

        try {
            const respuesta = await fetch('controllers/actions/importarDb.php', {
                method: 'POST',
                body: formData
            });
            const resultado = await respuesta.json();
            mensaje.innerText = resultado.message;
        } catch (error) {
            console.error(error);
            mensaje.innerText = "Error en la conexión con el servidor.";
        }
    }
</script>
