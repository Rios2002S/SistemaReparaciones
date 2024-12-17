<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar PDF Profesional</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #007bff;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Generar PDF Profesional</h1>
    <button id="generar-pdf">Generar PDF</button>

    <script>
    document.getElementById("generar-pdf").addEventListener("click", () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Ruta de la imagen del fondo
        const backgroundUrl = '../img/2recurso.jpg';

        // Cargar la imagen de fondo
        doc.addImage(backgroundUrl, 'JPEG', 0, 0, 210, 297); // El fondo ocupa toda la página (A4)

        // Margen y cursor inicial
        const marginX = 35;
        let cursorY = 35;

        doc.setFont("helvetica", "bold");
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text("PEDIDO DE MANUALIDADES Y MAQUETAS", 35, 45);

        // Espacio para el logo en la esquina superior derecha
        let logoWidth = 40; // Ancho del logo
        let logoHeight = 40; // Alto del logo
        doc.addImage('../img/grupomulticomp.png', 'PNG', 150, 12, logoWidth, logoHeight); // Asegúrate de que la ruta sea correcta

        // Datos del pedido
        cursorY += 25;
        const data = [
            { label: "NOMBRE DEL CLIENTE:", value: "Juan Pérez" },
            { label: "TELÉFONO DEL CLIENTE:", value: "+34 654 123 456" },
            { label: "DESCRIPCIÓN:", value: "Pedido de modelo a escala de una casa.", multiline: true },
            { label: "TIPO DE MATERIAL QUE DESEA:", value: "Madera y cartón", multiline: true },
            { label: "MEDIDAS DE SU PEDIDO (BASE Y ALTO):", value: "50x30 cm", multiline: true },
            { label: "SUCURSAL O DELIVERY:", value: "Envío a Calle Falsa 123, Madrid", multiline: true },
            { label: "FECHA QUE NECESITA EL PROYECTO:", value: "15/12/2024", multiline: true },
            { label: "PRESUPUESTO DEL CLIENTE:", value: "200€" },
            { label: "COSTO TOTAL:", value: "250€" },
            { label: "QUIÉN RECIBE EL PEDIDO:", value: "Fulano Probador" },
            { label: "FECHA QUE RECIBE EL PEDIDO:", value: "05/12/2024" }
        ];

        // Añadir los datos del pedido al PDF
        data.forEach(item => {
            // Etiqueta
            doc.setFont("helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            doc.text(item.label, marginX, cursorY);

            // Contenido
            if (item.multiline) {
                cursorY += 8;
                doc.setFont("helvetica", "normal");
                doc.text(item.value, marginX, cursorY);
                cursorY += 10;
            } else {
                doc.setFont("helvetica", "normal");
                doc.text(item.value, marginX + 80, cursorY);
                cursorY += 10;
            }

            // Separador después de secciones específicas
            if (item.label === "FECHA QUE NECESITA EL PROYECTO:") {
                doc.setDrawColor(200, 200, 200);
                doc.line(marginX, cursorY, 190, cursorY);
                cursorY += 5;
            }
        });

        // Footer con condiciones
        cursorY += 10;
        doc.setFontSize(10);
        doc.setFont("helvetica", "italic");
        doc.setTextColor(100, 100, 100);
        doc.text("Condiciones: El pago se realizará en un plazo de 15 días.", marginX, cursorY);
        cursorY += 10;
        doc.text("Gracias por confiar en nuestros servicios.", marginX, cursorY);

        // Guardar el PDF
        doc.save("pedido_manualidades.pdf");
    });
</script>

    
    
</body>
</html>
