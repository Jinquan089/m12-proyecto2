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
            <table cellspacing="0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Mesa</th>
                        <th>Fecha de ocupacion</th>
                        <th>Fecha de liberacion</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="historial">
                </tbody>
            </table>
        </div>
    </div>
    <script src="../js/historial.js"></script>
</body>
</html>
