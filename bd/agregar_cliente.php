<?php
require_once 'cn.php'; // Archivo de conexión

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre_cliente = $_POST['nombre_cliente'];
$telefono_cliente = $_POST['telefono_cliente'];
$email_cliente = $_POST['email_cliente'];
$direccion_cliente = $_POST['direccion_cliente'];

// Insertar el nuevo cliente en la base de datos
$sql = "INSERT INTO clientes (nombre_cliente, telefono_cliente, email_cliente, direccion_cliente)
        VALUES ('$nombre_cliente', '$telefono_cliente', '$email_cliente', '$direccion_cliente')";

if ($conn->query($sql) === TRUE) {
    // Verificar el origen del formulario y redirigir a la página correcta
    if (isset($_POST['form_source'])) {
        $formSource = $_POST['form_source'];

        if ($formSource === 'nrep') {
            header('Location: ../home/nrep.php?status=success');
        } elseif ($formSource === 'pedido_maquetas') {
            header('Location: ../home/maquetas.php?status=success');
        }
    }
} else {
    // En caso de error, redirigir a la página correspondiente con el mensaje de error
    if (isset($_POST['form_source'])) {
        $formSource = $_POST['form_source'];

        if ($formSource === 'nrep') {
            header('Location: ../home/nrep.php?status=error');
        } elseif ($formSource === 'pedido_maquetas') {
            header('Location: ../home/pedido_maquetas.php?status=error');
        }
    }
}

$conn->close();
?>
