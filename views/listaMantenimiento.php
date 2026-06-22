<?php
$limite = 4;
$num = 0;
$totalMantenimientos = myDB::obtenerTotalReservaciones();

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
    } else if (($salto + $limite) > $totalMantenimientos) {
        $banDerecha = false;
    }
    $listaMamtenimiento = myDB::obtenerListaMantenimiento($salto, $limite);
} else {
    $listaMamtenimiento = myDB::obtenerListaMantenimiento(0, $limite);
}
?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Habitaciones en Mantenimiento</h1>

    <div class="lista-generica__barra-navegacion">
        <div class="lista-generica__grupo-navegacion">
            <?php if ($banIzq) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosAnteriores" onclick="modificar(-1)">⬅</button>
            <?php } ?>
        </div>

        <div class="lista-generica__grupo-navegacion">
            <?php if ($banDerecha) { ?>
                <button type="button" class="navegacion-fecha__boton" id="registrosSiguientes" onclick="modificar(1)">➡</button>
            <?php } ?>
        </div>
    </div>

    <div class="lista-generica__encabezado lista-generica__encabezado--mantenimiento">
        <span>Num Hab</span>
        <span>Resumen del Problema</span>
        <span>Fecha de Inicio</span>
        <span>Estado</span>
        <span></span>
    </div>

    <?php foreach ($listaMamtenimiento as $mantenimiento) {
        $fecha = new DateTime($mantenimiento["fecha_inicio"]);
        $fecha = $fecha->format("d/m/Y");
        $fechaFinal = new DateTime($mantenimiento["fecha_final"]);
        if ($fechaFinal->format("Y") == "2250") {
            $textoEstado = "En Proceso";
        } else {
            $textoEstado = "Completado";
        }
    ?>

        <div class="lista-generica__item lista-generica__item--mantenimiento">
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__numero-habitacion"><?= $mantenimiento["nombre"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato" style="white-space: normal;"><?= $mantenimiento["descripcion"] ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $fecha ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <span class="lista-generica__valor-dato"><?= $textoEstado ?></span>
            </div>
            <div class="lista-generica__grupo-dato">
                <a href="controllers/router.php?code=estadoMantenimientoHabitacion&id=<?= $mantenimiento["id"] ?>" class="lista-generica__enlace-accion">Ver detalle</a>
            </div>
        </div>

    <?php } ?>
</div>

<script>
    const num = <?= $num ?>;
    const codigoRouter = "listaMantenimiento";

    function modificar(nuevo) {
        let salto = num + nuevo;
        let url = "controllers/router.php?code=" + codigoRouter + "&num=" + salto;
        window.location.href = url;
    }
</script>
