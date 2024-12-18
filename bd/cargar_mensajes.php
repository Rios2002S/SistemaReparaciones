<?php
require_once 'cn.php'; // Asegúrate de incluir tu archivo de conexión
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "No estás logueado.";
    exit();
}

$remitente_id = $_SESSION['id_usuario']; // ID del usuario actual
$destinatario_id = $_GET['destinatario']; // ID del usuario destinatario

// Consultar los mensajes entre los dos usuarios
$query = "SELECT m.mensaje, m.fecha_envio, u.nombreusu AS remitente
          FROM mensajes m
          JOIN usuarios u ON m.remitente_id = u.id_usuario
          WHERE (m.remitente_id = ? AND m.destinatario_id = ?)
             OR (m.remitente_id = ? AND m.destinatario_id = ?)
          ORDER BY m.fecha_envio ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $remitente_id, $destinatario_id, $destinatario_id, $remitente_id);
$stmt->execute();
$result = $stmt->get_result();

// Mostrar los mensajes en formato HTML
$mensajes = '';
while ($row = $result->fetch_assoc()) {
    // Separa los mensajes según el remitente
    if ($row['remitente'] == $_SESSION['nombreusu']) {
        // Mensaje enviado por el usuario actual
        $mensajes .= '<div class="message message-right">
                        <p>' . htmlspecialchars($row['remitente']) . ': ' . htmlspecialchars($row['mensaje']) . '</p>
                        <span class="message-time">' . $row['fecha_envio'] . '</span>
                      </div>';
    } else {
        // Mensaje recibido de otro usuario
        $mensajes .= '<div class="message message-left">
                        <p>' . htmlspecialchars($row['remitente']) . ': ' . htmlspecialchars($row['mensaje']) . '</p>
                        <span class="message-time">' . $row['fecha_envio'] . '</span>
                      </div>';
    }
}

// Devolver los mensajes como respuesta
echo $mensajes;
?>
