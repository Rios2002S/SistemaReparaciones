<?php
require_once 'cn.php';

$id_sucursal = $_POST['id_sucursal'];

$sql = "DELETE FROM sucursales WHERE id_sucursal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_sucursal);

if ($stmt->execute()) {
    header("Location: ../panel_administrador/register.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
