<?php
session_start();
if (isset($_SESSION["user"])) {
    header('Location: ./php/cerrar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/9b3003b0ac.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/error.css">
    <title>Restaurante</title>
</head>
<body>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <!-- Div de Logo -->
                <div class="logo">
                    <img src="./images/logo.png" alt="">
                </div>
                <!-- Div del Formulario -->
                <div class="form">
                    <form action="./php/login.php" method="post" onsubmit="return validaFormulario()">
                        <h2>Iniciar Sesión</h2>
                        <!-- Div login usuario -->
                        <div class="input-contenedor">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="user" id="user" placeholder="Usuario" oninput="limitarLongitud(this, 20)">
                        </div>
                        <!-- Div login contraseña -->
                        <div class="input-contenedor">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="pwd" id="pwd" placeholder="Contraseña" oninput="limitarLongitud(this, 15)">
                        </div>
                        <!-- Mensaje de error -->
                        <label for="" id="error" class="errorlogin"></label>
                        <br>
                        <br>
                        <!-- Boton iniciar sesión -->
                        <button type="submit" name="login" value="Login">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="js/validaciones.js"></script>
</body>
<?php if (isset($_GET['exist'])) {
    echo "<script>
    var user = document.getElementById('user').value;
    var Error = document.getElementById('error');
    var pwd = document.getElementById('pwd').value;
    Error.textContent = 'Usuario o Contraseña equivocada';
    </script>";
} 
session_destroy();
?>
</html>
