<?php
require_once '../headfooter/head.php'; 
?>

<div class="container my-5">

    <!-- Navegación de pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="true">Gestionar Usuarios</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="sucursales-tab" data-bs-toggle="tab" href="#sucursales" role="tab" aria-controls="sucursales" aria-selected="false">Gestionar Sucursales</a>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <?php
        require_once '../adicionales/usersucur.php';
        ?>
    </div>
</div>

<?php
require_once '../headfooter/footer.php';
?>
