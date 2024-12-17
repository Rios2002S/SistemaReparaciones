<?php
require_once '../headfooter/head.php';
?>
<div class="mx-5">
    <h1 class="text-center mb-4">Reportes de Reparaciones Pendientes de Entrega</h1>

    <div class="mb-3 text-end">
        <button class="home-btn" onclick="window.history.back()">
            <i class="fas fa-home home-icon"></i>
        </button>
        <button id="generarPDF7" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Imprimir Todo en PDF
        </button>
    </div>

    <div class="table-responsive">
        <?php
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
                            rf.diagnostico,
                            rf.enlace_drive
                    FROM reparaciones_finalizadas rf
                    INNER JOIN clientes c ON rf.id_cliente = c.id_cliente
                    WHERE rf.estado = 0
                    ORDER BY rf.id_finalizada DESC";
        } 
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0):
        ?>
        <table id="reparacionesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Equipo</th>
                    <th>Serie</th>
                    <th>Problema</th>
                    <th>Condiciones</th>
                    <th>Diagnóstico</th>
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
                <tr>
                    <td><?= $row['codigo_ticket'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['telefono_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_equipo']) ?></td>
                    <td><?= htmlspecialchars($row['serie']) ?></td>
                    <td><?= htmlspecialchars($row['problema_equipo']) ?></td>
                    <td><?= htmlspecialchars($row['condiciones_entrega']) ?></td>
                    <td><?= htmlspecialchars($row['diagnostico']) ?></td>
                    <td><?= htmlspecialchars($row['recibe_usuario']) ?></td>
                    <td><?= htmlspecialchars($row['sucursal']) ?></td>
                    <td>$<?= number_format($row['costo'], 2) ?></td>
                    <td>$<?= number_format($row['adelanto'], 2) ?></td>
                    <td>$<?= number_format($row['saldo_pendiente'], 2) ?></td>
                    <td>
                        <span class="text-success" data-bs-toggle="tooltip" title="Reparado">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    </td>
                    <td>
                    <button class="btn btn-success btn-sm mb-2" 
                        onclick="imprimirPDF8(
                            '<?= htmlspecialchars($row['nombre_cliente']); ?>', 
                            '<?= htmlspecialchars($row['telefono_cliente']); ?>', 
                            '<?= htmlspecialchars($row['tipo_equipo']); ?>', 
                            '<?= htmlspecialchars($row['serie']); ?>', 
                            '<?= htmlspecialchars($row['problema_equipo']); ?>'
                        )" 
                        data-bs-toggle="tooltip" 
                        title="Generar PDF">
                    <i class="fas fa-print"></i>
                </button>


                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            No hay reparaciones entregadas.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<!-- Estilos DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
    document.getElementById('generarPDF7').addEventListener('click', async function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        const margin = 10;
        const startX = margin;
        const startY = margin;
        const tableWidth = 280; 
        const lineHeight = 7; 

        // Agregar título y logo
        doc.setFontSize(22);
        doc.setTextColor(40, 60, 100);
        doc.text('Reporte de Reparaciones Entregadas', startX, startY + 10);

        const logoImg = new Image();
        logoImg.src = 'https://i.ibb.co/kgNwmJs/logo2.png'; 
        logoImg.onload = function () {
            doc.addImage(logoImg, 'PNG', 245, startY - 2, 40, 40);

            // Encabezado de la tabla
            doc.setFontSize(12);
            doc.setFillColor(40, 60, 100); 
            doc.rect(startX, startY + 35, tableWidth, 10, 'F'); 
            doc.setTextColor(255, 255, 255); 
            doc.setFont('helvetica', 'bold');
            
            doc.text('Clave', startX + 5, startY + 42);
            doc.text('Cliente', startX + 50, startY + 42);
            doc.text('Teléfono', startX + 100, startY + 42);
            doc.text('Equipo', startX + 125, startY + 42);
            doc.text('Problema', startX + 165, startY + 42);
            doc.text('Serie', startX + 240, startY + 42);

            // Obtener datos de la tabla
            const filas = document.querySelectorAll('#reparacionesTable tbody tr');
            let y = startY + 45; 

            filas.forEach((fila) => {
                const columnas = fila.querySelectorAll('td');

                // Verificar si se necesita nueva página
                if (y > 175) {
                    doc.addPage();
                    y = startY + 30; 
                    doc.text('Reporte de Reparaciones Entregadas', startX, startY + 10);
                    doc.addImage(logoImg, 'PNG', 245, startY - 2, 40, 40);
                    doc.setFillColor(40, 60, 100); 
                    doc.rect(startX, y, tableWidth, 10, 'F'); 
                    doc.setTextColor(255, 255, 255); 
                    doc.text('Orden', startX + 5, y + 7);
                    doc.text('Cliente', startX + 50, y + 7);
                    doc.text('Teléfono', startX + 100, y + 7);
                    doc.text('Equipo', startX + 150, y + 7);
                    doc.text('Problema', startX + 200, y + 7);
                    y += 15; 
                }


                // Formatear la "Orden"
                const ordenTexto = columnas[0].innerText;
                const prefijo = ordenTexto.slice(0, 3); // Primeros 3 caracteres
                const sufijo = ordenTexto.slice(-3);    // Últimos 3 caracteres
                const ordenFormateada = `${prefijo}-${sufijo}`; // Formato ABC-123

                // Dividir la "Orden" formateada en varias líneas
                const ordenDividido = doc.splitTextToSize(ordenFormateada, 35); // Ajusta el ancho aquí

                // Dividir texto de "Problema" en varias líneas
                const problemaTexto = columnas[6].innerText;
                const problemaDividido = doc.splitTextToSize(problemaTexto, 70); // Ajusta el ancho aquí

                // Dividir texto de "Equipo" en varias líneas
                const equipoTexto = columnas[4].innerText;
                const equipoDividido = doc.splitTextToSize(equipoTexto, 35); // Ajusta el ancho aquí
                
                // Calcular altura dinámica
                const alturaOrden = ordenDividido.length * lineHeight;
                const problemaOrden = problemaDividido.length * lineHeight;
                const alturaEquipo = equipoDividido.length * lineHeight;

                // Determinar la altura máxima
                const alturaMaxima = Math.max(alturaOrden, problemaOrden, alturaEquipo);

                // Dibujar datos con "Orden" dividido
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0); 

                doc.text(ordenDividido, startX + 5, y + 5); 
                doc.text(columnas[1].innerText, startX + 50, y + 5); 
                doc.text(columnas[2].innerText, startX + 100, y + 5); 
                doc.text(equipoDividido, startX + 125, y + 5); 
                doc.text(problemaDividido, startX + 165, y + 5); 
                doc.text(columnas[5].innerText, startX + 240, y + 5); 

                // Dibujar líneas divisorias con ajuste dinámico
                doc.rect(startX, y, 45, alturaMaxima); 
                doc.rect(startX + 45, y, 50, alturaMaxima); 
                doc.rect(startX + 95, y, 28, alturaMaxima); 
                doc.rect(startX + 123, y, 40, alturaMaxima); 
                doc.rect(startX + 163, y, 75, alturaMaxima); 
                doc.rect(startX + 238, y, 42, alturaMaxima); 

                // Incrementar y según la altura máxima
                y += alturaMaxima;

            });

            // Pie de página
            const addFooter = () => {
                const pageHeight = doc.internal.pageSize.height;
                doc.setFontSize(12);
                doc.setTextColor(100);
                doc.text(
                    'Reporte Reparaciones Multicomp - Página ' + doc.internal.getNumberOfPages(),
                    startX, pageHeight - 10
                );
            };

            addFooter(); 
            window.open(doc.output('bloburl'), '_blank');
        };
    });
