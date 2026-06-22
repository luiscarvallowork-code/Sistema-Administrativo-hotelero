<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);
?>

<div class="estilo-semanal">
    <div class="estilo-semanal__navegacion">
        <button type="button" id="js-btnSemanaAnterior" class="estilo-semanal__boton-fecha">⬅</button>
        <input id="js-rangoSemana" type="text" class="estilo-semanal__rango-fecha" value="SEMANA 1 02/02 | 08/02">
        <button type="button" id="js-btnSemanaSiguiente" class="estilo-semanal__boton-fecha">➡</button>
        <a href="controllers/router.php?code=estadoHabitaciones" class="btn btn--vista-semanal">Estado Diario</a>
    </div>

    <div class="estilo-semanal__grilla">
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
        <section class="estilo-semanal__columna js-columna-dia"></section>
    </div>

    <footer class="estilo-semanal__mantenimiento">
        <h3 class="estilo-semanal__mantenimiento-titulo">HABITACIONES EN REPARACION</h3>
        <div class="estilo-semanal__mantenimiento-lista" id="js-listaMantenimiento"></div>
    </footer>
</div>

<script src="resources\js\respuestasServidor.js"></script>

<script>
    const textRangoFechas = document.getElementById("js-rangoSemana");
    const listaContainerDias = document.getElementsByClassName("js-columna-dia");
    const diasTexto = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
    const contenedorCuadrosReparacion = document.getElementById("js-listaMantenimiento");

    function obtenerListaFechasSemana(fechaActual) {
        let semana = [];
        const dia = fechaActual.getDay();
        for (i = dia; i > 0; i--) {
            const auxFecha = new Date(fechaActual);
            auxFecha.setDate(fechaActual.getDate() - i);
            const dia2 = String(auxFecha.getDate()).padStart(2, "0");
            const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
            const anio = auxFecha.getFullYear();
            semana.push(dia2 + "/" + mes + "/" + anio);
        }
        const auxFecha = fechaActual;
        const dia2 = String(auxFecha.getDate()).padStart(2, "0");
        const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
        const anio = auxFecha.getFullYear();
        semana.push(dia2 + "/" + mes + "/" + anio);
        contAux = 1;
        for (i = dia + 1; i < 7; i++) {
            const auxFecha = new Date(fechaActual);
            auxFecha.setDate(fechaActual.getDate() + contAux);
            const dia2 = String(auxFecha.getDate()).padStart(2, "0");
            const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
            const anio = auxFecha.getFullYear();
            semana.push(dia2 + "/" + mes + "/" + anio);
            contAux++;
        }
        return semana;
    }

    function cambiarTextoRangoFechas(arraySemana) {
        primeraFecha = arraySemana[0].slice(0, 5);
        ultimaFecha = "";
        arraySemana.forEach(element => { ultimaFecha = element.slice(0, 5); });
        return primeraFecha + " " + ultimaFecha;
    }

    async function cargarEstadoSemanal(semanaObjetivo, listaContainerDias) {
        const datosEnvio = { semana: semanaObjetivo };
        const respuesta = await respuesta_servidor.consultaServidor("obtenerEstadoHabitacionesSemanal", datosEnvio);
        const datosSemana = respuesta[0];
        let habitacionesReparacion = [];

        for (i = 0; i < 7; i++) {
            const textDia = semanaObjetivo[i].slice(0, 2);
            const containerTitulo = document.createElement("div");
            containerTitulo.classList.add("estilo-semanal__columna-titulo");
            containerTitulo.innerHTML = diasTexto[i] + "  " + textDia;
            const containerContenido = document.createElement("div");
            containerContenido.classList.add("estilo-semanal__columna-contenido");
            listaContainerDias[i].innerHTML = "";
            listaContainerDias[i].appendChild(containerTitulo);
            listaContainerDias[i].appendChild(containerContenido);

            datosDia = datosSemana[i];
            const ocupadas = datosDia[0];
            const reparacion = datosDia[1];

            ocupadas.forEach(element2 => {
                const enlace = document.createElement("a");
                enlace.classList.add("estilo-semanal__tarjeta");
                const linkAuxiliar = "controllers/router.php?code=datosHabitacion&idRenta=" + element2["id"];
                enlace.href = linkAuxiliar;

                if (element2["activo"] == 1) {
                    enlace.dataset.estado = "ocupado";
                } else {
                    enlace.dataset.estado = "reservado";
                }

                enlace.innerHTML = element2["nombre"];
                containerContenido.appendChild(enlace);
            });

            reparacion.forEach(element2 => {
                let guardar = true;
                habitacionesReparacion.forEach(registro => {
                    if (registro["nombre"] == element2["nombre"] &&
                        registro["fecha_inicio"] == element2["fecha_inicio"] &&
                        registro["fecha_final"] == element2["fecha_final"]
                    ) guardar = false;
                });
                if (guardar == true) {
                    habitacionesReparacion.push(element2);
                }
            });
        }

        contenedorCuadrosReparacion.innerHTML = "";
        habitacionesReparacion.forEach(registro => {
            const enlace = document.createElement("a");
            enlace.classList.add("estilo-semanal__tarjeta");
            enlace.href = "controllers/router.php?code=estadoMantenimientoHabitacion&idMan=" + registro["id"];
            enlace.dataset.estado = "mantenimiento";
            enlace.innerHTML = registro["nombre"];
            contenedorCuadrosReparacion.appendChild(enlace);
        });
    }

    let fechaObjetivo = new Date();
    let semana = obtenerListaFechasSemana(fechaObjetivo);
    textRangoFechas.value = cambiarTextoRangoFechas(semana);
    cargarEstadoSemanal(semana, listaContainerDias);

    document.getElementById("js-btnSemanaAnterior").addEventListener("click", () => {
        const auxFecha = new Date(fechaObjetivo);
        auxFecha.setDate(fechaObjetivo.getDate() - 7);
        fechaObjetivo = auxFecha;
        semana = obtenerListaFechasSemana(auxFecha);
        textRangoFechas.value = cambiarTextoRangoFechas(semana);
        cargarEstadoSemanal(semana, listaContainerDias);
    });

    document.getElementById("js-btnSemanaSiguiente").addEventListener("click", () => {
        const auxFecha = new Date(fechaObjetivo);
        auxFecha.setDate(fechaObjetivo.getDate() + 7);
        fechaObjetivo = auxFecha;
        semana = obtenerListaFechasSemana(auxFecha);
        textRangoFechas.value = cambiarTextoRangoFechas(semana);
        cargarEstadoSemanal(semana, listaContainerDias);
    });
</script>
