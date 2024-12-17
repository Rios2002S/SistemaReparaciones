<?php
require_once '../headfooter/head.php';
?>

<!-- Modal personalizado en lugar de alert() -->
<div id="customAlert" style="display: none; background-color: black; color: white; padding: 20px; border-radius: 10px; position: fixed; top: 50px; left: 50%; transform: translateX(-50%); width: 80%; text-align: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); z-index: 1000;">
    <img src="https://seguridad.prestigia.es/wp-content/uploads/2020/12/implantar-ids-ips-prestigia-seguridad.png" alt="Alerta de Seguridad" style="max-width: 100%; height: auto;">
    <p style="font-size: 18px; font-weight: bold;">¡Alerta de Seguridad! Necesita ser Administrador</p>
    <button onclick="window.history.back()" style="background-color: red; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Cerrar</button>
</div>

<script>
    // Función para mostrar el alert
    function showAlert() {
        document.getElementById("customAlert").style.display = "block";
    }


    // Llamada a la función showAlert() para mostrar el alert al cargar la página
    window.onload = function() {
        showAlert();
    };
</script>

<?php
require_once '../headfooter/footer.php';
?>
