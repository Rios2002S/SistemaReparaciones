<?php
require_once '../headfooter/head.php';
?>
<div class="mx-5">
    <h1 class="text-center mb-4">Reparaciones Finalizadas</h1>

    <?php
    // Consulta dependiendo de si es admin o no
    if ($es_admin) {
        $sql2 = "SELECT rf.id_rep, 
                         c.nombre_cliente,
                         c.telefono_cliente,
                         rf.fecha, 
                         rf.tipo_equipo, 
                         rf.problema_equipo, 
                         rf.condiciones_entrega, 
                         rf.recibe_usuario, 
                         rf.sucursal, 
                         rf.costo, 
                         rf.estado,
                         rf.serie,
                         rf.adelanto,
                         rf.saldo_pendiente,
                         rf.codigo_ticket,
                         rf.diagnostico
                  FROM reparaciones_finalizadas rf
                  INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                  WHERE rf.estado = 0
                  ORDER BY rf.id_finalizada DESC";
    } else {
        $sql2 = "SELECT rf.id_rep, 
                         c.nombre_cliente,
                         c.telefono_cliente,
                         rf.fecha, 
                         rf.tipo_equipo, 
                         rf.problema_equipo, 
                         rf.condiciones_entrega, 
                         rf.recibe_usuario, 
                         rf.sucursal, 
                         rf.costo, 
                         rf.estado,
                         rf.serie,
                         rf.adelanto,
                         rf.saldo_pendiente,
                         rf.codigo_ticket,
                         rf.diagnostico
                  FROM reparaciones_finalizadas rf
                  INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                  WHERE rf.estado = 0 AND rf.sucursal = '$trabaja'
                  ORDER BY rf.id_finalizada DESC";
    }

    // Ejecutar la consulta
    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0):
    ?>

