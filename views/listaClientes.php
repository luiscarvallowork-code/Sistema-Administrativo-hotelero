<?php

$limite = 10;
$num = 0;
$totalClientes = myDB::obtenerNumeroClientes();
$salto = 0;
$banIzq = false;
$banDerecha = true;

$campoSelecionado = "";

if (isset($_GET["campo"])) {
    $campoSelecionado = $_GET["campo"];
}

$textoBusqueda = "";

if (isset($_GET["textoBusqueda"])) {
    $textoBusqueda = $_GET["textoBusqueda"];
}

if (isset($_GET["num"])) {
    $num = $_GET["num"];
}

    $listaClientes = myDB::obtenerListaClientes($num, $limite, $campoSelecionado, $textoBusqueda);
    $totalClientes = myDB::obtenerNumeroClientes();

$totalPagina = $num + $limite;

if ($num != 0) {
    $banIzq = true;
}
if ($totalPagina >= $totalClientes) {
    $banDerecha = false;
}

?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Lista de Clientes</h1>
    <div class="lista-generica__barra-navegacion">
        <div class="lista-generica__grupo-busqueda">
            <input type="text" class="input" id="js-inputBusqueda" value="<?= htmlspecialchars($textoBusqueda) ?>" style="max-width: 200px;">
            <select name="selectorCampo" id="js-selectorCampo" class="select" style="max-width: 140px;">
                <option value="nombre" <?php if ($campoSelecionado == "nombre") echo "selected " ?>>nombre</option>
                <option value="ci" <?php if ($campoSelecionado == "ci") echo "selected " ?>>C�dula</option>
                <option value="numeroTelefono" <?php if ($campoSelecionado == "numeroTelefono") echo "selected " ?>>Tel�fono</option>
                <option value="ciudad" <?php if ($campoSelecionado == "ciudad") echo "selected " ?>>Ciudad</option>
                <option value="empresa" <?php if ($campoSelecionado == "empresa") echo "selected " ?>>Compa��a</option>
            </select>
            <button class="btn btn--ghost btn--sm btn--buscar" onclick="busquedaTexto()">🔍</button>
            <?php if ((isset($_GET["textoBusqueda"]))) { ?>
                <button class="btn btn--ghost btn--sm btn--buscar" onclick="cancelarBusqueda()">❌</button>
            <?php } ?>
        </div>

        <div class="lista-generica__grupo-navegacion">
            <?php if ($banIzq) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosAnteriores" onclick="modificar(-1)">
                    ⬅
                </button>
            <?php } ?>
            <?php if ($banDerecha) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosSiguientes" onclick="modificar(1)">
                    ➡
                </button>
            <?php } ?>
        </div>

        <button class="btn btn--info btn--sm" onclick="exportarExcel()">Exportar Excel</button>
    </div>
    <div class="lista-generica__encabezado lista-generica__encabezado--clientes">
        <span>Nombre</span>
        <span>Cedula</span>
        <span>Telefono</span>
        <span>Ciudad</span>
        <span>Compañia</span>
        <span>Ult. Reserva</span>
        <span></span>
    </div>

    <?php
    foreach ($listaClientes as $cliente) {
        if ($cliente["ultimaFechaEntrada"] != null) {
            $fechaAux = new DateTime($cliente["ultimaFechaEntrada"]);
    ?>
            <div class="lista-generica__item lista-generica__item--clientes">
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $cliente["nombre"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $cliente["ci"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $cliente["numeroTelefono"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $cliente["ciudad"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $cliente["empresa"] ?></span>
                </div>
                <div class="lista-generica__grupo-dato">
                    <span class="lista-generica__valor-dato"><?= $fechaAux->format("d/m/yy")  ?></span>
                </div>
                <a href="controllers/router.php?code=editarDatosCliente&id=<?= $cliente["id"] ?>" class="lista-generica__enlace-accion">Editar</a>
            </div>
    <?php }
    } ?>
</div>

<script>
    const num = <?= $num ?>;
    const selectorCampo = document.getElementById("js-selectorCampo");

    function modificar(nuevo) {
        salto = num + nuevo;
        window.location.href = "controllers/router.php?code=listaClientes&num=" + salto;
    }

    function busquedaTexto() {
        const textoBusqueda = document.getElementById("js-inputBusqueda");
        window.location.href = "controllers/router.php?code=listaClientes&textoBusqueda=" + textoBusqueda.value + "&campo=" + selectorCampo.value;
    }

    function cancelarBusqueda() {
        window.location.href = "controllers/router.php?code=listaClientes";
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
        let url = "controllers/router.php?code=generadorArchivosExcel&action=clientes"+textoBusqueda+campoBusqueda;
        window.location.href = url;
    };
</script>
