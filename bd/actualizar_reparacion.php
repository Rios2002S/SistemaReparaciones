<?php
ob_start(); // Iniciar el buffer de salida

require_once '../headfooter/head.php';
require_once 'cn.php'; // Archivo de conexión a la base de datos

// Verificar que la solicitud sea un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id_rep = $_POST['id_rep']; // ID de la reparación
    $id_cliente = $_POST['id_cliente'];
    $tipo_equipo = $_POST['tipo_equipo'];
    $problema_equipo = $_POST['problema_equipo'];
    $condiciones_entrega = $_POST['condiciones_entrega'];
    $serie = $_POST['serie'];
    $costo = $_POST['costo'];
    $adelanto = $_POST['adelanto'];
    $sucursal = $_POST['sucursal'];
    // Mantener el código de ticket tal como está, sin actualizar
    $codigo_ticket = $_POST['codigo_ticket'];

    // Calcular el saldo pendiente
    $saldo_pendiente = $costo - $adelanto;

    // Conectar a la base de datos
    $conex = new mysqli($servername, $username, $password, $database);
    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    // Preparar la consulta SQL para actualizar la reparación
    $sql = "UPDATE reparaciones SET 
            tipo_equipo = ?, 
            problema_equipo = ?, 
            condiciones_entrega = ?, 
            serie = ?, 
            costo = ?, 
            adelanto = ?, 
            saldo_pendiente = ?, 
            sucursal = ? 
            WHERE id_reparacion = ?";

    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }

    // Enlazar parámetros sin incluir 'codigo_ticket' para que no se actualice
    $stmt->bind_param(
        "ssssdddsd", // Los parámetros a vincular, el 's' es para strings y 'd' es para decimal
        $tipo_equipo,        // s: string
        $problema_equipo,    // s: string
        $condiciones_entrega, // s: string
        $serie,              // s: string
        $costo,              // d: decimal
        $adelanto,           // d: decimal
        $saldo_pendiente,    // d: decimal
        $sucursal,           // s: string
        $id_rep              // d: ID de la reparación
    );

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir después de actualizar
    header("Location: ../home/home.php#reparacion$id_rep");
        exit();
    } else {
        echo "Error al actualizar la reparación: " . $conex->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conex->close();
}

ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>
