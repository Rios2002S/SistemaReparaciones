        <!-- Sección de gestión de usuarios -->
        <div class="tab-pane fade show active" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab"><br>
                    <h1 class="text-center mb-4">Gestión de Usuarios</h1>
                    
                    <!-- Formulario de registro de usuario -->
                    <form action="../bd/adduser.php" method="POST" id="register">
                        <h2 class="text-center">Agregar Usuario</h2>
                        <div class="mb-3">
                            <label for="nombreusu" class="form-label">Nombre de usuario</label>
                            <input type="text" name="nombreusu" id="nombreusu" class="form-control" placeholder="Nombre de usuario" required>
                        </div>

                        <!-- Agregar campo de sucursal asignada -->
                        <!-- Sucursal -->
                        <div class="mb-3">
                            <label for="sucursal_asignada" class="form-label">Sucursal</label>
                            <select name="sucursal_asignada" class="form-select" id="sucursal_asignada" required>
                                <option value="">Seleccione la sucursal que tendra a cargo</option>
                                <?php if ($results->num_rows > 0) { 
                                    // Mostrar las sucursales desde la base de datos
                                    while ($row = $results->fetch_assoc()) {
                                        echo "<option value='" . $row['nombre'] . "'>" . $row['nombre'] . "</option>";
                                    }
                                } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>

                        <div class="mb-3">
                            <label for="clave_admin" class="form-label">Clave de administrador</label>
                            <input type="password" name="clave_admin" id="clave_admin" class="form-control" placeholder="Clave de administrador" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                            <button type="reset" class="btn btn-secondary">Limpiar</button>
                        </div>
                    </form>

                    <!-- Usuarios registrados -->
                    <h2 class="text-center my-5">Usuarios Registrados</h2>
                    <div class="row">
                        <?php
                        $sql4 = "SELECT id_usuario, nombreusu, es_admin, sucursal_asignada FROM usuarios";
                        $result4 = $conn->query($sql4);

                        if ($result4->num_rows > 0) {
                            while ($row = $result4->fetch_assoc()) {
                                $rol = ($row['es_admin'] == 1) ? "Administrador" : "Usuario";
                                echo "
                                    <div class='col-md-4 mb-4'>
                                        <div class='card'>
                                            <div class='card-body'>
                                                <h5 class='card-title'>" . htmlspecialchars($row['nombreusu']) . "</h5>
                                                <p class='card-text'><strong>Rol:</strong> $rol</p>
                                                <p class='card-text'><strong>Sucursal: </strong>" . htmlspecialchars($row['sucursal_asignada']) . "</p>
                                                <div class='d-flex justify-content-between'>
                                                    <!-- Botón para eliminar -->
                                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='" . $row['id_usuario'] . "'>Eliminar</button>
                                                    <!-- Botón para actualizar -->
                                                    <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#updateModal' data-id='" . $row['id_usuario'] . "' data-nombre='" . htmlspecialchars($row['nombreusu']) . "' data-rol='" . $row['es_admin'] . "' data-sucursalAsig='" . htmlspecialchars($row['sucursal_asignada']) . "'>Actualizar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            }
                        } else {
                            echo "<p class='text-center'>No hay usuarios registrados</p>";
                        }
                        ?>
                    </div>

                    <!-- Modal para eliminar usuario -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="../bd/deleteuser.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                                        <input type="hidden" name="id_usuario" id="delete-id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para actualizar usuario -->
                    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="../bd/updateuser.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel">Actualizar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_usuario" id="update-id">
                                        <div class="mb-3">
                                            <label for="update-nombre" class="form-label">Nombre de usuario</label>
                                            <input type="text" name="nombreusu" id="update-nombre" class="form-control" required>
                                        </div> 
                                        <!-- Sucursal -->
                                    <div class="mb-3">
                                        <label for="update-sucursal_asignada" class="form-label">Sucursal</label>
                                        <select name="sucursal_asignada" id="update-sucursal_asignada" class="form-select" required>
                                            <?php
                                            // Verifica si $row está definido y tiene el índice 'sucursal_asignada'
                                            if (isset($row['sucursal_asignada'])) {
                                                echo "<option value='" . htmlspecialchars($row['sucursal_asignada']) . "' selected>" . htmlspecialchars($row['sucursal_asignada']) . "</option>";
                                            } else {
                                                echo "<option value=''>Sucursal no asignada</option>"; // Opción por defecto si no hay sucursal
                                            }
                                            ?>
                                            <?php
                                            // Verifica si $results tiene filas
                                            if ($results->num_rows > 0) {
                                                $results->data_seek(0);  // Resetea el puntero al inicio del resultado
                                                while ($row_sucursal = $results->fetch_assoc()) {
                                                    echo "<option value='" . htmlspecialchars($row_sucursal['nombre']) . "'>" . htmlspecialchars($row_sucursal['nombre']) . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No hay sucursales disponibles</option>"; // Opción por defecto si no hay sucursales
                                            }
                                            ?>
                                        </select>
                                    </div>
                                        <div class="mb-3">
                                            <label for="update-rol" class="form-label">Rol</label>
                                            <select name="es_admin" id="update-rol" class="form-select">
                                                <option value="1">Administrador</option>
                                                <option value="0">Usuario</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        



                <script>
                    //Eliminar
                        const deleteModal = document.getElementById('deleteModal');
                        deleteModal.addEventListener('show.bs.modal', event => {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        document.getElementById('delete-id').value = id;
                        });
                            
                        //Actualizar
                        const updateModal = document.getElementById('updateModal');
                        updateModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        const nombre = button.getAttribute('data-nombre');
                        const rol = button.getAttribute('data-rol');
                        const sucursal = button.getAttribute('data-sucursalAsig');

                        document.getElementById('update-id').value = id;
                        document.getElementById('update-nombre').value = nombre;
                        document.getElementById('update-rol').value = rol;
                        document.getElementById('update-sucursal_asignada').value = sucursal;  // Asegúrate de que este ID coincida
                        });
                </script>
                <script>
                    function openDeleteModal(id) {
                        document.getElementById('delete-id').value = id; // Asigna el ID al campo oculto
                        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    }
                </script>
        </div>

        <!-- Sección de gestión de sucursales -->
        <div class="tab-pane fade" id="sucursales" role="tabpanel" aria-labelledby="sucursales-tab"><br>
                <h1 class="text-center mb-4">Gestión de Sucursales</h1>

                <!-- Formulario para agregar sucursales -->
                <form action="../bd/addbranch.php" method="POST" class="mb-5">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre de la sucursal" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success w-100">Añadir</button>
                        </div>
                    </div>
                </form>

                <!-- Tabla de sucursales -->
                <h2 class="text-center mb-4">Sucursales Registradas</h2>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM sucursales";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id_sucursal']); ?></td>
                                    <td><?= htmlspecialchars($row['nombre']); ?></td>
                                    <td><?= htmlspecialchars($row['ubicacion']); ?></td>
                                    <td><?= htmlspecialchars($row['telefono']); ?></td>
                                    <td>
                                        <!-- Botón para eliminar con confirmación -->
                                        <form action="../bd/deletebranch.php" method="POST" class="d-inline" id="formEliminar">
                                            <input type="hidden" name="id_sucursal" value="<?= htmlspecialchars($row['id_sucursal']); ?>">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion(this)">Eliminar</button>
                                        </form>

                                        <script>
                                            // Función para confirmar la eliminación
                                            function confirmarEliminacion(button) {
                                                // Preguntar si el usuario está seguro de eliminar
                                                var confirmar = confirm("¿Estás seguro de eliminar esta sucursal?");
                                                if (confirmar) {
                                                    // Si el usuario confirma, se envía el formulario
                                                    button.closest("form").submit();
                                                }
                                            }
                                        </script>

                                        <!-- Botón para abrir modal de actualización -->
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalActualizarSucursal" 
                                                data-id="<?= htmlspecialchars($row['id_sucursal']); ?>"
                                                data-nombre="<?= htmlspecialchars($row['nombre']); ?>"
                                                data-ubicacion="<?= htmlspecialchars($row['ubicacion']); ?>"
                                                data-telefono="<?= htmlspecialchars($row['telefono']); ?>">
                                            Actualizar
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No hay sucursales registradas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Modal para actualizar sucursales -->
                <div class="modal fade" id="modalActualizarSucursal" tabindex="-1" aria-labelledby="modalActualizarSucursalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="../bd/updatebranch.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalActualizarSucursalLabel">Actualizar Sucursal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_sucursal" id="update_id_sucursal">
                                    <div class="mb-3">
                                        <label for="update_nombre" class="form-label">Nombre</label>
                                        <input type="text" name="nombre" id="update_nombre" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_ubicacion" class="form-label">Ubicación</label>
                                        <input type="text" name="ubicacion" id="update_ubicacion" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="update_telefono" class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" id="update_telefono" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>

        <script>
            // Pasar datos al modal de actualización
            document.querySelectorAll('[data-bs-target="#modalActualizarSucursal"]').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('update_id_sucursal').value = this.getAttribute('data-id');
                    document.getElementById('update_nombre').value = this.getAttribute('data-nombre');
                    document.getElementById('update_ubicacion').value = this.getAttribute('data-ubicacion');
                    document.getElementById('update_telefono').value = this.getAttribute('data-telefono');
                });
            });
        </script> 