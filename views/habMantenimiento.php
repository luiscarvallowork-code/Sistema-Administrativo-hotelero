<?php

if (isset($_GET["error"])) {
    $error = 1;
    if ($_GET["error"] == "sinHabitacionCambio") {
        $errorText = "ADVERTENCIA: no existen habitaciones disponible de
        ese tipo para cambiar a los huespedes";
    }
} else {
    $error = 0;
    $errorText = "";
}

$listaHabitaciones = myDB::obtenerListaDatosHabitacionesDisponiblesMantenimiento();

tools::mostrarVariableConsolaJs($listaHabitaciones);
?>

<div class="formulario-mantenimiento">
    <h1 class="formulario-mantenimiento__titulo">Ingreso de habitacion para Reparaciones</h1>

    <div class="card">
        <form method="POST" action="controllers\formularioControllers.php">
            <input type="date" id="maint-date" name="fechaInicio" hidden>

            <div class="grupo-campo">
                <label class="etiqueta" for="maint-room">Seleccionar Habitación</label>
                <select id="maint-room" name="idHabitacion" class="select" required value="null">
                    <?php foreach ($listaHabitaciones as $habitacion) { ?>
                        <option value="<?= $habitacion["id"] ?>" selected><?= $habitacion["nombre"] ?></option>
                    <?php } ?>
                    <option selected value="null" disabled>Elija una habitacion</option>
                </select>
            </div>

            <div class="grupo-campo">
                <label class="etiqueta" for="maint-desc">Descripción del Problema</label>
                <textarea id="maint-desc" name="descripcion" class="textarea" placeholder="Ej: Fuga de agua en el baño..."></textarea>
            </div>

            <button type="submit" name="submit" value="submit_ingresarHabitacionMantenimiento" class="btn btn--primary">
                Iniciar Reparacion de habitación
            </button>
        </form>
    </div>
</div>

<script>
    const error = <?= json_encode($error) ?>;
    if (error == 1) {
        alert(<?= json_encode($errorText) ?>);
    }
</script>
