<?php
require_once '../headfooter/head.php';

// Consulta SQL para obtener los ingresos totales de reparaciones finalizadas
$sql = "SELECT SUM(costo) AS total_ingresos FROM reparaciones_finalizadas";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_ingresos = $row['total_ingresos'];

// Consulta SQL para obtener la suma de adelantos cuando el estado es 0
$sql_adelantos = "SELECT SUM(adelanto) AS total_adelantos FROM reparaciones_finalizadas WHERE estado = 0";
$result_adelantos = $conn->query($sql_adelantos);
$row_adelantos = $result_adelantos->fetch_assoc();
$total_adelantos = $row_adelantos['total_adelantos'];

// Determinar el valor de dinero en caja basado en el estado
$dinero_en_caja = 0;
if ($total_adelantos > 0) {
    $dinero_en_caja = $total_adelantos;
} else {
    $dinero_en_caja = $total_ingresos;
}

$sql3 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
FROM reparaciones_finalizadas rf
INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
WHERE rf.estado = 1
ORDER BY rf.id_finalizada DESC";
$result3 = $conn->query($sql3);

$sql4 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
FROM reparaciones_finalizadas rf
INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
WHERE rf.estado = 0
ORDER BY rf.id_finalizada DESC";
$result4 = $conn->query($sql4);
?>

<div class="container mt-5">
        <button class="home-btn" onclick="window.history.back()">
            <i class="fas fa-home home-icon"></i>
        </button>

    <h2>Reporte de Ingresos Totales al entregar todas las reparaciones</h2>
    <p>Total de Ingresos: $<?= number_format($dinero_en_caja, 2) ?></p>
    <h2 class="card-title">Ingresos de Adelantos </h2>
    <p class="card-text">Dinero en Caja $<?= number_format($dinero_en_caja, 2) ?></p>
    <br><br>
</div>

<?php require_once '../headfooter/footer.php'; ?>
