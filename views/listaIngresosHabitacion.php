<?php
$fechaUso = "";
$campo = isset($_GET["campo"]) ? $_GET["campo"] : "";
$textoBusqueda = isset($_GET["textoBusqueda"]) ? $_GET["textoBusqueda"] : false;
if (isset($_GET["fecha"])) {
    $fechaUso = $_GET["fecha"];
    $fecha = new DateTime($_GET["fecha"]);
} else if ($campo != "" && $textoBusqueda != false) {
    $fechaUso = false;
    $fecha = new DateTime();
} else {
    $fecha = new DateTime();
    $fechaUso = $fecha->format("Y-m-d");
}
$listaIngresos = myDB::obtenerListaIngresosTotales($fechaUso, $campo, $textoBusqueda);
$reservaciones = myDB::obtenerListaReservaciones();
$listaIngresosAux = [];

foreach ($listaIngresos as $index => $ingreso) {
    $van = true;
    foreach ($reservaciones as $index2 => $reservacion) {
        if ($ingreso["id"] == $reservacion["idRenta"]) {
            if ($reservacion["estado"] == "1" || $reservacion["estado"] == "0") {
                $van = false;
            }
        }
    }
    if ($van) $listaIngresosAux[] = $ingreso;
}
$listaIngresos = $listaIngresosAux;
$listaIngresosAux = [];
?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Lista ingresos de habitacion</h1>

    <div class="lista-generica__barra-navegacion">
        <div class="lista-generica__grupo-busqueda">
            <input type="text" class="input" id="js-inputBusqueda" value="<?= htmlspecialchars($textoBusqueda) ?>" style="max-width: 200px;">
            <select name="selectorCampo" id="js-selectorCampo" class="select" style="max-width: 140px;">
                <option value="cliente" <?php if ($campo == "cliente") echo "selected " ?>>Nombre</option>
                <option value="estadoPago" <?php if ($campo == "estadoPago") echo "selected " ?>>Estado Pago</option>
                <option value="nombre" <?php if ($campo == "nombre") echo "selected " ?>>Num Hab</option>
            </select>
            <button class="btn btn--ghost btn--sm btn--buscar" onclick="busquedaTexto()">&#x1F50D;</button>
            <?php if ((isset($_GET["textoBusqueda"]))) { ?>
                <button class="btn btn--ghost btn--sm btn--buscar" onclick="cancelarBusqueda()">❌</button>
            <?php } ?>
        </div>

        <div class="lista-generica__grupo-navegacion">
            <?php if ($textoBusqueda === false) { ?>
                <button type="button" class="navegacion-fecha__boton" id="prevMonth" onclick="modificar(-1)">⬅</button>
                <div class="navegacion-fecha__texto" style="min-width: auto; padding: 8px 12px;">
                    <span><?= tools::obtenerMesEspaniol($fecha->format("m")) ?></span>
                    <span><?= $fecha->format("Y") ?></span>
                </div>
                <button type="button" class="navegacion-fecha__boton" id="nextMonth" onclick="modificar(1)">➡</button>
            <?php } ?>
        </div>

        <button class="btn btn--info btn--sm" onclick="exportarExcel()">Exportar Excel</button>
    </div>


    <div class="lista-generica__encabezado lista-generica__encabezado--ingresos">
        <span>Num Hab</span>
        <span>Cliente</span>
        <span>Fecha Entrada</span>
        <span>Fecha Salida</span>
        <span>Estado Pago</span>

    </div>

    <?php
    foreach ($listaIngresos as $ingreso) {
        if ($ingreso["estadoPago"] != null) {
            $texto = "Pagado";
            $clase = "insignia insignia--exito";
        } else {
            $texto = "Pendiente";
            $clase = "insignia insignia--advertencia";
        }
        $fechaEntrada = new DateTime($ingreso["fechaEntrada"]);
        $fechaEntrada = $fechaEntrada->format("d-m-Y");
        $fechaSalida = new DateTime($ingreso["fechaSalida"]);
        $fechaSalida = $fechaSalida->format("d-m-Y");
    ?>
        <div class="lista-generica__item lista-generica__item--ingresos">
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__numero-habitacion"><?= $ingreso["nombre"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $ingreso["cliente"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fechaEntrada ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fechaSalida ?></span>
            </div>
            <div>
                <span class="<?= $clase ?>"><?= $texto ?></span>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script>
    fechaObjetivo = new Date(" <?= $fecha->format("Y/m/d") ?>");
    const textoBusqueda = document.getElementById("js-inputBusqueda");
    const campo = "<?= $campo ?>";
    const selectorCampo = document.getElementById("js-selectorCampo");

    function modificar(num) {
        fechaObjetivo.setMonth(fechaObjetivo.getMonth() + num);
        fechaTexto = fechaObjetivo.getDate() + "-" + (fechaObjetivo.getMonth() + 1) + "-" + fechaObjetivo.getFullYear();
        window.location.href = "controllers/router.php?code=listaIngresosHabitacion&fecha=" + fechaTexto;
    }

    function busquedaTexto() {
        let url = "controllers/router.php?code=listaIngresosHabitacion&textoBusqueda=" + encodeURIComponent(textoBusqueda.value) + "&campo=" + encodeURIComponent(selectorCampo.value);
        window.location.href = url;
    }

    function cancelarBusqueda() {
        let url = "controllers/router.php?code=listaIngresosHabitacion";
        window.location.href = url;
    }

    function exportarExcel() {
        const textoBusqueda = '<?php
                                if ((isset($_GET["textoBusqueda"]))) {
                                    echo "&textoBusqueda=" . $_GET["textoBusqueda"];
                                } else echo "";
                                ?>';
        const campoBusqueda = '<?php
                                if ((isset($_GET["campo"]))) {
                                    echo "&campo=" . $_GET["campo"];
                                } else echo "";
                                ?>';
        let url = "controllers/router.php?code=generadorArchivosExcel&action=ingresos" + textoBusqueda + campoBusqueda;
        window.location.href = url;
    };
</script>


