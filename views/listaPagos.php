<?php
$limite = 10;
$num = 0;
$totalPagos = myDB::obtenerNumeroPagos();
$salto = 0;
$banIzq = false;
$banDerecha = true;
$textoBusqueda = "";
$campoSelecionado = "";
$moneda = null;

if (isset($_GET["moneda"])) {
    $moneda = $_GET["moneda"];
}

if (isset($_GET["num"])) {
    $num = $_GET["num"];
    $banIzq = true;
    $salto = $limite * $num;
    if ($salto <= 0) {
        $banIzq = false;
        $banDerecha = true;
    } else if (($salto + $limite) > $totalPagos) {
        $banDerecha = false;
    }
    $listaPagos = myDB::obtenerListaDatosPagos($salto, $limite);
} elseif (isset($_GET["textoBusqueda"]) && isset($_GET["campo"])) {
    $textoBusqueda = $_GET["textoBusqueda"];
    $limite = 999999;
    $banIzq = false;
    $banDerecha = false;
    $campoSelecionado = $_GET["campo"];
    $listaPagos = myDB::obtenerListaDatosPagos(0, $limite, $campoSelecionado, $textoBusqueda);
} else {
    $listaPagos = myDB::obtenerListaDatosPagos(0, $limite);
}
?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Historial de Pagos</h1>

    <div class="lista-generica__barra-navegacion">
        <div class="lista-generica__grupo-busqueda">
            <div class="lista-generica__grupo-botones">
                <a href="<?php if ($moneda != "BS") echo "controllers/router.php?code=listaPagos&moneda=BS"; ?>" class="btn btn--ghost btn--sm btn--buscar <?php if ($moneda == "BS") echo "btn--success"; ?>">Bs</a>
                <a href="<?php if ($moneda != "USD") echo "controllers/router.php?code=listaPagos&moneda=USD"; ?>" class="btn btn--ghost btn--sm btn--buscar <?php if ($moneda == "USD") echo "btn--success"; ?>">USD</a>
            </div>
            <input type="text" class="input" id="js-inputBusqueda" value="<?= htmlspecialchars($textoBusqueda) ?>" style="max-width: 200px;">
            <select name="selectorCampo" id="js-selectorCampo" class="select" style="max-width: 140px;">
                <option value="cliente" <?php if ($campoSelecionado == "cliente") echo "selected " ?>>nombre</option>
                <option value="referencia" <?php if ($campoSelecionado == "referencia") echo "selected " ?>>Referencia</option>
                <option value="cantidad" <?php if ($campoSelecionado == "cantidad") echo "selected " ?>>Cantidad</option>
                <option value="tipo" <?php if ($campoSelecionado == "tipo") echo "selected " ?>>tipo</option>
                <option value="nombre" <?php if ($campoSelecionado == "nombre") echo "selected " ?>>Num Hab</option>
            </select>
            <button class="btn btn--ghost btn--sm btn--buscar" onclick="busquedaTexto()">&#x1F50D;</button>
            <?php if ((isset($_GET["textoBusqueda"]))) { ?>
                <button class="btn btn--ghost btn--sm btn--buscar" onclick="cancelarBusqueda()">❌</button>
            <?php } ?>
        </div>

        <div class="lista-generica__grupo-navegacion">
            <?php if ($banIzq) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosAnteriores" onclick="modificar(-1)">⬅</button>
            <?php } ?>
            <?php if ($banDerecha) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosSiguientes" onclick="modificar(1)">➡</button>
            <?php } ?>
        </div>

        <button class="btn btn--info btn--sm" onclick="exportarExcel()">Exportar Excel</button>
    </div>


    <div class="lista-generica__encabezado lista-generica__encabezado--pagos">
        <span>Huesped</span>
        <span>Fecha de pago</span>
        <span>Monto</span>
        <span>T. Pago</span>
        <span>Tasa</span>
        <span>Referencia</span>
        <span>Habitacion</span>

    </div>

    <?php
    foreach ($listaPagos as $pago) {
        $fecha = new DateTime($pago["fecha"]);
        $fechaTasa = new DateTime($pago["fechaTasa"]);
        if ($moneda == null || $pago["codigo"] == $moneda) {
    ?>
            <div class="lista-generica__item lista-generica__item--pagos">
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $pago["cliente"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $fecha->format("d/m/Y") ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $pago["amount"] ?? $pago["cantidad"] ?> <?= $pago["codigo"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $pago["tipo"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__etiqueta-dato">Tasa BCV <?= $fechaTasa->format("d/m/Y") ?></span>
                    <span class="lista-generica__valor-dato"><?= $pago["tasa"] ?> BS</span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $pago["referencia"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__numero-habitacion"><?= $pago["nombre"] ?></span>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>

<script>
    const num = <?= $num ?>;
    const selectorCampo = document.getElementById("js-selectorCampo");
    const monedaActual = '<?= $moneda ?? "" ?>';

    function modificar(nuevo) {
        let salto = num + nuevo;
        let url = "controllers/router.php?code=listaPagos&num=" + salto;
        if (monedaActual) {
            url += "&moneda=" + monedaActual;
        }
        window.location.href = url;
    }

    function busquedaTexto() {
        const textoBusqueda = document.getElementById("js-inputBusqueda");
        let url = "controllers/router.php?code=listaPagos&textoBusqueda=" + encodeURIComponent(textoBusqueda.value) + "&campo=" + encodeURIComponent(selectorCampo.value);
        if (monedaActual) {
            url += "&moneda=" + monedaActual;
        }
        window.location.href = url;
    }

    function cancelarBusqueda() {
        let url = "controllers/router.php?code=listaPagos";
        if (monedaActual) {
            url += "?moneda=" + monedaActual;
        }
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
        const monedaAux = monedaActual ? "&moneda=" + monedaActual : "";
        let url = "controllers/router.php?code=generadorArchivosExcel&action=pagos" + textoBusqueda + campoBusqueda + monedaAux;
        window.location.href = url;
    };
</script>


