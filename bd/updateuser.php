<?php
require_once '../bd/cn.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = intval($_POST['id_usuario']);
    $nombreusu = htmlspecialchars(trim($_POST['nombreusu']));
    $es_admin = intval($_POST['es_admin']);
    $sucursalAsig = htmlspecialchars(trim($_POST['sucursal_asignada'])); 

    // Validar que los datos sean correctos
    if ($id_usuario && $nombreusu && ($es_admin === 0 || $es_admin === 1)) {
        $sql = "UPDATE usuarios SET nombreusu = ?, es_admin = ?, sucursal_asignada = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        
        // Asegúrate de que el tipo de datos sea correcto
        $stmt->bind_param("sisi", $nombreusu, $es_admin, $sucursalAsig, $id_usuario);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario actualizado correctamente.'); window.location.href='../panel_administrador/register.php';</script>";
        } else {
            // Mostrar el error de la base de datos
            echo "<script>alert('Error al actualizar el usuario: " . $stmt->error . "'); window.location.href='../panel_administrador/register.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Datos inválidos.'); window.location.href='../panel_administrador/register.php';</script>";
    }
}
$conn->close();
?>