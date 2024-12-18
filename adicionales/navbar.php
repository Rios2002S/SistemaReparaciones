<div id="sideMenu">
    <span class="close-btn" onclick="closeMenu()">&times;</span>

    <!-- Sección del logo y el nombre de usuario -->
    <div class="username">
        <div class="user-logo-container">
            <img src="https://i.ibb.co/S5TM0Ww/logop.png" alt="Logo" class="user-logo">
        </div>
    </div>

    <!-- Menú de navegación -->
    <a href="../home/home.php"><i class="fas fa-home"></i> Inicio</a>
    
    <!-- Dropdown para las opciones de reparación -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-tools"></i> Reparaciones</a>
        <ul class="dropdown-menu">
            <li><a href="../home/nrep.php" class="dropdown-item">Nueva Reparación</a></li>
            <li><a href="../home/maquetas.php" class="dropdown-item">Maquetas</a></li>
            <li><a href="../home/repen.php" class="dropdown-item">Reparaciones Finalizadas</a></li>
            <li><a href="../home/entregadas.php" class="dropdown-item">Reparaciones Entregadas</a></li>
        </ul>
    </div>

    <a href="../home/clientes.php"><i class="fas fa-users"></i> Editar Clientes</a>

    <!-- Opciones para admin -->
    <?php if ($es_admin): ?>
        <a href="../panel_administrador/register.php"><i class="fas fa-cogs"></i> Administrar Usuarios/Sucursales</a>
        <a href="../panel_administrador/dashboard.php"><i class="fas fa-tachometer-alt"></i> Principal</a>
    <?php endif; ?>

    <a href="../home/mensajeria.php"><i class="fas fa-users"></i> Mensajeria</a>

    <!-- Dropdown para el perfil y cerrar sesión -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i><?php echo htmlspecialchars($nombreu); ?></a>
        <ul class="dropdown-menu">
            <li><a href="../home/perfil.php" class="dropdown-item">Ver Perfil</a></li>
            <li><a href="../bd/logout.php" class="dropdown-item">Cerrar Sesión</a></li>
        </ul>
    </div>
</div>


<!-- Overlay -->
<div id="menuOverlay" onclick="closeMenu()"></div>

<div class="container mx-5">
    <button class="open-btn " onclick="openMenu()" data-bs-toggle="tooltip" title="Abrir menú"><i class="fas fa-bars"></i></button>
</div>

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