<?php
session_start(); // Inicia la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usu = $_POST['nombreusu'];
    $pas = $_POST['contrasena'];

    require_once 'cn.php';

    // Sanitización (si es necesario, aunque la consulta preparada lo mitiga)
    $usu = htmlspecialchars($usu, ENT_QUOTES, 'UTF-8');
    $pas = htmlspecialchars($pas, ENT_QUOTES, 'UTF-8');

    // Crear conexión
    $conex = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    // Preparar y ejecutar la consulta
    $sql = "SELECT id_usuario, nombreusu, nombre, contrasena, es_admin, sucursal_asignada FROM usuarios WHERE nombreusu = ?";
    $stmt = $conex->prepare($sql);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }

    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("s", $usu);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombreusu, $nombre, $hashedPassword, $es_admin, $sucursal_usuario);
    $stmt->fetch();

// Verificar si se encontró el usuario y la contraseña
if ($id_usuario && password_verify($pas, $hashedPassword)) {
    // La contraseña es correcta, iniciar sesión
    $_SESSION['id_usuario'] = $id_usuario;
    $_SESSION['nombreusu'] = $nombreusu;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['es_admin'] = $es_admin;
    $_SESSION['sucursal_asignada'] = $sucursal_usuario; // Asignar correctamente la sucursal

    // Redirigir a la página correspondiente según si es admin o no
    if ($es_admin) {
        // Si es admin, redirigir al dashboard
        header("Location: ../panel_administrador/dashboard.php"); // Redirigir al dashboard del administrador
    } else {
        // Si no es admin, redirigir a la página común
        header("Location: ../home/home.php"); // Redirigir a la página común
    }
    exit();
}
else {
        // Usuario o contraseña incorrectos
        // En producción, deberías evitar mostrar el error exacto por razones de seguridad
        echo "Usuario o contraseña incorrectos. Inténtalo de nuevo.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conex->close();
}
?>
