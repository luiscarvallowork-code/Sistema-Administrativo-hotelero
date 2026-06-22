<?php
if(isset($_GET["id"])){$id=$_GET["id"];}

if(isset($_GET["idMan"])){
    $id=$_GET["idMan"];
}
$data=myDB::obtenerDataMantenimiento($id);

$desactivarBoton="";
$claseBoton="btn btn--primary";
$textoBoton="Terminar Reparacion";
$colorCirculo = "var(--color-warning)";

$fechaFinal=new dateTime($data["fecha_final"]);
if($fechaFinal->format("Y")!="2250")
{
    $desactivarBoton="disabled";
    $claseBoton="btn btn--ghost";
    $textoBoton="Mantenimiento finalizado el ".$fechaFinal->format("d/m/Y");
    $colorCirculo = "var(--color-success)";
}

$fecha=new DateTime($data["fecha_inicio"]);
?>

<div class="estado-mantenimiento">
    <h1 class="estado-mantenimiento__titulo">Estado de Reparacion</h1>

    <div class="estado-mantenimiento__tarjeta">
        <div class="estado-mantenimiento__cabecera">
            <div class="estado-mantenimiento__numero" style="background: <?= $colorCirculo ?>;">
                <strong><?= $data["nombre"] ?></strong>
            </div>
            <div class="estado-mantenimiento__tipo">
                <h2>Habitación <?= $data["tipo"] ?></h2>
            </div>
        </div>

        <div class="estado-mantenimiento__cuerpo">
            <div class="estado-mantenimiento__grupo estado-mantenimiento__grupo--fecha">
                <span class="estado-mantenimiento__etiqueta">Fuera de servicio desde</span>
                <span class="estado-mantenimiento__valor">
                    <?= $fecha->format("d") ?> de
                    <?= tools::obtenerMesEspaniol($fecha->format("m")) ?>
                    del <?= $fecha->format("Y") ?>
                </span>
            </div>

            <div class="estado-mantenimiento__grupo estado-mantenimiento__grupo--vertical">
                <span class="estado-mantenimiento__etiqueta">Razón de la Reparacion</span>
                <span class="estado-mantenimiento__valor estado-mantenimiento__valor--razon">
                    <?= $data["descripcion"] ?>
                </span>
            </div>

            <form action="controllers\formularioControllers.php" method="POST" style="margin-top: var(--spacing-lg);">
                <input type="hidden" name="id_hab" value="<?= $data["id"] ?>">
                <button type="submit" <?= $desactivarBoton ?> class="<?= $claseBoton ?>" name="submit" value="submit_terminar_mantenimiento">
                   <?= $textoBoton ?>
                </button>
            </form>
        </div>
    </div>
</div>
