<?php
require_once '../headfooter/head.php';
?>
<style>
    /* Contenedor de los mensajes */
    #mensajesRecibidos {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Estilo común para todos los mensajes */
    .message {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 8px;
        max-width: 35%;
        word-wrap: break-word;
        position: relative;
    }

    /* Estilo para los mensajes enviados (al lado derecho) */
    .message-right {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        text-align: right;
    }

    .message-right p {
        background-color: #007bff;
        color: white;
    }

    /* Estilo para los mensajes recibidos (al lado izquierdo) */
    .message-left {
        background-color: #e0e0e0; /* Gris claro para los mensajes del otro usuario */
        color: black;
        margin-right: auto;
        text-align: left;
    }

    .message-left p {
        background-color: #e0e0e0;
        color: black;
    }

    /* Hora del mensaje */
    .message-time {
        font-size: 0.8em;
        color: #888;
        position: absolute;
        bottom: -20px;
        right: 5px;
    }
</style>
    <div class="container mt-5">
        <h3>Seleccionar Usuario para Enviar Mensaje</h3>
        <div class="mb-3">
            <label for="destinatario" class="form-label">Selecciona un Usuario:</label>
            <select class="form-select" id="destinatario">
                <option value="">Selecciona un usuario...</option>
            </select>
        </div>

        <div id="mensajesRecibidos" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background-color: #f9f9f9; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <!-- Los mensajes se mostrarán aquí -->
        </div>

        <div id="mensajeria" style="display:none;"><br>
            <textarea id="mensaje" class="form-control mb-3" rows="4" placeholder="Escribe tu mensaje"></textarea>
            <button id="enviarMensaje" class="btn btn-primary">Enviar Mensaje</button>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            var intervalId = null; // Variable para almacenar el ID del intervalo

            // Obtener los usuarios y cargar el select
            $.ajax({
                url: '../bd/obtener_usuarios.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        var options = '<option value="">Selecciona un usuario...</option>';
                        $.each(data, function(index, user) {
                            options += `<option value="${user.id_usuario}">${user.nombreusu}</option>`;
                        });
                        $('#destinatario').html(options);
                    }
                }
            });

            // Cuando seleccionan un destinatario, cargar los mensajes
            $('#destinatario').on('change', function() {
                var destinatario = $(this).val();
                if (destinatario) {
                    $('#mensajeria').show();
                    // Limpiar los mensajes antes de cargar los nuevos
                    $('#mensajesRecibidos').html('');
                    // Detener cualquier intervalo activo
                    if (intervalId !== null) {
                        clearInterval(intervalId);
                    }
                    // Cargar los mensajes del nuevo destinatario
                    cargarMensajes(destinatario);

                    // Actualizar los mensajes cada 3 segundos
                    intervalId = setInterval(function() {
                        cargarMensajes(destinatario);
                    }, 20000); // 3000ms = 3 segundos
                } else {
                    $('#mensajeria').hide();
                    // Detener cualquier intervalo activo cuando no se selecciona un destinatario
                    if (intervalId !== null) {
                        clearInterval(intervalId);
                    }
                }
            });

            // Función para cargar los mensajes y asegurar que el contenedor se desplace hacia abajo
            function cargarMensajes(destinatario) {
                $.ajax({
                    url: '../bd/cargar_mensajes.php',
                    method: 'GET',
                    data: { destinatario: destinatario },
                    success: function(response) {
                        $('#mensajesRecibidos').html(response);
                        // Desplazar el contenedor hacia abajo después de cargar los mensajes
                        $('#mensajesRecibidos').scrollTop($('#mensajesRecibidos')[0].scrollHeight);
                    }
                });
            }

            // Enviar un mensaje
            $('#enviarMensaje').on('click', function() {
                var destinatario = $('#destinatario').val();
                var mensaje = $('#mensaje').val();
                if (destinatario && mensaje) {
                    $.ajax({
                        url: '../bd/enviar_mensaje.php',
                        method: 'POST',
                        data: {
                            destinatario: destinatario,
                            mensaje: mensaje
                        },
                        success: function(response) {
                            alert(response);
                            $('#mensaje').val(''); // Limpiar el campo de mensaje
                            // Recargar los mensajes
                            cargarMensajes(destinatario);
                        }
                    });
                } else {
                    alert('Por favor, completa todos los campos.');
                }
            });
        });
    </script>


<?php
require_once '../headfooter/footer.php';
?>