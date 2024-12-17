<?php
// Conexión a la base de datos
require 'cn.php'; 

if (isset($_POST['claveorden'], $_POST['costo_total'])) {
    $claveOrden = $_POST['claveorden'];
    $costoTotal = $_POST['costo_total'];

    // Actualizar el costo total y estado a "2" (Terminado)
    $sql = "UPDATE pedido_maquetas 
            SET costo_total = ?, estado = 2 
            WHERE claveorden = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$costoTotal, $claveOrden])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'invalid']);
}
?>
