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
        format: [76, 145]  // Tamaño de ticket típico
    });

    // Variables para el logo
    let logoWidth = 70;
    let logoHeight = 25;
    let yPosition = 5; // Ajusta la posición inicial del logo

    // Añadir el logo
    doc.addImage('https://i.ibb.co/S7GRGtk/grupo-multicomp-2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
    yPosition += logoHeight + 5;  // Ajustar la posición después del logo

    // Configuración de la fuente y tamaño
    doc.setFont("Helvetica", "normal");
    doc.setFontSize(10);

    // Crear contenido del ticket
    doc.text("Ticket de Pedido", 40, yPosition, { align: "center" });
    yPosition += 5;
    doc.line(5, yPosition, 75, yPosition);  // Línea de separación
    yPosition += 5;

    doc.text(`Pedido N°: ${pedido.claveorden}`, 5, yPosition); yPosition += 5;
    doc.text(`Fecha: ${pedido.fecha_recibe}`, 5, yPosition); yPosition += 10;  // Salto de línea bajo la fecha
    doc.text(`Cliente: ${pedido.nombre_cliente}`, 5, yPosition); yPosition += 5;
    doc.text(`Teléfono: ${pedido.telefono_cliente}`, 5, yPosition); yPosition += 5;

// Dividir la descripción en líneas con el ancho máximo de 70 mm
const datosLines = doc.splitTextToSize(pedido.sucursal_o_delivery, 70);

// Imprimir las líneas de la descripción
doc.text(datosLines, 5, yPosition);

// Ajustar el alto dinámicamente según las líneas de descripción
yPosition += 5 * datosLines.length;  // Aumentamos la altura según el número de líneas

    // Descripción (ajuste automático en alto)
    doc.text("Descripción:", 5, yPosition); yPosition += 5;

    // Dividir la descripción en líneas con el ancho máximo de 70 mm
    const descripcionLines = doc.splitTextToSize(pedido.descripcion, 70);
    
    // Imprimir las líneas de la descripción
    doc.text(descripcionLines, 5, yPosition);
    
    // Ajustar el alto dinámicamente según las líneas de descripción
    yPosition += 5 * descripcionLines.length;  // Aumentamos la altura según el número de líneas

    // Medidas y Material
    doc.text(`Material: ${pedido.tipo_material}`, 5, yPosition); yPosition += 5;
    doc.text(`Medidas: ${pedido.medidas}`, 5, yPosition); yPosition += 10;

    // Presupuesto y Costo Total
    doc.text(`Presupuesto: $${pedido.presupuesto_cliente}`, 5, yPosition); yPosition += 5;
    doc.text(`Costo Total: $${pedido.costo_total}`, 5, yPosition); yPosition += 10;

    // Recibido por
    doc.text(`Recibido por: ${pedido.quien_recibe}`, 5, yPosition); yPosition += 10;
    doc.text("Gracias por su preferencia", 40, yPosition, { align: "center" });

    // Imprimir automáticamente
    doc.autoPrint();
    window.open(doc.output("bloburl"), "_blank");
}
</script>
