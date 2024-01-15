<?php
// Sesión de PHP
session_start();
if (!isset($_SESSION["user"])) {
  header('Location: ./cerrar.php');
  exit();
}

include("./connection.php");
$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Link de Ajax -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-------Styles----------->
  <title>Mapa de Mesas - Restaurante</title>
  <link rel="stylesheet" href="../css/style_visual_restaurante.css">
</head>
<body>
  <?php
  // Este script se ejecutará cuando se cargue la página
  echo
    "<script>
    // Este script se ejecutará cuando se cargue la página
    window.onload = function() {
        // Verificar si ya se ha mostrado el mensaje de bienvenida
        if (!localStorage.getItem('bienvenidaMostrada')) {
            Swal.fire({
                imageUrl: '../images/logo.png',
                imageWidth: 400,
                imageHeight: 200,
                confirmButtonText: 'Bienvenido $user',
                confirmButtonColor: 'grey'
            });
            // Marcar que se ha mostrado el mensaje de bienvenida
            localStorage.setItem('bienvenidaMostrada', 'true');
            }
        };
    </script>"
    ?>
<div class="All">
  <!-- Bienvenida usuario -->
  <h1 class="titulo-restaurante">
    <div class="responsive-flex">
      <button class="return-button" onclick="window.location = './cerrar.php'"><img src="../images/LOGOUT.PNG" width='27px'></button>
    </div>
      <img id="logo" src="../images/logo.png" alt="">
      <?php echo "Bienvenido " . $_SESSION['user'];?>
      <button class="historico" onclick="window.location = './historico.php'">Historial</button>
  </h1>
    <!-- Contenedor filtros -->
    <div class="container-dropdown">
      <!-- Comienzo del formulario de filtros -->
      <form class="formulario-filtros" action="./home.php" method="get" id="nocargar">
        <div class="dropdown">
          <label for="dropdown1">Sala</label>
          <!-- Selector para sala -->
          <select id="dropdown1" name="sala">
            <option selected disabled>Seleccione una sala</option>
          </select>
        </div>
        <!-- Acaba el selector para sala -->
        <!-- Comienzo del selector numero de sala -->
        <div class="dropdown">
          <label for="dropdown2">Num. Sala</label>
          <select id="dropdown2" name="num_sala">
            <option selected disabled>Seleccione una sala</option>
          </select>
        </div>
        <!-- Acaba el numero de sala -->
        <div class="dropdown">
          <label for="dropdown3">Mesa</label>
          <select id="dropdown3" name="mesa">
            <option selected disabled>Seleccione una mesa</option>
          </select>
        </div>
        <div class="dropdown">
          <label for="dropdown4">Ocupación</label>
          <select id="dropdown4" name="estado">
            <option selected disabled>Seleccione el estado</option>
          </select>
        </div>
        <div class="dropdown">
          <button type="submit" class="aplicar_filtros">Aplicar filtros</button>
        </div>
      </form>
    </div>
  <!-- <hr class="hr hr-blurry" /> -->
  <div class="cuerpo">
    <div class="legenda">
      <div class="container-legenda">
        <h2>Selecciones:</h2>
        <table id="leyenda"></table>
      </div>
    </div>
    <div id="fondo_sala" class="container-mapa-mesas">
      <!-- Contenedor mapa mesas -->
      <div id="mapa">
        <div class="contenedor-mesas">
          <form action="./mesa/ocupada.php" method="get" id="frmMesas"></form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/ajax.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>