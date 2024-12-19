<?php
require_once 'cn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $descripcion = $_POST['descripcion'];
    $tipo_material = $_POST['tipo_material'];
    $medidas = $_POST['medidas'];
    $sucursal_o_delivery = $_POST['sucursal_o_delivery'];
    $fecha_necesita = $_POST['fecha_necesita'];
    $presupuesto_cliente = $_POST['presupuesto_cliente'];
    $costo_total = $_POST['costo_total'];
    $quien_recibe = $_POST['quien_recibe'];
    $fecha_recibe = $_POST['fecha_recibe'];

    // Verificar si el valor de sucursal_o_delivery es "Delivery"
    if (strpos($sucursal_o_delivery, 'Delivery') !== false) {
        // Si es delivery, asegúrate de que incluya la dirección
        $direccion = $_POST['direccion_delivery'];
        $sucursal_o_delivery = 'Delivery: ' . $direccion;
    } else {
        // Si no es Delivery, guardar solo como "Sucursal"
        $sucursal_o_delivery = 'Sucursal';
    }

    // Validación de los campos
    if (empty($id_cliente) || empty($descripcion) || empty($tipo_material) || empty($medidas) || 
        empty($sucursal_o_delivery) || empty($fecha_necesita) || empty($quien_recibe) || empty($fecha_recibe)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Insertar en la base de datos
    $query = "INSERT INTO pedido_maquetas (id_cliente, descripcion, tipo_material, medidas, sucursal_o_delivery, 
                                            fecha_necesita, presupuesto_cliente, costo_total, quien_recibe, fecha_recibe, estado)
              VALUES ('$id_cliente', '$descripcion', '$tipo_material', '$medidas', '$sucursal_o_delivery', 
                      '$fecha_necesita', '$presupuesto_cliente', '$costo_total', '$quien_recibe', '$fecha_recibe', 0)";

    if (mysqli_query($conn, $query)) {
        $nuevoId = mysqli_insert_id($conn);
        $claveSucursal = strtoupper(substr($sucursal_o_delivery, 0, 3)); // Tomar los primeros 3 caracteres de "Delivery" o "Sucursal"
        $claveOrden = $claveSucursal . $nuevoId;

        $queryUpdate = "UPDATE pedido_maquetas SET claveorden = '$claveOrden' WHERE id_pedido = $nuevoId";

        if (mysqli_query($conn, $queryUpdate)) {
            $pedidoQuery = "SELECT p.*, c.nombre_cliente, c.telefono_cliente 
                            FROM pedido_maquetas p 
                            JOIN clientes c ON p.id_cliente = c.id_cliente 
                            WHERE p.id_pedido = $nuevoId";
            $result = mysqli_query($conn, $pedidoQuery);
            $pedido = mysqli_fetch_assoc($result);

            // Devolver un JSON con éxito y la información del pedido
            echo json_encode(["success" => true, "pedido" => $pedido]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar clave: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al añadir el pedido: " . mysqli_error($conn)]);
    }
}
?>
