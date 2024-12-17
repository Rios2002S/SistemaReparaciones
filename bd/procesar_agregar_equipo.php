<?php
// Conexión a la base de datos
require_once 'cn.php'; // 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre_tipo_equipo = trim($_POST['nombre_tipo_equipo']);

    // Validar que el campo no esté vacío
    if (!empty($nombre_tipo_equipo)) {
        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("INSERT INTO tipo_equipo (nombre_tipo_equipo) VALUES (?)");
        $stmt->bind_param("s", $nombre_tipo_equipo);

        if ($stmt->execute()) {
            header('Location: ../home/nrep.php');
        } else {
            // Manejo de errores
            echo "Error al agregar el tipo de equipo: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "El nombre del tipo de equipo no puede estar vacío.";
    }
}

$conn->close();
?>
