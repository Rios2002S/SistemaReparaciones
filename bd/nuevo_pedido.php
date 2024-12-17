<?php
require_once 'cn.php';  // Conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
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

    // Validar si todos los campos están completos
    if (empty($id_cliente) || empty($descripcion) || empty($tipo_material) || empty($medidas) || 
        empty($sucursal_o_delivery) || empty($fecha_necesita) || empty($quien_recibe) || empty($fecha_recibe)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Insertar el pedido en la base de datos
    $query = "INSERT INTO pedido_maquetas (id_cliente, descripcion, tipo_material, medidas, sucursal_o_delivery, 
                                            fecha_necesita, presupuesto_cliente, costo_total, quien_recibe, fecha_recibe, estado)
              VALUES ('$id_cliente', '$descripcion', '$tipo_material', '$medidas', '$sucursal_o_delivery', 
                      '$fecha_necesita', '$presupuesto_cliente', '$costo_total', '$quien_recibe', '$fecha_recibe', 0)";

    // Ejecutar la consulta
    if (mysqli_query($conn, $query)) {
        // Obtener el ID generado
        $nuevoId = mysqli_insert_id($conn);

        // Generar la clave de orden
        $claveSucursal = strtoupper(substr($sucursal_o_delivery, 0, 3));
        $claveOrden = $claveSucursal . $nuevoId;

        // Actualizar el registro con la clave de orden
        $queryUpdate = "UPDATE pedido_maquetas SET claveorden = '$claveOrden' WHERE id_pedido = $nuevoId";

        if (mysqli_query($conn, $queryUpdate)) {
            // Redirigir a la página de éxito
            header("Location: ../home/maquetas.php");
            exit;
        } else {
            echo "Error al actualizar la clave de orden: " . mysqli_error($conn);
        }
    } else {
        echo "Error al añadir el pedido: " . mysqli_error($conn);
    }
}
?>