</script>


<script>
    function imprimirPDF8(nombre_cliente, telefono_cliente, tipo_equipo, serie, problema_equipo) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        const margin = 20;
        const startX = margin;
        const startY = 50;
        const tableWidth = 280;
        const lineHeight = 7;

        // Agregar fondo (imagen)
        const backgroundImg = new Image();
        backgroundImg.src = 'https://static.vecteezy.com/system/resources/previews/019/566/390/non_2x/blue-abstract-background-border-on-white-template-background-adaptable-for-banner-poster-brochure-free-vector.jpg';
        backgroundImg.onload = function() {
            doc.addImage(backgroundImg, 'JPEG', 0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height);

            // Agregar título y logo
            doc.setFontSize(22);
            doc.setTextColor(40, 60, 100);
            doc.text('Reporte de Reparación', startX, startY + 20);
            
            const logoImg = new Image();
            logoImg.src = 'https://i.ibb.co/kgNwmJs/logo2.png'; 
            logoImg.onload = function () {
                doc.addImage(logoImg, 'PNG', 235, startY - 2, 40, 40);

                // Información de la reparación
                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0);

                doc.text(`Cliente: ${nombre_cliente}`, startX + 5, startY + 30);
                doc.text(`Teléfono: ${telefono_cliente}`, startX + 5, startY + 40);
                doc.text(`Tipo de Equipo: ${tipo_equipo}`, startX + 5, startY + 50);
                doc.text(`Serie: ${serie}`, startX + 5, startY + 60);
                doc.text(`Problema: ${problema_equipo}`, startX + 5, startY + 70);

                // Pie de página
                const addFooter = () => {
                    const pageHeight = doc.internal.pageSize.height;
                    doc.setFontSize(12);
                    doc.setTextColor(100);
                    doc.text(
                        'Reporte Reparaciones Multicomp - Página ' + doc.internal.getNumberOfPages(),
                        startX, pageHeight - 10
                    );
                };

                addFooter(); 
                window.open(doc.output('bloburl'), '_blank');
            };
        };
    }
</script>

<?php
require_once '../headfooter/footer.php';
?>