<div class="table-responsive">
        <table id="reparacionesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Equipo</th>
                    <th>Problema</th>
                    <th>Condiciones</th>
                    <th>Diagnostico</th>
                    <th>Recibió</th>
                    <th>Sucursal</th>
                    <th>Costo</th>
                    <th>Abonó</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result2->fetch_assoc()): ?>                            
                <?php
                                $cadena = $row['codigo_ticket'];  
                                $prefijo = substr($cadena, 0, 3);  
                                $numeros = substr($cadena, -3);   
                                $solucion = $prefijo . '-' . $numeros;  
                            ?>
                <tr>
                    <td><?= htmlspecialchars($solucion);?></td>
                    <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['telefono_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_equipo']) ?></td>
                    <td><?= htmlspecialchars($row['problema_equipo']) ?></td>
                    <td><?= htmlspecialchars($row['condiciones_entrega']) ?></td>
                    <td><?= htmlspecialchars($row['diagnostico']) ?></td>
                    <td><?= htmlspecialchars($row['recibe_usuario']) ?></td>
                    <td><?= htmlspecialchars($row['sucursal']) ?></td>
                    <td>$<?= number_format($row['costo'], 2) ?></td>
                    <td>$<?= number_format($row['adelanto'], 2) ?></td>
                    <td>$<?= number_format($row['saldo_pendiente'], 2) ?></td>
                    <td><span class="text-danger">No entregado</span></td>
                    <td>
                        <!-- Acciones -->
                        <button class="btn btn-info btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $row['id_rep']; ?>" data-bs-toggle="tooltip" title="Editar Diagnóstico y Costo"><i class="bi bi-pencil-square"></i></button>
                        
                        <!-- Condición para habilitar/deshabilitar el botón -->
                        <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalEntregado<?= $row['id_rep']; ?>" data-bs-toggle="tooltip" title="Marcar como Entregado" <?= $row['costo'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="bi bi-check-circle"></i>
                        </button>
                        
                        <button class="btn btn-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalRevertir<?= $row['id_rep']; ?>" data-bs-toggle="tooltip" title="Revertir (reparacion incompleta)"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </td>
                </tr>
                    <!-- Modal de Confirmación para Marcar como Entregado -->
                    <div class="modal fade" id="modalEntregado<?= $row['id_rep']; ?>" tabindex="-1" aria-labelledby="modalEntregadoLabel<?= $row['id_rep']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEntregadoLabel<?= $row['id_rep']; ?>">Confirmar Entregado</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas marcar esta reparación como entregada?</p>
                                    <!-- Formulario para actualizar estado a "Entregado" -->
                                    <form action="../bd/actualizar_estado.php" method="POST">                                    
                                        <!-- Mostrar Total, Pago recibido y Cambio -->
                                        <div class="mb-3">
                                            <label for="pago" class="form-label">Pago recibido</label>
                                            <input type="number" 
                                                step="0.01" 
                                                min='<?= number_format($row['costo'] - $row['adelanto'], 2, '.', ''); ?>' 
                                                class="form-control" 
                                                id="pago<?= $row['id_rep']; ?>" 
                                                placeholder="Ingrese el monto recibido" 
                                                required
                                                oninvalid="this.setCustomValidity('El monto recibido debe ser al menos $<?= number_format($row['costo'] - $row['adelanto'], 2); ?>')" 
                                                oninput="this.setCustomValidity(''); calcularYValidar(<?= $row['id_rep']; ?>)">
                                        </div>

                                        <div>
                                            <p>Total: <span id="totalAmount<?= $row['id_rep']; ?>">$<?= number_format($row['costo'] - $row['adelanto'], 2); ?></span></p>
                                            <p>Cambio: <span id="cambioAmount<?= $row['id_rep']; ?>">$0.00</span></p>
                                        </div>
                                        <input type="hidden" name="id_finalizada" value="<?= htmlspecialchars($row['id_rep']); ?>">
                                        <input type="hidden" id="totalFinal<?= $row['id_rep']; ?>" name="total_final" value="<?= $row['costo'] + $row['adelanto']; ?>"> <!-- Total final para enviar -->
                                        <input type="hidden" id="cambioFinal<?= $row['id_rep']; ?>" name="cambio" value="0"> <!-- Cambio para enviar -->

                                            <!-- Botón de Confirmar -->
                                            <button type="submit" class="btn btn-warning" id="confirmarBtn<?= $row['id_rep']; ?>" onclick="imprimirPDF2(
                                                <?= $row['id_rep']; ?>,
                                                '<?= $row['fecha']; ?>',
                                                '<?= htmlspecialchars($row['nombre_cliente']); ?>',
                                                '<?= htmlspecialchars($row['telefono_cliente']); ?>',
                                                '<?= htmlspecialchars($row['tipo_equipo']); ?>',
                                                '<?= htmlspecialchars($row['serie']); ?>',
                                                '<?= htmlspecialchars($row['problema_equipo']); ?>',
                                                '<?= htmlspecialchars($row['diagnostico']); ?>',
                                                '<?= htmlspecialchars($row['condiciones_entrega']); ?>',
                                                '<?= htmlspecialchars($row['sucursal']); ?>',
                                                '<?= htmlspecialchars($row['recibe_usuario']); ?>',
                                                <?= $row['costo']; ?>,
                                                <?= $row['adelanto']; ?>,
                                                <?= $row['saldo_pendiente']; ?>,
                                                '<?= htmlspecialchars($row['codigo_ticket']); ?>',
                                                '<?= htmlspecialchars($solucion); ?>',
                                                document.getElementById('pago<?= $row['id_rep']; ?>').value)" hidden>Confirmar</button>

                                                <!-- Botón de Guardar (para subir el archivo) -->
                                                <button type="button" class="btn btn-success" id="guardarBtn<?= $row['id_rep']; ?>" onclick="generatePdfAndUploadToDrive(
                                                <?= $row['id_rep']; ?>,
                                                '<?= $row['fecha']; ?>',
                                                '<?= htmlspecialchars($row['nombre_cliente']); ?>',
                                                '<?= htmlspecialchars($row['telefono_cliente']); ?>',
                                                '<?= htmlspecialchars($row['tipo_equipo']); ?>',
                                                '<?= htmlspecialchars($row['serie']); ?>',
                                                '<?= htmlspecialchars($row['problema_equipo']); ?>',
                                                '<?= htmlspecialchars($row['diagnostico']); ?>',
                                                '<?= htmlspecialchars($row['condiciones_entrega']); ?>',
                                                '<?= htmlspecialchars($row['sucursal']); ?>',
                                                '<?= htmlspecialchars($row['recibe_usuario']); ?>',
                                                <?= $row['costo']; ?>,
                                                <?= $row['adelanto']; ?>,
                                                <?= $row['saldo_pendiente']; ?>,
                                                '<?= htmlspecialchars($row['codigo_ticket']); ?>',
                                                '<?= htmlspecialchars($solucion); ?>',
                                                document.getElementById('pago<?= $row['id_rep']; ?>').value)" disabled>Guardar</button>




                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Función para calcular el cambio y habilitar/deshabilitar el botón de confirmar
                        function calcularYValidar(idRep) {
                            const pagoInput = document.getElementById('pago' + idRep);
                            const pago = parseFloat(pagoInput.value);
                            const minAmount = parseFloat(pagoInput.min);
                            const total = parseFloat(document.getElementById('totalAmount' + idRep).textContent.replace('$', ''));

                            // Validar si el pago es mayor o igual al mínimo
                            if (pago >= minAmount) {
                                // Calcular el cambio
                                const cambio = pago - total;
                                document.getElementById('cambioAmount' + idRep).textContent = "$" + (cambio > 0 ? cambio.toFixed(2) : "0.00");
                                document.getElementById('totalAmount' + idRep).textContent = "$" + total.toFixed(2);

                                // Actualizar los campos ocultos con los valores correspondientes
                                document.getElementById('totalFinal' + idRep).value = total;
                                document.getElementById('cambioFinal' + idRep).value = cambio > 0 ? cambio.toFixed(2) : 0;

                                // Habilitar el botón de guardar
                                document.getElementById('guardarBtn' + idRep).disabled = false;

                                // Deshabilitar el botón de "Confirmar" si el archivo no se ha subido aún
                                if (!document.getElementById('confirmarBtn' + idRep).hidden) {
                                    document.getElementById('confirmarBtn' + idRep).hidden = true;
                                }
                            } else {
                                // Si no es válido, deshabilitar el botón de guardar y confirmar
                                document.getElementById('guardarBtn' + idRep).disabled = true;
                                document.getElementById('confirmarBtn' + idRep).hidden = true;
                            }
                        }

                        // Prevenir que el formulario se envíe cuando presionas Enter
                        document.querySelectorAll('.form-control').forEach(input => {
                            input.addEventListener('keydown', function(event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault(); // Evita que el formulario se envíe
                                }
                            });
                        });
                    </script>

                    <!-- Modal para Editar Diagnóstico y Costo -->
                    <div class="modal fade" id="modalEditar<?= $row['id_rep']; ?>" tabindex="-1" aria-labelledby="modalEditarLabel<?= $row['id_rep']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarLabel<?= $row['id_rep']; ?>">Editar Diagnóstico y Costo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../bd/editar_diagnostico_costo.php" method="POST">
                                        <input type="hidden" name="id_rep" value="<?= htmlspecialchars($row['id_rep']); ?>">
                                        
                                        <!-- Diagnóstico -->
                                        <div class="mb-3">
                                            <label for="diagnostico" class="form-label">Diagnóstico</label>
                                            <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" required><?= htmlspecialchars($row['diagnostico']); ?></textarea>
                                        </div>

                                        <!-- Costo -->
                                        <div class="mb-3">
                                            <label for="costo" class="form-label">Costo</label>
                                            <input type="number" min="0.00" class="form-control" id="costo" name="costo" value="<?= htmlspecialchars($row['costo']); ?>" step="0.01" required>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                                            <!-- Modal para revertir -->
                                            <div class="modal fade" id="modalRevertir<?= $row['id_rep']; ?>" tabindex="-1" aria-labelledby="modalRevertirLabel<?= $row['id_rep']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalRevertirLabel<?= $row['id_rep']; ?>">Revertir Reparación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de que deseas revertir esta reparación?</p>
                                                            <form action="../bd/revertir_reparacion.php" method="POST">
                                                                <input type="hidden" name="id_rep" value="<?= htmlspecialchars($row['id_rep']); ?>">
                                                                <div class="d-flex justify-content-between">
                                                                    <button type="submit" class="btn btn-danger">Revertir</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            No hay reparaciones finalizadas.
        </div>
    <?php endif; ?>
