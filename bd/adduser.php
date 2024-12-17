<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreusu = $_POST['nombreusu'];
    $pas = $_POST['contrasena'];
    $claveAdmin = $_POST['clave_admin'];
    $sucursalAsignada = $_POST['sucursal_asignada'];

    // Clave de administrador predefinida (puedes cambiarla o almacenarla en una configuración segura)
    $claveAdminValida = "clavesecreta123";

    // Verificar la clave de administrador
    if ($claveAdmin !== $claveAdminValida) {
        die("Clave de administrador incorrecta. No tienes permisos para realizar el registro.");
    }

    require_once 'cn.php'; // Archivo de conexión a la base de datos

    // Generar el hash de la contraseña
    $hashedPassword = password_hash($pas, PASSWORD_DEFAULT);

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conex->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO usuarios (nombreusu, contrasena, sucursal_asignada) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Asignar los valores a los parámetros
    $stmt->bind_param("sss", $nombreusu, $hashedPassword, $sucursalAsignada);
    $stmt->execute();

    // Verificar la ejecución de la consulta
    if ($stmt->affected_rows > 0) {
        header("Location: ../panel_administrador/register.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conex->close();
}
?>
