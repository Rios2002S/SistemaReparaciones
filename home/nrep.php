<?php
require_once '../headfooter/head.php';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<script>alert('Nuevo cliente agregado exitosamente');</script>";
    } elseif ($_GET['status'] == 'error') {
        echo "<script>alert('Hubo un error al agregar el cliente');</script>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Registro de Reparación</h2>
    <form action="../bd/repa.php" method="POST">

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>
            <select name="id_cliente" class="form-select" id="id_cliente" required>
                <option value="">Seleccione un cliente</option>
                <?php if ($resultc->num_rows > 0) { 
                    // Mostrar los clientes desde la base de datos
                    while ($row = $resultc->fetch_assoc()) {
                        echo "<option value='" . $row['id_cliente'] . "'>" . $row['nombre_cliente'] . " - " . $row['telefono_cliente'] ."</option>";
                    }
                } ?>
            </select>
            
            <button type="button" class="btn btn-link mt-2" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">Agregar Nuevo Cliente</button>
        </div>

        <!-- Tipo de Equipo -->
        <div class="mb-3">
            <label for="tipo_equipo" class="form-label">Tipo Equipo</label>
            <select name="tipo_equipo" class="form-select" id="id_cliente" required>
                <?php if ($resultte->num_rows > 0) { 
                    // Mostrar los equipos desde la base de datos
                    while ($row = $resultte->fetch_assoc()) {
                        echo "<option value='" . $row['nombre_tipo_equipo'] . "'>" . $row['nombre_tipo_equipo'] . "</option>";
                    }
                } ?>
            </select>
            <button type="button" class="btn btn-link mt-2" data-bs-toggle="modal" data-bs-target="#modalAgregarEquipo">Agregar Nuevo Equipo</button>
       </div>

        <!-- Sucursal -->
        <div class="mb-3">
            <label for="sucursal" class="form-label">Sucursal</label>
            <input type="text" name="sucursal" class="form-control" value="<?php echo $trabaja; ?>" readonly>
        </div>



        <!-- Problema del Equipo -->
        <div class="mb-3">
            <label for="problema_equipo" class="form-label">Problema del Equipo</label>
            <textarea name="problema_equipo" class="form-control" rows="3" placeholder="Descripción del problema (sin saltos de línea)" required></textarea>
        </div>

        <!-- Condiciones de Entrega -->
        <div class="mb-3">
            <label for="condiciones_entrega" class="form-label">Condiciones de Entrega</label>
            <textarea name="condiciones_entrega" class="form-control" rows="3" placeholder="Condiciones con las que se entrega el equipo (sin saltos de línea)" required></textarea>
        </div>

        <!-- Serie del producto -->
        <div class="mb-3">
                <label for="serie" class="form-label">Serie</label>
                <input type="text" name="serie" class="form-control" placeholder="Serie del Equipo" required pattern="[A-Za-z0-9]+">

        </div>

        <!-- Costo de la Reparación -->
        <div class="mb-3">
            <label for="costo" class="form-label">Costo</label>
            <input type="number" name="costo" class="form-control" step="0.01" min="0" placeholder="$">
        </div>

        <!-- Adelanto -->
        <div class="mb-3">
            <label for="adelanto" class="form-label">Adelanto</label>
            <input type="number" name="adelanto" class="form-control" step="0.01" min="0" placeholder="$" >
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Registrar Reparación</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>

        </div>
    </form>
</div>



<!-- Modal para agregar cliente -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../bd/agregar_cliente.php" method="POST">
                    <div class="mb-3">
                        <input type="hidden" name="form_source" value="nrep" />
                        <label for="nombre_cliente" class="form-label">Nombre de Cliente</label>
                        <input type="text" name="nombre_cliente" class="form-control" placeholder="Nombre del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_cliente" class="form-label">Teléfono del Cliente</label>
                        <input type="text" name="telefono_cliente" class="form-control" placeholder="Número telefónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_cliente" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email_cliente" class="form-control" placeholder="Correo electrónico">
                    </div>
                    <div class="mb-3">
                        <label for="direccion_cliente" class="form-label">Dirección del Cliente</label>
                        <input type="text" name="direccion_cliente" class="form-control" placeholder="Dirección" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar un nuevo tipo de equipo -->
<div class="modal fade" id="modalAgregarEquipo" tabindex="-1" aria-labelledby="modalAgregarEquipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-primary text-white p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarEquipoLabel">Agregar Nuevo Tipo de Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../bd/procesar_agregar_equipo.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_tipo_equipo" class="form-label">Nombre del Tipo de Equipo</label>
                        <input type="text" class="form-control form-control-lg" id="nombre_tipo_equipo" name="nombre_tipo_equipo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<br><br>

<script>
    document.querySelector("form").addEventListener("submit", function(event) {
        // Obtener los campos de texto
        const problemaEquipo = document.querySelector("textarea[name='problema_equipo']");
        const condicionesEntrega = document.querySelector("textarea[name='condiciones_entrega']");
        
        // Reemplazar saltos de línea por espacio en blanco
        problemaEquipo.value = problemaEquipo.value.replace(/\n/g, ' ');
        condicionesEntrega.value = condicionesEntrega.value.replace(/\n/g, ' ');
    });
</script>

<?php
require_once '../headfooter/footer.php';
?>