</main>
<!-- Incluir Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // JavaScript para hacer el navbar pegajoso al hacer scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) { // Ajusta el valor según el punto en el que quieres que se vuelva pegajoso
                navbar.classList.add('navbar-sticky');
            } else {
                navbar.classList.remove('navbar-sticky');
            }
        });
    </script>

<script>
    $(document).ready(function() {
        $('#tablaReparaciones').DataTable({
            pageLength: 10,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    });
</script>

<script>
    function imprimirTicket(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion) {
        const { jsPDF } = window.jspdf;

        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [76, 210], 
        });

        let yPosition = -10;

        // Agregar el logo centrado
        let logoWidth = 70;  
        let logoHeight = 70; 
        doc.addImage('https://i.ibb.co/kgNwmJs/logo2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
        yPosition += logoHeight + -10;

        // Título principal
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("GRUPO MULTICOMP", 76 / 2, yPosition, { align: "center" });
        yPosition += 5;
        doc.text("(Taller Profesional)", 76 / 2, yPosition, { align: "center" });
        yPosition += 5;
        doc.text("(Tels: 7727-9900 / 24066984)", 76 / 2, yPosition, { align: "center" });
        yPosition += 3;

        // Línea de separación
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Función para dividir texto en líneas
        function dividirTexto(texto, maxWidth) {
            return doc.splitTextToSize(texto, maxWidth);
        }

        // Detalles principales
        const detalles = [
            { label: "Orden:", value: solucion },
            { label: "Ticket:", value: codigoTicket },
            { label: "Fecha:", value: fecha },
            { label: "Cliente:", value: nombreCliente },
            { label: "Teléfono:", value: telefonoCliente },
            { label: "Equipo:", value: tipoEquipo },
            { label: "Serie:", value: serie },
            { label: "Sucursal:", value: sucursal },
            { label: "Recibe:", value: recibeUsuario },
        ];

        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');

        // Procesar cada detalle y manejar saltos de línea
        detalles.forEach((item) => {
            const texto = `${item.label} ${item.value}`;
            const lineas = dividirTexto(texto, 66); // Ancho máximo ajustado a 66mm
            lineas.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 4; // Espaciado entre líneas
            });
            yPosition += 2; // Espaciado extra entre secciones
        });

        // Separador entre detalles y Problema/Condiciones
        yPosition += -2;
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Problema
        if (problemaEquipo.length > 0) {
            const textoProblema = `Problema: ${problemaEquipo}`;
            const lineasProblema = dividirTexto(textoProblema, 66);
            lineasProblema.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 5;
            });
            yPosition += 3; // Espaciado adicional después del problema
        }

        // Condiciones
        if (condicionesEntrega.length > 0) {
            const textoCondiciones = `Condiciones: ${condicionesEntrega}`;
            const lineasCondiciones = dividirTexto(textoCondiciones, 66);
            lineasCondiciones.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 5;
            });
            yPosition += 5; // Espaciado adicional después de las condiciones
        }

        // Línea de separación antes de la sección de costos
        yPosition += -6; // Espaciado para la línea de separación
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Sección de Costos: Costo Total, Adelanto, Saldo Pendiente
        const costos = [
            { label: "Costo Total:", value: `$${costo.toFixed(2)}` },
            { label: "Adelanto:", value: `$${adelanto.toFixed(2)}` },
            { label: "Saldo Pendiente:", value: `$${saldoPendiente.toFixed(2)}` },
        ];

        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold'); // Para destacar la sección de costos

        costos.forEach((item) => {
            doc.text(`${item.label}`, 5, yPosition); // Etiqueta a la izquierda
            doc.text(`${item.value}`, 38, yPosition); // Valor a la derecha
            yPosition += 5;
        });

        // Línea final de separación
        yPosition += -1;
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Mensaje de agradecimiento
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.text("Gracias por confiar en nuestro servicio.", 5, yPosition);
        yPosition += 6;

        // Mensaje de responsabilidad
        const nota = "Nota: NO NOS HACEMOS RESPONSABLES POR TRABAJOS NO RETIRADOS DESPUES DE 30 DIAS.";
        const lineasNota = dividirTexto(nota, 66);
        lineasNota.forEach((linea) => {
            doc.text(linea, 5, yPosition);
            yPosition += 4; // Espaciado entre líneas
        });

        // Guardar el PDF con un nombre descriptivo
        doc.save(`ticket_${idRep}_${nombreCliente}.pdf`);
        doc.autoPrint(); // Prepara el PDF para impresión
        window.open(doc.output('bloburl'), '_blank'); // Abre el PDF en una nueva ventana y activa la impresión
    }
