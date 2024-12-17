<?php
ob_start(); // Iniciar el buffer de salida
require_once '../headfooter/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $email_cliente = $_POST['telefono_cliente'];
    $direccion_cliente = $_POST['direccion_cliente'];

    // Actualizar los datos en la base de datos
    $sql = "UPDATE clientes SET nombre_cliente = ?, telefono_cliente = ?, direccion_cliente = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $nombre_cliente, $email_cliente, $direccion_cliente, $id_cliente);
    
    if ($stmt->execute()) {
        // Redirigir después de actualizar
        header('Location: ../home/clientes.php');
    } else {
        echo "Error al actualizar el cliente.";
    }
}

ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>