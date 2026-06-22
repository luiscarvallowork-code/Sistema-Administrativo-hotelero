let tasaDia = 0;

const input_tipoHabitacion = document.getElementById('js-tipoHabitacion');

const tasa_dia = document.getElementById('js-tasaDia');
const fecha_tasa = document.getElementById('js-fechaTasa');

const selectorHabitaciones = document.getElementById('js-listaHabitaciones');
selectorHabitaciones.style.display = 'none';

const input_monto = document.getElementById('js-montoUsd');
const input_montoBs = document.getElementById('js-montoBs');
const button_precioEspecial = document.getElementById('js-btnPrecioEspecial');

const input_fechaEntrada = document.getElementById('js-fechaEntrada');
const pikadayfechaEntrada = crearInputPikaday(input_fechaEntrada, true);

const input_fechaSalida = document.getElementById('js-fechaSalida');
const pikadayfechaSalida = crearInputPikaday(input_fechaSalida, true);

input_fechaEntrada.addEventListener('fechaCambiada', function () {
  consultarHabitacionesDisponibles();
});

input_fechaSalida.addEventListener('fechaCambiada', function () {
  consultarHabitacionesDisponibles();
});

input_monto.addEventListener('input', function (event) {
  tasaBs = input_monto.value * tasaDia;
  input_montoBs.value = tasaBs.toFixed(2);
});

input_montoBs.addEventListener('input', function (event) {
  cantidad = input_montoBs.value / tasaDia;
  input_monto.value = cantidad.toFixed(2);
});

let listaPagos = [];

async function inicializar() {
  tasaActual = await respuesta_servidor.consultaServidor(
    'obtenerTasaBCV',
    (body = ['hola']),
  );

  fechaTasaActual = new Date(tasaActual['fecha']);
  let textoAuxiliar = ' ' + tasaActual['tasa'] + '&nbsp;BS';
  tasa_dia.innerHTML = textoAuxiliar;
  tasaDia = tasaActual['tasa'];
  const mes = fechaTasaActual.getMonth() + 1;
  fecha_tasa.innerHTML =
    'TASA BCV FECHA:   ' +
    fechaTasaActual.getDate() +
    '/' +
    (mes < 10 ? '0' : '') + mes +
    '/' +
    fechaTasaActual.getFullYear();
}

inicializar();

function comprobarFomulario() {
  return validacion;
}

function cargarOpcionesHabitacion(listaHabitaciones) {
  selectorHabitaciones.innerHTML = '';
  first = true;
  listaHabitaciones.forEach((numeroHab) => {
    const nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = numeroHab[0];
    nuevaOpcion.textContent = numeroHab[0];
    if (first == true) {
      first = false;
      nuevaOpcion.selected = true;
    }
    selectorHabitaciones.appendChild(nuevaOpcion);
  });
}

async function consultarHabitacionesDisponibles() {
  if (
    pikadayfechaEntrada.toString() == '' ||
    pikadayfechaSalida.toString() == ''
  ) {
    return;
  }

  const fechaEntrada = pikadayfechaEntrada.getDate();
  const fechaSalida = pikadayfechaSalida.getDate();

  if (fechaSalida <= fechaEntrada) {
    selectorHabitaciones.style.display = 'none';
    return;
  }

  const datosEnvio = {
    fechaEntrada: fechaAnioMesDia(pikadayfechaEntrada),
    fechaSalida:  fechaAnioMesDia(pikadayfechaSalida),
    tipoHabitacion: input_tipoHabitacion.value,
  };

  const data = await respuesta_servidor.consultaServidor(
    'obtenerListaHabitacionesDisponibles',
    datosEnvio,
  );

  if (selectorHabitaciones.style.display == 'none') {
    selectorHabitaciones.style.display = 'inline';
  }

  cargarOpcionesHabitacion(data);
  cargarPrecio();
}

async function cargarPrecio() {
  const datosEnvio = {
    fechaEntrada: fechaAnioMesDia(pikadayfechaEntrada),
    fechaSalida: fechaAnioMesDia(pikadayfechaSalida),
    nombre: selectorHabitaciones.value,
  };

  const data = await respuesta_servidor.consultaServidor(
    'obtenerPrecioHabitacion',
    datosEnvio,
  );

  const valor = selectorHabitaciones.value;
  let monto;
  tasaBs = data * tasaDia;
  input_montoBs.value = tasaBs.toFixed(2);
  input_monto.value = data;
}

function liberarPrecio() {
  if (input_monto.value == '') {
    return;
  }
  if (button_precioEspecial.value == 'ingresar') {
    button_precioEspecial.value = 'regresar';
    button_precioEspecial.textContent = 'Cargar Precio Estandar';
    button_precioEspecial.classList.remove('btn--ghost');
    button_precioEspecial.classList.add('btn--danger');
    input_monto.removeAttribute('readonly');
    input_montoBs.removeAttribute('readonly');
  } else {
    button_precioEspecial.value = 'ingresar';
    button_precioEspecial.textContent = 'ingresar Precio Especial';
    button_precioEspecial.classList.remove('btn--danger');
    button_precioEspecial.classList.add('btn--ghost');
    input_monto.setAttribute('readonly', 'true');
    input_montoBs.setAttribute('readonly', 'true');
    cargarPrecio();
  }
}

input_tipoHabitacion.addEventListener('change', function (event) {
  consultarHabitacionesDisponibles();
});

selectorHabitaciones.addEventListener('change', function (event) {
  cargarPrecio();
});

document.addEventListener('DOMContentLoaded', function () {
  const radioIngreso = document.getElementById('js-ingreso');
  const radioReservacion = document.getElementById('js-reservacion');
  const inputOculto = document.getElementById('js-tipoFormulario');

  function actualizarTipoFormulario() {
    if (inputOculto.value == '1') {
      inputOculto.value = '0';
    } else {
      inputOculto.value = '1';
    }
  }

  radioIngreso.addEventListener('change', actualizarTipoFormulario);
  radioReservacion.addEventListener('change', actualizarTipoFormulario);

  window.addEventListener('libreriasListas', () => {
  });
});
