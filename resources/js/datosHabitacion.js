const boton_elimina_registro = document.getElementById('js-btnEliminarRenta');

const boton_editar_cliente = document.getElementById('js-btnEditarCliente');
const boton_enviar_cliente = document.getElementById('js-btnConfirmarCliente');

const nombre_cliente = document.getElementById('js-clienteNombre');
const cedula_cliente = document.getElementById('js-clienteCedula');
const telefono_cliente = document.getElementById('js-clienteTelefono');
const empresa_cliente = document.getElementById('js-clienteEmpresa');
const ciudad_cliente = document.getElementById('js-clienteCiudad');

let nombre_cliente_aux;
let cedula_cliente_aux;
let telefono_cliente_aux;
let empresa_cliente_aux;
let ciudad_cliente_aux;

const boton_editar_numeroHabitacion = document.getElementById('js-btnCambiarHabitacion');
const select_numeroHabitacion = document.getElementById('js-selectHabitacion');
const boton_enviar_numeroHabitacion = document.getElementById('js-btnConfirmarHabitacion');
const numero_habitacion_texto = document.getElementById('js-numeroHabitacionTexto');

const boton_editar_plazo = document.getElementById('js-btnEditarPlazo');
const boton_enviar_plazo = document.getElementById('js-btnConfirmarPlazo');

const fecha_entrada = document.getElementById('js-fechaEntrada');
const fecha_entrada_falsa = document.getElementById('js-fechaEntradaTexto');
const fecha_salida = document.getElementById('js-fechaSalida');
const fecha_salida_falsa = document.getElementById('js-fechaSalidaTexto');

const pikadayfechaEntrada = crearInputPikaday(fecha_entrada, false, 0, fecha_entrada.value);
const pikadayfechaSalida = crearInputPikaday(fecha_salida, false, 0, fecha_salida.value);

let fecha_entrada_aux;
let fecha_salida_aux;

const boton_editar_pago = document.getElementById('js-btnEditarPago');
const boton_enviar_pago = document.getElementById('js-btnConfirmarPago');

const tipo_pago = document.getElementById('js-pagoTipoTexto');
const tipo_pago_seleccion = document.getElementById('js-pagoTipoSelect');
const ref = document.getElementById('js-pagoReferencia');
const cantidad_pago = document.getElementById('js-pagoCantidad');

let ref_pago_aux;
let cantidad_pago_aux;

function comprobarFechas() {
  const fechaEntrada = new Date(fecha_entrada.value);
  const fechaSalida = new Date(fecha_salida.value);
  const fechaEntrada2 = new Date(fechaAnioMesDia2(pikadayfechaEntrada));
  const fechaSalida2 = new Date(fechaAnioMesDia2(pikadayfechaSalida));
  return fechaEntrada2 < fechaSalida2;
}

boton_editar_cliente.addEventListener('click', function () {
  if (nombre_cliente.readOnly == true) {
    nombre_cliente.readOnly = false;
    cedula_cliente.readOnly = false;
    telefono_cliente.readOnly = false;
    empresa_cliente.readOnly = false;
    ciudad_cliente.readOnly = false;

    nombre_cliente_aux = nombre_cliente.value;
    cedula_cliente_aux = cedula_cliente.value;
    telefono_cliente_aux = telefono_cliente.value;
    empresa_cliente_aux = empresa_cliente.value;
    ciudad_cliente_aux = ciudad_cliente.value;

    boton_enviar_cliente.hidden = false;
    boton_editar_cliente.innerHTML = 'Cancelar';
    cambiarElemento(boton_editar_cliente, true);
  } else {
    nombre_cliente.readOnly = true;
    cedula_cliente.readOnly = true;
    telefono_cliente.readOnly = true;
    empresa_cliente.readOnly = true;
    ciudad_cliente.readOnly = true;

    nombre_cliente.value = nombre_cliente_aux;
    cedula_cliente.value = cedula_cliente_aux;
    telefono_cliente.value = telefono_cliente_aux;
    empresa_cliente.value = empresa_cliente_aux;
    ciudad_cliente.value = ciudad_cliente_aux;

    boton_enviar_cliente.hidden = true;
    boton_editar_cliente.innerHTML = 'Editar Cliente';
    cambiarElemento(boton_editar_cliente);
  }
});

