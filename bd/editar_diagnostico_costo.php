<?php
require_once 'cn.php'; // Archivo de conexión

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_rep']) && !empty($_POST['id_rep'])) {
    $id_rep = intval($_POST['id_rep']);
    $diagnostico = $_POST['diagnostico'];
    $costo = floatval($_POST['costo']);
    
    // Verificar que el ID es válido
    if ($id_rep <= 0) {
        die("El ID proporcionado no es válido.");
    }

    // Consultar el registro específico
    $sqlSelect = "SELECT * FROM reparaciones_finalizadas WHERE id_rep = ?";
    $stmt = $conn->prepare($sqlSelect);
    if (!$stmt) {
        die("Error en la consulta SELECT: " . $conn->error);
    }
    $stmt->bind_param("i", $id_rep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Obtener el adelanto de la base de datos
        $adelanto = $row['adelanto'];

        // Calcular el saldo pendiente
        $saldo_pendiente = $costo - $adelanto;

        // Actualizar el diagnóstico, costo y saldo pendiente
        $sqlUpdate = "UPDATE reparaciones_finalizadas 
                      SET diagnostico = ?, costo = ?, saldo_pendiente = ? 
                      WHERE id_rep = ?";

        $stmtUpdate = $conn->prepare($sqlUpdate);
        if (!$stmtUpdate) {
            die("Error en la consulta UPDATE: " . $conn->error);
        }
        $stmtUpdate->bind_param("sdii", $diagnostico, $costo, $saldo_pendiente, $id_rep);

        if ($stmtUpdate->execute()) {
            echo "Diagnóstico, Costo y Saldo Pendiente actualizados correctamente.<br>";
            // Redirigir a la página donde se muestran las reparaciones
            header("Location: ../home/repen.php");
            exit();
        } else {
            echo "Error al actualizar los datos: " . $stmtUpdate->error . "<br>";
        }
    } else {
        echo "No se encontró un registro con el ID proporcionado.<br>";
    }
} else {
    echo "No se recibió un ID válido.<br>";
}

// Cerrar conexión
$conn->close();
?>
