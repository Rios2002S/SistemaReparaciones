
<main>
        <?php
            // Establecer la zona horaria de Centroamérica (El Salvador)
            date_default_timezone_set('America/El_Salvador');

            // Establecer los nombres de los meses en español
            $meses = array(
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            );

            // Obtener la hora actual
            $hora_actual = date('H'); // La hora en formato de 24 horas (00-23)

            // Determinar el saludo según la hora
            if ($hora_actual >= 0 && $hora_actual < 12) {
                $saludo = "Buen día 🌅";
            } elseif ($hora_actual >= 12 && $hora_actual < 18) {
                $saludo = "Buenas tardes 🌞";
            } else {
                $saludo = "Buenas noches 🌙";
            }

            // Obtener la fecha actual (mes, día)
            $mes = $meses[date('n')]; // Usar el mes en español
            $dia = date('j'); // Día del mes (sin ceros a la izquierda)
        ?>
        <div class="container">
            <div class="alert alert-primary mt-4" role="alert">
                <h5><?php echo $saludo . " " . htmlspecialchars($nombre); ?></h5>
                <p>Fecha: <?php echo $mes . " " . $dia; ?></p>
                <p>Hora: <span id="hora"></span></p> 
            </div>       
        </div>

        <!-- Script para actualizar la hora dinámicamente -->
        <script>
            function actualizarHora() {
                const ahora = new Date();
                const horas = ahora.getHours().toString().padStart(2, '0');
                const minutos = ahora.getMinutes().toString().padStart(2, '0');
                const segundos = ahora.getSeconds().toString().padStart(2, '0');
                const horaActual = horas + ":" + minutos + ":" + segundos;
                document.getElementById('hora').textContent = horaActual;
            }

            // Llamar a la función de actualización cada 1000 ms (1 segundo)
            setInterval(actualizarHora, 1000);

            // Llamar a la función una vez al cargar la página para mostrar la hora inmediatamente
            actualizarHora();
        </script>
</main>