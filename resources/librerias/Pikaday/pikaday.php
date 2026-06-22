<link rel="stylesheet" type="text/css" href="resources\librerias\Pikaday\pikaday.css">
<script src="resources\librerias\Pikaday\pikaday.js"></script>

<script>
    const i18n = {
        previousMonth: 'Mes anterior',
        nextMonth: 'Mes siguiente',
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
    };
    // input de tipo texto, aparece vacio el input?, numero de dias a sumar (o restar), fecha por defecto
    function crearInputPikaday(inputFecha, vacio = false, addD = 0, fecha = null) {
        const hoy = vacio ? null : new Date();
        const fechaAuxiliar = fecha ? new Date(fecha.replace(/-/g, '/')) : null;
        const fechaDefault = fechaAuxiliar ? fechaAuxiliar : hoy;



        const picker = new Pikaday({
            field: inputFecha,
            format: 'DD-MM-YYYY', // El formato que necesitas
            defaultDate: fechaDefault,
            setDefaultDate: true, // Fuerza a que el input muestre la fecha actual al cargar
            toString(date, format) {
                // Aquí puedes personalizar cómo se devuelve la cadena de texto
                const day = date.getDate() + addD;
                const month = date.getMonth() + 1;
                const year = date.getFullYear();
                return `${day < 10 ? '0' + day : day}/${month < 10 ? '0' + month : month}/${year}`;
            },
            onSelect: function(date) {
                // Usamos CustomEvent para poder pasarle detalles (la fecha)
                let evento = new CustomEvent('fechaCambiada', {
                    detail: {
                        fechaTexto: this.toString(), // Ej: "2026-05-22"
                        fechaObjeto: date // El objeto Date nativo
                    }
                });
                inputFecha.dispatchEvent(evento);
            },


            i18n: i18n
        });

        return picker
    }

    
    function fechaAnioMesDia(pikadayfecha) {

        const fecha = pikadayfecha.getDate();
        return fecha.getFullYear() +
            "-" +
            (fecha.getMonth() + 1 < 10 ? "0" + (fecha.getMonth() + 1) : fecha.getMonth() + 1) +
            "-" +
            fecha.getDate();
        //anio-mes-dia
    };

    function fechaAnioMesDia2(pikadayfecha) {

        const fecha = pikadayfecha.getDate();
        return fecha.getFullYear() +
            "/" +
            (fecha.getMonth() + 1 < 10 ? "0" + (fecha.getMonth() + 1) : fecha.getMonth() + 1) +
            "/" +
            fecha.getDate();
        //anio/mes/dia
    };
</script>