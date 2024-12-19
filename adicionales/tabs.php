    <!-- Pestañas para los diferentes estados de los pedidos -->
    <ul class="nav nav-tabs" id="pedidoTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="recibidos-tab" data-bs-toggle="tab" href="#recibidos" role="tab" aria-controls="recibidos" aria-selected="true">Pedidos Recibidos</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="terminados-tab" data-bs-toggle="tab" href="#terminados" role="tab" aria-controls="terminados" aria-selected="false">Pedidos Terminados</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="entregados-tab" data-bs-toggle="tab" href="#entregados" role="tab" aria-controls="entregados" aria-selected="false">Pedidos Entregados</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="pedidoTabsContent">
        <!-- Pedidos Recibidos -->
        <div class="tab-pane fade show active" id="recibidos" role="tabpanel" aria-labelledby="recibidos-tab">
            <div class="table-responsive">  
                <table id="tablaPedidos" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Descripción</th>
                            <th>Material</th>
                            <th>Medidas</th>
                            <th>Sucursal/Delivery</th>
                            <th>Fecha Necesaria</th>
                            <th>Presupuesto</th>
                            <th>Costo Total</th>
                            <th>Quién Recibe</th>
                            <th>Fecha de Recepción</th>
                            <th>Estado</th>
                            <th hidden>Sucursal Asignada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php if ($pedido['estado'] == 0): ?>
                            <tr>
                                <td><?php echo $pedido['claveorden']; ?></td>
                                <td><?php echo $pedido['nombre_cliente']; ?></td>
                                <td><?php echo $pedido['telefono_cliente']; ?></td>
                                <td><?php echo $pedido['descripcion']; ?></td>
                                <td><?php echo $pedido['tipo_material']; ?></td>
                                <td><?php echo $pedido['medidas']; ?></td>
                                <td><?php echo $pedido['sucursal_o_delivery']; ?></td>
                                <td><?php echo $pedido['fecha_necesita']; ?></td>
                                <td><?php echo $pedido['presupuesto_cliente']; ?></td>
                                <td><?php echo $pedido['costo_total']; ?></td>
                                <td><?php echo $pedido['quien_recibe']; ?></td>
                                <td><?php echo $pedido['fecha_recibe']; ?></td>
                                <td hidden><?php echo $trabaja; ?></td>
                                <td>
                                    <?php 
                                    switch ($pedido['estado']) {
                                        case 0: 
                                            echo '<span title="En proceso" style="font-size: 1.5rem;">🛠️</span>';
                                            break;
                                        case 1: 
                                            echo '<span title="Terminado" style="font-size: 1.5rem;">✅</span>';
                                            break;
                                        case 2: 
                                            echo '<span title="Entregado" style="font-size: 1.5rem;">📦</span>';
                                            break;
                                        default: 
                                            echo 'Desconocido';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- Botón para imprimir PDF -->
                                    <button class="btn btn-success btn-sm mb-2" onclick="imprimirPDFLib('<?= htmlspecialchars($pedido['quien_recibe']); ?>',  '<?= htmlspecialchars($pedido['claveorden']); ?>', '<?= htmlspecialchars($pedido['nombre_cliente']); ?>', '<?= htmlspecialchars($pedido['telefono_cliente']); ?>', '<?= htmlspecialchars($pedido['descripcion']); ?>', '<?= htmlspecialchars($pedido['tipo_material']); ?>', '<?= htmlspecialchars($pedido['medidas']); ?>', '<?= htmlspecialchars($pedido['sucursal_o_delivery']); ?>', '<?= htmlspecialchars($pedido['fecha_necesita']); ?>', '<?= htmlspecialchars($pedido['presupuesto_cliente']); ?>', '<?= htmlspecialchars($pedido['costo_total']); ?>', '<?= htmlspecialchars($pedido['fecha_recibe']); ?>', '<?= htmlspecialchars($trabaja); ?>')"  data-bs-toggle="tooltip" title="Comprobante"><i class="fas fa-receipt"></i></button>
                                    <!-- Botón para abrir el modal -->
                                    <button class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#confirmarModal" data-claveorden="<?= htmlspecialchars($pedido['claveorden']); ?>" title="Marcar como Terminada">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de Confirmación -->
                            <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmarModalLabel">Confirmar Acción</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de marcar este pedido como <strong>Terminado</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="confirmarTerminado">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje de Éxito -->
                            <div id="mensajeExito" class="position-fixed top-50 start-50 translate-middle text-center" style="display: none;">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                <p class="text-success mt-2 fw-bold">Pedido Terminado</p>
                            </div>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pedidos Terminados -->
        <div class="tab-pane fade" id="terminados" role="tabpanel" aria-labelledby="terminados-tab">
            <div class="table-responsive">  
                <table id="tablaPedidos2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Descripción</th>
                            <th>Material</th>
                            <th>Medidas</th>
                            <th>Sucursal/Delivery</th>
                            <th>Fecha Necesaria</th>
                            <th>Presupuesto</th>
                            <th>Costo Total</th>
                            <th>Quién Recibe</th>
                            <th>Fecha de Recepción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php if ($pedido['estado'] == 1): ?>
                            <tr>
                                <td><?php echo $pedido['claveorden']; ?></td>
                                <td><?php echo $pedido['nombre_cliente']; ?></td>
                                <td><?php echo $pedido['telefono_cliente']; ?></td>
                                <td><?php echo $pedido['descripcion']; ?></td>
                                <td><?php echo $pedido['tipo_material']; ?></td>
                                <td><?php echo $pedido['medidas']; ?></td>
                                <td><?php echo $pedido['sucursal_o_delivery']; ?></td>
                                <td><?php echo $pedido['fecha_necesita']; ?></td>
                                <td><?php echo $pedido['presupuesto_cliente']; ?></td>
                                <td><?php echo $pedido['costo_total']; ?></td>
                                <td><?php echo $pedido['quien_recibe']; ?></td>
                                <td><?php echo $pedido['fecha_recibe']; ?></td>
                                <td>
                                    <?php 
                                    switch ($pedido['estado']) {
                                        case 0: 
                                            echo '<span title="En proceso" style="font-size: 1.5rem;">🛠️</span>';
                                            break;
                                        case 1: 
                                            echo '<span title="Terminado" style="font-size: 1.5rem;">✅</span>';
                                            break;
                                        case 2: 
                                            echo '<span title="Entregado" style="font-size: 1.5rem;">📦</span>';
                                            break;
                                        default: 
                                            echo 'Desconocido';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- Botón para imprimir PDF -->
                                    <button class="btn btn-success btn-sm mb-2" 
                                            onclick="imprimirPDFLib('<?= htmlspecialchars($pedido['quien_recibe']); ?>',  
                                                                    '<?= htmlspecialchars($pedido['claveorden']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['nombre_cliente']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['telefono_cliente']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['descripcion']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['tipo_material']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['medidas']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['sucursal_o_delivery']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['fecha_necesita']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['presupuesto_cliente']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['costo_total']); ?>', 
                                                                    '<?= htmlspecialchars($pedido['fecha_recibe']); ?>', 
                                                                    '<?= htmlspecialchars($trabaja); ?>')"  
                                            data-bs-toggle="tooltip" title="Comprobante">
                                        <i class="fas fa-receipt"></i>
                                    </button>

                                    <!-- Botón para revertir el estado -->
                                    <button class="btn btn-warning btn-sm mb-2" 
                                            data-bs-toggle="modal" data-bs-target="#revertirEstadoModal" 
                                            data-claveorden="<?= htmlspecialchars($pedido['claveorden']); ?>"
                                            title="Revertir Estado">
                                        <i class="fas fa-undo"></i> 
                                    </button>

                                    <!-- Botón para marcar como entregado -->
                                    <a href="entregar_pedido.php?claveorden=<?= urlencode($pedido['claveorden']); ?>" 
                                    class="btn btn-success btn-sm mb-2" 
                                    title="Realizar cobro">
                                        <i class="fas fa-dollar-sign"></i>
                                    </a>
                                </td>

                                <!-- Modal para revertir el estado -->
                                <div class="modal fade" id="revertirEstadoModal" tabindex="-1" aria-labelledby="revertirEstadoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="revertirEstadoModalLabel">¿Revertir Estado?</h5>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas revertir el estado de este pedido?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger" id="confirmarRevertir">Revertir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pedidos Entregados -->
        <div class="tab-pane fade" id="entregados" role="tabpanel" aria-labelledby="entregados-tab">
            <div class="table-responsive">  
                    <table id="tablafinal" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Descripción</th>
                                <th>Material</th>
                                <th>Medidas</th>
                                <th>Sucursal/Delivery</th>
                                <th>Fecha Necesaria</th>
                                <th>Presupuesto</th>
                                <th>Costo Total</th>
                                <th>Quién Recibe</th>
                                <th>Fecha de Recepción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <?php if ($pedido['estado'] == 2): ?>
                                        <td><?php echo $pedido['claveorden']; ?></td>
                                        <td><?php echo $pedido['nombre_cliente']; ?></td>
                                        <td><?php echo $pedido['telefono_cliente']; ?></td>
                                        <td><?php echo $pedido['descripcion']; ?></td>
                                        <td><?php echo $pedido['tipo_material']; ?></td>
                                        <td><?php echo $pedido['medidas']; ?></td>
                                        <td><?php echo $pedido['sucursal_o_delivery']; ?></td>
                                        <td><?php echo $pedido['fecha_necesita']; ?></td>
                                        <td><?php echo $pedido['presupuesto_cliente']; ?></td>
                                        <td><?php echo $pedido['costo_total']; ?></td>
                                        <td><?php echo $pedido['quien_recibe']; ?></td>
                                        <td><?php echo $pedido['fecha_recibe']; ?></td>
                                        <td>
                                            <?php 
                                            switch ($pedido['estado']) {
                                                case 0: 
                                                    echo '<span title="En proceso" style="font-size: 1.5rem;">🛠️</span>';
                                                    break;
                                                case 1: 
                                                    echo '<span title="Terminado" style="font-size: 1.5rem;">✅</span>';
                                                    break;
                                                case 2: 
                                                    echo '<span title="Entregado" style="font-size: 1.5rem;">📦</span>';
                                                    break;
                                                default: 
                                                    echo 'Desconocido';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <!-- Botón para imprimir PDF -->
                                            <button class="btn btn-success btn-sm mb-2" onclick="imprimirPDFLib('<?= htmlspecialchars($pedido['quien_recibe']); ?>',  '<?= htmlspecialchars($pedido['claveorden']); ?>', '<?= htmlspecialchars($pedido['nombre_cliente']); ?>', '<?= htmlspecialchars($pedido['telefono_cliente']); ?>', '<?= htmlspecialchars($pedido['descripcion']); ?>', '<?= htmlspecialchars($pedido['tipo_material']); ?>', '<?= htmlspecialchars($pedido['medidas']); ?>', '<?= htmlspecialchars($pedido['sucursal_o_delivery']); ?>', '<?= htmlspecialchars($pedido['fecha_necesita']); ?>', '<?= htmlspecialchars($pedido['presupuesto_cliente']); ?>', '<?= htmlspecialchars($pedido['costo_total']); ?>', '<?= htmlspecialchars($pedido['fecha_recibe']); ?>', '<?= htmlspecialchars($trabaja); ?>')"  data-bs-toggle="tooltip" title="Comprobante"><i class="fas fa-receipt"></i></button>
                                            <!-- Botón con ícono de Imprimir, usando enlace_drive como href -->
                                            <a href="<?= htmlspecialchars($pedido['enlace_drive']) ?>" class="btn" data-bs-toggle="tooltip" title="Imprimir Ticket">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>  