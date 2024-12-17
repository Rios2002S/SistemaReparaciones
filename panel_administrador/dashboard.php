<?php
ob_start(); 
    require_once '../headfooter/head.php'; 

// Si no es administrador, redirigirlo a una página de acceso denegado
if ($es_admin != 1) {
    header("Location: ../home/no_acceso.php"); // Puedes cambiar la URL según tu necesidad
    exit();
}
    // Consultas SQL
    $sql1 = "SELECT r.id_reparacion, r.id_cliente, c.nombre_cliente, c.telefono_cliente, r.fecha, r.tipo_equipo, r.serie, r.problema_equipo, r.condiciones_entrega, r.recibe_usuario, r.sucursal, r.costo, r.adelanto, r.saldo_pendiente, r.codigo_ticket 
                FROM reparaciones r 
                INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                ORDER BY r.id_reparacion DESC
                LIMIT 6";
    $result1 = $conn->query($sql1);

    $sql2 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.serie, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
                FROM reparaciones_finalizadas rf
                INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                WHERE rf.estado = 0
                ORDER BY rf.id_finalizada DESC";
    $result2 = $conn->query($sql2);

    $sql3 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
                FROM reparaciones_finalizadas rf
                INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                WHERE rf.estado = 1
                ORDER BY rf.id_finalizada DESC";
    $result3 = $conn->query($sql3);


    $sqlc = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes";
    $resultc = $conn->query($sqlc);

    // Consulta SQL para obtener las reparaciones por sucursal
    $sql_sucursal = "SELECT sucursal, COUNT(*) AS num_reparaciones
        FROM reparaciones
        GROUP BY sucursal
        ORDER BY num_reparaciones DESC";
    $result_sucursal = $conn->query($sql_sucursal);
    
    // Consulta SQL para obtener las reparaciones por sucursal
    $sql_sucursales = "SELECT nombre, ubicacion, telefono FROM sucursales";
    $result_sucursales = $conn->query($sql_sucursales);

  // Consulta para reparaciones finalizadas por sucursal
  $sql_sucursal_end = "SELECT sucursal, COUNT(*) AS num_reparaciones2
  FROM reparaciones_finalizadas
  GROUP BY sucursal
  ORDER BY num_reparaciones2 DESC";
$result_sucursal_end = $conn->query($sql_sucursal_end);
// Arrays para almacenar los datos
$sucursales = [];
$reparaciones = [];
while ($row = $result_sucursal_end->fetch_assoc()) {
  $sucursales[] = $row['sucursal'];
  $reparaciones[] = $row['num_reparaciones2'];
}
ob_end_flush();
?>
    <style>
        .card { box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card-body { padding: 2rem; }
        .card-title { font-size: 1.25rem; }
        .header { text-align: center; margin-bottom: 30px; }
        .chart-container { height: 300px; }
        .todo-list ul { list-style-type: none; padding-left: 0; }
        .todo-list li { margin: 10px 0; }
        .todo-list input { margin-right: 10px; }
    </style>

<div class="container mt-5">
    <!-- Header -->
    <div class="header">
        <h1>Dashboard</h1>
        <p class="text-muted">Bienvenido al sistema de reparaciones</p>
    </div>

    <!-- Row 1: Cards for Key Metrics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Reparaciones Pendientes</h5>
                    <p class="card-text"><?= $result1->num_rows ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Reparaciones Finalizadas</h5>
                    <p class="card-text"><?= $result2->num_rows + $result3->num_rows ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ingresos Totales</h5>
                    <p class="card-text">$<?= array_sum(array_map(function($row) { return $row['costo']; }, $result3->fetch_all(MYSQLI_ASSOC))) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Clientes Nuevos</h5>
                    <p class="card-text"><?= $resultc->num_rows ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Chart (Bar Chart) -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reparaciones Completadas por Mes</h5>
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Recent Repairs List -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reparaciones Recientes</h5>
                    <table class="table">
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
        
        <div class="col-md-4"> 
            <div class="card">
                <div class="card-body">
                    <h3>Reparaciones Pendientes Sucursal</h3>
                    <canvas id="reparacionesChart" style="width: 200px;"></canvas> 
                </div>
            </div>
        </div>

        <!-- Row 2: Tarjeta de Reportes -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Generar Reportes</h5>
                    <p class="card-text">Accede a diferentes reportes para gestionar el sistema.</p>
                    <!-- Botones para generar reportes -->
                    <!-- Botón para Generar Reporte de Clientes -->
                   <a href="javascript:void(0);" class="btn btn-primary mb-2"  id="generarPDF"><i class="bi bi-file-earmark-pdf-fill"></i> Clientes Taller Multicomp</a>
                    <a href="javascript:void(0);" class="btn btn-secondary mb-2" id="generarPDFSucursal">Sucursales Multicomp</a>
                    <a href="reporte_entregas.php" class="btn btn-success mb-2">Reparaciones Entregadas</a>
                    <a href="reporte_pendientes.php" class="btn btn-success mb-2">Reparaciones Pendientes de Entrega</a>
                    <a href="reporte_ingresos.php" class="btn btn-success mb-2">Reporte de Ingresos</a>
                    <a href="javascript:void(0);" class="btn btn-info mb-2" onclick="window.print();">Imprimir Reporte</a> <!-- Imprimir reporte -->
                </div>
            </div>
        </div>
    </div><br><br>
    
</div>



<?php
require_once '../headfooter/footer.php'; 
?>
