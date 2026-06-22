<?php
if (isset($_GET["idRenta"])) {
    $idRentaHabitacion = $_GET["idRenta"];
    $datos = myDB::obtenerDataReservacion($idRentaHabitacion);
    $incidencia = $datos["datos_renta"]["nota"];
    $datosRentaHabitacion = $datos["datos_renta"];
    $datosCliente = $datos["datos_cliente"];
    $datosPago = $datos["datos_pago"];
    $datosHabitacion = $datos["datos_habitacion"];
    $opcionesPago = myDB::obtenerOpcionesPago();
    $listaHabitacionesDisponibles = myDB::obtenerListaHabitacionesDisponibles($datosRentaHabitacion["fechaEntrada"], $datosRentaHabitacion["fechaSalida"], 0);

    $fechaEntrada = tools::fechaFormato_Ymd($datosRentaHabitacion["fechaEntrada"]);
    $fechaSalida = tools::fechaFormato_Ymd($datosRentaHabitacion["fechaSalida"]);
}
?>

<div class="detalle-habitacion">
    <h1 class="detalle-habitacion__titulo">INFORMACION DE HABITACION</h1>

    <section class="detalle-habitacion__seccion">
        <!-- <h2 class="detalle-habitacion__seccion-titulo">DETALLES DE LA HABITACIÓN</h2> -->
        <article class="detalle-habitacion__tarjeta">
            <div class="detalle-habitacion__tarjeta-horizontal">
                <div class="detalle-habitacion__item-input">
                    <label class="detalle-habitacion__label-tarjeta">TIPO DE HABITACIÓN:</label>
                    <input type="text" class="detalle-habitacion__input-lectura" value="<?= $datosHabitacion["tipo"]; ?>" readonly>
                </div>
                <div class="detalle-habitacion__item-input">
                    <label class="detalle-habitacion__label-tarjeta">NÚMERO:</label>
                    <div class="detalle-habitacion__input-group">
                        <input type="text" class="detalle-habitacion__input-lectura" id="js-numeroHabitacionTexto" value="<?= $datosHabitacion["nombre"]; ?>" readonly>
                        <form action="controllers\formularioControllers.php" method="post" class="detalle-habitacion__cambio-habitacion-form">
                            <input type="number" name="id_rentaHabitacion" value=<?= $datosRentaHabitacion["id"] ?> hidden>
                            <select name="habitacionesDisponibles" id="js-selectHabitacion" class="select" style="display: none; width: auto;">
                                <?php foreach ($listaHabitacionesDisponibles as $numHabitacion) {
                                    echo '<option value="' . $numHabitacion[1] . '">' . $numHabitacion[0] . '</option>';
                                } ?>
                            </select>
                            <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnConfirmarHabitacion" value="submit_renta_cambiar_habitacion" style="display: none;">Actualizar</button>
                            <button type="button" class="btn btn--primary btn--sm" id="js-btnCambiarHabitacion">Cambiar de habitación</button>
                        </form>
                    </div>
                </div>
                <div class="detalle-habitacion__item-input" style="margin-left: 35%;">
                    <label class="detalle-habitacion__label-tarjeta">ESTADO ACTUAL:</label>
                    <?php
                    if ($datosRentaHabitacion["activo"] == 1) {
                        echo '<span class="insignia insignia--exito">OCUPADA</span>';
                    } else {
                        echo '<span class="insignia insignia--advertencia">RESERVADA</span>';
                    }
                    ?>
                </div>
            </div>
        </article>
    </section>

    <hr class="detalle-habitacion__separador">

    <section class="detalle-habitacion__seccion">
       
        <div class="detalle-habitacion__flex-tarjetas">
            <article class="detalle-habitacion__tarjeta">
                <form method="post" action="controllers\formularioControllers.php">
                    <h3 class="detalle-habitacion__tarjeta-titulo">DATOS DEL CLIENTE</h3>
                    <input hidden type="number" name="id" value="<?= $datosCliente["id"] ?>">
                    <input type="number" name="id_rentaHabitacion" value=<?= $datosRentaHabitacion["id"] ?> hidden>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">Nombre:</label>
                        <input id="js-clienteNombre" type="text" name="cliente_nombre" class="detalle-habitacion__input-lectura" value="<?= $datosCliente["nombre"] ?>" readonly>
                    </div>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">Cedula:</label>
                        <input id="js-clienteCedula" type="text" name="cliente_cedula" class="detalle-habitacion__input-lectura" value="<?= $datosCliente["ci"] ?>" readonly>
                    </div>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">Telefono:</label>
                        <input id="js-clienteTelefono" type="text" name="cliente_telefono" class="detalle-habitacion__input-lectura" value="<?= $datosCliente["numeroTelefono"] ?>" readonly>
                    </div>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">Empresa:</label>
                        <input id="js-clienteEmpresa" type="text" name="cliente_empresa" class="detalle-habitacion__input-lectura" value="<?= $datosCliente["empresa"] ?>" readonly>
                    </div>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">Ciudad:</label>
                        <input id="js-clienteCiudad" type="text" name="cliente_ciudad" class="detalle-habitacion__input-lectura" value="<?= $datosCliente["ciudad"] ?>" readonly>
                    </div>
                    <button type="button" class="btn btn--ghost btn--sm" id="js-btnEditarCliente">Editar Cliente</button>
                    <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnConfirmarCliente" value="submit_renta_actualizarDatosCliente" hidden>Actualizar datos</button>
                </form>
            </article>

            <article class="detalle-habitacion__tarjeta">
                <h3 class="detalle-habitacion__tarjeta-titulo">PLAZO DE LA RESERVACION</h3>
                <form action="controllers\formularioControllers.php" method="post" onsubmit="return comprobarFechas()">
                    <input type="number" name="id_rentaHabitacion" value=<?= $datosRentaHabitacion["id"] ?> hidden>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">FECHA DE ENTRADA:</label>
                        <input type="text" class="detalle-habitacion__input-lectura" id="js-fechaEntradaTexto" readonly value="<?= tools::fechaF_dmy($fechaEntrada) ?>">
                        <input type="text" id="js-fechaEntrada" name="fecha_entrada" class="detalle-habitacion__input-lectura" value="<?= $fechaEntrada ?>" style="display: none;">
                    </div>
                    <div class="detalle-habitacion__item-input">
                        <label class="detalle-habitacion__label-tarjeta">FECHA DE SALIDA:</label>
                        <input type="text" class="detalle-habitacion__input-lectura" id="js-fechaSalidaTexto" readonly value="<?= tools::fechaF_dmy($fechaSalida) ?>">
                        <input type="text" id="js-fechaSalida" name="fecha_salida" class="detalle-habitacion__input-lectura" value="<?= $fechaSalida ?>" style="display: none;">
                    </div>
                    <button type="button" class="btn btn--ghost btn--sm" id="js-btnEditarPlazo">Editar Plazo</button>
                    <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnConfirmarPlazo" value="submit_renta_actualizarDatosPlazo" hidden>Actualizar datos</button>
                </form>

                <hr class="detalle-habitacion__separador-interno">
                <div class="detalle-habitacion__incidencia">
                    <form id="formulario_envio_textArea" method="post" action="controllers\formularioControllers.php">
                        <input type="number" name="id_rentaHabitacion_incidencia" value=<?= $datosRentaHabitacion["id"] ?> hidden>
                        <button type="button" class="btn btn--<?php echo $incidencia ? "danger" : "primary"; ?> btn--sm" id="js-btnIncidencia" onclick="mostrarIncidencia()"><?php echo $incidencia ? "x" : "mostrar incidencias"; ?></button>
                        <textarea id="js-textoIncidencia" class="textarea" form="formulario_envio_textArea" name="textArea_incidencia" cols="30" rows="3" style="display: <?php echo $incidencia ? "block" : "none"; ?>; margin-top: var(--spacing-xs);" placeholder="Escribir incidencia"><?php echo $incidencia ? $incidencia : ""; ?></textarea>
                        <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnActualizarIncidencia" value="submit_renta_actualizarIncidencia" style="display: <?php echo $incidencia ? "inline-block" : "none"; ?>; margin-top: var(--spacing-xs);">Actualizar</button>
                    </form>
                </div>
            </article>

            <article class="detalle-habitacion__tarjeta" style="position:relative;">
                <div class="detalle-habitacion__acciones-pie" style="position: absolute; top: 25px; right: var(--spacing-md); gap: var(--spacing-xs); margin: 0; z-index: 1;">
                    <button hidden id="js-btnPagoAnterior" type="button" class="btn btn--ghost btn--sm" onclick="modificarPagoMostrado(-1)">⬅</button>
                    <button type="button" class="btn btn--ghost btn--sm" onclick="agregarPago()">➕</button>
                    <button hidden id="js-btnPagoSiguiente" type="button" class="btn btn--ghost btn--sm" onclick="modificarPagoMostrado(1)">➡</button>
                </div>

                <form method="post" action="controllers\formularioControllers.php">
                    <div class="formulario-ingreso__carrusel-pagos" id="js-carruselPagos">
                        <input type="number" name="id_rentaHabitacion" value=<?= $datosRentaHabitacion["id"] ?> hidden>
                        <input type="number" name="numPagosOriginales" value=<?= count($datosPago) ?> hidden>
                        <?php
                        foreach ($datosPago as $index => $pago) {
                        ?>
                            <div id="js-contenedorPago" class="formulario-ingreso__columna-pago js-columna-pago" hidden>
                                <div class="detalle-habitacion__tarjeta-titulo-contenedor">
                                    <h3 class="detalle-habitacion__tarjeta-titulo js-titulo-pago" style="border: none; margin: 0; padding: 0;">ESTADO DEL PAGO <?= ($index + 1) ?></h3>
                                    <input type="number" name="id[]" value=<?= $pago["id"] ?> hidden>
                                    <!-- <p class="detalle-habitacion__texto-pagado detalle-habitacion__texto-pagado--si js-texto-constante-pago">PAGADO</p> -->
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">TIPO de PAGO:</label>
                                    <input id="js-pagoTipoTexto" type="text" name="pago_tipo[]" class="detalle-habitacion__input-lectura" value="<?= $pago["tipoNombre"] ?>" readonly data-campo-pago="tipo-texto">
                                    <select name="pago_tipo_seleccion[]" id="js-pagoTipoSelect" class="select" hidden data-campo-pago="tipo-select">
                                        <?php
                                        foreach ($opcionesPago as $indexAux => $opcion) {
                                            $textAux = ($indexAux + 1) == $pago["tipoId"] ? "selected" : "";
                                        ?>
                                            <option value="<?= $opcion["id"] ?>" <?= $textAux ?>><?= $opcion["nombre"]  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">Cantidad:</label>
                                    <input id="js-pagoCantidad" type="number" name="pago_cantidad[]" step="any" class="detalle-habitacion__input-lectura" readonly data-campo-pago="monto" value=<?= $pago["cantidad"] ?>>
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">REFERENCIA:</label>
                                    <input id="js-pagoReferencia" type="text" name="referenciaPago[]" class="detalle-habitacion__input-lectura" readonly data-campo-pago="referencia" value=<?= $pago["referencia"] ?>>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (count($datosPago) == 0) { ?>
                            <div id="js-contenedorPago" class="formulario-ingreso__columna-pago js-columna-pago" hidden>
                                <div class="detalle-habitacion__tarjeta-titulo-contenedor">
                                    <h3 class="detalle-habitacion__tarjeta-titulo js-titulo-pago" style="border: none; margin: 0; padding: 0;">REGISTRAR PAGO 1</h3>
                                    <input type="number" name="id[]" hidden>
                                    <!-- <p class="detalle-habitacion__texto-pagado detalle-habitacion__texto-pagado--no js-texto-constante-pago">PENDIENTE</p> -->
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">TIPO de PAGO:</label>
                                    <select name="pago_tipo_seleccion[]" id="js-pagoTipoSelect" class="select" data-campo-pago="tipo-select">
                                        <?php
                                        foreach ($opcionesPago as $indexAux => $opcion) {
                                        ?>
                                            <option value="<?= $opcion["id"] ?>"><?= $opcion["nombre"]  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">Cantidad:</label>
                                    <input id="js-pagoCantidad" type="number" name="pago_cantidad[]" step="any" class="detalle-habitacion__input-lectura" data-campo-pago="monto">
                                </div>
                                <div class="detalle-habitacion__item-input">
                                    <label class="detalle-habitacion__label-tarjeta">REFERENCIA:</label>
                                    <input id="js-pagoReferencia" type="text" name="referenciaPago[]" class="detalle-habitacion__input-lectura" data-campo-pago="referencia">
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if (count($datosPago) == 0) { ?>
                        <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnRegistrarPago" value="submit_renta_actualizarDatosPago" style="margin-top: var(--spacing-sm);">Registrar Pagos</button>
                    <?php } else { ?>
                        <button type="button" class="btn btn--ghost btn--sm" id="js-btnEditarPago" style="margin-top: var(--spacing-sm);">Editar Pago</button>
                        <button type="submit" name="submit" class="btn btn--primary btn--sm" id="js-btnConfirmarPago" value="submit_renta_actualizarDatosPago" hidden>Actualizar Datos</button>
                    <?php } ?>
                </form>
            </article>
        </div>
    </section>


    <div class="detalle-habitacion__acciones-pie">
        <button type="button" id="js-btnEliminarRenta" class="btn btn--danger">ELIMINAR REGISTRO DE RENTA</button>
        <?php if ($datosRentaHabitacion["activo"] == 0) { ?>
            <form action="controllers\formularioControllers.php" method="post">
                <input type="number" name="id_reservacion" value=0 hidden>
                <input type="number" name="id_renta" value="<?= $datosRentaHabitacion["id"] ?>" hidden>
                <button type="submit" name="submit" value="submit_convertirReservacionIngreso" class="btn btn--primary">Confirmar Ingreso</button>
            </form>
        <?php } ?>
    </div>
</div>

<?php include_once "resources\librerias\Pikaday\pikaday.php"; ?>
<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\datosHabitacion.js"></script>

<script>
    boton_elimina_registro.addEventListener("click", function() {
        window.location.replace("controllers/router.php?code=confirmarBorradoRegistro&idRenta=<?= $datosRentaHabitacion["id"] ?>");
    });

    function mostrarIncidencia() {
        const textArea = document.getElementById("js-textoIncidencia");
        const boton = document.getElementById("js-btnIncidencia");
        const botonEnviar = document.getElementById("js-btnActualizarIncidencia");
        if (textArea.style.display == "none") {
            textArea.style.display = "block";
            botonEnviar.style.display = "inline-block";
            boton.innerText = "x";
            boton.classList.add("btn--danger");
            boton.classList.remove("btn--primary");
        } else {
            textArea.style.display = "none";
            botonEnviar.style.display = "none";
            boton.innerText = "mostrar incidencias";
            boton.classList.remove("btn--danger");
            boton.classList.add("btn--primary");
        }
    }

</script>




