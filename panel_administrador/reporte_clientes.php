<?php
    require_once '../headfooter/head.php';

    // Consulta SQL para obtener la lista de clientes
    $sql = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes";
    $result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Reporte de Clientes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_cliente'] ?></td>
                    <td><?= $row['nombre_cliente'] ?></td>
                    <td><?= $row['telefono_cliente'] ?></td>
                    <td><?= $row['direccion_cliente'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../headfooter/footer.php'; ?>
