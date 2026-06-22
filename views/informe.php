<?php

if (isset($_GET["fecha"])) {
    $fecha = new DateTime($_GET["fecha"]);
} else {
    $fecha = new DateTime();
}
$fechaUso = $fecha->format("Y-m-d");
$listaIngresos = myDB::obtenerListaIngresosTotales($fechaUso);
$listaSalidas = myDB::obtenerListaSalidasTotales($fechaUso);
$listaEntradas = myDB::obtenerListaEntradasTotales($fechaUso);

$listaReservaciones = myDB::obtenerListaReservaciones();

$listaIngresosFinal = [];
$listaReservacionesFinal = [];
$listaPagosFinal = [];

foreach ($listaReservaciones as $index => $item) {
    $fechaEntrada = new DateTime($item["fechaEntrada"]);
    $fechaSalida = new DateTime($item["fechaSalida"]);

    if ($fecha >= $fechaEntrada && $fecha < $fechaSalida && $item["estado"] != "2") {
        $listaReservacionesFinal[] = $item;
    }
}
foreach ($listaIngresos as $index => $item) {
    $fechaEntrada = new DateTime($item["fechaEntrada"]);
    $fechaSalida = new DateTime($item["fechaSalida"]);

    if ($fecha >= $fechaEntrada && $fecha < $fechaSalida) {
        $van = true;
        foreach ($listaReservaciones as $index => $reservacion) {
            if ($reservacion["idRenta"] == $item["id"]) {
                if ($reservacion["estado"] != "2") {
                    $van = false;
                }
            };
        }
        if ($van) $listaIngresosFinal[] = $item;
    }
}

$listaPagos = myDB::obtenerListaDatosPagos(0, 9999999);
foreach ($listaPagos as $index => $item) {
    $fechaPago = new DateTime($item["fecha"]);
    if ($fecha->format("Y-m-d") == $fechaPago->format("Y-m-d")) {
        $listaPagosFinal[] = $item;
    }
}
?>

