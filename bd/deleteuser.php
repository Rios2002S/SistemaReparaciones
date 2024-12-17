<?php
require_once '../bd/cn.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = intval($_POST['id_usuario']);

    if ($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario eliminado correctamente.'); window.location.href='../panel_administrador/register.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el usuario.'); window.location.href='../panel_administrador/register.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('ID de usuario inválido.'); window.location.href='../panel_administrador/register.php';</script>";
    }
}
$conn->close();
?>
