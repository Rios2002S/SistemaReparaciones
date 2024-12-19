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
                    <form id="formAgregarPedido" action="../bd/nuevo_pedido.php" method="POST">
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
                        <button type="submit" id="btnEnviarPedido" class="btn btn-success">Añadir Pedido</button>
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