<div class="informe-turno">
    <header class="informe-turno__encabezado">
        <h1 class="informe-turno__titulo">INFORME DE TURNO - <?= $fecha->format("d/m/Y") ?></h1>
    </header>

    <div class="informe-turno__navegacion-fecha" id="js-contenedorFecha">
        <button type="button" id="js-btnDiaAnterior" class="navegacion-fecha__boton">&lt;</button>
        <input id="js-fechaSeleccion" type="text" class="navegacion-fecha__texto" value="<?= $fecha->format("d/m/Y") ?>" readonly>
        <button type="button" id="js-btnDiaSiguiente" class="navegacion-fecha__boton">&gt;</button>
    </div>

    <form class="informe-turno__cuerpo">
        <div class="informe-turno__seccion-completa">

            <input type="text" class="input" placeholder="Nombre Recepcionista" style="max-width: 400px;  font-size: 18px !important;" >
        </div>

        <div class="informe-turno__grilla-estados">
            <div class="informe-turno__columna-estado">
                <div class="informe-turno__columna-header">HABITACIONES OCUPADAS</div>
                <div class="informe-turno__caja-habitaciones">
                    <?php foreach ($listaIngresosFinal as $ocupada) { ?>
                        <div class="informe-turno__rect-habitacion js-tarjeta-ocupada" data-estado="ocupado">
                            <?= $ocupada["nombre"] ?>
                            <button class="js-botonEliminar" type="button" onclick="borarContenedorPadre(event)">❌</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="informe-turno__columna-estado">
                <div class="informe-turno__columna-header">INGRESOS DE HABITACIONES</div>
                <div class="informe-turno__caja-habitaciones">
                    <?php foreach ($listaEntradas as $ocupada) { ?>
                        <div class="informe-turno__rect-habitacion js-tarjeta-ocupada" data-estado="ocupado">
                            <?= $ocupada["nombre"] ?>
                            <button class="js-botonEliminar" type="button" onclick="borarContenedorPadre(event)">❌</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="informe-turno__columna-estado">
                <div class="informe-turno__columna-header">SALIDA DE HABITACIONES</div>
                <div class="informe-turno__caja-habitaciones">
                    <?php foreach ($listaSalidas as $ocupada) { ?>
                        <div class="informe-turno__rect-habitacion js-tarjeta-ocupada" data-estado="ocupado">
                            <?= $ocupada["nombre"] ?>
                            <button class="js-botonEliminar" type="button" onclick="borarContenedorPadre(event)">❌</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="informe-turno__columna-estado">
                <div class="informe-turno__columna-header">HABITACIONES RESERVADAS</div>
                <div class="informe-turno__caja-habitaciones">
                    <?php foreach ($listaReservacionesFinal as $reservada) { ?>
                        <div class="informe-turno__rect-habitacion informe-turno__rect-habitacion--reservado js-tarjeta-reservada" data-estado="reservado">
                            <?= $reservada["hab"] ?>
                            <button class="js-botonEliminar" type="button" onclick="borarContenedorPadre(event)">❌</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="informe-turno__detalle-pagos">
            <div class="informe-turno__columna-header" style="border-radius: var(--radius-sm) var(--radius-sm) 0 0;">DETALLE DE PAGOS</div>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>HAB</th>
                        <th>HUESPED</th>
                        <th>CANTIDAD</th>
                        <th>TIPO</th>
                        <th>REF.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPagosFinal as $pago) { ?>
                        <tr>
                            <td><?= $pago["nombre"] ?></td>
                            <td><?= $pago["cliente"] ?></td>
                            <td><?= $pago["cantidad"] ?> <?= $pago["codigo"] ?></td>
                            <td><?= $pago["tipo"] ?></td>
                            <td><?= $pago["referencia"] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="informe-turno__grid-secundario">
            <div class="informe-turno__contenedor-tabla">
                <div class="informe-turno__tabla-header">
                    <span>FACTURAS RECIBIDAS</span>
                    <button type="button" class="btn btn--ghost btn--sm" onclick="agregarFilaFactura()">➕</button>
                </div>
                <table class="tabla" id="js-tablaFacturas">
                    <thead>
                        <tr>
                            <th>RAZON SOCIAL</th>
                            <th>MONTO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="informe-turno__contenedor-tabla">
                <div class="informe-turno__tabla-header">
                    <span>SALIDAS DE INVENTARIO</span>
                    <button type="button" class="btn btn--ghost btn--sm" onclick="agregarFilaInventario()">➕</button>
                </div>
                <table class="tabla" id="js-tablaInventario">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>CANTIDAD</th>
                            <th>NOTA</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="js-contenedorIncidencia" class="informe-turno__incidencias">
            <label class="informe-turno__seccion-nombre" style="margin-bottom: var(--spacing-sm);">INCIDENCIAS DEL DÍA </label>

            <button class="btn btn--primary btn--sm" type="button" onclick="agregarIncidencia()" style="margin-left: 8px;">Agregar</button>

            <div id="js-incidenciaPlantilla" style="display: none;" class="informe-turno__item-incidencia">
                <input class="informe-turno__input-incidencia" type="text">
                <button class="btn btn--ghost btn--sm" type="button" onclick="borarContenedorPadre(event)">❌</button>
            </div>

            <?php foreach ($listaIngresosFinal as $ocupada) {
                if ($ocupada["nota"] != null) {
            ?>
                    <div class="informe-turno__item-incidencia">
                        <input class="informe-turno__input-incidencia" type="text" value="<?= "Hab " . $ocupada["nombre"] . ": " . $ocupada["nota"] ?>">
                        <button class="btn btn--ghost btn--sm" type="button" onclick="borarContenedorPadre(event)">❌</button>
                    </div>
            <?php }
            } ?>
        </div>

        <div class="informe-turno__pie">
            <button type="button" onclick="descargarPDF()" class="btn btn--primary">GENERAR INFORME</button>
        </div>
    </form>
</div>

<?php include_once "resources\librerias\Pikaday\pikaday.php"; ?>
<script src="resources\librerias\html2pdf.js-main\html2pdf.js-main\dist\html2pdf.bundle.min.js"></script>

<script>
    function agregarIncidencia() {
        const original = document.getElementById("js-incidenciaPlantilla");
        const contenedorIncidencia = document.getElementById("js-contenedorIncidencia");
        const nuevo = original.cloneNode(true);
        nuevo.style.display = "flex";
        contenedorIncidencia.appendChild(nuevo);
    }

    function borarContenedorPadre(e) {
        const contenedor = e.currentTarget.parentElement;
        contenedor.remove();
    }

    function ejecutarCapturaPDF() {
        const elemento = document.querySelector(".informe-turno");
        const fecha = "<?= $fecha->format("d-m-Y") ?>"
        const opciones = {
            margin: 10,
            filename: 'Informe_Turno ' + fecha + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, logging: false, width: 800, useCORS: true },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        return html2pdf().set(opciones).from(elemento).save();
    }

    async function descargarPDF() {
        const styleTag = document.createElement('style');
        styleTag.id = "estilo-temporal-pdf";
        styleTag.innerHTML = `
            .informe-turno__rect-habitacion { color: black !important; }
            .barra-lateral,
            .informe-turno__pie, .informe-turno__navegacion-fecha { display: none !important; }
            .informe-turno__columna-header, .informe-turno__caja-habitaciones, .tabla tr, .tabla th, .informe-turno__rect-habitacion {
                background-color: #ffffff !important;
            }
            .btn { display: none !important; }
            .informe-turno__rect-habitacion { font-size: 15px !important; width: 50px !important; height: 20px !important; }
            .informe-turno__caja-habitaciones { border: none !important; padding: 2px !important; min-height: none !important; }
            .informe-turno { padding: 0px !important; }
            .informe-turno__seccion-completa { margin-bottom: 10px !important; margin-top: 10px !important; }
            .informe-turno__incidencias { margin-bottom: 10px !important; margin-top: 10px !important; }
            .informe-turno__input-incidencia { width: 90%; padding: 0px; margin: 0px; height: 30px; border: none; border-bottom: 1px solid rgb(175, 175, 175); background-color: transparent; border-radius: 0px; }
            .informe-turno__item-incidencia { display: flex !important; }
            .informe-turno { display: block !important; }
            .js-botonEliminar { display: none !important; }
            .input { height: 50px; }
            .informe-turno__columna-estado { background: #ffffff !important; border: none !important; }
            .informe-turno .input { border: none !important; font-size: 16px !important; }
            .informe-turno .tabla td, .informe-turno .tabla th { padding: 0 6px !important; word-break: break-word !important; }
            .informe-turno__grid-secundario { align-items: start !important; gap: 2px !important; }
            .input{padding: 0px; padding-top:1px !important;   padding-right: 2px !important;}
            .informe-turno__seccion-completa{margin-bottom: 0px !important;} 
            .informe-turno__detalle-pagos .tabla td { border-bottom: none !important; }
        `;
        document.head.appendChild(styleTag);
        try {
            await ejecutarCapturaPDF();
        } catch (error) {
            console.error("Error al generar el PDF:", error);
        } finally {
            document.getElementById("estilo-temporal-pdf").remove();
        }
    }

    function agregarFilaFactura() {
        const tabla = document.getElementById("js-tablaFacturas").getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.insertRow();
        nuevaFila.innerHTML = `
            <td><input type="text" class="input" placeholder="..." style="width: 100%; border: none; background: transparent;"></td>
            <td><input type="text" class="input" placeholder="0.00 BS" style="width: 100%; border: none; background: transparent;"></td>
            <td><input type="text" class="input" placeholder="01/01/1999" style="width: 100%; border: none; background: transparent;"></td>
        `;
    }

    function agregarFilaInventario() {
        const tabla = document.getElementById("js-tablaInventario").getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.insertRow();
        nuevaFila.innerHTML = `
            <td><input type="text" class="input" placeholder="..." style="width: 100%; border: none; background: transparent;"></td>
            <td><input type="number" class="input" placeholder="0" style="width: 100%; border: none; background: transparent;"></td>
            <td><input type="text" class="input" placeholder="..." style="width: 100%; border: none; background: transparent;"></td>
        `;
    }

    const fechaObjetivo = new Date(" <?= $fecha->format("Y/m/d") ?>");

    function cambiarFecha(num) {
        fechaObjetivo.getDate(fechaObjetivo.getDate() + num);
        const otroDia = new Date(fechaObjetivo);
        otroDia.setDate(fechaObjetivo.getDate() + num);
        fechaTexto = otroDia.getDate() + "-" + (otroDia.getMonth() + 1) + "-" + otroDia.getFullYear();
        window.location.href = "controllers/router.php?code=informe&fecha=" + fechaTexto;
    }

    document.getElementById("js-btnDiaAnterior").addEventListener("click", function() { cambiarFecha(-1); });
    document.getElementById("js-btnDiaSiguiente").addEventListener("click", function() { cambiarFecha(1); });
</script>
