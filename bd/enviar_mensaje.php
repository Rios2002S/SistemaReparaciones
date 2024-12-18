<?php
require_once 'cn.php'; // Asegúrate de incluir tu archivo de conexión
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "No estás logueado.";
    exit();
}

$remitente_id = $_SESSION['id_usuario']; // ID del usuario actual
$destinatario_id = $_POST['destinatario']; // ID del usuario destinatario
$mensaje = $_POST['mensaje']; // Mensaje enviado

// Verificar que los campos no estén vacíos
if (empty($destinatario_id) || empty($mensaje)) {
    echo "Por favor, completa todos los campos.";
    exit();
}

// Insertar el mensaje en la base de datos
$query = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje, fecha_envio) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $remitente_id, $destinatario_id, $mensaje);

if ($stmt->execute()) {
    echo "Mensaje enviado correctamente.";
} else {
    echo "Error al enviar el mensaje.";
}
?>