</script>


<script>
    function imprimirPDF(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('portrait', 'mm', 'a4');

        // Agregar imagen de fondo
        const backgroundUrl = 'https://i.ibb.co/tbqfXSY/2recurso.jpg';
        doc.addImage(backgroundUrl, 'JPEG', 0, 0, 210, 297); 

        // Título
        doc.setFontSize(22);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 0, 0);
        doc.text("GRUPO MULTICOMP", 20, 50);
        doc.text("(Taller Profesional)", 30, 60); 

        // Línea de separación
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0); 
        doc.line(20, 65, 190, 65);

        // Logo
        const logoUrl = 'https://i.ibb.co/kgNwmJs/logo2.png'; 
        doc.addImage(logoUrl, 'PNG', 155, 35, 35, 35); 

        let yPosition = 75;

        // Sección de detalles
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 102, 204); 
        doc.text("Detalles de la Reparación", 20, yPosition);
        yPosition += 10;

        const data = [
            { label: "Orden:", value: solucion },
            { label: "Ticket (Clave):", value: codigoTicket },
            { label: "Fecha:", value: fecha },
            { label: "Cliente:", value: nombreCliente },
            { label: "Teléfono:", value: telefonoCliente },
            { label: "Tipo de equipo:", value: tipoEquipo },
            { label: "Serie:", value: serie },
            { label: "Problema:", value: problemaEquipo },
            { label: "Condiciones:", value: condicionesEntrega },
            { label: "Sucursal:", value: sucursal },
            { label: "Recibe:", value: recibeUsuario },
            { label: "Costo:", value: "$" + costo.toFixed(2) },
            { label: "Adelanto:", value: "$" + adelanto.toFixed(2) },
            { label: "Saldo Pendiente:", value: "$" + saldoPendiente.toFixed(2) }
        ];

        // Mostrar datos
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);

        data.forEach(item => {
            doc.text(`${item.label} ${item.value}`, 20, yPosition);
            yPosition += 8;
        });

        // Información de contacto
        yPosition += 10;
        doc.text("Contactos telefónicos:", 20, yPosition);
        yPosition += 6;
        doc.text("Tel: 7727-9900", 20, yPosition);
        yPosition += 6;
        doc.text("Tel: 2406-6984", 20, yPosition);

        // Mensaje de responsabilidad
        yPosition += 10;
        doc.setFontSize(10);
        doc.setFont('helvetica', 'italic');
        doc.setTextColor(128, 128, 128); 
        doc.text("NO NOS HACEMOS RESPONSABLES POR TRABAJOS NO RETIRADOS DESPUES DE 30 DIAS.", 20, yPosition);

        // Línea final
        yPosition += 5;
        doc.setLineWidth(1);
        doc.setDrawColor(0, 102, 204); 
        doc.line(20, yPosition, 190, yPosition);

        // Mensaje de agradecimiento
        yPosition += 10;
        doc.setFontSize(12);
        doc.setFont('helvetica', 'italic');
        doc.setTextColor(128, 128, 128); 
        doc.text("Gracias por confiar en nuestro servicio.", 20, yPosition);

        // Guardar PDF
        doc.save(`reparacion_${idRep}_${nombreCliente}.pdf`);

        // Abrir WhatsApp
        const phoneNumber = telefonoCliente;  
        const message = `¡Hola! Aquí está el PDF con los detalles de la reparación de tu equipo.`;
        const whatsappUrl = `https://wa.me/+503${phoneNumber}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
</script>


<script>
    function imprimirPDF2(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, diagnostico, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion) {
        // Calcular los valores de pago recibido, total y cambio
        const pagoRecibido = parseFloat(document.getElementById('pago' + idRep).value);
        const total = costo - adelanto;
        const cambio = pagoRecibido - total;

        if (typeof window.jspdf === 'undefined') {
            console.error("jsPDF no está cargado correctamente.");
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [76, 230], // Ancho 76mm, altura ajustable (tamaño adecuado para tickets térmicos)
        });

        let yPosition = -10;

        // Agregar el logo centrado
        let logoWidth = 70;  
        let logoHeight = 70; 
        doc.addImage('https://i.ibb.co/kgNwmJs/logo2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
        yPosition += logoHeight + -10;

        // Título
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("COMPROBANTE DE ENTREGA", 5, yPosition); // Nueva línea agregada
        yPosition += 2;

        // Línea de separación después de "Comprobante de Entrega"
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Título principal
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("GRUPO MULTICOMP", 76 / 2, yPosition, { align: "center" });
        yPosition += 5;
        doc.text("(Taller Profesional)", 76 / 2, yPosition, { align: "center" });
        yPosition += 5;
        doc.text("(Tels: 7727-9900 / 24066984)", 76 / 2, yPosition, { align: "center" });
        yPosition += 3;

        // Línea de separación después de "GRUPO MULTICOMP"
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;


        // Función para dividir texto en líneas
        function dividirTexto(texto, maxWidth) {
            return doc.splitTextToSize(texto, maxWidth);
        }

        // Detalles principales
        const detalles = [
            { label: "Orden:", value: solucion },
            { label: "Ticket:", value: codigoTicket },
            { label: "Fecha:", value: fecha },
            { label: "Cliente:", value: nombreCliente },
            { label: "Teléfono:", value: telefonoCliente },
            { label: "Equipo:", value: tipoEquipo },
            { label: "Serie:", value: serie },
            { label: "Sucursal:", value: sucursal },
            { label: "Recibe:", value: recibeUsuario },
        ];

        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');

        // Procesar cada detalle y manejar saltos de línea
        detalles.forEach((item) => {
            const texto = `${item.label} ${item.value}`;
            const lineas = dividirTexto(texto, 66); // Ancho máximo ajustado a 66mm
            lineas.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 4; // Espaciado entre líneas
            });
            yPosition += 2; // Espaciado extra entre secciones
        });


        // Separador entre detalles y Problema/Condiciones
        yPosition += -2;
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Problema
        if (problemaEquipo.length > 0) {
            const textoProblema = `Problema: ${problemaEquipo}`;
            const lineasProblema = dividirTexto(textoProblema, 66);
            lineasProblema.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 5;
            });
            yPosition += 3; // Espaciado adicional después del problema
        }

        // Diagnostico
        if (diagnostico.length > 0) {
            const textoDiagnostico = `Diagnostico: ${diagnostico}`;
            const lineasDiagnostico = dividirTexto(textoDiagnostico, 66);
            lineasDiagnostico.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 5;
            });
            yPosition += 3; // Espaciado adicional después de las condiciones
        }

        // Condiciones
        if (condicionesEntrega.length > 0) {
            const textoCondiciones = `Condiciones: ${condicionesEntrega}`;
            const lineasCondiciones = dividirTexto(textoCondiciones, 66);
            lineasCondiciones.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 5;
            });
            yPosition += 5; // Espaciado adicional después de las condiciones
        }

        // Línea de separación antes de la sección de costos
        yPosition += -6; // Espaciado para la línea de separación
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Sección de Costos: Costo Total, Adelanto, Saldo Pendiente
        const costos = [
            { label: "Costo Total:", value: `$${costo.toFixed(2)}` },
            { label: "Adelanto:", value: `$${adelanto.toFixed(2)}` },
            { label: "Saldo Pendiente:", value: `$${saldoPendiente.toFixed(2)}` },
            { label: "Efectivo:", value: `$${pagoRecibido.toFixed(2)}` },
            { label: "Cambio:", value: `$${cambio.toFixed(2)}` },
        ];

        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold'); // Para destacar la sección de costos

        costos.forEach((item) => {
            doc.text(`${item.label}`, 5, yPosition); // Etiqueta a la izquierda
            doc.text(`${item.value}`, 38, yPosition); // Valor a la derecha
            yPosition += 5;
        });

        // Línea final de separación
        yPosition += -1;
        doc.setLineWidth(0.5);
        doc.setDrawColor(0, 0, 0);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Mensaje de agradecimiento
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.text("Gracias por confiar en nuestro servicio.", 5, yPosition);
        yPosition += 6;

        // Mensaje de responsabilidad
        const nota = "Nota: NO NOS HACEMOS RESPONSABLES POR TRABAJOS NO RETIRADOS DESPUES DE 30 DIAS.";
        const lineasNota = dividirTexto(nota, 66);
        lineasNota.forEach((linea) => {
            doc.text(linea, 5, yPosition);
            yPosition += 4; // Espaciado entre líneas
        });

        // Guardar el PDF con un nombre descriptivo
        doc.save(`ticket_${idRep}_${nombreCliente}.pdf`);
        doc.autoPrint(); // Prepara el PDF para impresión
        window.open(doc.output('bloburl'), '_blank'); // Abre el PDF en una nueva ventana y activa la impresión
    }
</script>

<script>
    document.getElementById('generarPDF').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape'); // Cambiar a orientación horizontal

        // Definir márgenes
        const margin = 10;
        const startX = margin;
        const startY = margin;
        const tableWidth = 280; // El ancho de la tabla para ajustarse al formato horizontal
        const lineHeight = 8; // Alto de cada fila

        // Agregar título con estilo
        doc.setFontSize(22);
        doc.setTextColor(40, 60, 100); // Color azul oscuro
        doc.text('Reporte de Clientes', startX, startY + 10);

        // Agregar logo (icono) en la esquina superior derecha
        const logoImg = new Image();
        logoImg.src = 'https://i.ibb.co/kgNwmJs/logo2.png'; // LOGO
        logoImg.onload = function() {
            doc.addImage(logoImg, 'PNG', 245, startY - 2, 40, 40); // Añadir el logo (ajustar tamaño y posición)
            
            // Luego de cargar el logo, seguimos con el diseño del contenido
            doc.setFontSize(12);
            doc.setTextColor(60, 60, 60); // Color gris
            doc.text('Listado de Clientes', startX, startY + 30);

            // Dibujar la línea azul debajo del encabezado y sobre los encabezados de la tabla
            const headerHeight = 10; // Altura de la línea de encabezado
            doc.setFillColor(40, 60, 100); // Color azul
            doc.rect(startX, startY + 35, tableWidth, headerHeight, 'F'); // Línea azul de fondo

            // Títulos de las columnas dentro de la línea azul
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(255, 255, 255); // Texto blanco
            doc.text('ID Cliente', startX + 5, startY + 42);
            doc.text('Nombre', startX + 40, startY + 42);
            doc.text('Teléfono', startX + 120, startY + 42);
            doc.text('Dirección', startX + 180, startY + 42);

            // Obtener los datos de la base de datos (suponiendo que se ha hecho un `json_encode` previamente)
            const clientes = <?php echo json_encode($resultc->fetch_all(MYSQLI_ASSOC)); ?>;

            // Crear la tabla tipo Excel
            let y = startY + 45; // Empezamos a dibujar las filas de la tabla debajo de la línea azul

            // Establecer los estilos para la tabla
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0); // Color negro para el contenido

            // Función para agregar el pie de página
            function addFooter() {
                const pageHeight = doc.internal.pageSize.height;
                const footerY = pageHeight - 10; // Ajuste para el pie de página en todas las páginas
                doc.setFontSize(12);
                doc.setTextColor(100);
                doc.text('Reporte Reparaciones Multicomp - Página ' + doc.internal.getNumberOfPages(), startX, footerY); // Ajuste para el pie de página
            }

            // Rellenar la tabla con los datos de los clientes
            clientes.forEach((cliente, index) => {
                // Verificar si necesitamos agregar una nueva página
                if (y > 175) {
                    doc.addPage(); // Agregar una nueva página si excede el límite
                    y = startY + 30; // Reiniciar la posición Y para la nueva página
                    doc.setTextColor(40, 60, 100); // Color azul oscuro
                    doc.setFontSize(22);
                    doc.text('Reporte de Clientes', startX, startY + 10);
                    doc.addImage(logoImg, 'PNG', 245, startY - 2, 40, 40); 
                    doc.setFontSize(12);
                    doc.text('Listado de Clientes', startX, y);
                    y += 15;
                    doc.setFont('helvetica', 'bold');
                    doc.setFillColor(40, 60, 100); // Color de fondo para el encabezado
                    doc.rect(startX, y - headerHeight, tableWidth, headerHeight, 'F'); // Fondo del encabezado
                    doc.setTextColor(255, 255, 255); // Texto blanco
                    doc.text('ID Cliente', startX + 5, y + -3);
                    doc.text('Nombre', startX + 40, y + -3);
                    doc.text('Teléfono', startX + 120, y + -3);
                    doc.text('Dirección', startX + 180, y + -3);
                }

                // Rellenar la tabla con los datos de cada cliente
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0); // Color negro para el contenido
                doc.text(`${cliente.id_cliente}`, startX + 5, y + 5);
                doc.text(`${cliente.nombre_cliente}`, startX + 42, y + 5);
                doc.text(`${cliente.telefono_cliente}`, startX + 122, y + 5);
                doc.text(`${cliente.direccion_cliente}`, startX + 182, y + 5);

                // Dibujar líneas divisorias para las columnas
                doc.rect(startX, y, 40, lineHeight); // Columna 1
                doc.rect(startX + 40, y, 80, lineHeight); // Columna 2
                doc.rect(startX + 120, y, 60, lineHeight); // Columna 3
                doc.rect(startX + 180, y, 100, lineHeight); // Columna 4
                
                y += lineHeight;

                // Añadir pie de página después de cada página si es necesario
                if (y > 175) {
                    addFooter();  // Agregar pie de página en cada página
                }
            });

            // Agregar línea de separación al final de la tabla
            doc.line(startX, y + 5, startX + tableWidth, y + 5); // Línea horizontal

            // Agregar el pie de página de la última página
            addFooter();

            // Guardar el PDF generado
            doc.save('reporte_clientes.pdf');
        };
    });
</script>


<script>
    document.getElementById('generarPDFSucursal').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape'); // Orientación horizontal

        // Definir márgenes
        const margin = 10;
        const startX = margin;
        const startY = margin;
        const tableWidth = 280; // Ancho de la tabla
        const lineHeight = 8; // Altura de cada fila

        // Título y logo
        doc.setFontSize(22);
        doc.setTextColor(40, 60, 100);
        doc.text('Reporte de Sucursales', startX, startY + 10); // Título

        // Logo de la empresa
        const logoImg = new Image();
        logoImg.src = 'https://i.ibb.co/kgNwmJs/logo2.png'; // Ruta del logo
        logoImg.onload = function() {
            doc.addImage(logoImg, 'PNG', 245, startY - 2, 40, 40); // Añadir el logo

            // Títulos de la tabla
            doc.setFontSize(12);
            doc.setTextColor(60, 60, 60);
            doc.text('Listado de Sucursales', startX, startY + 30);

            const headerHeight = 10; // Altura del encabezado de la tabla
            doc.setFillColor(40, 60, 100); // Color de fondo para el encabezado
            doc.rect(startX, startY + 35, tableWidth, headerHeight, 'F'); // Línea de fondo

            // Títulos de las columnas
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(255, 255, 255); // Color blanco para los títulos
            doc.text('Sucursal', startX + 5, startY + 42);
            doc.text('Ubicación', startX + 80, startY + 42);
            doc.text('Teléfono', startX + 180, startY + 42);

            // Aquí se pasa los datos obtenidos de la consulta SQL a JavaScript
            const sucursales = <?php echo json_encode($result_sucursales->fetch_all(MYSQLI_ASSOC)); ?>;

            let y = startY + 45; // Comienza a dibujar las filas

            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0); // Color negro para el contenido de la tabla

            sucursales.forEach(sucursal => {
                if (y > 175) { // Si se excede la altura de la página, agregar nueva página
                    doc.addPage();
                    y = startY + 30; // Reiniciar posición Y
                    doc.setTextColor(40, 60, 100);
                    doc.setFontSize(22);
                    doc.text('Reporte de Sucursales', startX, startY + 10);
                    doc.addImage(logoImg, 'PNG', 250, startY + 2, 30, 30);
                    doc.setFontSize(12);
                    doc.text('Listado de Sucursales', startX, y);
                    y += 15;

                    doc.setFont('helvetica', 'bold');
                    doc.setFillColor(40, 60, 100);
                    doc.rect(startX, y - headerHeight, tableWidth, headerHeight, 'F');
                    doc.setTextColor(255, 255, 255);
                    doc.text('Sucursal', startX + 5, y + -3);
                    doc.text('Ubicación', startX + 80, y + -3);
                    doc.text('Teléfono', startX + 180, y + -3);
                }

                // Rellenar la tabla con los datos de cada sucursal
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0); // Color negro para el contenido
                doc.text(sucursal.nombre, startX + 5, y + 5);
                doc.text(sucursal.ubicacion, startX + 80, y + 5);
                doc.text(sucursal.telefono, startX + 184, y + 5);

                // Dibujar líneas divisorias
                doc.rect(startX, y, 75, lineHeight); // Columna 1 (Sucursal)
                doc.rect(startX + 75, y, 105, lineHeight); // Columna 2 (Ubicación)
                doc.rect(startX + 180, y, 100, lineHeight); // Columna 3 (Teléfono)

                y += lineHeight; // Moverse a la siguiente fila
            });

            doc.line(startX, y + 5, startX + tableWidth, y + 5); // Línea de separación al final

            // Pie de página (en cada página)
            const pageHeight = doc.internal.pageSize.height;
            const footerY = pageHeight - 10;
            doc.setFontSize(12);
            doc.setTextColor(100);
            doc.text('Reporte de Sucursales - Página ' + doc.internal.getNumberOfPages(), startX, footerY);

            // Guardar el archivo PDF
            doc.save('reporte_sucursales.pdf');
        };
    });
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js"></script>

<script>
    // Recuperar los datos dinámicos desde PHP
    var sucursales = <?= json_encode($sucursales); ?>;
    var reparaciones = <?= json_encode($reparaciones); ?>;

    // Configuración del gráfico
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: sucursales, // Etiquetas dinámicas de las sucursales
            datasets: [{
                label: 'Reparaciones Finalizadas',
                data: reparaciones, // Datos dinámicos de las reparaciones
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

        <script>
            // Crear un array vacío para los datos
            let labels = [];
            let data = [];

            // Obtener los datos desde PHP
            <?php while ($row_sucursal = $result_sucursal->fetch_assoc()) { ?>
                labels.push("<?php echo $row_sucursal['sucursal']; ?>");
                data.push(<?php echo $row_sucursal['num_reparaciones']; ?>);
            <?php } ?>

            // Configuración de la gráfica de pastel
            var ctx = document.getElementById('reparacionesChart').getContext('2d');
            var reparacionesChart = new Chart(ctx, {
                type: 'pie', // Tipo de gráfico (pastel)
                data: {
                    labels: labels, // Etiquetas (sucursales)
                    datasets: [{
                        label: 'Reparaciones por Sucursal',
                        data: data, // Datos (número de reparaciones)
                        backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A6', '#FFDA33'], // Colores
                        borderColor: ['#fff', '#fff', '#fff', '#fff', '#fff'], // Borde blanco para los segmentos
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true, // Ajusta el gráfico al tamaño de la pantalla
                    plugins: {
                        legend: {
                            position: 'top', // Posición de la leyenda
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' reparaciones';
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>

        
    </body>
</html>
