<?php
// Conexión a la base de datos
require 'cn.php'; 

if (isset($_POST['claveorden'])) {
    $claveOrden = $_POST['claveorden'];

    // Actualizar el estado a 1 (Terminado)
    $sql = "UPDATE pedido_maquetas SET estado = 1 WHERE claveorden = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$claveOrden])) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
?>
