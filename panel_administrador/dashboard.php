<?php
ob_start(); 
    require_once '../headfooter/head.php'; 

// Si no es administrador, redirigirlo a una página de acceso denegado
if ($es_admin != 1) {
    header("Location: ../home/no_acceso.php"); // Puedes cambiar la URL según tu necesidad
    exit();
}

   require_once "../bd/consultas/consultas_dashboard.php";
ob_end_flush();
?>

<div class="container mt-5">
    <!-- Header -->
    <div class="header text-center">
        <h1>Panel</h1>
        <p class="text-muted">Bienvenido al sistema de reparaciones</p>
    </div><br>

    <!-- Row 1: Cards for Key Metrics -->
    <div class="row">
        <!-- Card 1: Reparaciones Pendientes -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Reparaciones Pendientes</h5>
                    <h2 class="fw-bold"><?= $result1->num_rows ?></h2>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Reparaciones Finalizadas -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Reparaciones Finalizadas</h5>
                    <h2 class="fw-bold"><?= $result2->num_rows + $result3->num_rows ?></h2>
                </div>
            </div>
        </div>
        
        <!-- Card 3: Ingresos Totales -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Ingresos Totales</h5>
                    <h2 class="fw-bold">$<?= number_format(array_sum(array_map(function($row) { return $row['costo']; }, $result3->fetch_all(MYSQLI_ASSOC))), 2) ?></h2>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Clientes Nuevos -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Clientes Nuevos</h5>
                    <h2 class="fw-bold"><?= $resultc->num_rows ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Chart (Bar Chart) and Recent Repairs List -->
    <div class="row mt-4">
        <!-- Reparaciones Completadas por Mes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-muted">Reparaciones Completadas por Mes</h5>
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reparaciones Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-muted">Reparaciones Recientes</h5>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Equipo</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result1->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id_reparacion'] ?></td>
                                        <td><?= $row['nombre_cliente'] ?></td>
                                        <td><?= $row['tipo_equipo'] ?></td>
                                        <td><?= $row['fecha'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts and Reportes -->
    <div class="row mt-4">
        <!-- Reparaciones Pendientes Sucursal -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-muted">Reparaciones Pendientes Sucursal</h5>
                    <canvas id="reparacionesChart" style="width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Generar Reportes -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-muted">Generar Reportes</h5>
                    <p class="card-text">Accede a diferentes reportes para gestionar el sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary mb-2" id="generarPDF"><i class="bi bi-file-earmark-pdf-fill"></i> Clientes Taller Multicomp</a>
                        <a href="javascript:void(0);" class="btn btn-secondary mb-2" id="generarPDFSucursal">Sucursales Multicomp</a>
                        <a href="reporte_entregas.php" class="btn btn-success mb-2">Reparaciones Entregadas</a>
                        <a href="reporte_pendientes.php" class="btn btn-success mb-2">Reparaciones Pendientes de Entrega</a>
                        <a href="reporte_ingresos.php" class="btn btn-success mb-2">Reporte de Ingresos</a>
                        <a href="javascript:void(0);" class="btn btn-info mb-2" onclick="window.print();">Imprimir Reporte</a> <!-- Imprimir reporte -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<?php
require_once '../headfooter/footer.php'; 
?>
