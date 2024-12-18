<?php
        session_start();
        require_once("../bd/cn.php");
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../index.php"); // Si no está autenticado, redirigir al login
            exit();
        }

        // Verificar si el usuario está autenticado y obtener los valores de la sesión
        $es_admin = $_SESSION['es_admin'] ?? 0;
        $nombreu = $_SESSION['nombreusu'];
        $trabaja = $_SESSION['sucursal_asignada']; // Sucursal donde trabaja el usuario
        $idusuario = $_SESSION['id_usuario']; 
        $nombre = $_SESSION['nombre']; 

        // Si es administrador, consulta todas las reparaciones
        if ($es_admin) {
            $sql1 = "SELECT r.id_reparacion, r.id_cliente, c.nombre_cliente, c.telefono_cliente, r.fecha, r.tipo_equipo, r.serie, r.problema_equipo, r.condiciones_entrega, r.recibe_usuario, r.sucursal, r.costo, r.adelanto, r.saldo_pendiente, r.codigo_ticket 
                    FROM reparaciones r 
                    INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                    ORDER BY r.id_reparacion DESC;";
        } else {
            // Si no es administrador, solo consulta las reparaciones de la sucursal donde trabaja el usuario
            $sql1 = "SELECT r.id_reparacion, r.id_cliente, c.nombre_cliente, c.telefono_cliente, r.fecha, r.tipo_equipo, r.serie, r.problema_equipo, r.condiciones_entrega, r.recibe_usuario, r.sucursal, r.costo, r.adelanto, r.saldo_pendiente, r.codigo_ticket 
                    FROM reparaciones r 
                    INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                    WHERE r.sucursal = '$trabaja'  
                    ORDER BY r.id_reparacion DESC;";
        }

        // Ejecutar la consulta
        $result1 = $conn->query($sql1);



        // Clientes
        $sqlc = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes ORDER BY id_cliente DESC;";
        $resultc = $conn->query($sqlc);

        // Sucursales
        $sqls = "SELECT nombre FROM sucursales";
        $results = $conn->query($sqls);

        // Tipo de Equipo
        $sqlte = "SELECT nombre_tipo_equipo FROM tipo_equipo";
        $resultte = $conn->query($sqlte);

        // Consulta para obtener los datos de la tabla pedido_maquetas
        $query = "SELECT pm.id_pedido, c.nombre_cliente, c.telefono_cliente, pm.descripcion, pm.tipo_material, 
        pm.medidas, pm.sucursal_o_delivery, pm.fecha_necesita, pm.presupuesto_cliente, 
        pm.costo_total, pm.quien_recibe, pm.fecha_recibe, pm.claveorden, pm.estado, pm.enlace_drive
        FROM pedido_maquetas pm
        JOIN clientes c ON pm.id_cliente = c.id_cliente";

        $result = mysqli_query($conn, $query);

        // Verifica si hay resultados
        $pedidos = [];
        if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
        $pedidos[] = $row;
        }
        }
?>
<!doctype html>
<html lang="en">
    <head>
        <title>MULTICOMP</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Protest+Guerrilla&display=swap" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://apis.google.com/js/api.js"></script>
     <link rel="stylesheet" href="../css/disenio.css">   
    </head>

    <body>
        <header>
            <div class="infor mb-0 fijo">
                <h3 class="m-0 fs-3">MULTICOMP&nbsp;</h3>
            </div><br><br>


            <!-- Menú deslizante -->
            <?php
            require_once  '../adicionales/navbar.php';
            ?>

        </header>
        <?php
        require_once '../adicionales/saludo.php';
        ?>
        <main>