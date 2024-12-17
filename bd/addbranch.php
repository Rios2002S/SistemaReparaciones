<?php
require_once 'cn.php';

$nombre = $_POST['nombre'];
$ubicacion = $_POST['ubicacion'];
$telefono = $_POST['telefono'];

$sql = "INSERT INTO sucursales (nombre, ubicacion, telefono) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $ubicacion, $telefono);

if ($stmt->execute()) {
    header("Location: ../panel_administrador/register.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
