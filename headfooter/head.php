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
        <style>
            .infor {
                text-align: right;
                background: darkblue;
                color: white;
                font-family: "Protest Guerrilla", sans-serif;
            }
                        /* Estilo para el navbar cuando se vuelve pegajoso */
            .fijo {
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 1030; 
                transition: top 0.3s; 
            }
            /* Estilo para la imagen del logo */
            .user-logo {
                width: 200px; /* Logo más pequeño */
                height: auto; /* Mantiene la proporción del logo */
                margin-bottom: -25px; /* Menos espacio entre el logo y el nombre */
            }
            .username {
                font-size: 20px;
                font-weight: bold;
                color: #333;
                text-align: center;
                margin-bottom: 20px;
                padding: 0 20px;
                border-bottom: 2px solid #bbb;
                display: flex;
                flex-direction: column;  /* Alinea el logo y el nombre en columna */
                align-items: center;     /* Centra todo el contenido horizontalmente */
            }
         /* Estilos del menú deslizante */
         #sideMenu {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #d3d3d3;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            z-index: 1050; /* Encima del contenido principal */
        }

        #sideMenu a {
            padding: 10px 20px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
            transition: 0.3s;
            border-bottom: 1px solid #bbb;
        }

        #sideMenu a:hover {
            background-color: #bbb;
        }

        #sideMenu .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }

        #sideMenu .username {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            padding: 0 20px;
            border-bottom: 2px solid #bbb;
        }

        /* Botón para abrir el menú */
        .open-btn {
            position: fixed;    /* Mantenerlo fijo en la pantalla */
            top: 35px;          /* Ajusta la posición superior */
            left: 5px;         /* Ajusta la posición izquierda */
            font-size: 20px;
            cursor: pointer;
            background-color: darkblue;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
            z-index: 1000;      /* Asegura que esté sobre otros elementos */
        }


        .open-btn:hover {
            background-color: #0056b3;
        }

        /* Overlay oscuro */
        #menuOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1049; /* Debajo del menú pero encima del contenido */
            transition: opacity 0.3s;
        }

        #menuOverlay.active {
            display: block;
            opacity: 1;
        }

        /* Estilo del botón de Home */
        .home-btn {
            background-color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .home-btn:hover {
            transform: scale(1.2);
            background-color: #f0f0f0;
        }

        /* Estilo del icono */
        .home-icon {
            font-size: 24px;
            color: darkblue;
        }

        </style>
    </head>

    <body>
        <header>
            <div class="infor mb-0 fijo">
                <h3 class="m-0 fs-3">MULTICOMP&nbsp;</h3>
            </div><br><br>


          <!-- Menú deslizante -->
          <div id="sideMenu">
                <span class="close-btn" onclick="closeMenu()">&times;</span>
                <div class="username">
                    <!-- Logo sobre el nombre -->
                    <img src="https://i.ibb.co/kgNwmJs/logo2.png" alt="Logo" class="user-logo">
                    <?php echo htmlspecialchars($nombreu); ?>
                </div>                
                <a href="../home/home.php">Inicio</a>
                <a href="../home/nrep.php">Nueva Reparación</a>
                <a href="../home/maquetas.php">Maquetas</a>
                <a href="../home/repen.php">Reparaciones Finalizadas</a>
                <a href="../home/entregadas.php">Reparaciones Entregadas</a>
                <a href="../home/clientes.php">Editar Clientes</a>
                <?php if ($es_admin): ?>
                    <a href="../panel_administrador/register.php">Administrar Usuarios/Sucursales</a>
                    <a href="../panel_administrador/dashboard.php">Principal</a>
                <?php endif; ?>
                <a href="../bd/logout.php">Cerrar Sesión</a>
          </div>

        <!-- Overlay -->
        <div id="menuOverlay" onclick="closeMenu()"></div>

        <div class="container mx-5">
            <button class="open-btn " onclick="openMenu()" data-bs-toggle="tooltip" title="Abrir menú"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main>
        <?php
            // Establecer la zona horaria de Centroamérica (El Salvador)
            date_default_timezone_set('America/El_Salvador');

            // Establecer los nombres de los meses en español
            $meses = array(
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            );

            // Obtener la hora actual
            $hora_actual = date('H'); // La hora en formato de 24 horas (00-23)

            // Determinar el saludo según la hora
            if ($hora_actual >= 0 && $hora_actual < 12) {
                $saludo = "Buen día 🌅";
            } elseif ($hora_actual >= 12 && $hora_actual < 18) {
                $saludo = "Buenas tardes 🌞";
            } else {
                $saludo = "Buenas noches 🌙";
            }

            // Obtener la fecha actual (mes, día)
            $mes = $meses[date('n')]; // Usar el mes en español
            $dia = date('j'); // Día del mes (sin ceros a la izquierda)
        ?>
            <div class="container">
                <div class="alert alert-primary mt-4" role="alert">
                    <h5><?php echo $saludo . " " . htmlspecialchars($nombreu); ?></h5>
                    <p>Fecha: <?php echo $mes . " " . $dia; ?></p>
                    <p>Hora: <span id="hora"></span></p>
                </div>
            </div>

            <!-- Script para actualizar la hora dinámicamente -->
            <script>
                function actualizarHora() {
                    const ahora = new Date();
                    const horas = ahora.getHours().toString().padStart(2, '0');
                    const minutos = ahora.getMinutes().toString().padStart(2, '0');
                    const segundos = ahora.getSeconds().toString().padStart(2, '0');
                    const horaActual = horas + ":" + minutos + ":" + segundos;
                    document.getElementById('hora').textContent = horaActual;
                }

                // Llamar a la función de actualización cada 1000 ms (1 segundo)
                setInterval(actualizarHora, 1000);

                // Llamar a la función una vez al cargar la página para mostrar la hora inmediatamente
                actualizarHora();
            </script>
    </main>

    <script>
        function openMenu() {
            document.getElementById("sideMenu").style.width = "250px";
            document.getElementById("menuOverlay").classList.add("active");
        }

        function closeMenu() {
            document.getElementById("sideMenu").style.width = "0";
            document.getElementById("menuOverlay").classList.remove("active");
        }
    </script>
        </header>
        <main>