<script>
    function imprimirPDFLib(quienRecibe, claveOrden, cliente, telefono, descripcion, material, medidas, sucursal, fechaNecesita, presupuesto, costo, fechaRecibe, trabaja) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Agregar la imagen como fondo
        doc.addImage('https://i.ibb.co/4WkkRq4/Dise-o-sin-t-tulo-2.png', 'PNG', 0, 0, 210, 297); // Página A4 completa

        // Información de contacto
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 255); // Azul
        doc.text('Contactanos: +503 7277-6037', 75, 70);

        // Volver a color negro
        doc.setTextColor(0, 0, 0);
        //doc.line(20, 88, 185, 88);
   
        // Título
        doc.setFontSize(18);
        doc.text('Detalles del Pedido', 20, 85);
        doc.line(20, 88, 185, 88);

        // Información básica
        doc.setFontSize(12);
        doc.text(`Clave: ${claveOrden}`, 120, 95);
        doc.text(`Cliente: ${cliente}`, 20, 95);
        doc.text(`Teléfono: ${telefono}`, 20, 107);
        doc.line(20, 110, 185, 110);

        // Tabla ajustable
        let startY = 114;
        const colWidth1 = 50;
        const colWidth2 = 115;

        const rows = [
            ['Descripción:', descripcion],
            ['Material:', material],
            ['Medidas:', medidas],
            ['Sucursal/Delivery:', sucursal],
            ['Fecha Necesita:', fechaNecesita]
        ];

        rows.forEach((row) => {
            const textLines = doc.splitTextToSize(row[1], colWidth2 - 5);
            const cellHeight = textLines.length * 6 + 4;

            doc.rect(20, startY, colWidth1, cellHeight);
            doc.rect(20 + colWidth1, startY, colWidth2, cellHeight);

            doc.text(row[0], 25, startY + 6);
            doc.text(textLines, 25 + colWidth1, startY + 6);
            
            startY += cellHeight;
        });

        doc.line(25, startY, 185, startY);
        doc.line(20, startY + 5, 185, startY + 5);

        // Información adicional con celdas ajustables
        const displayCost = parseFloat(costo) === 0 ? 'Costo Total: Pendiente' : `Costo Total: ${costo}`;
        const displayPres = parseFloat(presupuesto) === 0 ? 'Presupuesto: Sin Problema' : `Presupuesto: ${presupuesto}`;

        // Información adicional con celdas ajustables
        const additionalInfo = [
                    [displayPres, 130, startY + 20],
                    [displayCost, 130, startY + 31]
                ];

                additionalInfo.forEach(([text, x, y]) => {
                    const textLines = doc.splitTextToSize(text, 80);
                    const cellHeight = textLines.length * 5 + 6;
                    doc.rect(x - 5, y - 10, 60, cellHeight); // Crear celda ajustable
                    doc.text(textLines, x, y - 6 + 6);
                });


                doc.setFont('helvetica', 'italic');
                doc.setTextColor(100);

                doc.text(`${trabaja}`, 17, 252);
                doc.text(`Recibido por: ${quienRecibe}`, 95, 265);

                doc.line(68, 268, 157, 268);  // Línea horizontal
                doc.text(`Fecha de Recepción: ${fechaRecibe}`, 85, 275);

                // Guardar PDF
                doc.save(`pedido${claveOrden}_${cliente}.pdf`);

                // Abrir WhatsApp
                const phoneNumber = telefono;  
                const message = `¡Hola! Aquí está el PDF con los detalles de la reparación de tu equipo.`;
                const whatsappUrl = `https://wa.me/+503${phoneNumber}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
    }
</script>