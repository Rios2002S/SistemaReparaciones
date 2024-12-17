<?php
require_once '../headfooter/head.php';
?>
 <div class="mx-5">
    
    <div class="table-responsive">
        
        <table id="tablaReparaciones" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Ticket</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Equipo</th>
                    <th>Serie</th>
                    <th>Problema</th>
                    <th>Condiciones</th>
                    <th>Sucursal</th>
                    <th>Recibe</th>
                    <th>Costo</th>
                    <th>Adelanto</th>
                    <th>Pendiente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        $cadena = $row['codigo_ticket'];  
                        $prefijo = substr($cadena, 0, 3);   
                        $numeros = substr($cadena, -3);     
                        $solucion = ($prefijo . '-' . $numeros);
                        ?>
                        <tr id='reparacion<?=$row['id_reparacion']?>'>
                            <td><?= htmlspecialchars($solucion); ?></td>
                            <td><?= htmlspecialchars($row['codigo_ticket']); ?></td>
                            <td><?= htmlspecialchars($row['fecha']); ?></td>
                            <td><?= htmlspecialchars($row['nombre_cliente']); ?></td>
                            <td><?= htmlspecialchars($row['telefono_cliente']); ?></td>
                            <td><?= htmlspecialchars($row['tipo_equipo']); ?></td>
                            <td><?= htmlspecialchars($row['serie']); ?></td>
                            <td><?= htmlspecialchars($row['problema_equipo']); ?></td>
                            <td><?= htmlspecialchars($row['condiciones_entrega']); ?></td>
                            <td><?= htmlspecialchars($row['sucursal']); ?></td>
                            <td><?= htmlspecialchars($row['recibe_usuario']); ?></td>
                            <td>$<?= htmlspecialchars(number_format($row['costo'], 2)); ?></td>
                            <td>$<?= htmlspecialchars(number_format($row['adelanto'], 2)); ?></td>
                            <td>$<?= htmlspecialchars(number_format($row['saldo_pendiente'], 2)); ?></td>
                            <td>
                                <!-- Botón para actualizar -->
                                <button class="btn btn-secondary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalActualizar<?= $row['id_reparacion']; ?>" data-bs-toggle="tooltip" title="Editar"><i class="bi bi-pencil"></i></button>

                                <!-- Botón para marcar como reparado -->
                                <button class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalReparada<?= $row['id_reparacion']; ?>" data-bs-toggle="tooltip" title="Reparado"><i class="fas fa-check-circle"></i></button>

                                <!-- Botón para imprimir PDF -->
                                <button class="btn btn-success btn-sm mb-2" onclick="imprimirPDF(<?= $row['id_reparacion']; ?>, '<?= htmlspecialchars($row['fecha']); ?>', '<?= htmlspecialchars($row['nombre_cliente']); ?>', '<?= htmlspecialchars($row['telefono_cliente']); ?>', '<?= htmlspecialchars($row['tipo_equipo']); ?>', '<?= htmlspecialchars($row['serie']); ?>', '<?= htmlspecialchars($row['problema_equipo']); ?>', '<?= htmlspecialchars($row['condiciones_entrega']); ?>', '<?= htmlspecialchars($row['sucursal']); ?>', '<?= htmlspecialchars($row['recibe_usuario']); ?>', <?= $row['costo']; ?>, <?= $row['adelanto']; ?>, <?= $row['saldo_pendiente']; ?>, '<?= htmlspecialchars($row['codigo_ticket']); ?>', '<?= htmlspecialchars($solucion); ?>')" data-bs-toggle="tooltip" title="Enviar por WhatsApp"><i class="fab fa-whatsapp"></i></button>

                                <!-- Botón para imprimir Ticket -->
                                <button class="btn btn-dark btn-sm mb-2" onclick="imprimirTicket(<?= $row['id_reparacion']; ?>, '<?= htmlspecialchars($row['fecha']); ?>', '<?= htmlspecialchars($row['nombre_cliente']); ?>', '<?= htmlspecialchars($row['telefono_cliente']); ?>', '<?= htmlspecialchars($row['tipo_equipo']); ?>', '<?= htmlspecialchars($row['serie']); ?>', '<?= htmlspecialchars($row['problema_equipo']); ?>', '<?= htmlspecialchars($row['condiciones_entrega']); ?>', '<?= htmlspecialchars($row['sucursal']); ?>', '<?= htmlspecialchars($row['recibe_usuario']); ?>', <?= $row['costo']; ?>, <?= $row['adelanto']; ?>, <?= $row['saldo_pendiente']; ?>, '<?= htmlspecialchars($row['codigo_ticket']); ?>', '<?= htmlspecialchars($solucion); ?>')" data-bs-toggle="tooltip" title="Imprimir Ticket"><i class="fas fa-print"></i></button>
                                

                                <!-- Modal de Confirmación de "Reparada" -->
                                <div class="modal fade" id="modalReparada<?= $row['id_reparacion']; ?>" tabindex="-1" aria-labelledby="modalReparadaLabel<?= $row['id_reparacion']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalReparadaLabel<?= $row['id_reparacion']; ?>">Confirmar Reparación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../bd/procesar_reparaciones.php" method="POST">
                                                                                                    <!-- Pregunta de confirmación -->
                                                    <p>¿Estás seguro de que deseas marcar esta reparación como realizada?</p>

                                                    <!-- Campo para el diagnóstico -->
                                                    <div class="mb-3">
                                                        <label for="diagnostico<?= $row['id_reparacion']; ?>" class="form-label">Diagnóstico del Equipo</label>
                                                        <textarea 
                                                            name="diagnostico" 
                                                            id="diagnostico<?= $row['id_reparacion']; ?>" 
                                                            class="form-control" 
                                                            rows="4" 
                                                            placeholder="Describe el diagnóstico del equipo antes de marcar como reparado" 
                                                            required></textarea>
                                                    </div>

                                                    <!-- ID de reparación oculto -->
                                                    <input type="hidden" name="id_rep" value="<?= htmlspecialchars($row['id_reparacion']); ?>">
                                                    
                                                    <!-- Diagnóstico oculto (rellenado por JavaScript antes del envío del formulario) -->
                                                    <input type="hidden" name="diagnostico" id="hiddenDiagnostico<?= $row['id_reparacion']; ?>">

                                                    <!-- Botón para confirmar -->
                                                    <button type="submit" class="btn btn-success" onclick="guardarDiagnostico(<?= $row['id_reparacion']; ?>)">Confirmar</button>
                                                    
                                                    <!-- Botón para cancelar -->
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Botón para eliminar -->
                                <form action="../bd/eliminar_reparacion.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_rep" value="<?= htmlspecialchars($row['id_reparacion']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm mb-2" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reparación?')" data-bs-toggle="tooltip" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                                <!-- Modal para actualizar -->
                                <div class="modal fade" id="modalActualizar<?= $row['id_reparacion']; ?>" tabindex="-1" aria-labelledby="modalActualizarLabel<?= $row['id_reparacion']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalActualizarLabel<?= $row['id_reparacion']; ?>">Actualizar Reparación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../bd/actualizar_reparacion.php" method="POST">
                                                        <input type="hidden" name="id_rep" value="<?= htmlspecialchars($row['id_reparacion']); ?>">

                                                        <!-- Ticket (no editable, pero se visualiza) -->
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Clave</label>
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['codigo_ticket']); ?>" disabled>
                                                            <input type="hidden" name="codigo_ticket" class="form-control" value="<?= htmlspecialchars($row['codigo_ticket']); ?>">

                                                        </div>

                                                        <!-- Cliente (no editable, pero se visualiza) -->
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Cliente</label>
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['nombre_cliente']); ?>" disabled>
                                                            <input type="hidden" name="id_cliente" class="form-control" value="<?= htmlspecialchars($row['id_cliente']); ?>">

                                                        </div>

                                                        <!-- Usuario que recibe (no editable, pero se visualiza) -->
                                                        <div class="mb-3">
                                                            <label for="recibe_usuario" class="form-label">Recibido</label>
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['recibe_usuario']); ?>" disabled>
                                                        </div>


                                                        <!-- Tipo de Equipo -->
                                                        <div class="mb-3">
                                                            <label for="tipo_equipo" class="form-label">Tipo de Equipo</label>
                                                            <select name="tipo_equipo" class="form-select" required>
                                                                <option value="<?= $row['tipo_equipo']; ?>" selected><?= htmlspecialchars($row['tipo_equipo']); ?></option>
                                                                <?php
                                                                // Mover el bucle de tipo de equipo a un bloque separado
                                                                $resultte->data_seek(0); // Resetear el puntero del resultado
                                                                while ($rowte = $resultte->fetch_assoc()) {
                                                                    echo "<option value='" . $rowte['nombre_tipo_equipo'] . "'>" . htmlspecialchars($rowte['nombre_tipo_equipo']) . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <!-- Problema del Equipo -->
                                                        <div class="mb-3">
                                                            <label for="problema_equipo" class="form-label">Problema</label>
                                                            <textarea name="problema_equipo" class="form-control" rows="3"><?= htmlspecialchars($row['problema_equipo'] ?? ''); ?></textarea>

                                                        </div>


                                                        <!-- Condiciones de Entrega -->
                                                        <div class="mb-3">
                                                            <label for="condiciones_entrega" class="form-label">Condiciones de Entrega</label>
                                                            <textarea name="condiciones_entrega" class="form-control" rows="3" required><?= htmlspecialchars($row['condiciones_entrega']); ?></textarea>
                                                        </div>
                                                        
                                                        <!-- Sucursal -->
                                                        <div class="mb-3">
                                                            <label for="sucursal" class="form-label">Sucursal</label>
                                                            <?php if ($es_admin): ?>
                                                                <!-- Si es administrador, mostrar un select de sucursales -->
                                                                <select name="sucursal" class="form-select" required>
                                                                    <option value="<?= htmlspecialchars($row['sucursal']); ?>" selected><?= htmlspecialchars($row['sucursal']); ?></option>
                                                                    <?php
                                                                    // Asegurarse de que no sobrescriba el valor de la fila original
                                                                    if ($results->num_rows > 0) {
                                                                        // Resetea el puntero al inicio del resultado
                                                                        $results->data_seek(0);  
                                                                        while ($row_sucursal = $results->fetch_assoc()) {
                                                                            // Mostrar las opciones de sucursal
                                                                            echo "<option value='" . $row_sucursal['nombre'] . "'>" . htmlspecialchars($row_sucursal['nombre']) . "</option>";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            <?php else: ?>
                                                                <!-- Si no es administrador, mostrar un input de solo lectura -->
                                                                <input type="text" name="sucursal" class="form-control" value="<?php echo htmlspecialchars($trabaja); ?>" readonly>
                                                            <?php endif; ?>
                                                        </div>


                                                        <!-- Serie -->
                                                        <div class="mb-3">
                                                            <label for="serie" class="form-label">Serie del Producto</label>
                                                            <input type="text" name="serie" class="form-control" value="<?= htmlspecialchars($row['serie']); ?>" required>
                                                        </div>

                                                        <!-- Costo de la Reparación -->
                                                        <div class="mb-3">
                                                            <label for="costo" class="form-label">Costo</label>
                                                            <input type="number" name="costo" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($row['costo']); ?>" required>
                                                        </div>

                                                        <!-- Adelanto -->
                                                        <div class="mb-3">
                                                            <label for="adelanto" class="form-label">Adelanto</label>
                                                            <input type="number" name="adelanto" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($row['adelanto']); ?>" required>
                                                        </div>

                                                        <!-- Botones -->
                                                        <div class="d-flex justify-content-between">
                                                            <button type="submit" class="btn btn-primary">Actualizar Reparación</button>
                                                            <button type="reset" class="btn btn-secondary">Limpiar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info text-center'>Bienvenido, esperamos tener nuevas reparaciones.</div>";
                }
                ?>
            </tbody>
            <!-- Script para gestionar el diagnóstico -->
            <script>
                function guardarDiagnostico(id) {
                    const diagnostico = document.getElementById('diagnostico' + id).value;
                    const hiddenDiagnostico = document.getElementById('hiddenDiagnostico' + id);
                    hiddenDiagnostico.value = diagnostico;
                }
            </script>
        </table>
    </div>
    <?php if (isset($_GET['id_rep'])): ?>
    <script>
        window.onload = function() {
            var row = document.getElementById('reparacion<?= $_GET['id_rep']; ?>');
            if (row) {
                row.scrollIntoView({ behavior: 'smooth' });
            }
        };
    </script>
    <?php endif; ?>

</div>
<script>
    document.querySelector("form").addEventListener("submit", function(event) {
        // Obtener los campos de texto
        const problemaEquipo = document.querySelector("textarea[name='diagnostico']");
        const condicionesEntrega = document.querySelector("textarea[name='condiciones_entrega']");
        
        // Reemplazar saltos de línea por espacio en blanco
        problemaEquipo.value = problemaEquipo.value.replace(/\n/g, ' ');
        condicionesEntrega.value = condicionesEntrega.value.replace(/\n/g, ' ');
    });
</script>
<script>
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevenir el salto de línea
                alert('No puedes presionar Enter para saltar líneas en este campo.');
            }
        });
    });
</script>                                                        

<?php
require_once '../headfooter/footer.php';
?>
