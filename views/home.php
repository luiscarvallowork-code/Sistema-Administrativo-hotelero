<?php
$fechaActual = new DateTime();
$mesActual = intval($fechaActual->format("m"));
$data = myDB::obtenerDatosDashboar();
?>
<?php if (isset($_GET['backup_ready']) && $_GET['backup_ready'] == 1): ?>
    <iframe src="controllers/actions/descargarRespaldo.php" style="display:none;"></iframe>
<?php endif; ?>

<div class="tablero">
    <h1 class="tablero__titulo">Panel Principal</h1>
    <div class="tablero__grilla">
        <div class="tablero__tarjeta" style="border-left: 4px solid var(--color-success);">
            <span class="tablero__etiqueta">Tasa de cambio (BCV) | fecha: <?= $data["tasaFecha"] ?></span>
            <div class="tablero__valor"><?= $data["tasa"] ?> Bs</div>
            <div class="tablero__input-grupo">
                <form id="js-formTasaManual" action="controllers\formularioControllers.php" method="post" style="display:none">
                    <input type="number" placeholder="0.00" id="new-rate" step="any" name="cantidad" value=0 class="input" style="width: 120px;">
                    <button id="botonConfirmarEnvio" type="submit" class="btn btn--primary" name="submit" value="actualizarTasaBcv" style="margin-left: var(--spacing-xs);">confirmar</button>
                </form>
                <form id="js-formTasaApi" action="controllers\formularioControllers.php" method="post">
                    <button id="js-btnActualizarTasaApi" type="submit" class="btn btn--primary" name="submit" value="actualizarTasaBcvApi">Actualizar</button>
                </form>
                <button id="js-btnToggleTasa" class="btn btn--primary" onclick="cambiarVisibilidad()">editar</button>
            </div>
        </div>

        <div class="tablero__tarjeta" style="border-left: 4px solid var(--color-success);">
            <span class="tablero__etiqueta">Habitaciones Ocupadas</span>
            <div class="tablero__valor"><?= $data["NumHabOcupadas"] ?> <span style="font-size: 1rem; color: var(--color-text-secondary);">/ <?= $data["NumHabTotales"] ?></span></div>
            <div style="height: 8px; background: var(--color-border); border-radius: 4px; margin-top: 15px;">
                <div style="width:  <?= ($data["NumHabOcupadas"] * 100) / $data["NumHabTotales"] ?>%; height: 100%; background: var(--color-success); border-radius: 4px;"></div>
            </div>
        </div>

        <div class="tablero__tarjeta" style="border-left: 4px solid var(--color-success);">
            <span class="tablero__etiqueta">Facturado este Mes (Est.)</span>
            <div class="tablero__valor" style="color: var(--color-success);"><?= $data["facturacionTotal"] ?> USD</div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 15px;">
        <div class="tablero__tarjeta" style="flex: 1; margin-right: var(--spacing-lg);">
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 0;">Tarifas por Categoría</h3>
                <p style="font-size: 0.8rem; color: var(--color-text-secondary); margin: 5px 0 0 0;">Precios base configurados para la venta directa</p>
            </div>
            <div class="tablero__grilla-precios">
                <?php
                foreach ($data["listaPrecios"] as $registro) {
                    $cantidaCamas = $registro["nombre"];
                    if ($cantidaCamas == "Matrimonial") $cantidaCamas = "1 cama matrimonial";
                    else if ($cantidaCamas == "Doble") $cantidaCamas = "2 camas matrimonial";
                    else if ($cantidaCamas == "Triple") $cantidaCamas = "3 camas individuales";
                    else if ($cantidaCamas == "Suit") $cantidaCamas = "1 cama matrimonial";
                    else if ($cantidaCamas == "ApartaHotel") $cantidaCamas = "1 cama matrimonial";
                    if ($cantidaCamas == "Sala de Conferencia") $cantidaCamas = "Mesa y sillas para conferencias";
                ?>
                    <div class="tablero__item-precio">
                        <div>
                            <span class="tablero__tipo-habitacion"><?= $registro["nombre"] ?></span>
                            <span class="tablero__descripcion"><?= $cantidaCamas ?></span>
                        </div>
                        <div class="tablero__monto"> <?= $registro["cantidad"] ?> USD</div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="tablero__tarjeta" style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Reservaciones Recientes</h3>
            </div>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Hab.</th>
                        <th>Nombre del Cliente</th>
                        <th>Fecha de Reservacion</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data["reservacionesRecientes"] as $registro) {
                        $fecha = new DateTime($registro["fechaEntrada"]);
                        $textoEmpresa = "";
                        if ($registro["empresa"] != "") {
                            $textoEmpresa = "(" . $registro["empresa"] . ")";
                        }
                        if ($registro["estado"] == 1) {
                            $claseEstado = "insignia--advertencia";
                            $textoRes = "Activa";
                        } else if (($registro["estado"] == 0)) {
                            $claseEstado = "insignia--peligro";
                            $textoRes = "Caducada";
                        } else {
                            $claseEstado = "insignia--exito";
                            $textoRes = "Completada";
                        }
                    ?>
                        <tr>
                            <td><strong><?= $registro["hab"] ?></strong></td>
                            <td><?= $registro["cliente"] ?> <?= $textoEmpresa ?></td>
                            <td><?= $fecha->format("d/m/Y") ?></td>
                            <td>
                                <span class="insignia <?= $claseEstado ?>"><?= $textoRes ?></span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const botonActualizar = document.getElementById("js-btnToggleTasa");
    const botonActualizarAutomaticamente = document.getElementById("js-btnActualizarTasaApi");
    const formulario = document.getElementById("js-formTasaManual");

    function cambiarVisibilidad() {
        if (botonActualizar.textContent != "Cancelar") {
            botonActualizar.textContent = "Cancelar";
            botonActualizar.classList.remove("btn--primary");
            botonActualizar.classList.add("btn--danger");
            formulario.style.display = "block";
            botonActualizarAutomaticamente.style.display = "none";
        } else {
            botonActualizar.textContent = "Cancelar";
            botonActualizar.textContent = "Editar";
            botonActualizar.classList.remove("btn--danger");
            botonActualizar.classList.add("btn--primary");
            formulario.style.display = "none";
            botonActualizarAutomaticamente.style.display = "block";
        }
    }
</script>
