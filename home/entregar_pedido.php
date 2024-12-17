<?php
require_once '../headfooter/head.php';

// Verifica si se recibe la clave de la orden
if (isset($_GET['claveorden'])) {
    $claveorden = mysqli_real_escape_string($conn, $_GET['claveorden']);

    // Consulta para obtener detalles del pedido
    $query = "SELECT pm.id_pedido, c.nombre_cliente, c.telefono_cliente, pm.descripcion, pm.tipo_material, 
                     pm.medidas, pm.sucursal_o_delivery, pm.fecha_necesita, pm.presupuesto_cliente, 
                     pm.costo_total, pm.claveorden
              FROM pedido_maquetas pm
              JOIN clientes c ON pm.id_cliente = c.id_cliente
              WHERE pm.claveorden = '$claveorden'";

    $result = mysqli_query($conn, $query);
    $pedido = mysqli_fetch_assoc($result);

    if (!$pedido) {
        echo "<p class='alert alert-danger'>Pedido no encontrado.</p>";
        exit;
    }
} else {
    echo "<p class='alert alert-danger'>Clave de orden no proporcionada.</p>";
    exit;
}
?>

<div class="container mt-5">
    <button class="home-btn" onclick="window.history.back()">
        <i class="fas fa-home home-icon"></i>
    </button>
    <h1>Registrar Cobro</h1>
    <form id="cobroForm" action="javascript:void(0);">

        <input type="hidden" id="claveorden" value="<?= htmlspecialchars($pedido['claveorden']); ?>">
        <input type="hidden" id="nombre_cliente" value="<?= htmlspecialchars($pedido['nombre_cliente']); ?>">
        <input type="hidden" id="telefono_cliente" value="<?= htmlspecialchars($pedido['telefono_cliente']); ?>">
        <input type="hidden" id="descripcion" value="<?= htmlspecialchars($pedido['descripcion']); ?>">
        <input type="hidden" id="material" value="<?= htmlspecialchars($pedido['tipo_material']); ?>">

        <!-- Monto Total -->
        <div class="mb-3">
            <label for="costo_total" class="form-label">Monto Total</label>
            <input type="number" id="costo_total" name="costo_total" 
                   class="form-control" step="0.01" value="<?= htmlspecialchars($pedido['costo_total']); ?>">
        </div>

        <!-- Presupuesto -->
        <div class="mb-3">
            <label for="presupuesto_cliente" class="form-label">Presupuesto del Cliente:</label>
            <input type="text" id="presupuesto_cliente" name="presupuesto_cliente" 
                   class="form-control" value="<?= htmlspecialchars($pedido['presupuesto_cliente']); ?>" readonly>
        </div>

        <!-- Monto a Pagar (Calculado) -->
        <div class="mb-3">
            <label for="monto_a_pagar" class="form-label">Monto a Pagar</label>
            <input type="text" id="monto_a_pagar" name="monto_a_pagar" 
                   class="form-control" readonly>
        </div>

        <!-- Monto Pagado -->
        <div class="mb-3">
            <label for="monto_pagado" class="form-label">Monto Recibido</label>
            <input type="number" id="monto_pagado" name="monto_pagado" 
                   class="form-control" step="0.01" min="0" value="0.00" required>
        </div>

        <!-- Vuelto (Calculado) -->
        <div class="mb-3">
            <label for="vuelto" class="form-label">Vuelto</label>
            <input type="text" id="vuelto" name="vuelto" 
                   class="form-control" readonly>
        </div>

        <button type="button" class="btn btn-primary" id="registrarCobroBtn">Registrar Cobro</button>
    </form><br>
</div>

<!-- Modal de Exito -->
<div class="modal" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExitoLabel">Cobro Registrado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>El cobro se ha registrado con éxito. ¿Deseas imprimir el ticket?</p>
            </div>
            <div class="modal-footer">
                <button type="button"  onclick="window.history.back()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnImprimirTicket">Imprimir Ticket</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        calcularMontoAPagar();
        document.getElementById('costo_total').addEventListener('input', calcularMontoAPagar);
        document.getElementById('monto_pagado').addEventListener('input', calcularCobro);
        
        document.getElementById('registrarCobroBtn').addEventListener('click', registrarCobro);
    });

    function calcularMontoAPagar() {
        const costoTotal = parseFloat(document.getElementById('costo_total').value) || 0;
        const presupuesto = parseFloat(document.getElementById('presupuesto_cliente').value) || 0;

        const montoAPagar = Math.max(costoTotal - presupuesto, 0);
        document.getElementById('monto_a_pagar').value = montoAPagar.toFixed(2);

        calcularCobro();
    }

    function calcularCobro() {
        const montoAPagar = parseFloat(document.getElementById('monto_a_pagar').value) || 0;
        const montoPagado = parseFloat(document.getElementById('monto_pagado').value) || 0;

        const vuelto = Math.max(montoPagado - montoAPagar, 0);
        document.getElementById('vuelto').value = vuelto.toFixed(2);
    }

    function registrarCobro() {
        const claveorden = document.getElementById('claveorden').value;
        const costo_total = document.getElementById('costo_total').value;

        const formData = new FormData();
        formData.append('claveorden', claveorden);
        formData.append('costo_total', costo_total);

        fetch('../bd/marcar_entregado.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                $('#modalExito').modal('show');
            } else {
                alert('Error al registrar el cobro');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
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


<?php
require_once '../headfooter/footer.php';
?>
