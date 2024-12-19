<script>
    function imprimirPDFTickLib(quienRecibe, claveOrden, cliente, telefono, descripcion, material, medidas, sucursal, fechaNecesita, presupuesto, costo, fechaRecibe, trabaja) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [76, 200], // Ticket de 76mm de ancho
        });

        let yPosition = 5;

        // Logo
        let logoWidth = 65;
        let logoHeight = 20;
        doc.addImage('https://i.ibb.co/S7GRGtk/grupo-multicomp-2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
        yPosition += logoHeight + 5;

        // Título
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("TICKET DE PEDIDO", 38, yPosition, { align: "center" });
        yPosition += 8;

        // Línea separadora
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Información principal
        doc.setFontSize(10);
        const details = [
            { label: "Pedido N°:", value: claveOrden },
            { label: "Cliente:", value: cliente },
            { label: "Teléfono:", value: telefono },
            { label: "Fecha Necesita:", value: fechaNecesita },
        ];

        details.forEach((item) => {
            doc.setFont("helvetica", "normal");
            doc.text(`${item.label} ${item.value}`, 5, yPosition);
            yPosition += 5;
        });

        // Sucursal con salto de línea si es necesario
        const sucursalLines = doc.splitTextToSize(sucursal, 66); // Ajuste el ancho para la sucursal
        doc.setFont("helvetica", "normal");
        doc.text("Sucursal:", 5, yPosition);
        yPosition += 5;
        doc.text(sucursalLines, 5, yPosition);
        yPosition += sucursalLines.length * 5 + 5;

        // Descripción con salto de línea si es necesario
        const descripcionLines = doc.splitTextToSize(descripcion, 66);
        doc.setFont("helvetica", "normal");
        doc.text("Descripción:", 5, yPosition);
        yPosition += 5;
        doc.text(descripcionLines, 5, yPosition);
        yPosition += descripcionLines.length * 5 + 3;

        // Material y Medidas: Si no hay mucho texto, empiezan en la misma línea
        const materialText = material ? material : "Sin Información";
        const medidasText = medidas ? medidas : "Sin Información";

        const materialLines = doc.splitTextToSize(materialText, 66);
        const medidasLines = doc.splitTextToSize(medidasText, 66);

        // Si el texto es corto, empieza en la misma línea
        const startX = 5;
        const startY = yPosition;

        doc.setFont("helvetica", "normal");
        doc.text("Material:", startX, startY);
        doc.text(materialLines, startX + 15, startY); // Ajustar el desplazamiento
        yPosition += materialLines.length * 5;

        doc.text("Medidas:", startX, yPosition);
        doc.text(medidasLines, startX + 15, yPosition); // Ajustar el desplazamiento
        yPosition += medidasLines.length * 5 + 5;

        // Presupuesto y Costo Total
        const additionalInfo = [
            { label: "Presupuesto:", value: presupuesto ? `$${presupuesto}` : "Sin Problema" },
            { label: "Costo Total:", value: costo ? `$${costo}` : "Pendiente" },
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

        // Información del recepcionista
        doc.setFontSize(10);
        doc.setFont("helvetica", "italic");
        doc.text(`Recibido por: ${quienRecibe}`, 5, yPosition);
        yPosition += 5;
        doc.text(`Fecha de Recepción: ${fechaRecibe}`, 5, yPosition);
        yPosition += 5;

        doc.setFontSize(9);
        doc.setFont("helvetica", "italic");
        doc.text(`Encargado del Trabajo: ${trabaja}`, 5, yPosition);
        yPosition += 10;

        // Mensaje de agradecimiento
        doc.setFontSize(9);
        doc.setFont("helvetica", "bold");
        doc.text("¡Gracias por confiar en nosotros!", 38, yPosition, { align: "center" });

        // Guardar el ticket
        doc.save(`ticket_pedido_${claveOrden}.pdf`);
    }
</script>