boton_editar_plazo.addEventListener('click', function () {
  if (fecha_entrada.style.display == 'none') {
    fecha_entrada_falsa.style.display = 'none';
    fecha_entrada.style.display = 'block';
    fecha_salida_falsa.style.display = 'none';
    fecha_salida.style.display = 'block';

    boton_enviar_plazo.hidden = false;
    boton_editar_plazo.innerHTML = 'Cancelar';
    cambiarElemento(boton_editar_plazo, true);
  } else {
    fecha_entrada_falsa.style.display = 'block';
    fecha_entrada.style.display = 'none';
    fecha_salida_falsa.style.display = 'block';
    fecha_salida.style.display = 'none';

    boton_enviar_plazo.hidden = true;
    boton_editar_plazo.innerHTML = 'Editar Plazo';
    cambiarElemento(boton_editar_plazo);
  }
});

boton_editar_numeroHabitacion.addEventListener('click', function () {
  if (boton_enviar_numeroHabitacion.style.display == 'none') {
    numero_habitacion_texto.style.display = 'none';
    boton_enviar_numeroHabitacion.style.display = 'inline';
    select_numeroHabitacion.style.display = 'inline';
    boton_editar_numeroHabitacion.innerHTML = 'Cancelar';
    cambiarElemento(boton_editar_numeroHabitacion, true);
  } else {
    numero_habitacion_texto.style.display = '';
    boton_enviar_numeroHabitacion.style.display = 'none';
    select_numeroHabitacion.style.display = 'none';
    boton_editar_numeroHabitacion.innerHTML = 'Cambiar de habitacion';
    cambiarElemento(boton_editar_numeroHabitacion);
  }
});

function cambiarElemento(element, van = false) {
  if (van) {
    element.classList.add('js-btn-cancelar');
    element.classList.remove('js-btn-editar');
  } else {
    element.classList.add('js-btn-editar');
    element.classList.remove('js-btn-cancelar');
  }
}

const pago = document.getElementById('js-contenedorPago');
let indicePago = 0;
const contenedorDeslizante = document.getElementById('js-carruselPagos');

const botonAtras = document.getElementById('js-btnPagoAnterior');
const botonSiguiente = document.getElementById('js-btnPagoSiguiente');

const pagosAux = contenedorDeslizante.querySelectorAll('.js-columna-pago');

let first = true;

pagosAux.forEach(pago => {
  if (first) {
    first = false;
  } else {
    limpiarElementosId(pago);
  }
});

ocultarPagos(pagosAux, indicePago);

if (pagosAux.length > 1) {
  botonAtras.hidden = false;
  botonSiguiente.hidden = false;
}

function ocultarPagos(listaPagos, number) {
  listaPagos.forEach(element => {
    element.setAttribute('hidden', true);
  });
  listaPagos[number].hidden = false;
}

function modificarPagoMostrado(number) {
  const listaPagos = contenedorDeslizante.querySelectorAll('.js-columna-pago');
  console.log("hoas");
  let aux = indicePago + number;

  if (aux == listaPagos.length || aux < 0) {
    return;
  }
  indicePago = aux;
  ocultarPagos(listaPagos, indicePago);
}

function limpiarElementosId(contenedorPago) {
  const pagoRef = contenedorPago.querySelector('[data-campo-pago="referencia"]');
  const pagoCantidad = contenedorPago.querySelector('[data-campo-pago="monto"]');
  const pagoTipo = contenedorPago.querySelector('[data-campo-pago="tipo-texto"]');
  const pagoTipoSelect = contenedorPago.querySelector('[data-campo-pago="tipo-select"]');

  pagoRef.id = '';
  pagoCantidad.id = '';
  if (pagoTipo) {
    pagoTipo.id = '';
  }
  pagoTipoSelect.id = '';
}

