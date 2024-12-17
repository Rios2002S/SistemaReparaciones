<?php
require_once '../headfooter/head.php';

// Ejecutar la consulta para obtener los clientes
$sqlc = "SELECT id_cliente, nombre_cliente, telefono_cliente, direccion_cliente FROM clientes";
$resultc = $conn->query($sqlc);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Nuestros Clientes</h1>
        <!-- Barra de búsqueda -->
        <div class="mb-4">
            <input type="text" id="barraClientes" class="form-control" placeholder="Buscar Cliente...">
        </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // Verificar si hay resultados
        if ($resultc->num_rows > 0) {
            // Iterar a través de los resultados y mostrar las tarjetas
            while ($cliente = $resultc->fetch_assoc()) {
        ?>
                <div class="col cliente-item" data-nombre="<?= htmlspecialchars(strtolower($cliente['nombre_cliente'])) ?>">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></h5>
                            <p class="card-text"><strong>Telefono:</strong> <?php echo htmlspecialchars($cliente['telefono_cliente']); ?></p>
                            <p class="card-text"><strong>Dirección:</strong> <?php echo htmlspecialchars($cliente['direccion_cliente']); ?></p>
                            <!-- Botón de editar que abre el modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $cliente['id_cliente']; ?>">
                                Editar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar el cliente -->
                <div class="modal fade" id="editModal_<?php echo $cliente['id_cliente']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Editar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="../bd/editar_cliente.php" method="POST">
                                    <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                                    <div class="mb-3">
                                        <label for="nombre_cliente" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="<?php echo htmlspecialchars($cliente['nombre_cliente']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono_cliente" class="form-label">Telefono</label>
                                        <input type="text" class="form-control" id="telefono_cliente" name="telefono_cliente" value="<?php echo htmlspecialchars($cliente['telefono_cliente']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccion_cliente" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion_cliente" name="direccion_cliente" value="<?php echo htmlspecialchars($cliente['direccion_cliente']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
            }
        } else {
            echo '<p>No hay clientes disponibles.</p>';
        }
        ?>
    </div> <!-- Cierre del row -->
</div> <!-- Cierre del container -->

<?php
require_once '../headfooter/footer.php'; 
?>
