<?php
require_once '../headfooter/head.php';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<script>alert('Nuevo cliente agregado exitosamente en Pedido Maquetas.');</script>";
    } elseif ($_GET['status'] == 'error') {
        echo "<script>alert('Hubo un error al agregar el cliente en Pedido Maquetas.');</script>";
    }
}
?>

<div class="mx-5">
    <!-- Alerta -->
    <div class="alert alert-warning d-flex align-items-center justify-content-center" role="alert" style="border-radius: 10px; padding: 15px;">
        <img src="https://i.ibb.co/zfvTCq0/logolib.png" width="700" height="200" alt="Librería" class="mx-auto d-block">
    </div>

     <?php
     require_once "../adicionales/modalesmaquetas.php";
     ?>                         
    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php
      require_once "../adicionales/tabs.php";
    ?>
              
</div>
    <!-- Modal de éxito -->
    <div class="modal fade" id="mensajeExitoModal" tabindex="-1" aria-labelledby="mensajeExitoLabel" aria-hidden="true">
        <div class="modal-dialog mt-5"> <!-- Ajusta el margen si es necesario -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeExitoLabel">¡Éxito!</h5>
                </div>
                <div class="modal-body text-center">
                    <img src="https://cdn-icons-gif.flaticon.com/10970/10970392.gif" alt="Éxito" width="75" height="75">
                    <p>El proceso se completó con éxito.</p>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "../reportes/maquetas.php";
require_once "../reportes/pedidonuevo.php";
require_once "../reportes/ticketpedidonuevo.php";
?>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inicialización del modal de confirmación
        const modal = new bootstrap.Modal(document.getElementById('confirmarModal'));
        let claveOrdenSeleccionada = '';

        // Detectar cuándo se va a mostrar el modal y asignar la clave
        document.querySelector('#confirmarModal').addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            claveOrdenSeleccionada = button.getAttribute('data-claveorden');
        });

        // Manejo del clic en el botón de confirmar
        document.getElementById('confirmarTerminado').addEventListener('click', () => {
            // Cerramos el modal de confirmación
            modal.hide();

            // Realizamos la solicitud al servidor
            fetch('../bd/marcar_terminado.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `claveorden=${encodeURIComponent(claveOrdenSeleccionada)}`
            })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === "success") {
                    // Inicializamos y mostramos el modal de éxito
                    const modalExito = new bootstrap.Modal(document.getElementById('mensajeExitoModal'));
                    modalExito.show();
                    
                    // Cerramos el modal de éxito después de 2 segundos
                    setTimeout(() => {
                        modalExito.hide();
                        window.location.reload(); // Refrescar la página
                    }, 2000);
                } else {
                    alert('Error al actualizar el pedido.');
                }
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let claveOrdenSeleccionada;

    // Capturar el evento del botón "Revertir"
    document.querySelectorAll('[data-bs-target="#revertirEstadoModal"]').forEach(button => {
        button.addEventListener('click', (event) => {
            claveOrdenSeleccionada = event.target.getAttribute('data-claveorden');
        });
    });

    // Acción de revertir el estado
    document.getElementById('confirmarRevertir').addEventListener('click', () => {
        fetch('../bd/revertir_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `claveorden=${encodeURIComponent(claveOrdenSeleccionada)}&estado=0`
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === "success") {
                // Cerrar el modal de revertir
                var myModal = new bootstrap.Modal(document.getElementById('revertirEstadoModal'));
                myModal.hide();
                
                // Inicializamos y mostramos el modal de éxito
                const modalExito = new bootstrap.Modal(document.getElementById('mensajeExitoModal'));
                modalExito.show();
                
                // Cerrar el modal de éxito después de 2 segundos y recargar la página
                setTimeout(() => {
                    modalExito.hide();
                    window.location.reload(); // Refrescar la página
                }, 2000);
            } else {
                alert("Error al revertir el estado.");
            }
        });
    });
});
</script>



<!-- Incluir las librerías de Bootstrap para el modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- Incluir las librerías de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaPedidos').DataTable();
        $('#tablaPedidos2').DataTable();
        $('#tablafinal').DataTable();
    });
</script>

<?php
require_once '../headfooter/footer.php';
?>

