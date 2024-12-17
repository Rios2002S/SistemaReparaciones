<?php
require_once 'cn.php';

$id_sucursal = $_POST['id_sucursal'];
$nombre = $_POST['nombre'];
$ubicacion = $_POST['ubicacion'];
$telefono = $_POST['telefono'];

$sql = "UPDATE sucursales SET nombre = ?, ubicacion = ?, telefono = ? WHERE id_sucursal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nombre, $ubicacion, $telefono, $id_sucursal);

if ($stmt->execute()) {
    header("Location: ../panel_administrador/register.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