</div>

<!-- Scripts DataTables -->
<script>
    $(document).ready(function() {
        $('#reparacionesTable').DataTable({
            "searching": true, // Habilita la búsqueda
            "paging": true, // Paginación
            "info": true, // Muestra información sobre la cantidad de registros
            "lengthChange": false, // Desactiva la opción de cambiar la cantidad de registros mostrados
            "language": {
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ total registros)"
            }
        });
    });
</script>

<script>
        // Cliente de Google
        const CLIENT_ID = "152128458064-nh8k36rob16m9t5cqngvj7n0v91f09n9.apps.googleusercontent.com"; // Reemplaza con tu Client ID
        const SCOPES = "https://www.googleapis.com/auth/drive.file";
        let tokenClient;

        window.onload = () => {
            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: (response) => {
                    if (response.error) {
                        console.error("Error de autorización:", response.error);
                    } else {
                        console.log("Token recibido:", response.access_token);
                    }
                },
            });
        };

        // Solicitar el token de acceso
        function requestTokenAndUpload() {
            tokenClient.requestAccessToken();
        }

        function generatePdfAndUploadToDrive(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, diagnostico, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion, pagoRecibido) {
            // Aseguramos que el valor de pagoRecibido esté correctamente asignado
            pagoRecibido = parseFloat(pagoRecibido) || 0; // Si no es válido, se asigna 0 por defecto

            const total = costo - adelanto;
            const cambio = pagoRecibido - total;

            tokenClient.requestAccessToken();
            tokenClient.callback = async (response) => {
                if (response.error) {
                    console.error("Error de autorización:", response.error);
                    return;
                }

                const token = response.access_token;

                // Generar el PDF con los datos recibidos
                const pdfBlob = generatePdf(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, diagnostico, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion, pagoRecibido, total, cambio);

                // Subir el PDF a Google Drive
            await uploadToGoogleDrive(pdfBlob, token, idRep, nombreCliente);    
            };
        }

        function generatePdf(idRep, fecha, nombreCliente, telefonoCliente, tipoEquipo, serie, problemaEquipo, diagnostico, condicionesEntrega, sucursal, recibeUsuario, costo, adelanto, saldoPendiente, codigoTicket, solucion, pagoRecibido, total, cambio) {
            const { jsPDF } = window.jspdf;        
                    const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "mm",
                    format: [76, 230], // Ancho 76mm, altura ajustable (tamaño adecuado para tickets térmicos)
                });

            let yPosition = -10;

            // Agregar el logo centrado
            let logoWidth = 70;  
            let logoHeight = 70; 
            doc.addImage('https://i.ibb.co/kgNwmJs/logo2.png', 'PNG', (76 - logoWidth) / 2, yPosition, logoWidth, logoHeight);
            yPosition += logoHeight + -10;

            // Título
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text("COMPROBANTE DE ENTREGA", 5, yPosition); // Nueva línea agregada
            yPosition += 2;

            // Línea de separación después de "Comprobante de Entrega"
            doc.setLineWidth(0.5);
            doc.setDrawColor(0, 0, 0);
            doc.line(5, yPosition, 71, yPosition);
            yPosition += 5;

            // Título principal
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text("GRUPO MULTICOMP", 76 / 2, yPosition, { align: "center" });
            yPosition += 5;
            doc.text("(Taller Profesional)", 76 / 2, yPosition, { align: "center" });
            yPosition += 5;
            doc.text("(Tels: 7727-9900 / 24066984)", 76 / 2, yPosition, { align: "center" });
            yPosition += 3;

            // Línea de separación después de "GRUPO MULTICOMP"
            doc.setLineWidth(0.5);
            doc.setDrawColor(0, 0, 0);
            doc.line(5, yPosition, 71, yPosition);
            yPosition += 5;


            // Función para dividir texto en líneas
            function dividirTexto(texto, maxWidth) {
                return doc.splitTextToSize(texto, maxWidth);
            }

            // Detalles principales
            const detalles = [
                { label: "Orden:", value: solucion },
                { label: "Ticket:", value: codigoTicket },
                { label: "Fecha:", value: fecha },
                { label: "Cliente:", value: nombreCliente },
                { label: "Teléfono:", value: telefonoCliente },
                { label: "Equipo:", value: tipoEquipo },
                { label: "Serie:", value: serie },
                { label: "Sucursal:", value: sucursal },
                { label: "Recibe:", value: recibeUsuario },
            ];

            doc.setFontSize(10);
            doc.setFont('helvetica', 'bold');

            // Procesar cada detalle y manejar saltos de línea
            detalles.forEach((item) => {
                const texto = `${item.label} ${item.value}`;
                const lineas = dividirTexto(texto, 66); // Ancho máximo ajustado a 66mm
                lineas.forEach((linea) => {
                    doc.text(linea, 5, yPosition);
                    yPosition += 4; // Espaciado entre líneas
                });
                yPosition += 2; // Espaciado extra entre secciones
            });


            // Separador entre detalles y Problema/Condiciones
            yPosition += -2;
            doc.setLineWidth(0.5);
            doc.line(5, yPosition, 71, yPosition);
            yPosition += 5;

            // Problema
            if (problemaEquipo.length > 0) {
                const textoProblema = `Problema: ${problemaEquipo}`;
                const lineasProblema = dividirTexto(textoProblema, 66);
                lineasProblema.forEach((linea) => {
                    doc.text(linea, 5, yPosition);
                    yPosition += 5;
                });
                yPosition += 3; // Espaciado adicional después del problema
            }

            // Diagnostico
            if (diagnostico.length > 0) {
                const textoDiagnostico = `Diagnostico: ${diagnostico}`;
                const lineasDiagnostico = dividirTexto(textoDiagnostico, 66);
                lineasDiagnostico.forEach((linea) => {
                    doc.text(linea, 5, yPosition);
                    yPosition += 5;
                });
                yPosition += 3; // Espaciado adicional después de las condiciones
            }

            // Condiciones
            if (condicionesEntrega.length > 0) {
                const textoCondiciones = `Condiciones: ${condicionesEntrega}`;
                const lineasCondiciones = dividirTexto(textoCondiciones, 66);
                lineasCondiciones.forEach((linea) => {
                    doc.text(linea, 5, yPosition);
                    yPosition += 5;
                });
                yPosition += 5; // Espaciado adicional después de las condiciones
            }

            // Línea de separación antes de la sección de costos
            yPosition += -6; // Espaciado para la línea de separación
            doc.setLineWidth(0.5);
            doc.line(5, yPosition, 71, yPosition);
            yPosition += 5;

            // Sección de Costos: Costo Total, Adelanto, Saldo Pendiente
            const costos = [
                { label: "Costo Total:", value: `$${costo.toFixed(2)}` },
                { label: "Adelanto:", value: `$${adelanto.toFixed(2)}` },
                { label: "Saldo Pendiente:", value: `$${saldoPendiente.toFixed(2)}` },
                { label: "Efectivo:", value: `$${pagoRecibido.toFixed(2)}` },
                { label: "Cambio:", value: `$${cambio.toFixed(2)}` },
            ];

            doc.setFontSize(10);
            doc.setFont('helvetica', 'bold'); // Para destacar la sección de costos

            costos.forEach((item) => {
                doc.text(`${item.label}`, 5, yPosition); // Etiqueta a la izquierda
                doc.text(`${item.value}`, 38, yPosition); // Valor a la derecha
                yPosition += 5;
            });

            // Línea final de separación
            yPosition += -1;
            doc.setLineWidth(0.5);
            doc.setDrawColor(0, 0, 0);
            doc.line(5, yPosition, 71, yPosition);
            yPosition += 5;

            // Mensaje de agradecimiento
            doc.setFontSize(9);
            doc.setFont('helvetica', 'bold');
            doc.text("Gracias por confiar en nuestro servicio.", 5, yPosition);
            yPosition += 6;

            // Mensaje de responsabilidad
            const nota = "Nota: NO NOS HACEMOS RESPONSABLES POR TRABAJOS NO RETIRADOS DESPUES DE 30 DIAS.";
            const lineasNota = dividirTexto(nota, 66);
            lineasNota.forEach((linea) => {
                doc.text(linea, 5, yPosition);
                yPosition += 4; // Espaciado entre líneas
            });

            return doc.output("blob"); // Guardamos el PDF como un blob
        }


        // Función para subir el archivo PDF a Google Drive
        async function uploadToGoogleDrive(pdfBlob, token, idRep, nombreCliente) {
            const metadata = {
                name: `ticket_${idRep}_${nombreCliente}.pdf`, // Nombre dinámico del archivo
                mimeType: "application/pdf", // Tipo MIME
            };

            const formData = new FormData();
            formData.append("metadata", new Blob([JSON.stringify(metadata)], { type: "application/json" }));
            formData.append("file", pdfBlob);

            try {
                const response = await fetch("https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart", {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                    body: formData,
                });

                if (response.ok) {
                    const jsonResponse = await response.json();
                    console.log("Archivo subido correctamente:", jsonResponse);

                    // Obtener el ID del archivo subido
                    const fileId = jsonResponse.id;
                    const fileLink = `https://drive.google.com/file/d/${fileId}/view?usp=sharing`;

                    // Enviar el enlace al servidor para actualizar la base de datos
                    await actualizarEnlaceEnBaseDeDatos(idRep, fileLink);

                    // Habilitar el botón "Confirmar" después de subir el archivo
                    document.getElementById('confirmarBtn' + idRep).hidden = false;
                    document.getElementById('guardarBtn' + idRep).hidden = true;
                } else {
                    console.error("Error al subir el archivo:", response.statusText);
                }
            } catch (error) {
                console.error("Error al subir el archivo:", error);
            }
        }

        // Función para actualizar el enlace en la base de datos
        async function actualizarEnlaceEnBaseDeDatos(idRep, fileLink) {
            try {
                const response = await fetch('../bd/actualizar_enlace.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        idRep: idRep,
                        fileLink: fileLink,
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Enlace actualizado en la base de datos:', data);
                } else {
                    console.error('Error al actualizar el enlace en la base de datos');
                }
            } catch (error) {
                console.error('Error al enviar el enlace al servidor:', error);
            }
        }

</script>

<script>
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevenir el salto de línea
                alert('No puedes presionar Enter para saltar líneas en este campo.');
            }
        });
    });
</script>  
<?php
require_once '../headfooter/footer.php';
?>
