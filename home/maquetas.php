<?php
require_once '../headfooter/head.php';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<script>alert('Nuevo cliente agregado exitosamente en Pedido Maquetas.');</script>";
    } elseif ($_GET['status'] == 'error') {
        echo "<script>alert('Hubo un error al agregar el cliente en Pedido Maquetas.');</script>";
    }
}
?>

<div class="mx-5">
    <!-- Alerta -->
    <div class="alert alert-warning d-flex align-items-center justify-content-center" role="alert" style="border-radius: 10px; padding: 15px;">
        <img src="https://i.ibb.co/zfvTCq0/logolib.png" width="700" height="200" alt="Librería" class="mx-auto d-block">
    </div>
    <!-- Botón para abrir el modal -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPedido">Añadir Nuevo Pedido</button>
    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">Agregar Nuevo Cliente</button>
    <br>
    <p></p>

    <!-- Modal para añadir nuevo pedido -->
    <div class="modal fade" id="modalAgregarPedido" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPedidoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarPedidoLabel">Nuevo Pedido de Maqueta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: transparent; border: none; font-size: 1.5rem; color: #333; opacity: 0.7;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de pedido -->
                    <form method="POST" action="../bd/nuevo_pedido.php">
                        <div class="form-group">
                            <label for="id_cliente">Cliente</label>
                            <select class="form-control" id="id_cliente" name="id_cliente" required>
                                <!-- Aquí se deben listar los clientes desde la base de datos -->
                                <?php
                                $clientesQuery = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes ORDER BY id_cliente DESC;";
                                $clientesResult = mysqli_query($conn, $clientesQuery);
                                while ($cliente = mysqli_fetch_assoc($clientesResult)) {
                                    echo "<option value='" . $cliente['id_cliente'] . "'>" . $cliente['nombre_cliente'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tipo_material">Tipo de Material</label>
                            <input type="text" class="form-control" id="tipo_material" name="tipo_material" required>
                        </div>
                        <div class="form-group">
                            <label for="medidas">Medidas (Base y Alto)</label>
                            <input type="text" class="form-control" id="medidas" name="medidas" required>
                        </div>

                        <div class="form-group">
                            <label for="sucursal_o_delivery">Sucursal/Delivery</label>
                            <!-- Radio button para Sucursal -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sucursal_o_delivery" id="sucursal" value="<?php echo $trabaja; ?>" checked>
                                <label class="form-check-label" for="sucursal">
                                    Sucursal
                                </label>
                            </div>
                            <!-- Radio button para Delivery -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sucursal_o_delivery" id="delivery" value="Delivery">
                                <label class="form-check-label" for="delivery">
                                    Delivery
                                </label>
                            </div>
                            <!-- Campo de texto para dirección (solo será visible si elige "Delivery") -->
                            <input type="text" class="form-control" id="direccion_delivery" name="direccion_delivery" value="<?php echo $trabaja; ?>" readonly>
                        </div>

                        <script>
                            $(document).ready(function() {
                                // Función para cambiar el campo de entrada cuando se elige "Delivery"
                                $('input[name="sucursal_o_delivery"]').on('change', function() {
                                    if ($('#delivery').is(':checked')) {
                                        // Si selecciona "Delivery", permitir que edite la dirección
                                        $('#direccion_delivery').removeAttr('readonly');
                                        $('#direccion_delivery').val('');  // Limpiar el valor
                                    } else {
                                        // Si selecciona "Sucursal", poner el valor original y hacer el campo solo lectura
                                        $('#direccion_delivery').val('<?php echo $trabaja; ?>');
                                        $('#direccion_delivery').attr('readonly', true);
                                    }
                                });

                                // Antes de enviar el formulario, combinamos los valores
                                $("form").submit(function() {
                                    var sucursal_o_delivery = $('input[name="sucursal_o_delivery"]:checked').val();
                                    if (sucursal_o_delivery === "Delivery") {
                                        var direccion = $('#direccion_delivery').val();
                                        $('input[name="sucursal_o_delivery"]').val(sucursal_o_delivery + ": " + direccion); // Combinamos "Delivery" y la dirección
                                    }
                                });
                            });
                        </script>

                        <div class="form-group">
                            <label for="fecha_necesita">Fecha que Necesita el Proyecto</label>
                            <input type="date" class="form-control" id="fecha_necesita" name="fecha_necesita" required>
                        </div>
                        <div class="form-group">
                            <label for="presupuesto_cliente">Presupuesto del Cliente</label>
                            <input type="number" min="0" class="form-control" id="presupuesto_cliente" name="presupuesto_cliente" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="costo_total">Costo Total</label>
                            <input type="number" min="0" class="form-control" id="costo_total" name="costo_total" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="quien_recibe">Quién Recibe el Pedido</label>
                            <input type="text" class="form-control" id="quien_recibe" name="quien_recibe" value='<?php echo htmlspecialchars($nombreu); ?>' readonly>
                        </div>
                        <div class="form-group">
                            <label for="fecha_recibe">Fecha de Recepción</label>
                            <input type="date" class="form-control" id="fecha_recibe" name="fecha_recibe" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Añadir Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Establecer la fecha de recepción como la fecha actual
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("fecha_recibe").value = today;

            // Establecer la fecha de entrega solo después del día actual
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            var minDate = tomorrow.toISOString().split('T')[0];
            document.getElementById("fecha_necesita").setAttribute("min", minDate);
        });
    </script>

    <!-- Modal para agregar cliente -->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../bd/agregar_cliente.php" method="POST">
                        <div class="mb-3">
                            <input type="hidden" name="form_source" value="pedido_maquetas" />
                            <label for="nombre_cliente" class="form-label">Nombre de Cliente</label>
                            <input type="text" name="nombre_cliente" class="form-control" placeholder="Nombre del cliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono_cliente" class="form-label">Teléfono del Cliente</label>
                            <input type="text" name="telefono_cliente" class="form-control" placeholder="Número telefónico" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_cliente" class="form-label">Correo Electrónico</label>
                            <input type="email" name="email_cliente" class="form-control" placeholder="Correo electrónico">
                        </div>
                        <div class="mb-3">
                            <label for="direccion_cliente" class="form-label">Dirección del Cliente</label>
                            <input type="text" name="direccion_cliente" class="form-control" placeholder="Dirección" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                              
    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php
      require_once "../adicionales/tabs.php";
    ?>
              
