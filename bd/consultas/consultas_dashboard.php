<?php
    // Consultas SQL
    $sql1 = "SELECT r.id_reparacion, r.id_cliente, c.nombre_cliente, c.telefono_cliente, r.fecha, r.tipo_equipo, r.serie, r.problema_equipo, r.condiciones_entrega, r.recibe_usuario, r.sucursal, r.costo, r.adelanto, r.saldo_pendiente, r.codigo_ticket 
                FROM reparaciones r 
                INNER JOIN clientes c ON r.id_cliente = c.id_cliente 
                ORDER BY r.id_reparacion DESC
                LIMIT 6";
    $result1 = $conn->query($sql1);

    $sql2 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.serie, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
                FROM reparaciones_finalizadas rf
                INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                WHERE rf.estado = 0
                ORDER BY rf.id_finalizada DESC";
    $result2 = $conn->query($sql2);

    $sql3 = "SELECT rf.id_rep, c.nombre_cliente, c.telefono_cliente, rf.fecha, rf.tipo_equipo, rf.problema_equipo, rf.condiciones_entrega, rf.recibe_usuario, rf.sucursal, rf.costo, rf.estado, rf.adelanto, rf.saldo_pendiente, rf.codigo_ticket
                FROM reparaciones_finalizadas rf
                INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                WHERE rf.estado = 1
                ORDER BY rf.id_finalizada DESC";
    $result3 = $conn->query($sql3);


    $sqlc = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes";
    $resultc = $conn->query($sqlc);

    // Consulta SQL para obtener las reparaciones por sucursal
    $sql_sucursal = "SELECT sucursal, COUNT(*) AS num_reparaciones
        FROM reparaciones_finalizadas
        GROUP BY sucursal
        ORDER BY num_reparaciones DESC";
    $result_sucursal = $conn->query($sql_sucursal);
    
    // Consulta SQL para obtener las reparaciones por sucursal
    $sql_sucursales = "SELECT nombre, ubicacion, telefono FROM sucursales";
    $result_sucursales = $conn->query($sql_sucursales);

    // Consulta para reparaciones finalizadas por sucursal
    $sql_sucursal_end = "SELECT sucursal, COUNT(*) AS num_reparaciones2
    FROM reparaciones_finalizadas
    GROUP BY sucursal
    ORDER BY num_reparaciones2 DESC";
    $result_sucursal_end = $conn->query($sql_sucursal_end);

    // Arrays para almacenar los datos
    $sucursales = [];
    $reparaciones = [];
    while ($row = $result_sucursal_end->fetch_assoc()) {
    $sucursales[] = $row['sucursal'];
    $reparaciones[] = $row['num_reparaciones2'];
    }
?>