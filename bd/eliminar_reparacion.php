<?php
// Incluir el archivo de conexión a la base de datos
require_once 'cn.php';

if (isset($_POST['id_rep'])) {
    $id_rep = intval($_POST['id_rep']); // Aseguramos que sea un número entero

    // Consulta para eliminar la reparación
    $sql = "DELETE FROM reparaciones WHERE id_reparacion = ?";
    
    // Preparar y ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_rep);

        if ($stmt->execute()) {
            echo "<script>alert('Reparación eliminada con éxito'); window.location.href='../home/home.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar la reparación'); window.history.back();</script>";
        }

        $stmt->close(); // Cerrar la consulta
    } else {
        echo "<script>alert('Error en la consulta'); window.history.back();</script>";
    }

    $conn->close(); // Cerrar la conexión
} else {
    echo "<script>alert('ID de reparación no recibido'); window.history.back();</script>";
}
?>
