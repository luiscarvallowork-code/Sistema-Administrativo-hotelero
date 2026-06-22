let pisoActual = 1;

const botonSiguienteDia = document.getElementById('js-btnDiaSiguiente');
const botonAnteriorDia = document.getElementById('js-btnDiaAnterior');

const tablaEstadoHabitaciones = document.getElementById('js-grillaPosiciones');
const celdasTablaEstados = tablaEstadoHabitaciones.querySelectorAll('td');

const input_fecha = document.getElementById('js-fechaSeleccion');

const pikadayfecha = crearInputPikaday(input_fecha, false, 0);

function manipularFechaPikaday(dias) {
  let fechaActual = pikadayfecha.getDate();

  if (fechaActual === null) {
    fechaActual = new Date();
  }

  fechaActual.setDate(fechaActual.getDate() + dias);

  pikadayfecha.setDate(fechaActual);
}

function fechaAnioMesDia(pikadayfecha) {
  const fecha = pikadayfecha.getDate();
  return (
    fecha.getFullYear() +
    '-' +
    (fecha.getMonth() + 1 < 10
      ? '0' + (fecha.getMonth() + 1)
      : fecha.getMonth() + 1) +
    '-' +
   (fecha.getDate()< 10 ?'0'+fecha.getDate():fecha.getDate())
  );
}

async function cargarEstadoHabitaciones() {
  try {
    const fechaObjetivo_ = fechaAnioMesDia(pikadayfecha) + ' 00:00:00';
    const idPiso = document.getElementById('js-pisoActual');

    const datosEnvio = {
      fechaActual: fechaObjetivo_,
      pisoId: idPiso ? idPiso.value : 1
    };

    const respuesta = await respuesta_servidor.consultaServidor(
      'obtenerEstadoHabitaciones',
      datosEnvio,
    );

    const listaEstadosHabitaciones = respuesta[0];
    const listaEstadosHabitacionesMantenimiento = respuesta[1];

    celdasTablaEstados.forEach((element) => {
      const tarjetaHabitacion = element.querySelector('.js-tarjeta-habitacion');

      if (tarjetaHabitacion) {
        tarjetaHabitacion.dataset.estado = 'disponible';
        tarjetaHabitacion.dataset.tooltip = 'Habitacion: ' + tarjetaHabitacion.dataset.tipoHabitacion;
      }
    });

    listaEstadosHabitaciones.forEach((habitacion) => {
      const nombre = habitacion['nombre'];
      const fechaAux = new Date(habitacion['fechaSalida']);
      const fechaSalida = fechaAux.getDate() + '/' + (fechaAux.getMonth() + 1) + '/' + fechaAux.getFullYear();
      const cliente = habitacion['cliente'];

      const tarjetaHabitacion = document.querySelector('.js-tarjeta-habitacion[data-id-habitacion="' + nombre + '"]');

      if (!tarjetaHabitacion) return;

      if (habitacion['activo'] == 1) {
        tarjetaHabitacion.dataset.estado = 'ocupado';
      } else {
        tarjetaHabitacion.dataset.estado = 'reservado';
      }

      tarjetaHabitacion.dataset.tooltip =
        'Habitacion: ' + tarjetaHabitacion.dataset.tipoHabitacion + '\n' +
        (habitacion['activo'] == 1 ? 'Ocupada por ' : 'Reservada para ') + cliente + '\n' +
        'hasta el ' + fechaSalida + '\n';

      const enlace = tarjetaHabitacion.parentElement;
      enlace.href = 'controllers/router.php?code=datosHabitacion&idRenta=' + habitacion['id'];
    });

    listaEstadosHabitacionesMantenimiento.forEach((habitacion) => {
      const nombre = habitacion['nombre'];
      const fechaAux = new Date(habitacion['fecha_inicio']);
      const fechaInicial = fechaAux.getDate() + '/' + (fechaAux.getMonth() + 1) + '/' + fechaAux.getFullYear();

      const tarjetaHabitacion = document.querySelector('.js-tarjeta-habitacion[data-id-habitacion="' + nombre + '"]');

      if (!tarjetaHabitacion) return;

      tarjetaHabitacion.dataset.estado = 'mantenimiento';
      tarjetaHabitacion.dataset.tooltip =
        'Habitacion: ' + tarjetaHabitacion.dataset.tipoHabitacion + '\n' +
        'Se encuentra en mantenimiento desde: ' + fechaInicial;

      const enlace = tarjetaHabitacion.parentElement;
      enlace.href = 'controllers/router.php?code=estadoMantenimientoHabitacion&idMan=' + habitacion['id'];
    });
  } catch (error) {
    console.error('Error:', error);
  }
}

document.getElementById('js-btnDiaAnterior').addEventListener('click', () => {
  manipularFechaPikaday(-1);
  cargarEstadoHabitaciones();
});

document.getElementById('js-btnDiaSiguiente').addEventListener('click', () => {
  manipularFechaPikaday(1);
  cargarEstadoHabitaciones();
});

cargarEstadoHabitaciones();

input_fecha.addEventListener('fechaCambiada', function () {
  cargarEstadoHabitaciones();
});
