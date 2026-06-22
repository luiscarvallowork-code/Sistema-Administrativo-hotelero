<?php

$datosClientes=myDB::obtenerDatosClientes($_GET["id"]);
tools::mostrarVariableConsolaJs($datosClientes);

?>

<div class="editar-cliente">
    <h1 class="editar-cliente__titulo">Editar Datos del Cliente</h1>

    <div class="card">
        <form action="controllers/formularioControllers.php" method="POST">
            <input type="number" name="id" value="<?= $datosClientes["id"] ?>">
            <div class="grupo-campo">
                <label class="etiqueta">Nombre Completo</label>
                <input type="text" name="nombre" class="input" value="<?= $datosClientes["nombre"] ?>">
            </div>

            <div class="grupo-campo">
                <label class="etiqueta">Cédula / ID</label>
                <input type="text" name="cedula" class="input" value="<?= $datosClientes["ci"] ?>">
            </div>

            <div class="grupo-campo">
                <label class="etiqueta">Teléfono</label>
                <input type="tel" name="telefono" class="input" value="<?= $datosClientes["numeroTelefono"] ?>">
            </div>

            <div class="grupo-campo">
                <label class="etiqueta">Ciudad</label>
                <input type="text" name="ciudad" class="input" value="<?= $datosClientes["ciudad"] ?>">
            </div>

            <div class="grupo-campo">
                <label class="etiqueta">Compañía</label>
                <input type="text" name="empresa" class="input" value="<?= $datosClientes["empresa"] ?>">
            </div>

            <div class="editar-cliente__acciones">
                <button type="submit" name="submit" value="submit_actualizarDatosCliente" class="btn btn--primary">Actualizar Datos</button>
                <a href="controllers/router.php?code=configuraciones" class="btn btn--ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>
