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
        <div class="contenedor">
        <div class="columna3 card">
            <form action="" method="POST" id="frmReserva">
                <div class='form-group'>
                    <input type="text" name="nombre" id="nombre" class='form-control' placeholder="Nombre">
                </div><br>
                <div class='form-group'>
                    <select name="tiposala" id="tiposala" class='form-control'>
                        <option selected disabled>Seleccione una sala</option>
                    </select></div><br>
                <div class='form-group'>
                    <select name="personas" id="personas" class='form-control'>
                        <option selected disabled>Numero de personas</option>
                    </select></div><br>
                <div class='form-group'>
                    <select name="hora" id="hora" class='form-control'>
                        <option disabled selected>Hora</option>
                        <option value="13">13:00</option>
                        <option value="14">14:00</option>
                        <option value="15">15:00</option>
                        <option value="19">19:00</option>
                        <option value="20">20:00</option>
                        <option value="21">21:00</option>
                    </select></div><br>
                <div class='form-group'>
                    <input type="date" id="fecha" name="fecha" class='form-control'>
                </div><br>
                <div class='form-group'>
                    <select name="mesa" id="mesa" class='form-control'>
                        <option selected disabled>Mesa</option>
                    </select>
                </div><br>
                <div class='form-group'>
                    <input type="submit" value="Enviar" id="enviar" class='form-control'>
                </div>
            </form>
            </div>
            <div class="columna2">
                    <table cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Numero Personas</th>
                                <th>Hora</th>
                                <th>Fecha</th>
                                <th>Mesa</th>
                                <th>Cancelar</th>
                            </tr>
                        </thead>
                        <tbody id="crdreservados">
                        </tbody>
            </div>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>