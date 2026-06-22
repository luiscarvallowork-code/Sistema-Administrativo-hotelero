<?php
$limite = 10;
$num = 0;
$totalReservas = myDB::obtenerTotalReservaciones();
$salto = 0;
$banIzq = false;
$banDerecha = true;
$textoBusqueda = "";
$campoSeleccionado = "";

if (isset($_GET["num"])) {
    $num = $_GET["num"];
    $banIzq = true;
    $salto = $limite * $num;
    if ($salto <= 0) {
        $banIzq = false;
        $banDerecha = true;
    } else if (($salto + $limite) > $totalReservas) {
        $banDerecha = false;
    }
    $listaReservacionesActivas = myDB::obtenerListaReservaciones($salto, $limite);
} elseif (isset($_GET["textoBusqueda"]) && isset($_GET["campo"])) {
    $textoBusqueda = $_GET["textoBusqueda"];
    $limite = 999999;
    $banIzq = false;
    $banDerecha = false;
    $campoSeleccionado = $_GET["campo"];
    $listaReservacionesActivas = myDB::obtenerListaReservaciones(0, $limite, $campoSeleccionado, $textoBusqueda);
} else {
    $listaReservacionesActivas = myDB::obtenerListaReservaciones(0, $limite);
}
?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Lista de Reservaciones</h1>

    <div class="lista-generica__barra-navegacion">
        <div class="lista-generica__grupo-busqueda">
            <input type="text" class="input" id="js-inputBusqueda" value="<?= htmlspecialchars($textoBusqueda) ?>" style="max-width: 200px;">
            <select name="selectorCampo" id="js-selectorCampo" class="select" style="max-width: 140px;">
                <option value="cliente" <?php if ($campoSeleccionado == "cliente") echo "selected " ?>>Cliente</option>
                <option value="hab" <?php if ($campoSeleccionado == "hab") echo "selected " ?>>Habitaci&#243;n</option>
                <option value="estadoPago" <?php if ($campoSeleccionado == "estadoPago") echo "selected " ?>>Estado pago</option>
                <option value="estado" <?php if ($campoSeleccionado == "estado") echo "selected " ?>>Estado reservacion</option>
            </select>
            <button class="btn btn--ghost btn--sm btn--buscar" onclick="busquedaTexto()">&#x1F50D;</button>
            <?php if (isset($_GET["textoBusqueda"])) { ?>
                <button class="btn btn--ghost btn--sm btn--buscar" onclick="cancelarBusqueda()">&#x2716;</button>
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


    <div class="lista-generica__encabezado lista-generica__encabezado--reservas">
        <span>Cliente</span>
        <span>Habitacion</span>
        <span>Fecha Entrada</span>
        <span>Fecha Salida</span>
        <span>Estado Pago</span>
        <span>Estado Res.</span>
        <span></span>

    </div>

    <?php
    foreach ($listaReservacionesActivas as $res) {
        $fechaEntradaAux = tools::fechaF_dmy($res["fechaEntrada"]);
        $fechaSalidaAux = tools::fechaF_dmy($res["fechaSalida"]);
        $pago = "";
        $clasePago = "";
        $estadoReservacion = "";
        $claseEstadoReservacion = "";

        if ($res["estadoPago"]) {
            $pago = "Pagado";
            $clasePago = "insignia insignia--exito";
        } else {
            $pago = "Pendiente";
            $clasePago = "insignia insignia--advertencia";
        }

        if ($res["estado"] == 0) {
            $estadoReservacion = "Caducada";
            $claseEstadoReservacion = "insignia insignia--advertencia";
        } else if ($res["estado"] == 1) {
            $estadoReservacion = "Activa";
            $claseEstadoReservacion = "insignia insignia--exito";
        } else {
            $estadoReservacion = "Completada";
            $claseEstadoReservacion = "insignia insignia--exito";
        }
    ?>
        <div class="lista-generica__item lista-generica__item--reservas">
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $res["cliente"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__numero-habitacion"><?= $res["hab"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fechaEntradaAux ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fechaSalidaAux ?></span>
            </div>
            <div>
                <span class="<?= $clasePago ?>"><?= $pago ?></span>
            </div>
            <div>
                <span class="<?= $claseEstadoReservacion ?>"><?= $estadoReservacion ?></span>
            </div>
            <div class="lista-generica__grupo-botones">
                <?php if($res["estado"] == 1){ ?>
                    <form action="controllers\formularioControllers.php" method="post">
                        <input type="number" value="<?= $res["id"] ?>" name="id_reservacion" hidden>
                        <input type="number" value="<?= $res["idRenta"] ?>" name="id_renta" hidden>
                        <button type="submit" name="submit" value="submit_convertirReservacionIngreso" class="btn btn--primary btn--sm">Confirmar Ingreso</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script>
    const num = <?= $num ?>;
    const selectorCampo = document.getElementById("js-selectorCampo");
    const codigoRouter = "listaReservacion";

    function modificar(nuevo) {
        let salto = num + nuevo;
        let url = "controllers/router.php?code=" + codigoRouter + "&num=" + salto;
        window.location.href = url;
    }

    function busquedaTexto() {
        const textoBusqueda = document.getElementById("js-inputBusqueda");
        let url = "controllers/router.php?code=" + codigoRouter + "&textoBusqueda=" + encodeURIComponent(textoBusqueda.value) + "&campo=" + encodeURIComponent(selectorCampo.value);
        window.location.href = url;
    }

    function cancelarBusqueda() {
        let url = "controllers/router.php?code=" + codigoRouter;
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
        let url = "controllers/router.php?code=generadorArchivosExcel&action=reservas" + textoBusqueda + campoBusqueda;
        window.location.href = url;
    };
</script>


