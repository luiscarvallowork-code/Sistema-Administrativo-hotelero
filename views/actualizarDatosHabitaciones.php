<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);
$listaTiposHabitaciones = myDB::obtenerTiposHabitaciones();
$datosHabitacionActual = myDB::obtenerDatosHabitacion($_GET["id"]);

$positionX = null;
$positionY = null;

$piso = 1;
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
                    <input type="text" id="js-nombreHabitacion" name="nombre" class="input" value="<?= $datosHabitacionActual["nombre"] ?>" required>
                </div>

                <div class="grupo-campo">
                    <label for="js-tipoHabitacion" class="etiqueta">Tipo de Habitación</label>
                    <select id="js-tipoHabitacion" name="tipo" class="select">
                        <?php foreach ($listaTiposHabitaciones as $tipo) { ?>
                            <option value=<?= $tipo["id"] ?>><?= $tipo["nombre"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <span class="etiqueta" style="display: block; margin-bottom: var(--spacing-sm);">Selecciona la posición de la habitación</span>
            <table class="configuracion-habitacion__grilla-posiciones" id="js-grillaPosiciones">
                <?php
                $posX = null;
                $posY = null;
                for ($i = 1; $i <= 8; $i++) {
                ?>
                    <tr>
                    <?php
                    for ($j = 1; $j <= 8; $j++) {
                        $ocupado = false;
                        $texto = "";
                        $seleccionado = false;

                        foreach ($listaPosiciones as $element) {
                            $posX = $element["posicion_x"] + 0;
                            $posY = $element["posicion_y"] + 0;
                            if ($posX == $i && $posY == $j) {
                                $ocupado = true;
                                $texto = $element["nombre"];
                                if ($texto == $datosHabitacionActual["nombre"]) {
                                    $seleccionado = true;
                                    $positionX = $posX;
                                    $positionY = $posY;
                                    $ocupado = false;
                                }
                            }
                        }

                        $id = $i . "" . $j;
                        $slotClass = "configuracion-habitacion__slot";
                        if ($ocupado) {
                            $slotClass .= " configuracion-habitacion__slot--ocupado js-slot-ocupado";
                        } else {
                            $slotClass .= " configuracion-habitacion__slot--disponible js-slot-disponible";
                        }
                        if ($seleccionado) {
                            $slotClass .= " configuracion-habitacion__slot--seleccionado";
                        }
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
            <input type="number" name="piso" id="js-piso" value=<?= $piso ?> hidden>
            <input type="number" name="id" id="js-id" value=<?= $datosHabitacionActual["id"] ?> hidden>

            <div style="text-align: right;">
                <button type="submit" name="submit" value="submit_actualizarDatosHabitacion" class="btn btn--primary">
                    Actualizar Habitación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const slots = document.querySelectorAll('.js-slot-disponible');
    const positionX = document.getElementById("js-positionX");
    const positionY = document.getElementById("js-positionY");

    positionX.setAttribute("value", <?= json_encode($positionX) ?>);
    positionY.setAttribute("value", <?= json_encode($positionY) ?>);

    slots.forEach(slot => {
        slot.addEventListener('click', () => {
            slots.forEach(s => s.classList.remove('configuracion-habitacion__slot--seleccionado'));
            slot.classList.add('configuracion-habitacion__slot--seleccionado');

            positionX.setAttribute("value", Math.floor(slot.id / 10));
            positionY.setAttribute("value", (slot.id % 10).toFixed(0));
        });
    });

    function comprobarFomulario() {
        if (positionX.value != 0 && positionY.value != 0) return true;
        else return false;
    }
</script>
