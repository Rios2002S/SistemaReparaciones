<?php
// actualizar_enlace.php
header('Content-Type: application/json');

// Obtener los datos JSON enviados desde JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Obtener los valores
$idRep = $data['idRep'];
$fileLink = $data['fileLink'];

// Validar que los datos no estén vacíos
if (empty($idRep) || empty($fileLink)) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'reparacion_hardware';
$username = 'root';
$password = '';

// Crear la conexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar el enlace en la base de datos
    $sql = "UPDATE reparaciones_finalizadas SET enlace_drive = :fileLink WHERE id_rep = :idRep";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fileLink', $fileLink);
    $stmt->bindParam(':idRep', $idRep);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Enlace actualizado correctamente']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el enlace']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}
?>
