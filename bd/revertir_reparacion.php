<?php
require_once 'cn.php'; // Archivo de conexión

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_rep']) && !empty($_POST['id_rep'])) {
    $id_rep = intval($_POST['id_rep']);
    
    // Verificar que el ID es válido
    if ($id_rep <= 0) {
        die("El ID proporcionado no es válido.");
    }

    // Consultar el registro específico de la tabla 'reparaciones_finalizadas'
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

        // Preparar el INSERT en la tabla 'reparaciones' para restaurar el registro
        $sqlInsert = "INSERT INTO reparaciones 
        (id_reparacion, fecha, id_cliente, tipo_equipo, problema_equipo, condiciones_entrega, recibe_usuario, costo, sucursal, serie, adelanto, saldo_pendiente, codigo_ticket) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmtInsert = $conn->prepare($sqlInsert);
        if (!$stmtInsert) {
            die("Error en la consulta INSERT: " . $conn->error);
        }

        // Insertar los datos de la reparación finalizada de vuelta a 'reparaciones'
        $stmtInsert->bind_param(
            "isissssdssdds", // Tipos de datos de los campos
            $row['id_reparacion'], // i: ID de la reparación
            $row['fecha'],        // s: Fecha original
            $row['id_cliente'],   // i: ID del cliente
            $row['tipo_equipo'],  // s: Tipo de equipo
            $row['problema_equipo'], // s: Problema del equipo
            $row['condiciones_entrega'], // s: Condiciones de entrega
            $row['recibe_usuario'],  // s: Usuario que recibe
            $row['costo'],         // d: Costo
            $row['sucursal'],      // s: Sucursal
            $row['serie'],         // s: Serie
            $row['adelanto'],      // d: Adelanto
            $row['saldo_pendiente'], // d: Saldo pendiente
            $row['codigo_ticket']         // s: Ticket
        );

        // Ejecutar el INSERT
        if ($stmtInsert->execute()) {
            echo "Reparación movida de vuelta a reparaciones correctamente.<br>";

            // Eliminar el registro de la tabla 'reparaciones_finalizadas'
            $sqlDelete = "DELETE FROM reparaciones_finalizadas WHERE id_rep = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            if (!$stmtDelete) {
                die("Error en la consulta DELETE: " . $conn->error);
            }
            $stmtDelete->bind_param("i", $id_rep);
            if ($stmtDelete->execute()) {
                echo "Registro eliminado de reparaciones_finalizadas.<br>";
                // Redirigir de vuelta a la página de reparaciones
                header("Location: ../home/repen.php");
                exit();
            } else {
                echo "Error al eliminar el registro de reparaciones_finalizadas: " . $stmtDelete->error . "<br>";
            }
        } else {
            echo "Error al mover el registro de vuelta a reparaciones: " . $stmtInsert->error . "<br>";
        }
    } else {
        echo "No se encontró un registro con el ID proporcionado en 'reparaciones_finalizadas'.<br>";
    }
} else {
    echo "No se recibió un ID válido.<br>";
}

// Cerrar conexión
$conn->close();
?>
