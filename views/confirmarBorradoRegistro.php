<?php
?>

<div class="card" style="max-width: 500px; margin: 30px auto;">
    <form action="controllers\formularioControllers.php" method="post">
        <h3>ESTA SEGURO DE BORRAR PERMANENTEMENTE EL REGISTRO DE RENTA NUMERO : <?=$_GET["idRenta"]?></h3>
        <input type="number" name="id_rentaHabitacion" value="<?= $_GET["idRenta"]?>" hidden>
        <div style="display: flex; gap: var(--spacing-sm); margin-top: var(--spacing-lg);">
            <button type="submit" name="submit" value="submit_borrado_registroRenta" class="btn btn--danger">Confirmar borrado</button>
            <button type="submit" name="submit" id="js-btnCancelarBorrado" value="submit_cancelar_borrado_registroRenta" class="btn btn--ghost">Cancelar</button>
        </div>
    </form>
</div>
