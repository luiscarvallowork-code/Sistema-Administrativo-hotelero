<?php
myDB::actualizarDatosReservaciones("2026-01-05");
?>

<div class="formulario-ingreso">
    <div class="formulario-ingreso__encabezado">
        <h1 class="formulario-ingreso__titulo">Registro de renta de habitación</h1>

        <div class="formulario-ingreso__barra-acciones">
            <div class="formulario-ingreso__info-tasa">
                <span class="formulario-ingreso__fecha-tasa" id="js-fechaTasa"></span>
                <span class="formulario-ingreso__valor-tasa" id="js-tasaDia"></span>
            </div>

            <div class="formulario-ingreso__alternador">
                <label class="formulario-ingreso__alternador-opcion">
                    <input type="radio" id="js-ingreso" name="tipoFormulario" value="1" checked> INGRESO
                </label>
                <label class="formulario-ingreso__alternador-opcion">
                    <input type="radio" id="js-reservacion" name="tipoFormulario" value="0"> RESERVACIÓN
                </label>
            </div>

            <button type="submit" form="formulario-ingreso" name="submit" value="submit_ingresarHabitacion" class="btn btn--primary">CONFIRMAR INGRESO / RESERVACIÓN</button>
        </div>
    </div>

    <form id="formulario-ingreso" action="controllers\formularioControllers.php" method="post" onsubmit="return comprobarFomulario()">
        <input type="text" hidden name="tipoFormularioEnviado" id="js-tipoFormulario" value="1">

        <div class="formulario-ingreso__cuerpo">
            <div class="formulario-ingreso__columna" style="position: relative;">
                <h2 class="formulario-ingreso__columna-titulo">DATOS DEL CLIENTE</h2>
                <div class="grupo-campo">
                    <label for="js-nombreCliente" class="etiqueta">Nombre Completo</label>
                    <div class="formulario-ingreso__grupo-busqueda">
                        <input type="text" name="nombre" id="js-nombreCliente" class="input" required>
                        <button type="button" class="btn btn--primary btn--sm" onclick="togglePanelBusqueda()">🔍 Buscar</button>
                    </div>
                </div>

                <div id="js-panelBusqueda" class="formulario-ingreso__panel-busqueda" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 650px; z-index: 9999;">
                    <div class="formulario-ingreso__panel-encabezado">
                        <span>Buscar Cliente Existente</span>
                        <button type="button" class="btn btn--ghost btn--sm" onclick="togglePanelBusqueda()">❌</button>
                    </div>
                    <div style="padding: var(--spacing-md);">
                        <div class="formulario-ingreso__grupo-busqueda" style="margin-bottom: var(--spacing-sm);">
                            <input type="text" id="js-inputBusqueda" class="input" placeholder="Escribe el nombre...">
                            <button type="button" class="btn btn--primary btn--sm" onclick="buscarClientePorCoincidencia()">Buscar</button>
                        </div>
                        <ul id="js-listaResultados" class="formulario-ingreso__lista-resultados">
                            <li class="formulario-ingreso__item-resultado" style="color: var(--color-text-secondary); text-align: center; cursor: default;">Ingresa un nombre para buscar...</li>
                        </ul>
                    </div>
                </div>

                <div class="grupo-campo">
                    <label for="cedula" class="etiqueta">Cédula / Pasaporte</label>
                    <input type="number" name="cedula" class="input" required>
                </div>
                <div class="grupo-campo">
                    <label for="telefono" class="etiqueta">Teléfono</label>
                    <input type="tel" name="telefono" class="input" required>
                </div>
                <div class="grupo-campo">
                    <label for="ciudad" class="etiqueta">Ciudad de Origen</label>
                    <input type="text" name="ciudad" class="input" required>
                </div>
                <div class="grupo-campo">
                    <label for="empresa" class="etiqueta">Empresa (Opcional)</label>
                    <input type="text" name="empresa" class="input">
                </div>
            </div>

            <div class="formulario-ingreso__columna">
                <h2 class="formulario-ingreso__columna-titulo">DISPONIBILIDAD DE HABITACIÓN</h2>
                <div class="grupo-campo">
                    <label for="js-fechaEntrada" class="etiqueta">Fecha Entrada</label>
                    <input type="text" id="js-fechaEntrada" name="input_fechaEntrada" class="input" readonly>
                </div>
                <div class="grupo-campo">
                    <label for="js-fechaSalida" class="etiqueta">Fecha Salida</label>
                    <input type="text" id="js-fechaSalida" name="input_fechaSalida" class="input" readonly>
                </div>
                <div class="grupo-campo">
                    <label for="js-tipoHabitacion" class="etiqueta">Tipo de Habitación</label>
                    <select name="tipoHabitacion" id="js-tipoHabitacion" class="select">
                        <option value="0">Todas</option>
                        <option value="1">Matrimonial</option>
                        <option value="2">Doble</option>
                        <option value="3">Triple</option>
                        <option value="4">Ejecutiva</option>
                        <option value="5">Apartamento</option>
                    </select>
                </div>
                <h2 class="formulario-ingreso__columna-titulo">SELECCIONAR HABITACIÓN</h2>
                <div class="grupo-campo">
                    <label for="js-listaHabitaciones" class="etiqueta">Habitación Número:</label>
                    <select name="habitacion" id="js-listaHabitaciones" class="select"></select>
                </div>
                <div class="formulario-ingreso__incidencia">
                    <button type="button" class="btn btn--ghost btn--sm" id="js-btnIncidencia" onclick="mostrarIncidencia()">Añadir Incidencia</button>
                    <textarea id="js-textoIncidencia" class="textarea" form="formulario-ingreso" name="textArea_incidencia" cols="30" rows="3" style="display: none; margin-top: var(--spacing-xs);" placeholder="Escribir incidencia"></textarea>
                </div>
            </div>

            <div class="formulario-ingreso__columna" style="overflow: hidden;">
                <div class="formulario-ingreso__carrusel-pagos" id="js-carruselPagos">
                    <div id="js-contenedorPago" class="formulario-ingreso__columna-pago">
                        <div class="formulario-ingreso__encabezado-pago">
                            <h2 class="formulario-ingreso__columna-titulo js-titulo-pago-principal">Estado DEL PAGO</h2>
                            <div class="formulario-ingreso__botones-pago">
                                <button hidden id="js-btnPagoAnterior" type="button" class="btn btn--ghost btn--sm" onclick="modificarPagoMostrado(-1)">⬅</button>
                                <button type="button" class="btn btn--ghost btn--sm" onclick="agregarPago()">➕</button>
                                <button hidden id="js-btnPagoSiguiente" type="button" class="btn btn--ghost btn--sm" onclick="modificarPagoMostrado(1)">➡</button>
                            </div>
                        </div>
                        <div class="formulario-ingreso__estado-pago js-contenedor-estado-pago">
                            <label class="formulario-ingreso__estado-pago-opcion">
                                <input type="radio" id="estadoPagoTrue" name="estadoPago" value="1" checked> PAGADO
                            </label>
                            <label class="formulario-ingreso__estado-pago-opcion">
                                <input type="radio" id="estadoPagoFalse" name="estadoPago" value="0"> PENDIENTE
                            </label>
                        </div>

                        <h2 class="formulario-ingreso__columna-titulo js-titulo-pago">Detalles del pago 1</h2>

                        <div class="grupo-campo">
                            <label for="js-montoUsd" class="etiqueta">Monto (USD)</label>
                            <div class="js-contenedor-monto" style="display: flex; gap: var(--spacing-xs); align-items: center;">
                                <input type="number" name="monto[]" class="input js-input-monto" id="js-montoUsd" step="any" readonly required style="flex-grow: 1;">
                                <span class="btn btn--ghost btn--sm js-btn-precio-especial" id="js-btnPrecioEspecial" type="button" onclick="liberarPrecio()">Ingresar Precio Especial</span>
                            </div>
                        </div>
                        <div class="grupo-campo">
                            <label for="js-montoBs" class="etiqueta">Monto en Bs</label>
                            <input class="input js-input-montoBs" type="number" step="any" name="montoBs[]" id="js-montoBs" readonly>
                        </div>

                        <div class="grupo-campo">
                            <label for="js-tipoPago" class="etiqueta">Tipo de Pago</label>
                            <select name="tipoPago[]" id="js-tipoPago" class="select">
                                <option value="1">Bs</option>
                                <option value="2">Pago móvil</option>
                                <option value="3">Transferencia</option>
                                <option value="4">Zelle</option>
                                <option value="5">Divisas</option>
                            </select>
                        </div>

                        <div class="grupo-campo">
                            <label for="referenciaPago" class="etiqueta">Referencia (Opcional)</label>
                            <input type="text" name="referenciaPago[]" class="input" placeholder="Opcional">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>