function agregarPago() {
  const listaPagos = contenedorDeslizante.querySelectorAll('.js-columna-pago');

  if (indicePago == 0) {
    botonAtras.hidden = false;
    botonSiguiente.hidden = false;
  }

  if (listaPagos.length == 4) {
    return;
  }
  indicePago = listaPagos.length;

  const pagoNuevo = pago.cloneNode(true);

  limpiarElementosId(pagoNuevo);

  const pagoRef = pagoNuevo.querySelector('[data-campo-pago="referencia"]');
  const pagoCantidad = pagoNuevo.querySelector('[data-campo-pago="monto"]');
  const pagoTipo = pagoNuevo.querySelector('[data-campo-pago="tipo-texto"]');
  const pagoTipoSelect = pagoNuevo.querySelector('[data-campo-pago="tipo-select"]');
  const tituloPagoBorrar = pagoNuevo.querySelector('.js-texto-constante-pago');
  const titulo = pagoNuevo.querySelector('.js-titulo-pago');

  pagoRef.value = '';
  pagoCantidad.value = 0;
  if (pagoTipo) {
    pagoTipo.value = '';
  }
  pagoTipoSelect.value = '';
  titulo.innerHTML = 'REGISTRAR PAGO ' + (listaPagos.length + 1);

  pagoNuevo.setAttribute('data-pago-nuevo', 'true');
  contenedorDeslizante.appendChild(pagoNuevo);

  const listaPagosNueva = contenedorDeslizante.querySelectorAll('.js-columna-pago');

  if (pagoCantidad.readOnly == true) {
    cambiarElementosPago();
  }

  ocultarPagos(listaPagosNueva, indicePago);
}

function cambiarElementosPago() {
  const listaPagosAux = contenedorDeslizante.querySelectorAll('.js-columna-pago');

  listaPagosAux.forEach(contenedorPago => {
    const pagoRef = contenedorPago.querySelector('[data-campo-pago="referencia"]');
    const pagoCantidad = contenedorPago.querySelector('[data-campo-pago="monto"]');
    const pagoTipo = contenedorPago.querySelector('[data-campo-pago="tipo-texto"]');
    const pagoTipoSelect = contenedorPago.querySelector('[data-campo-pago="tipo-select"]');

    if (!contenedorPago.hasAttribute('data-pago-nuevo')) {
      if (pagoRef.readOnly == true) {
        pagoRef.setAttribute('data-valor-original', pagoRef.value);
        pagoCantidad.setAttribute('data-valor-original', pagoCantidad.value);
        pagoTipo.setAttribute('data-valor-original', pagoTipo.value);
        pagoTipoSelect.setAttribute('data-valor-original', pagoTipoSelect.value);

        boton_enviar_pago.hidden = false;
        boton_editar_pago.innerHTML = 'Cancelar';
        cambiarElemento(boton_editar_pago, true);
      } else {
        pagoRef.value = pagoRef.getAttribute('data-valor-original');
        pagoCantidad.value = pagoCantidad.getAttribute('data-valor-original');
        pagoTipo.value = pagoTipo.getAttribute('data-valor-original');
        pagoTipoSelect.value = pagoTipoSelect.getAttribute('data-valor-original');

        boton_enviar_pago.hidden = true;
        boton_editar_pago.innerHTML = 'Editar Pago';
        cambiarElemento(boton_editar_pago);
      }
    } else {
      if (pagoTipoSelect.children[pagoTipoSelect.value]) {
        const auxElement = pagoTipoSelect.children[(pagoTipoSelect.value - 1)];
        pagoTipo.value = auxElement.textContent;
      }
    }

    pagoRef.readOnly = !pagoRef.readOnly;
    pagoCantidad.readOnly = !pagoCantidad.readOnly;
    pagoTipo.hidden = !pagoCantidad.readOnly;
    pagoTipoSelect.hidden = !pagoTipoSelect.hidden;
  });
}

if (boton_editar_pago) {
  boton_editar_pago.addEventListener('click', function () {
    cambiarElementosPago();
  });
}

