<?php

$listaPosicionesPisos = myDB::obtenerPosicionesHabitacionesTodosPisos();
$pisos = myDB::obtenerPisos();
$multipisos = count($pisos) > 1;

if (isset($_GET["piso"])) {
    foreach ($listaPosicionesPisos as $piso) {
        if ($piso["id"] == $_GET["piso"]) $listaPosicionesSeleccionada = $piso;
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

<div class="grilla-habitaciones">
    <h1 class="grilla-habitaciones__titulo">Estado de Habitaciones</h1>

    <div class="grilla-habitaciones__navegacion-fecha">
        <div class="grilla-habitaciones__navegacion-grupo">
            <button type="button" id="js-btnDiaAnterior" class="grilla-habitaciones__boton-fecha">⬅</button>
            <input type="text" id="js-fechaSeleccion" class="grilla-habitaciones__input-fecha" value="<?= date("d/m/Y") ?>">
            <button type="button" id="js-btnDiaSiguiente" class="grilla-habitaciones__boton-fecha">➡</button>
        </div>
        <?php if ($multipisos) { ?>
            <div class="grilla-habitaciones__navegacion-grupo">
                <div class="grilla-habitaciones__selector-piso">
                    <span class="grilla-habitaciones__selector-piso-label">Piso</span>
                    <span class="grilla-habitaciones__selector-piso-valor"><?= $listaPosicionesSeleccionada["nombre"] ?></span>
                    <input type="hidden" id="js-pisoActual" value="<?= $idPisoActual ?>">
                    <?php if ($limiteSuperio != $idPisoActual) { ?>
                        <button onclick="moverPiso(1)" type="button" class="grilla-habitaciones__selector-piso-btn" title="Subir piso">⬆</button>
                    <?php } ?>
                    <?php if ($limiteInferior != $idPisoActual) { ?>
                        <button onclick="moverPiso(-1)" type="button" class="grilla-habitaciones__selector-piso-btn" title="Bajar piso">⬇</button>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <input type="hidden" id="js-pisoActual" value="<?= $idPisoActual ?>">
        <?php } ?>
        <div class="grilla-habitaciones__navegacion-grupo">
            <a href="controllers/router.php?code=estadoSemanal" class="btn btn--vista-semanal">Vista Semanal</a>
        </div>
    </div>

    <table class="grilla-habitaciones__tabla" id="js-grillaPosiciones">
        <?php
        for ($i = 1; $i <= 8; $i++) {
            echo "<tr>";
            for ($j = 1; $j <= 8; $j++) {
                $classExtra = " ";
                $texto = " ";
                $tipo = "";

                foreach ($listaPosicionesSeleccionada["datosHabitaciones"] as $element) {
                    $posX = $element["posicion_x"] + 0;
                    $posY = $element["posicion_y"] + 0;
                    if ($posX == $i && $posY == $j) {
                        $classExtra = "js-tarjeta-habitacion";
                        $texto = $element["nombre"];
                        $tipo = $element["tipo"];
                    }
                }

                $claseCelda = $texto === " " ? "grilla-habitaciones__celda grilla-habitaciones__celda--vacia" : "grilla-habitaciones__celda grilla-habitaciones__celda--ocupada";
                echo '
                    <td class="' . $claseCelda . '">
                        <a class="grilla-habitaciones__enlace">
                            <div
                                data-tipo-habitacion="' . $tipo . '"
                                data-id-habitacion="' . $texto . '"
                                data-estado="disponible"
                                class="grilla-habitaciones__tarjeta ' . $classExtra . '">'
                    . $texto .
                    '</div>
                        </a>
                    </td>
                ';
            }
            echo "</tr>";
        }
        ?>
    </table>

    <div class="grilla-habitaciones__leyenda">
        <span class="badge badge--success">Ocupado</span>
        <span class="badge badge--danger">Reservado</span>
        <span class="badge badge--warning">Mantenimiento</span>
        <span class="badge" style="background:#6b7280; color:#fff;">Disponible</span>
    </div>
</div>

<?php include_once "resources\librerias\Pikaday\pikaday.php"; ?>

<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\estadoHabitacion.js"></script>

<script>
    function moverPiso($mov) {
        let idPiso = <?= $listaPosicionesSeleccionada["id"] ?>;
        const limite = <?= $limiteSuperio ?>;
        const inicio = <?= $limiteInferior ?>;
        idPiso += $mov;
        if (idPiso < inicio || idPiso > limite) {
            return;
        }
        const url = "controllers/router.php?code=estadoHabitaciones&piso=" + idPiso;
        window.location.href = url;
    }
</script>