<script type="module" src="resources\js\libreriasExternas.js"></script>
<?php include_once "resources\librerias\Pikaday\pikaday.php"; ?>

<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\ingreso_reservacion.js"></script>
<script>
    const pago = document.getElementById("js-contenedorPago");
    let indicePago = 0;
    const contenedorDeslizante = document.getElementById("js-carruselPagos");
    const botonAtras = document.getElementById("js-btnPagoAnterior");
    const botonSiguiente = document.getElementById("js-btnPagoSiguiente");

    function ocultarPagos(listaPagos, number) {
        listaPagos.forEach(element => {
            element.setAttribute("hidden", true);
        });
        listaPagos[number].hidden = false;
    }

    function modificarPagoMostrado(number) {
        const listaPagos = contenedorDeslizante.querySelectorAll('.formulario-ingreso__columna-pago');
        aux = indicePago + number;
        if (aux == listaPagos.length || aux < 0) {
            return;
        }
        indicePago = aux;
        ocultarPagos(listaPagos, indicePago);
    }

    function agregarPago() {
        const listaPagos = contenedorDeslizante.querySelectorAll('.formulario-ingreso__columna-pago');
        if (indicePago == 0) {
            botonAtras.hidden = false;
            botonSiguiente.hidden = false;
        }
        if (listaPagos.length == 4) {
            return;
        }
        indicePago = listaPagos.length;
        const pagoNuevo = pago.cloneNode(true);

        const titulo = pagoNuevo.getElementsByClassName("js-titulo-pago")[0];
        titulo.textContent = "Detalles del Pago " + (indicePago + 1);

        const tituloBorrar = pagoNuevo.getElementsByClassName("js-titulo-pago-principal")[0];
        tituloBorrar.remove();
        const seccionBorrar = pagoNuevo.getElementsByClassName("js-contenedor-estado-pago")[0];
        seccionBorrar.remove();

        const containerMonto = pagoNuevo.getElementsByClassName("js-contenedor-monto")[0];
        const seccionMonto = containerMonto.getElementsByClassName("js-input-monto")[0];
        seccionMonto.removeAttribute("id");
        seccionMonto.removeAttribute("readonly");
        seccionMonto.value = 0;

        const borrarBoton = containerMonto.getElementsByClassName("js-btn-precio-especial")[0];

        const seccionMontoBs = pagoNuevo.getElementsByClassName("js-input-montoBs")[0];
        seccionMontoBs.removeAttribute("readonly");
        seccionMontoBs.value = 0;

        seccionMontoBs.addEventListener("input", function(event) {
            seccionMonto.value = (seccionMontoBs.value / tasaDia).toFixed(2);
        });
        seccionMonto.addEventListener("input", function(event) {
            seccionMontoBs.value = (seccionMonto.value * tasaDia).toFixed(2);
        });
        borrarBoton.remove();

        contenedorDeslizante.appendChild(pagoNuevo);

        const listaPagosNueva = contenedorDeslizante.querySelectorAll('.formulario-ingreso__columna-pago');
        ocultarPagos(listaPagosNueva, indicePago);
    }

    function mostrarIncidencia() {
        const textArea = document.getElementById("js-textoIncidencia");
        const boton = document.getElementById("js-btnIncidencia");
        if (textArea.style.display == "none") {
            textArea.style.display = "block";
            boton.innerText = "Cancelar";
            boton.classList.remove("btn--ghost");
            boton.classList.add("btn--danger");
        } else {
            textArea.style.display = "none";
            boton.innerText = "añadir incidencia";
            boton.classList.add("btn--ghost");
            boton.classList.remove("btn--danger");
            textArea.value = "";
        }
    }

    let clientesAux = false;

    function togglePanelBusqueda() {
        const panel = document.getElementById("js-panelBusqueda");
        if (panel.style.display === "none" || panel.style.display === "") {
            panel.style.display = "block";
            document.getElementById("js-inputBusqueda").focus();
            buscarClientePorCoincidencia();
        } else {
            panel.style.display = "none";
        }
    }

    async function buscarClientePorCoincidencia() {
        if (!clientesAux) {
            const datosEnvio = { cotinue: true };
            const respuesta = await respuesta_servidor.consultaServidor("obtenerClientes", datosEnvio);
            clientesAux = respuesta;
        }
        const clientes = clientesAux;
        const termino = document.getElementById("js-inputBusqueda").value.trim().toLowerCase();
        const lista = document.getElementById("js-listaResultados");
        lista.innerHTML = "";

        const filtrados = (termino === "") ?
            clientes :
            clientes.filter(cliente => cliente.nombre.toLowerCase().includes(termino));

        if (filtrados.length === 0) {
            lista.innerHTML = '<li class="formulario-ingreso__item-resultado" style="color: var(--color-text-secondary); text-align: center; cursor: default;">No se encontraron coincidencias</li>';
            return;
        }

        filtrados.forEach(cliente => {
            const li = document.createElement("li");
            li.classList.add("formulario-ingreso__item-resultado");
            li.textContent = `${cliente.nombre} - C.I: ${cliente.ci}`;
            li.onclick = function() { seleccionarCliente(cliente); };
            lista.appendChild(li);
        });
    }

    function seleccionarCliente(cliente) {
        document.getElementById("js-nombreCliente").value = cliente.nombre;
        document.querySelector('input[name="cedula"]').value = cliente.ci;
        document.querySelector('input[name="telefono"]').value = cliente.numeroTelefono;
        document.querySelector('input[name="ciudad"]').value = cliente.ciudad;
        if (cliente.empresa) {
            document.querySelector('input[name="empresa"]').value = cliente.empresa;
        }
        togglePanelBusqueda();
        document.getElementById("js-inputBusqueda").value = "";
        document.getElementById("js-listaResultados").innerHTML = '<li class="formulario-ingreso__item-resultado" style="color: var(--color-text-secondary); text-align: center; cursor: default;">Ingresa un nombre para buscar...</li>';
    }
</script>
