
<script>
    // Cliente de Google
    const CLIENT_ID = "152128458064-nh8k36rob16m9t5cqngvj7n0v91f09n9.apps.googleusercontent.com"; // Reemplaza con tu Client ID
    const SCOPES = "https://www.googleapis.com/auth/drive.file";
    let tokenClient;

    window.onload = () => {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: CLIENT_ID,
            scope: SCOPES,
            callback: (response) => {
                if (response.error) {
                    console.error("Error de autorización:", response.error);
                } else {
                    console.log("Token recibido:", response.access_token);
                }
            },
        });
    };

    // Solicitar el token de acceso
    function requestTokenAndUpload() {
        tokenClient.requestAccessToken();
    }

    // Función que se ejecuta al hacer clic en el botón de imprimir
    document.getElementById('btnImprimirTicket').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [76, 150], // Formato de ticket (ajustado)
        });

        const claveOrden = document.getElementById('claveorden').value;
        const nombreCliente = document.getElementById('nombre_cliente').value;
        const telCliente = document.getElementById('telefono_cliente').value;
        const descrip = document.getElementById('descripcion').value;
        const material = document.getElementById('material').value;
        const costoTotal = parseFloat(document.getElementById('costo_total').value).toFixed(2);
        const montoPagado = parseFloat(document.getElementById('monto_pagado').value).toFixed(2);
        const vuelto = parseFloat(document.getElementById('vuelto').value).toFixed(2);

        let yPosition = 10;

        // Logo
        let logoWidth = 50;
        let logoHeight = 20;
        doc.addImage('https://i.ibb.co/MN0xKF3/logolib.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
        yPosition += logoHeight + 5;

        // Título del ticket
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("COMPROBANTE DE PAGO", 10, yPosition);
        yPosition += 5;

        // Fecha actual
        const today = new Date();
        const dateString = today.toLocaleDateString('es-ES'); // Formato: dd/mm/yyyy
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text(`Fecha: ${dateString}`, 22, yPosition);
        yPosition += 5;

        // Línea de separación
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Detalles del ticket
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        const details = [
            { label: "Orden:", value: claveOrden },
            { label: "Nombre:", value: nombreCliente },
            { label: "Telefono:", value: telCliente },
        ];

        details.forEach((item) => {
            doc.text(`${item.label} ${item.value}`, 5, yPosition);
            yPosition += 8;
        });

        // Línea de separación después del teléfono
        yPosition += -1; // Espacio extra antes de la línea
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Ajuste para el material con saltos de línea
        const lineHeight = 6; // Espacio entre líneas
        const materialWidth = 66; // Ancho del área para el material (ajustado para el ticket)
        const materialLines = doc.splitTextToSize(material, materialWidth);

        // Escribir el material línea por línea
        doc.text("Material:", 5, yPosition); // Etiqueta de material
        yPosition += lineHeight; // Espacio antes del material

        materialLines.forEach(line => {
            doc.text(line, 5, yPosition);
            yPosition += lineHeight;
        });

        // Ajuste para la descripción con saltos de línea
        const descriptionWidth = 66; // Ancho del área para la descripción (ajustado para el ticket)
        const descriptionLines = doc.splitTextToSize(descrip, descriptionWidth);

        // Escribir la descripción línea por línea
        doc.text("Descripcion:", 5, yPosition); // Etiqueta de la descripción
        yPosition += lineHeight; // Espacio antes de la descripción

        descriptionLines.forEach(line => {
            doc.text(line, 5, yPosition);
            yPosition += lineHeight;
        });

        // Línea de separación entre detalles y monto
        yPosition += -1;
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Sección de dinero (Monto Total, Monto Pagado y Vuelto)
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        const dineroDetails = [
            { label: "Monto Total:", value: `$${costoTotal}` },
            { label: "Monto Pagado:", value: `$${montoPagado}` },
            { label: "Vuelto:", value: `$${vuelto}` },
        ];

        dineroDetails.forEach((item) => {
            doc.text(`${item.label} ${item.value}`, 5, yPosition);
            yPosition += 5;
        });

        // Línea de separación final entre detalles y agradecimiento
        yPosition += -1;
        doc.setLineWidth(0.5);
        doc.line(5, yPosition, 71, yPosition);
        yPosition += 5;

        // Agradecimiento
        doc.setFontSize(9);
        doc.setFont('helvetica', 'italic');
        doc.text("Gracias por su compra!", 5, yPosition);

        // Generar PDF como Blob
        const pdfBlob = doc.output("blob");

        // Crear una URL temporal para el Blob
        const url = URL.createObjectURL(pdfBlob);

        // Abrir el PDF en una nueva ventana del navegador
        window.open(url, "_blank");

        // Solicitar token y subir el archivo a Google Drive
        tokenClient.requestAccessToken();
        tokenClient.callback = async (response) => {
            if (response.error) {
                console.error("Error de autorización:", response.error);
                return;
            }

            const token = response.access_token;
            await uploadToGoogleDrive(pdfBlob, token, claveOrden);
        };
    });

    // Función para subir el archivo PDF a Google Drive
    async function uploadToGoogleDrive(pdfBlob, token, claveOrden) {
        const metadata = {
            name: `ticket_pago_${claveOrden}.pdf`, // Nombre dinámico del archivo
            mimeType: "application/pdf", // Tipo MIME
        };

        const formData = new FormData();
        formData.append("metadata", new Blob([JSON.stringify(metadata)], { type: "application/json" }));
        formData.append("file", pdfBlob);

        try {
            const response = await fetch("https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                body: formData,
            });

            if (response.ok) {
                const jsonResponse = await response.json();
                console.log("Archivo subido correctamente:", jsonResponse);

                // Obtener el ID del archivo subido
                const fileId = jsonResponse.id;
                const fileLink = `https://drive.google.com/file/d/${fileId}/view?usp=sharing`;

                // Actualizar enlace en base de datos
                await actualizarEnlaceEnBaseDeDatos(claveOrden, fileLink);

            } else {
                console.error("Error al subir el archivo:", response.statusText);
            }
        } catch (error) {
            console.error("Error al subir el archivo:", error);
        }
    }

    // Función para actualizar el enlace en la base de datos
    async function actualizarEnlaceEnBaseDeDatos(claveOrden, fileLink) {
        try {
            const response = await fetch('../bd/actualizar_enlace_libreria.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    claveOrden: claveOrden,
                    fileLink: fileLink,
                }),
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Enlace actualizado en la base de datos:', data);
            } else {
                console.error('Error al actualizar el enlace en la base de datos');
            }
        } catch (error) {
            console.error('Error al enviar el enlace al servidor:', error);
        }
    }
</script>