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
                <!-- Desactivamos el botón de cerrar -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" disabled></button>
            </div>
            <div class="modal-body">
                <p>El cobro se ha registrado con éxito. ¿Deseas imprimir el ticket?</p>
            </div>
            <div class="modal-footer">
                <!-- Deshabilitamos el botón de cerrar -->
                <button type="button" id="btnCerrar" onclick="window.history.back()" class="btn btn-secondary" disabled>Aceptar</button>
                <button type="button" class="btn btn-primary" id="btnImprimirTicket">Imprimir Ticket</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Evitar que el modal se cierre por fuera o ESC
    var modalExito = new bootstrap.Modal(document.getElementById('modalExito'), {
        backdrop: 'static', // No cerrar haciendo clic fuera
        keyboard: false // No cerrar con la tecla ESC
    });


    // Función cuando se hace clic en el botón de imprimir
    document.getElementById('btnImprimirTicket').addEventListener('click', function() {
        // Aquí puedes agregar la lógica para imprimir el ticket, por ejemplo:
        // window.print();
        
        alert('Imprimiendo el ticket...');
        
        // Habilitar el botón de cerrar después de imprimir
        document.getElementById('btnCerrar').disabled = false;
        document.querySelector('.btn-close').disabled = false; // Habilitar el botón de cerrar también
    });
</script>


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

        const montoAPagar = Math.max(costoTotal, 0);
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



<?php
require_once '../reportes/ticketpedido.php';
require_once '../headfooter/footer.php';
?>
