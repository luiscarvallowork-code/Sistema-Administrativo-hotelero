<?php
include_once "model/CRUD.php";


include_once "controllers/tools.php";



if ($_GET["action"] == "clientes") {

    $texto = isset($_GET["textoBusqueda"]) ? $_GET["textoBusqueda"] : "";
    $textoUrl = isset($_GET["textoBusqueda"]) ? "&textoBusqueda=" . $_GET["textoBusqueda"] : "";
    $campo = isset($_GET["campo"]) ? $_GET["campo"] : false;
    $campoUrl = isset($_GET["campo"]) ? "&campo=" . $_GET["campo"] : "";
    $urlRedireccion = "controllers/router.php?code=listaClientes" . $textoUrl . $campoUrl;

    $listaBD = myDB::obtenerListaClientes(0, 99999, $campo, $texto);

    $datosExcel = [];
    foreach ($listaBD as $fila) {
        $fecha = new DateTime($fila["ultimaFechaEntrada"]);

        $datosExcel[] = [
            "Nombre"       => $fila["nombre"],
            "Cédula"       => $fila["ci"],
            "Teléfono"     => $fila["numeroTelefono"],
            "Última Renta" => $fecha->format("d/m/Y"),
            "Ciudad"       => $fila["ciudad"],
            "Empresa"      => $fila["empresa"]
        ];
    }
    $nombreHoja = "clientes";
    $nombreArchivo = "clientes.xlsx";
    $anchosColumnas = [30, 20, 20, 15, 30, 35];
}

if ($_GET["action"] == "pagos") {

    $texto = isset($_GET["textoBusqueda"]) ? $_GET["textoBusqueda"] : "";
    $textoUrl = isset($_GET["textoBusqueda"]) ? "&textoBusqueda=" . $_GET["textoBusqueda"] : "";
    $campo = isset($_GET["campo"]) ? $_GET["campo"] : "false";
    $campoUrl = isset($_GET["campo"]) ? "&campo=" . $_GET["campo"] : "";
    $moneda = isset($_GET["moneda"]) ? "&moneda=" . $_GET["moneda"] : "";
    $monedaUrl = isset($_GET["moneda"]) ? "&moneda=" . $_GET["moneda"] : "";

    $urlRedireccion = "controllers/router.php?code=listaPagos" . $textoUrl . $campoUrl . $monedaUrl;

    $listaBD = myDB::obtenerListaDatosPagos(0, 99999, $campo, $texto);

    $datosExcel = [];
    foreach ($listaBD as $fila) {
        $fecha = new DateTime($fila["fecha"]);

        $datosExcel[] = [
            "cliente"       => $fila["cliente"],
            "Cantidad"       => $fila["cantidad"],
            "Moneda"     => $fila["codigo"],
            "Fecha" => $fecha->format("d/m/Y"),
            "referencia"       => $fila["referencia"],
            "Tipo pago"      => $fila["tipo"],
            "Num Hab"      => $fila["nombre"],
        ];
    }
    $nombreHoja = "pagos";
    $nombreArchivo = "pagos.xlsx";
    $anchosColumnas = [30, 15, 10, 15, 10, 20, 10];
}

if ($_GET["action"] == "tasas") {

    $salto = isset($_GET["salto"]) ? $_GET["salto"] : "";

    $limite = isset($_GET["limite"]) ? $_GET["limite"] : "false";



    $urlRedireccion = "controllers/router.php?code=listaTasas";

    $listaBD = myDB::obtenerListaTasas($salto, $limite);

    $datosExcel = [];
    foreach ($listaBD as $fila) {
        $fecha = new DateTime($fila["fecha"]);

        $datosExcel[] = [
            "Nombre"       => $fila["nombre"],
            "Tasa"       => $fila["tasa"],
            "Fecha" => $fecha->format("d/m/Y"),

        ];
    }
    $nombreHoja = "tasas BCV";
    $nombreArchivo = "tasas bcv.xlsx";
    $anchosColumnas = [15, 20, 15];
}

