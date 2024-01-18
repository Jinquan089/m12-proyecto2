<?php
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}
include './connection.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Historico de reservas</title>
    <link rel="stylesheet" href="../css/style_historico.css">
</head>

<body>
    <div class="statistics-container">
        <div class="responsive-flex">
            <button class="return-button" onclick="window.location = './home.php'">
                <img src="../images/LOGOUT.PNG" width='27px'>
            </button>
            <img id="logo" src="../images/logo.png" alt="">
        </div>
        <div class="statistics-list-container">
            <form action="" method="POST">
                <input type="text" name="nombre" id="nombre"><br>
                <select name="tiposala" id="tiposala">
                    <option selected disabled>Seleccione una sala</option>
                </select><br>
                <select name="personas" id="personas">
                    <option selected disabled>Numero de personas</option>
                </select><br>
                <select name="hora" id="hora">
                    <option disabled selected>Hora</option>
                    <option value="13">13:00</option>
                    <option value="14">14:00</option>
                    <option value="15">15:00</option>
                    <option value="19">19:00</option>
                    <option value="20">20:00</option>
                    <option value="21">21:00</option>
                </select><br>
                    <input type="date" id="fecha" name="fecha"><br>
                <select name="mesa" id="mesa">
                    <option selected disabled>Mesa</option>
                </select><br>
                <input type="button" value="Enviar">
            </form>
        </div>
    </div>

</body>
</html>
<script>
    var hoy = new Date();
    var semanaDespues = new Date();
    semanaDespues.setDate(hoy.getDate() + 7);
    document.getElementById('fecha').min = hoy.toISOString().split('T')[0];
    document.getElementById('fecha').max = semanaDespues.toISOString().split('T')[0];
</script>
<script src="../js/reserva.js"></script>