<?php
require_once 'cn.php'; // Incluir el archivo de conexión

// Verificar si se ha recibido el ID de la reparación
if (isset($_POST['id_finalizada']) && !empty($_POST['id_finalizada'])) {
    $id_finalizada = intval($_POST['id_finalizada']);
    
    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para actualizar el estado de la reparación
    $sql = "UPDATE reparaciones_finalizadas SET estado = 0 WHERE id_rep = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_finalizada);

    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de reparaciones finalizadas con éxito
        header("Location: ../home/repen.php");
        exit();
    } else {
        echo "Error al actualizar el estado: " . $stmt->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "No se ha recibido un ID válido.";
}
?>
 