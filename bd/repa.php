<?php
ob_start(); // Iniciar el buffer de salida

require_once '../headfooter/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id_cliente = $_POST['id_cliente']; // ID del cliente
    $tipo_equipo = $_POST['tipo_equipo'];
    $problema_equipo = $_POST['problema_equipo'];
    
    // Validar y limpiar el campo 'serie'
    $serie = trim($_POST['serie']);
    if (empty($serie)) {
        echo "El campo de la serie no puede estar vacío.";
        exit();  // Terminar el script si 'serie' está vacío
    }

    $condiciones_entrega = $_POST['condiciones_entrega'];
    $costo = $_POST['costo'];
    $sucursal = $_POST['sucursal']; // Sucursal
    $adelanto = $_POST['adelanto']; // Nuevo: Adelanto
    $recibe_usuario = $_SESSION['nombreusu']; // Nombre del usuario en sesión

    // Obtener la fecha actual
    $fecha_actual = date('Y-m-d'); // Formato: 'AAAA-MM-DD'

    require_once 'cn.php'; // Archivo de conexión a la base de datos

    // Crear la conexión
    $conex = new mysqli($servername, $username, $password, $database);
    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    // Calcular el saldo pendiente
    $saldo_pendiente = $costo - $adelanto;  // Resta el adelanto al costo total

    // Obtener el nombre del cliente
    $sqlCliente = "SELECT nombre_cliente FROM clientes WHERE id_cliente = ?";
    $stmtCliente = $conex->prepare($sqlCliente);
    $stmtCliente->bind_param("i", $id_cliente);
    $stmtCliente->execute();
    $resultCliente = $stmtCliente->get_result();
    
    $nombre_cliente = '';
    if ($resultCliente->num_rows > 0) {
        $rowCliente = $resultCliente->fetch_assoc();
        $nombre_cliente = $rowCliente['nombre_cliente'];
    }

    // Generar un código único combinando parte del nombre del cliente y la serie
    //$codigo_reparacion = strtoupper(substr($nombre_cliente, 0, 3)) . '-' . strtoupper(substr($sucursal, 0, 3)) . '-' . date('Ymd-His');
    $codigo_reparacion = strtoupper(substr(explode(' ', $sucursal)[0], 0, 2)) . strtoupper(substr(explode(' ', $sucursal)[1], 0, 1)) . '-' . strtoupper(substr($nombre_cliente, 0, 3)) . '-' . date('Ymd-His');

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO reparaciones (fecha, id_cliente, tipo_equipo, problema_equipo, condiciones_entrega, recibe_usuario, costo, sucursal, serie, adelanto, saldo_pendiente, codigo_ticket) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conex->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }

    $stmt->bind_param(
        "sisssssssdds", 
        $fecha_actual,     // s: String
        $id_cliente,       // i: Integer
        $tipo_equipo,      // s: String
        $problema_equipo,  // s: String
        $condiciones_entrega, // s: String
        $recibe_usuario,   // s: String
        $costo,            // d: Decimal
        $sucursal,         // s: String
        $serie,            // s: String
        $adelanto,         // d: Decimal
        $saldo_pendiente,  // d: Decimal
        $codigo_reparacion // s: String (Código de reparación)
    );
    
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirigir inmediatamente después de insertar
        header('Location: ../home/home.php');
        exit();  
    } else {
        echo "Error al registrar la reparación: " . $conex->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conex->close();
}

ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>
