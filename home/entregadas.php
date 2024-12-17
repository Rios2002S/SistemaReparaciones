<?php
require_once '../headfooter/head.php';
?>
<div class="mx-5">
    <h1 class="text-center mb-4">Reparaciones Entregadas</h1>

    <div class="table-responsive">
                <?php
                // Consulta dependiendo de si es admin o no
                if ($es_admin) {
                    $sql2 = "SELECT rf.id_rep, 
                                    c.nombre_cliente,
                                    c.telefono_cliente,
                                    rf.fecha, 
                                    rf.tipo_equipo, 
                                    rf.problema_equipo, 
                                    rf.condiciones_entrega, 
                                    rf.recibe_usuario, 
                                    rf.sucursal, 
                                    rf.costo, 
                                    rf.estado,
                                    rf.serie,
                                    rf.adelanto,
                                    rf.saldo_pendiente,
                                    rf.codigo_ticket,
                                    rf.diagnostico,
                                    rf.enlace_drive
                            FROM reparaciones_finalizadas rf
                            INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                            WHERE rf.estado = 1
                            ORDER BY rf.id_finalizada DESC";
                } else {
                    $sql2 = "SELECT rf.id_rep, 
                                    c.nombre_cliente,
                                    c.telefono_cliente,
                                    rf.fecha, 
                                    rf.tipo_equipo, 
                                    rf.problema_equipo, 
                                    rf.condiciones_entrega, 
                                    rf.recibe_usuario, 
                                    rf.sucursal, 
                                    rf.costo, 
                                    rf.estado,
                                    rf.serie,
                                    rf.adelanto,
                                    rf.saldo_pendiente,
                                    rf.codigo_ticket,
                                    rf.diagnostico,
                                    rf.enlace_drive
                            FROM reparaciones_finalizadas rf
                            INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                            WHERE rf.estado = 1 AND rf.sucursal = '$trabaja'
                            ORDER BY rf.id_finalizada DESC";
                }

                // Ejecutar la consulta
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0):
            ?>

        <table id="reparacionesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Equipo</th>
                    <th>Problema</th>
                    <th>Condiciones</th>
                    <th>Diagnostico</th>
                    <th>Recibió</th>
                    <th>Sucursal</th>
                    <th>Costo</th>
                    <th>Abonó</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result2->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php
                                $cadena = $row['codigo_ticket'];  
                                $prefijo = substr($cadena, 0, 3);  
                                $numeros = substr($cadena, -3);     
                                echo $prefijo . '-' . $numeros;
                            ?>
                        </td>
                        <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                        <td><?= htmlspecialchars($row['telefono_cliente']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['tipo_equipo']) ?></td>
                        <td><?= htmlspecialchars($row['problema_equipo']) ?></td>
                        <td><?= htmlspecialchars($row['condiciones_entrega']) ?></td>
                        <td><?= htmlspecialchars($row['diagnostico']) ?></td>
                        <td><?= htmlspecialchars($row['recibe_usuario']) ?></td>
                        <td><?= htmlspecialchars($row['sucursal']) ?></td>
                        <td>$<?= number_format($row['costo'], 2) ?></td>
                        <td>$<?= number_format($row['adelanto'], 2) ?></td>
                        <td>$<?= number_format($row['saldo_pendiente'], 2) ?></td>
                        <td>
                            <!-- Icono de Entregado -->
                            <span class="text-success" data-bs-toggle="tooltip" title="Reparado"><i class="fas fa-check-circle"></i></span>
                        </td>
                        <td>
                            <!-- Botón con ícono de Imprimir, usando enlace_drive como href -->
                            <a href="<?= htmlspecialchars($row['enlace_drive']) ?>" class="btn" data-bs-toggle="tooltip" title="Imprimir Ticket">
                                <i class="fas fa-print"></i>
                            </a>
                            <?php if ($es_admin): ?>
                                <button class="btn btn-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalRevertir<?= $row['id_rep']; ?>" data-bs-toggle="tooltip" title="Revertir (reparacion incompleta)"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>

                               <!-- Modal para revertir -->
                               <div class="modal fade" id="modalRevertir<?= $row['id_rep']; ?>" tabindex="-1" aria-labelledby="modalRevertirLabel<?= $row['id_rep']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalRevertirLabel<?= $row['id_rep']; ?>">Revertir Reparación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de que deseas revertir esta reparación?</p>
                                                            <form action="../bd/revertir_entrega.php" method="POST">
                                                                <input type="hidden" name="id_finalizada" value="<?= htmlspecialchars($row['id_rep']); ?>">
                                                                <div class="d-flex justify-content-between">
                                                                    <button type="submit" class="btn btn-danger">Revertir</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                    </div>   
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay reparaciones entregadas.
            </div>
        <?php endif; ?>

    </div>
</div>
<!-- Scripts DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<!-- Estilos DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#reparacionesTable').DataTable({
            "searching": true, // Habilita la búsqueda
            "paging": true, // Paginación
            "info": true, // Muestra información sobre la cantidad de registros
            "lengthChange": false, // Desactiva la opción de cambiar la cantidad de registros mostrados
            "language": {
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ total registros)"
            }
        });
    });
</script>

<?php
require_once '../headfooter/footer.php';
?>
