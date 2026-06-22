<?php
$limite = 30;
$num = 0;
$totalTasas = myDB::obtenerNumeroTasas();
$salto = 0;
$banIzq = false;
$banDerecha = true;

if (isset($_GET["num"])) {
    $num = $_GET["num"];
    $banIzq = true;
    $salto = $limite * $num;
    if ($salto <= 0) {
        $banIzq = false;
        $banDerecha = true;
    } else if (($salto + $limite) > $totalTasas) {
        $banDerecha = false;
    }
    $listaTasas = myDB::obtenerListaTasas($salto, $limite);
} else {
    $listaTasas = myDB::obtenerListaTasas(0, $limite);
}
?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Registro de Tasas Cambiarias</h1>

    <div class="lista-generica__barra-navegacion">
        <span></span>

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


    <div class="lista-generica__encabezado lista-generica__encabezado--tasas">
        <span>Tipo de Tasa</span>
        <span>Valor (Bs.)</span>
        <span>Fecha de Registro</span>

    </div>

    <?php
    foreach ($listaTasas as $tasa) {
        $fechaAux = new DateTime($tasa["fecha"]);
    ?>
        <div class="lista-generica__item lista-generica__item--tasas">
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $tasa["nombre"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato" style="color: #27ae60; font-weight: bold;"><?= $tasa["tasa"] ?> BS</span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fechaAux->format("d/m/Y") ?></span>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    const num = <?= $num ?>;

    function modificar(nuevo) {
        let salto = num + nuevo;
        window.location.href = "controllers/router.php?code=listaTasas&num=" + salto;
    }

    function exportarExcel() {
        const salto = "&salto=" + '<?= $salto ?>';
        const limite = "&limite=" + '<?= $limite ?>';
        let url = "controllers/router.php?code=generadorArchivosExcel&action=tasas" + salto + limite;
        window.location.href = url;
    };
</script>


