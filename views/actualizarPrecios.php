<?php
$listaPrecios = myDB::obtenerListaPreciosHabitacion();
?>

<div class="contenedor-centrado">
    <h1 class="tablero__titulo">Actualizar Tarifas</h1>
    <div class="card">
        <form action="controllers\formularioControllers.php" method="POST">
            <div class="grupo-campo">
                <label class="etiqueta">Tipo de Habitación</label>
                <select name="tipo_habitacion" class="select" required id="js-tipoHabitacion">
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <?php
                    foreach ($listaPrecios as $precio) {
                    ?>
                         <option value="<?= $precio["id"] ?>" cantidad=<?= $precio["cantidad"] ?>><?= $precio["nombre"] ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="grupo-campo">
                <label class="etiqueta">Nuevo Precio Base (USD)</label>
                <input id="js-nuevoPrecio" type="number" step="any" name="nuevo_precio" class="input" placeholder="0.00" required>
            </div>
            <button type="submit" name="submit" value="submit_actualizarPrecio" class="btn btn--primary">Actualizar Precio</button>
        </form>
    </div>
</div>

<script>
    const listaPrecios = <?= json_encode($listaPrecios) ?>;
    const tipo_habitacion = document.getElementById("js-tipoHabitacion");
    const nuevo_precio = document.getElementById("js-nuevoPrecio");

    tipo_habitacion.addEventListener("change", ()=>{
        listaPrecios.forEach(element => {
           if(element.id == tipo_habitacion.value){
                nuevo_precio.value = element.cantidad;
           }
        });
    });
</script>
