<?php
ob_start(); // Iniciar el buffer de salida
require_once  '../headfooter/head.php';

// Obtener información del usuario (por ejemplo, del ID de sesión)
$id_usuario =  $idusuario; 
$query = "SELECT nombreusu, nombre, numero_telefono, foto, sucursal_asignada FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Actualizar información si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $numero_telefono = $_POST['numero_telefono'];
    $foto_actual = $usuario['foto']; // Foto actual en la base de datos

    // Manejo del archivo de foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = '../uploads/' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    } else {
        $foto = $foto_actual; // Mantener la foto actual si no se cambia
    }

    // Actualizar la información del usuario
    $query_update = "UPDATE usuarios SET nombre = ?, numero_telefono = ?, foto = ? WHERE id_usuario = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sssi", $nombre, $numero_telefono, $foto, $id_usuario);

    if ($stmt_update->execute()) {
        echo "<div class='alert alert-success'>Perfil actualizado correctamente.</div>";
        // Refrescar la información del usuario
        header("Location: perfil.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el perfil.</div>";
    }
}

$conn->close();
ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>

<div class="container mt-5">
    <h1 class="mb-4">Perfil del Usuario</h1>
    <div class="card">
        <div class="card-header">Información del Usuario</div>
        <div class="card-body ">
            
            <!-- Mostrar la foto de perfil arriba centrada y en forma de círculo -->
            <div class="text-center mb-3">
                <?php if ($usuario['foto']): ?>
                    <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de Perfil" class="rounded-circle" width="175" height="150">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Foto Predeterminada" class="rounded-circle" width="175" height="150">
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombreusu" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="nombreusu" value="<?= htmlspecialchars($usuario['nombreusu']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="numero_telefono" class="form-label">Número de Teléfono:</label>
                    <input type="text" class="form-control" id="numero_telefono" name="numero_telefono" value="<?= htmlspecialchars($usuario['numero_telefono']) ?>">
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Actualizar Foto de Perfil:</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                </div>
                <div class="mb-3">
                    <label for="sucursal_asignada" class="form-label">Sucursal Asignada:</label>
                    <input type="text" class="form-control" id="sucursal_asignada" value="<?= htmlspecialchars($usuario['sucursal_asignada']) ?>" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once  '../headfooter/footer.php';
?>