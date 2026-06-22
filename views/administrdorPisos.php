<?php

$pisos = myDB::obtenerPisos();


tools::mostrarVariableConsolaJs($pisos);
?>

<div class="admin-pisos">
    <div class="admin-pisos__alternador">
        <span id="js-labelCrear" class="admin-pisos__etiqueta-activa">Crear Piso</span>
        <label class="admin-pisos__interruptor">
            <input type="checkbox" id="js-toggleModo">
            <span class="admin-pisos__deslizador"></span>
        </label>
        <span id="js-labelActualizar">Actualizar Piso</span>
    </div>

    <form id="js-formCrear" class="admin-pisos__formulario" action="controllers/formularioControllers.php" method="POST">
        <h2>Nuevo Piso</h2>
        <input type="hidden" name="accion" value="crear">

        <div class="grupo-campo">
            <label for="js-createNombre" class="etiqueta">Nombre del Piso</label>
            <input type="text" id="js-createNombre" name="nombre" class="input" required placeholder="Ej: Piso 1 o Planta Baja">
        </div>

        <div class="grupo-campo">
            <label for="js-createDescripcion" class="etiqueta">Descripción</label>
            <textarea id="js-createDescripcion" name="descripcion" class="textarea" placeholder="Opcional: Detalles del piso..."></textarea>
        </div>

        <button name="submit" type="submit" class="btn btn--primary" value="submit_ingresarPiso" style="margin-top: 10px;">Guardar Piso</button>
    </form>

    <form id="js-formActualizar" class="admin-pisos__formulario template--oculto" action="controllers/formularioControllers.php" method="POST">
        <h2>Modificar Piso</h2>
        <input type="hidden" name="accion" value="actualizar">

        <div class="grupo-campo">
            <label for="js-selectPiso" class="etiqueta">Selecciona el Piso a editar</label>
            <select id="js-selectPiso" name="id_piso" class="select" required>
                <option value="" disabled selected>-- Seleccione un piso --</option>
                <?php foreach ($pisos as $piso) { ?>
                    <option value="<?= $piso["id"] ?>"><?= $piso["nombre"] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="grupo-campo">
            <label for="js-updateNombre" class="etiqueta">Nuevo Nombre</label>
            <input type="text" id="js-updateNombre" name="nombre" class="input" required>
        </div>

        <div class="grupo-campo">
            <label for="js-updateDescripcion" class="etiqueta">Nueva Descripción</label>
            <textarea id="js-updateDescripcion" name="descripcion" class="textarea"></textarea>
        </div>

        <button name="submit" type="submit" class="btn btn--primary" value="submit_actualizarDatosPiso">Actualizar Cambios</button>
    </form>
</div>

<script>
    const modeToggle = document.getElementById('js-toggleModo');
    const formCrear = document.getElementById('js-formCrear');
    const formActualizar = document.getElementById('js-formActualizar');
    const labelCrear = document.getElementById('js-labelCrear');
    const labelActualizar = document.getElementById('js-labelActualizar');

    modeToggle.addEventListener('change', function() {
        if (this.checked) {
            formCrear.classList.add('template--oculto');
            formActualizar.classList.remove('template--oculto');
            labelActualizar.classList.add('admin-pisos__etiqueta-activa');
            labelCrear.classList.remove('admin-pisos__etiqueta-activa');
        } else {
            formActualizar.classList.add('template--oculto');
            formCrear.classList.remove('template--oculto');
            labelCrear.classList.add('admin-pisos__etiqueta-activa');
            labelActualizar.classList.remove('admin-pisos__etiqueta-activa');
        }
    });

    const listaPisos = <?php echo json_encode(array_column($pisos, null, 'id')); ?>;

    const selectPiso = document.getElementById('js-selectPiso');
    const inputNombre = document.getElementById('js-updateNombre');
    const txtDescripcion = document.getElementById('js-updateDescripcion');

    selectPiso.addEventListener('change', function() {
        const idPisoSeleccionado = this.value;
        if (listaPisos[idPisoSeleccionado]) {
            const piso = listaPisos[idPisoSeleccionado];
            inputNombre.value = piso.nombre;
            txtDescripcion.value = piso.descripcion;
        } else {
            inputNombre.value = '';
            txtDescripcion.value = '';
        }
    });
</script>
