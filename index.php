<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tienda</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>
<?php
if (isset($_GET['mensaje'])) {
    $mensaje = urldecode($_GET['mensaje']);
    echo '<script>alert("' . $mensaje . '");</script>';
}
?>
    <main>
        <article>
            <section>
                <form action="./bd/verifyuser.php" method="POST">
                    <h1>Inicia Sesion</h1>

                    <input type="text" name="nombreusu" placeholder="Usuario"><br/>
                    <input type="password" name="contrasena" placeholder="Contrase&ntilde;a" required><br/>
                    <button type="submit">Entrar</button>
                    <button type="reset">Limpiar</button>

                    <p>Si no posees cuenta pide al administrador que te asigne una</p>

                </form>
            </section>
        </article>
    </main>
    
</body>
</html>