if ($_GET["action"] == "ingresos") {
    $texto = isset($_GET["textoBusqueda"]) ? $_GET["textoBusqueda"] : false;
    $textoUrl = isset($_GET["textoBusqueda"]) ? "&textoBusqueda=" . $_GET["textoBusqueda"] : "";
    $campo = isset($_GET["campo"]) ? $_GET["campo"] : "";
    $campoUrl = isset($_GET["campo"]) ? "&campo=" . $_GET["campo"] : "";

    $fecha = isset($_GET["fecha"]) ? $_GET["fecha"] : false;
    if ($texto == "") {
        $fechaAux = new DateTime();
        $fecha = $fechaAux->format("Y-m-d");
    }

    $fechaUrl = isset($_GET["fecha"]) ? "&fecha=" . $_GET["fecha"] : "";

    $urlRedireccion = "controllers/router.php?code=listaIngresosHabitacion" . $fechaUrl . $textoUrl . $campoUrl;
    // $urlRedireccion = "controllers/router.php?code=listaTasas";

    $listaBD = myDB::obtenerListaIngresosTotales($fecha, $campo, $texto);
    tools::mostrarVariableConsolaJs($listaBD);
    $datosExcel = [];
    foreach ($listaBD as $fila) {
        $fechaEntrada = new DateTime($fila["fechaEntrada"]);
        $fechaSalida = new DateTime($fila["fechaSalida"]);

        $datosExcel[] = [
            "Num Hab" => $fila["nombre"],
            "Entrada" => $fechaEntrada->format("d/m/Y"),
            "Salida" => $fechaSalida->format("d/m/Y"),
            "Cliente"       => $fila["cliente"],
            "Estado Pago"   => $fila["estadoPago"] == true ? "Pagado" : "Pendiente",


        ];
    }
    $nombreHoja = "rentas";
    $nombreArchivo = "rentas.xlsx";
    $anchosColumnas = [15, 25, 20, 20, 20];
}


if ($_GET["action"] == "reservas") {
    // 1. Capturamos los datos de búsqueda de la URL si existen
    $texto = isset($_GET["textoBusqueda"]) ? $_GET["textoBusqueda"] : "";
    $textoUrl = isset($_GET["textoBusqueda"]) ? "&textoBusqueda=" . $_GET["textoBusqueda"] : "";
    $campo = isset($_GET["campo"]) ? $_GET["campo"] : "";
    $campoUrl = isset($_GET["campo"]) ? "&campo=" . $_GET["campo"] : "";

    // 2. Definimos la URL de redirección correcta para reservaciones
    $urlRedireccion = "controllers/router.php?code=listaReservacion" . $textoUrl . $campoUrl;

    // 3. Ejecutamos la consulta enviando los filtros (Límite alto para exportar todo lo buscado)
    $listaBD = myDB::obtenerListaReservaciones(0, 999999, $campo, $texto);

    $datosExcel = [];

    foreach ($listaBD as $fila) {
        // Creamos los objetos DateTime con los índices correctos de tu base de datos
        $fechaEntrada = new DateTime($fila["fechaEntrada"]);
        $fechaSalida = new DateTime($fila["fechaSalida"]);

        // Lógica para el Estado de Pago (Booleano)
        $estadoPagoText = $fila["estadoPago"] ? "Pagado" : "Pendiente";

        // Lógica para el Estado de la Reservación (0, 1 u otros)
        if ($fila["estado"] == 0) {
            $estadoResText = "Caducada";
        } else if ($fila["estado"] == 1) {
            $estadoResText = "Activa";
        } else {
            $estadoResText = "Completada";
        }

        // Armamos la fila con las columnas correspondientes a la vista
        $datosExcel[] = [
            "Cliente"            => $fila["cliente"],
            "Hab"         => $fila["hab"],
            "fecha Entrada"           => $fechaEntrada->format("d/m/Y"),
            "fecha Salida"          => $fechaSalida->format("d/m/Y"),
            "Estado Pago"        => $estadoPagoText,
            "Estado Reservación" => $estadoResText,
        ];
    }
    $nombreHoja = "reservas";
    $nombreArchivo = "reservas.xlsx";
    $anchosColumnas = [25, 15, 20, 20, 20, 20];
}

tools::mostrarVariableConsolaJs($listaBD);
$jsonDatos = json_encode($datosExcel, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
$jsonAnchos = isset($anchosColumnas) ? json_encode($anchosColumnas) : "[]";

?>


<script src="resources\librerias\xlsx.full.min.js"></script>
<script>
    // 1. Recibir los datos generados dinámicamente desde PHP
    const datos = <?= $jsonDatos ?>;
    const nombreArchivo = "<?= $nombreArchivo ?? 'Exportacion.xlsx' ?>";
    const nombreHoja = "<?= $nombreHoja ?? 'Datos' ?>";
    const anchos = <?= $jsonAnchos ?>;
    const urlRedireccion = "<?= $urlRedireccion ?? 'javascript:history.back()' ?>";

    if (datos && datos.length > 0) {
        // 2. Convertir el arreglo JSON a la hoja de cálculo
        // SheetJS tomará automáticamente las claves del objeto como cabeceras de columnas
        const hoja = XLSX.utils.json_to_sheet(datos);

        // 3. Aplicar anchos de columna dinámicos si existen
        if (anchos.length > 0) {
            hoja['!cols'] = anchos.map(ancho => ({
                wch: ancho
            }));
        }

        // 4. Crear el libro de trabajo y añadir la hoja
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, nombreHoja);

        // 5. Descargar el archivo
        XLSX.writeFile(libro, nombreArchivo);
    } else {
        alert("No hay datos para exportar.");
    }

    // 6. Retornar a la vista anterior
    window.location.href = urlRedireccion;
</script>