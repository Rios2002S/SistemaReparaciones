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
    
    // Verificar que el ID es válido
    if ($id_rep <= 0) {
        die("El ID proporcionado no es válido.");
    }

    // Obtener la fecha actual
    $fecha_finalizada = date('Y-m-d'); // Genera la fecha actual en formato 'YYYY-MM-DD'

    // Consultar el registro específico
    $sqlSelect = "SELECT * FROM reparaciones WHERE id_reparacion = ?";
    $stmt = $conn->prepare($sqlSelect);
    if (!$stmt) {
        die("Error en la consulta SELECT: " . $conn->error);
    }
    $stmt->bind_param("i", $id_rep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Preparar el INSERT con la fecha_finalizada como variable
        $sqlInsert = "INSERT INTO reparaciones_finalizadas 
        (id_rep, id_cliente, fecha, tipo_equipo, problema_equipo, condiciones_entrega, recibe_usuario, costo, sucursal, fecha_finalizada, serie, adelanto, saldo_pendiente, diagnostico, codigo_ticket) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtInsert = $conn->prepare($sqlInsert);
        if (!$stmtInsert) {
            die("Error en la consulta INSERT: " . $conn->error);
        }

        // Corregir el bind_param agregando la coma que faltaba
        $stmtInsert->bind_param(
            "iisssssdsssddss", // Tipos de datos de los campos
            $row['id_reparacion'],       // i: ID de reparación
            $row['id_cliente'],          // i: ID del cliente
            $row['fecha'],               // s: Fecha original
            $row['tipo_equipo'],         // s: Tipo de equipo
            $row['problema_equipo'],     // s: Problema del equipo
            $row['condiciones_entrega'], // s: Condiciones de entrega
            $row['recibe_usuario'],      // s: Usuario que recibe
            $row['costo'],               // d: Costo
            $row['sucursal'],            // s: Sucursal
            $fecha_finalizada,           // s: Fecha de finalización
            $row['serie'],               // s: Serie
            $row['adelanto'],            // d: Adelanto
            $row['saldo_pendiente'],     // d: Saldo pendiente
            $diagnostico,        // s: Diagnostico
            $row['codigo_ticket']        // s: Codigo del Ticket
        );

        // Ejecutar el INSERT
        if ($stmtInsert->execute()) {
            echo "Reparación movida a reparaciones_finalizadas correctamente.<br>";

            // Eliminar el registro de la tabla original
            $sqlDelete = "DELETE FROM reparaciones WHERE id_reparacion = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            if (!$stmtDelete) {
                die("Error en la consulta DELETE: " . $conn->error);
            }
            $stmtDelete->bind_param("i", $id_rep);
            if ($stmtDelete->execute()) {
                echo "Registro eliminado de reparaciones.<br>";
                // Redirigir de vuelta a la página de reparaciones
                header("Location: ../home/repen.php");
                exit();
            } else {
                echo "Error al eliminar el registro de reparaciones: " . $stmtDelete->error . "<br>";
            }
        } else {
            echo "Error al mover el registro a reparaciones_finalizadas: " . $stmtInsert->error . "<br>";
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
