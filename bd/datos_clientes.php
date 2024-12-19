<?php
// Incluir archivo de conexión
include('cn.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar datos de clientes
$sql = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes";
$resultc = $conn->query($sql);

// Convertir a JSON y devolver
echo json_encode($resultc->fetch_all(MYSQLI_ASSOC));

// Cerrar conexión
$conn->close();
?>
