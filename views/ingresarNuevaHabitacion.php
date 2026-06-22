<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);
$listaPosicionesPisos = myDB::obtenerPosicionesHabitacionesTodosPisos();
$listaTiposHabitaciones = myDB::obtenerTiposHabitaciones();
$pisos = myDB::obtenerPisos();

$multipisos = count($pisos) > 1;

$jsonDatos = json_encode($listaPosiciones);

if (isset($_GET["piso"])) {
    foreach ($listaPosicionesPisos as $piso) {
        if ($piso["id"] == $_GET["piso"])  $listaPosicionesSeleccionada = $piso;
    }
} else {
    $listaPosicionesSeleccionada = $listaPosicionesPisos[0];
}

$limiteInferior = $listaPosicionesPisos[0]["id"];
$limiteSuperio = 0;
$idPisoActual = $listaPosicionesSeleccionada["id"];

foreach ($listaPosicionesPisos as $piso) {
    $limiteSuperio = $piso["id"];
}
?>

<div class="configuracion-habitacion">
    <header>
        <h1 class="configuracion-habitacion__titulo">Configuración de Habitación</h1>
    </header>

    <div class="configuracion-habitacion__tarjeta">
        <form action="controllers/formularioControllers.php" method="POST" onsubmit="return comprobarFomulario()">

            <div class="configuracion-habitacion__fila">
                <div class="grupo-campo">
                    <label for="js-nombreHabitacion" class="etiqueta">Nombre / Número</label>
                    <input type="text" id="js-nombreHabitacion" name="nombre" class="input" placeholder="Ej: 101" required>
                </div>

                <div class="grupo-campo">
                    <label for="js-tipoHabitacion" class="etiqueta">Tipo de Habitación</label>
                    <select id="js-tipoHabitacion" name="tipo" class="select">
                        <?php foreach ($listaTiposHabitaciones as $tipo) { ?>
                            <option value=<?= $tipo["id"] ?>><?= $tipo["nombre"] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php if ($multipisos) { ?>
                    <div class="grupo-campo">
                        <label class="etiqueta">Piso</label>
                        <div style="display: flex; gap: var(--spacing-xs); align-items: center;">
                            <input name="piso" type="text" class="input" readonly value="<?= $listaPosicionesSeleccionada["nombre"] ?>">
                            <?php if ($limiteSuperio != $idPisoActual) { ?>
                                <button onclick="moverPiso(1)" type="button" class="btn btn--ghost btn--base">⬆</button>
                            <?php } ?>
                            <?php if ($limiteInferior != $idPisoActual) { ?>
                                <button onclick="moverPiso(-1)" type="button" class="btn btn--ghost btn--base">⬇</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <input name="piso" type="text" class="input" readonly value="<?= $listaPosicionesSeleccionada["id"] ?>" hidden>
                <?php } ?>
            </div>

            <span class="etiqueta" style="display: block; margin-bottom: var(--spacing-sm);">Selecciona la posición de la habitación</span>
            <table class="configuracion-habitacion__grilla-posiciones" id="js-grillaPosiciones">
                <?php
                for ($i = 1; $i <= 8; $i++) {
                ?>
                    <tr>
                    <?php
                    for ($j = 1; $j <= 8; $j++) {
                        $ocupado = false;
                        $texto = "";
                        $tipo = "";

                        foreach ($listaPosicionesSeleccionada["datosHabitaciones"] as $element) {
                            $posX = $element["posicion_x"] + 0;
                            $posY = $element["posicion_y"] + 0;
                            if ($posX == $i && $posY == $j) {
                                $ocupado = true;
                                $texto = $element["nombre"];
                                $tipo = $element["tipo"];
                            }
                        }

                        $id = $i . "" . $j;
                        $slotClass = $ocupado
                            ? "configuracion-habitacion__slot configuracion-habitacion__slot--ocupado js-slot-ocupado"
                            : "configuracion-habitacion__slot configuracion-habitacion__slot--disponible js-slot-disponible";
                        echo '
                            <td class="configuracion-habitacion__celda">
                                <div class="' . $slotClass . '" id="' . $id . '">'
                                    . $texto .
                                '</div>
                            </td>
                        ';
                    }
                    ?>
                    </tr>
                <?php
                }
                ?>
            </table>

            <input type="number" name="positionX" id="js-positionX" value=0 hidden>
            <input type="number" name="positionY" id="js-positionY" value=0 hidden>

            <div style="text-align: right;">
                <button type="submit" name="submit" value="submit_registrarNuevaHabitacion" class="btn btn--primary">
                    Registrar Habitación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const slots = document.querySelectorAll('.js-slot-disponible');
    const positionX = document.getElementById("js-positionX");
    const positionY = document.getElementById("js-positionY");

    slots.forEach(slot => {
        slot.addEventListener('click', () => {
            slots.forEach(s => s.classList.remove('configuracion-habitacion__slot--seleccionado'));
            slot.classList.add('configuracion-habitacion__slot--seleccionado');

            positionX.setAttribute("value", Math.floor(slot.id / 10));
            positionY.setAttribute("value", (slot.id % 10).toFixed(0));
        });
    });

    function comprobarFomulario() {
        const num = document.getElementById("js-nombreHabitacion");
        let van = true;
        const listaDatosHabitaciones = <?php echo $jsonDatos ?>;

        listaDatosHabitaciones.forEach(hab => {
            if (hab.nombre == num.value) van = false;
        });

        van = (positionX.value != 0 && positionY.value != 0) ? van : false;

        return van;
    }

    function moverPiso($mov) {
        let idPiso = <?= $listaPosicionesSeleccionada["id"] ?>;
        const limite = <?= $limiteSuperio ?>;
        const inicio = <?= $limiteInferior ?>;
        idPiso += $mov;

        if (idPiso < inicio || idPiso > limite) {
            return;
        }

        const url = "controllers/router.php?code=ingresarNuevaHabitacion&piso=" + idPiso;
        window.location.href = url;
    }
</script>
