<?php
require_once 'cn.php'; // Asegúrate de incluir tu archivo de conexión

// Verificar que el usuario esté logueado
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "No estás logueado."]);
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario actual

// Consultar todos los usuarios excepto el actual
$query = "SELECT id_usuario, nombreusu FROM usuarios WHERE id_usuario != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Crear un array de usuarios
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

// Devolver los usuarios en formato JSON
echo json_encode($usuarios);
?>
