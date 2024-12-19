<script>
document.getElementById("formAgregarPedido").addEventListener("submit", function (event) {
    // Evitamos el comportamiento por defecto (enviar el formulario)
    event.preventDefault();

    // Crear el objeto FormData
    const formData = new FormData(this);  // this hace referencia al formulario

    // Enviar el formulario utilizando fetch
    fetch("../bd/nuevo_pedido.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())  // Convertimos la respuesta en JSON
    .then(data => {
        // Verificamos si el servidor respondió con éxito
        if (data.success) {
            // Generamos el ticket con los datos del pedido
            generarTicket(data.pedido);

            // Opcional: Cerrar el modal
            $('#modalAgregarPedido').modal('hide');

            // Mensaje de éxito y redirección
            alert("Pedido registrado con éxito");
            window.location.href = "../home/maquetas.php";
        } else {
            // Si hay un error, mostrar el mensaje
            alert("Error al añadir el pedido: " + data.message);
        }
    })
    .catch(error => {
        // Manejo de errores si algo falla con la solicitud
        console.error("Error:", error);
        alert("Ocurrió un error inesperado.");
    });
});

async function generarTicket(pedido) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            unit: "mm",
            format: [76, 200]  // Tamaño de ticket típico
        });

        let logoWidth = 65;
        let logoHeight = 20;
        let yPosition = 5; // Ajusta la posición inicial del logo

        // Añadir el logo
        doc.addImage('https://i.ibb.co/S7GRGtk/grupo-multicomp-2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
        yPosition += logoHeight + 5;  // Ajustar la posición después del logo

        // Título
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("TICKET DE PEDIDO", 38, yPosition, { align: "center" });
        yPosition += 8;

        // Línea separadora
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Información del pedido
        doc.setFontSize(10);
        const details = [
            { label: "Pedido N°:", value: pedido.claveorden },
            { label: "Cliente:", value: pedido.nombre_cliente },
            { label: "Teléfono:", value: pedido.telefono_cliente },
            { label: "Fecha Necesita:", value: pedido.fecha_necesita },
        ];

        details.forEach((item) => {
            doc.setFont("helvetica", "normal");
            doc.text(`${item.label} ${item.value}`, 5, yPosition);
            yPosition += 5;
        });

        // Sucursal con salto de línea si es necesario
        const sucursalLines = doc.splitTextToSize(pedido.sucursal_o_delivery, 66); // Ajuste el ancho para la sucursal
        doc.setFont("helvetica", "normal");
        doc.text("Sucursal:", 5, yPosition);
        yPosition += 5;
        doc.text(sucursalLines, 5, yPosition);
        yPosition += sucursalLines.length * 5 + 5;

        // Descripción con salto de línea automático
        const descripcionLines = doc.splitTextToSize(pedido.descripcion, 66);
        doc.setFont("helvetica", "normal");
        doc.text("Descripción:", 5, yPosition);
        yPosition += 5;
        doc.text(descripcionLines, 5, yPosition);
        yPosition += descripcionLines.length * 5 + 5;

        // Material y Medidas
        doc.text(`Material: ${pedido.tipo_material}`, 5, yPosition); yPosition += 5;
        doc.text(`Medidas: ${pedido.medidas}`, 5, yPosition); yPosition += 10;

        // Presupuesto y Costo Total
        const additionalInfo = [
            { label: "Presupuesto:", value: `$${pedido.presupuesto_cliente}` },
            { label: "Costo Total:", value: `$${pedido.costo_total}` },
        ];

        additionalInfo.forEach((item) => {
            doc.setFont("helvetica", "normal");
            doc.text(`${item.label} ${item.value}`, 5, yPosition);
            yPosition += 5;
        });

        // Línea separadora final
        yPosition += 2;
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Información del recepcionista (Asegúrate de que estas variables existan en el objeto pedido)
        doc.setFontSize(10);
        doc.setFont("helvetica", "italic");
        doc.text(`Recibido por: ${pedido.quien_recibe}`, 5, yPosition);
        yPosition += 5;
        doc.text(`Fecha de Recepción: ${pedido.fecha_recibe}`, 5, yPosition);
        yPosition += 5;

        doc.setFontSize(9);
        doc.setFont("helvetica", "italic");
        doc.text(`Encargado del Trabajo: ${pedido.trabaja}`, 5, yPosition);
        yPosition += 10;

        // Mensaje de agradecimiento
        doc.setFontSize(9);
        doc.setFont("helvetica", "bold");
        doc.text("¡Gracias por confiar en nosotros!", 38, yPosition, { align: "center" });

        // Imprimir automáticamente
        doc.autoPrint();
        window.open(doc.output("bloburl"), "_blank");
    }
</script>