</div>
    <!-- Modal de éxito -->
    <div class="modal fade" id="mensajeExitoModal" tabindex="-1" aria-labelledby="mensajeExitoLabel" aria-hidden="true">
        <div class="modal-dialog mt-5"> <!-- Ajusta el margen si es necesario -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeExitoLabel">¡Éxito!</h5>
                </div>
                <div class="modal-body text-center">
                    <img src="https://cdn-icons-gif.flaticon.com/10970/10970392.gif" alt="Éxito" width="75" height="75">
                    <p>El proceso se completó con éxito.</p>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "../reportes/maquetas.php";
?>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inicialización del modal de confirmación
        const modal = new bootstrap.Modal(document.getElementById('confirmarModal'));
        let claveOrdenSeleccionada = '';

        // Detectar cuándo se va a mostrar el modal y asignar la clave
        document.querySelector('#confirmarModal').addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            claveOrdenSeleccionada = button.getAttribute('data-claveorden');
        });

        // Manejo del clic en el botón de confirmar
        document.getElementById('confirmarTerminado').addEventListener('click', () => {
            // Cerramos el modal de confirmación
            modal.hide();

            // Realizamos la solicitud al servidor
            fetch('../bd/marcar_terminado.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `claveorden=${encodeURIComponent(claveOrdenSeleccionada)}`
            })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === "success") {
                    // Inicializamos y mostramos el modal de éxito
                    const modalExito = new bootstrap.Modal(document.getElementById('mensajeExitoModal'));
                    modalExito.show();
                    
                    // Cerramos el modal de éxito después de 2 segundos
                    setTimeout(() => {
                        modalExito.hide();
                        window.location.reload(); // Refrescar la página
                    }, 2000);
                } else {
                    alert('Error al actualizar el pedido.');
                }
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let claveOrdenSeleccionada;

    // Capturar el evento del botón "Revertir"
    document.querySelectorAll('[data-bs-target="#revertirEstadoModal"]').forEach(button => {
        button.addEventListener('click', (event) => {
            claveOrdenSeleccionada = event.target.getAttribute('data-claveorden');
        });
    });

    // Acción de revertir el estado
    document.getElementById('confirmarRevertir').addEventListener('click', () => {
        fetch('../bd/revertir_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `claveorden=${encodeURIComponent(claveOrdenSeleccionada)}&estado=0`
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === "success") {
                // Cerrar el modal de revertir
                var myModal = new bootstrap.Modal(document.getElementById('revertirEstadoModal'));
                myModal.hide();
                
                // Inicializamos y mostramos el modal de éxito
                const modalExito = new bootstrap.Modal(document.getElementById('mensajeExitoModal'));
                modalExito.show();
                
                // Cerrar el modal de éxito después de 2 segundos y recargar la página
                setTimeout(() => {
                    modalExito.hide();
                    window.location.reload(); // Refrescar la página
                }, 2000);
            } else {
                alert("Error al revertir el estado.");
            }
        });
    });
});
</script>




<!-- Incluir las librerías de Bootstrap para el modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- Incluir las librerías de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaPedidos').DataTable();
        $('#tablaPedidos2').DataTable();
        $('#tablafinal').DataTable();
    });
</script>

<?php
require_once '../headfooter/footer.php';
?>

