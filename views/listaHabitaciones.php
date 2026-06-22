<?php

$listaHabitaciones=myDB::obtenerListaDatosHabitaciones();

?>

<div class="lista-generica">
    <h1 class="lista-generica__titulo">Lista de Habitaciones</h1>

    <div class="lista-generica__encabezado lista-generica__encabezado--habitaciones">
        <span>Nombre / Nro</span>
        <span>Tipo de Habitación</span>
        <span>Precio (USD)</span>
        <span>Última Reserva</span>
        <span></span>
    </div>

    <?php
    foreach($listaHabitaciones as $data){
       if($data["ultimaFechaEntrada"]){
            $fecha=new DateTime($data["ultimaFechaEntrada"]);
            $fecha=$fecha->format("d/m/Y");
       }
       else{
        $fecha="Sin Ingresos";
       }
    ?>

    <div class="lista-generica__item lista-generica__item--habitaciones">
        <div class="lista-generica__grupo-dato">
            <span class="lista-generica__valor-dato"><?= $data["nombre"] ?></span>
        </div>
        <div class="lista-generica__grupo-dato">
            <span class="lista-generica__valor-dato"><?= $data["tipo"] ?></span>
        </div>
        <div class="lista-generica__grupo-dato">
            <span class="lista-generica__valor-dato"><?= $data["cantidad"] ?></span>
        </div>
        <div class="lista-generica__grupo-dato">
            <span class="lista-generica__valor-dato"><?= $fecha ?></span>
        </div>

        <a href="controllers/router.php?code=actualizarDatosHabitaciones&id=<?= $data["id"] ?>" class="lista-generica__enlace-accion">Actualizar</a>
    </div>

   <?php
   }
   ?>

</div>
