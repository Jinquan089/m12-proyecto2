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
        <button class="return-button" onclick="window.location = './cerrar.php'">
          <img src="../images/LOGOUT.PNG" width='27px'>
        </button>
      </div>
      <img id="logo" src="../images/logo.png" alt="">
      <?php
      echo "Bienvenido " . $_SESSION['user'];
      ?>
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
            <option value="nu">Seleccione un número</option>
          </select>
        </div>
        <!-- Acaba el numero de sala -->
        <div class="dropdown">
          <label for="dropdown3">Mesa</label>
          <select id="dropdown3" name="mesa">
            <option value="nu">Seleccione un número</option>
          </select>
        </div>
        <div class="dropdown">
          <label for="dropdown4">Ocupación</label>
          <select id="dropdown4" name="estado">
            <option value="nu">Seleccione una opción</option>
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
      <table>
        <tr>
        </tr>
        <?php
        // Procesar las selecciones enviadas por el formulario
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          $selecciones = [];
          // Obtener y almacenar las selecciones
          // Procesar la selección de Num. Sala
          if (isset($_GET['sala'])) {
            if ($_GET['sala'] != "nu") {
              $selecciones[] = ["Sala :", htmlspecialchars($_GET['sala'])];
            }
          }
          if (isset($_GET['num_sala'])) {
            if ($_GET['num_sala'] != "nu") {
              $selecciones[] = ["Num.Sala:", htmlspecialchars($_GET['num_sala'])];
            }
          }
          // Verificar si se proporcionaron filtros para la ocupación de mesas
          if (
            isset($_GET['sala']) && $_GET['sala'] != "nu" &&
            (
              (isset($_GET['num_sala']) && $_GET['num_sala'] != "nu") ||
              (isset($_GET['mesa']) && $_GET['mesa'] != "nu") ||
              (isset($_GET['estado']) && $_GET['estado'] != "nu")
            )
          ) {
            // Obtener el valor del parámetro 'Sala'
            $tipoSala = htmlspecialchars($_GET['sala']);
            // Construir la consulta SQL para obtener el número de mesas ocupadas y libres
            $sqlOcupacion = "SELECT
                              COUNT(CASE WHEN estado_nombre = 'Ocupado' THEN 1 END) as mesas_ocupadas,
                              COUNT(*) as total_mesas
                              FROM tbl_tipos_salas tsa 
                              INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
                              INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
                              INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
                              WHERE nombre_tipos = :tipoSala";

            if ($_GET['num_sala'] != "nu") {
              $numSala = htmlspecialchars($_GET['num_sala']);
              $sqlOcupacion .= " AND nombre_sala = :numSala";
            }
            if ($_GET['mesa'] != "nu") {
              $mesa = htmlspecialchars($_GET['mesa']);
              $sqlOcupacion .= " AND sillas = :mesa";
            }
            if ($_GET['estado'] != "nu") {
              $estado = htmlspecialchars($_GET['estado']);
              $sqlOcupacion .= " AND estado_nombre = :estado";
            }

            // Preparar la consulta de ocupación
            $stmtOcupacion = $conn->prepare($sqlOcupacion);

            // Vincular parámetros para la consulta de ocupación
            $stmtOcupacion->bindParam(':tipoSala', $tipoSala, PDO::PARAM_STR);

            if (isset($numSala)) {
              $stmtOcupacion->bindParam(':numSala', $numSala, PDO::PARAM_STR);
            }
            if (isset($mesa)) {
              $stmtOcupacion->bindParam(':mesa', $mesa, PDO::PARAM_STR);
            }
            if (isset($estado)) {
              $stmtOcupacion->bindParam(':estado', $estado, PDO::PARAM_STR);
            }

            $stmtOcupacion->execute();

            // Obtener el resultado de ocupación
            $rowOcupacion = $stmtOcupacion->fetch(PDO::FETCH_ASSOC);

            // Obtener el número de mesas ocupadas y libres
            $mesasOcupadas = $rowOcupacion['mesas_ocupadas'];
            $mesasTotal = $rowOcupacion['total_mesas'];

            // Guardar información de ocupación en el array de selecciones
            $selecciones[] = ["Ocupación :", $mesasOcupadas . "/" . $mesasTotal];
          }
          // Procesar la selección de Mesa
          if (isset($_GET['mesa']) && $_GET['mesa'] != "nu") {
            $selecciones[] = ["Mesa :", htmlspecialchars($_GET['mesa'])];
          }
          foreach ($selecciones as $seleccion) {
            echo "<tr><td id='izq'>{$seleccion[0]}</td><td id='derch'>{$seleccion[1]}</td></tr>";
          }
        }
        ?>
      </table>
    </div>
  </div>
<div id="fondo_sala" class="container-mapa-mesas">
<!-- Contenedor mapa mesas -->
<div id="mapa">
  <?php
  if (
    isset($_GET['sala']) && $_GET['sala'] != "nu" &&
    (
      (isset($_GET['num_sala']) && $_GET['num_sala'] != "nu") ||
      (isset($_GET['mesa']) && $_GET['mesa'] != "nu") ||
      (isset($_GET['estado']) && $_GET['estado'] != "nu")
    )
  ) {
    // Obtener el valor del parámetro 'Sala'
    $tipoSala = $_GET['sala'];
    // Construir la consulta SQL basada en los filtros proporcionados
    $sql = "SELECT id_mesa, nombre_mesa, sillas, estado_nombre FROM tbl_tipos_salas tsa 
          INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
          INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
          INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
          WHERE nombre_tipos = ?";
    // Agregar condiciones adicionales según los filtros proporcionados
    if ($_GET['num_sala'] != "nu") {
      $numSala = $_GET['num_sala'];
      $sql .= " AND nombre_sala = ?";
    }
    if ($_GET['mesa'] != "nu") {
      $mesa = $_GET['mesa'];
      $sql .= " AND sillas = ?";
    }

    if ($_GET['estado'] != "nu") {
      $estado = $_GET['estado'];
      $sql .= " AND estado_nombre = ?";
    }
    $sql .= " ORDER BY id_mesa ASC;";
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    // Bind de parámetros según los filtros proporcionados

    if (isset($tipoSala) && isset($numSala) && !isset($mesa) && !isset($estado)) {
      /* Filtro de solo tipo de sala y numero sala */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $numSala, PDO::PARAM_STR);

    } else if (isset($tipoSala) && isset($mesa) && !isset($numSala) && !isset($estado)) {
      /* Filtro de solo tipo de sala y mesas */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $mesa, PDO::PARAM_STR);

    } elseif (isset($tipoSala) && isset($estado) && !isset($numSala) && !isset($mesa)) {
      /* Filtro de solo tipo de sala y estado */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $estado, PDO::PARAM_STR);

    } elseif (isset($tipoSala) && isset($numSala) && isset($mesa) && !isset($estado)) {
      /* Filtro de solo tipo de sala, numero de sala y mesa */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $numSala, PDO::PARAM_STR);
      $stmt->bindParam(3, $mesa, PDO::PARAM_STR);

    } elseif (isset($tipoSala) && isset($mesa) && isset($estado) && !isset($numSala)) {
      /* Filtro de solo tipo de sala, mesa y estado de las mesas */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $mesa, PDO::PARAM_STR);
      $stmt->bindParam(3, $estado, PDO::PARAM_STR);

    } elseif (isset($tipoSala) && isset($numSala) && isset($estado) && !isset($mesa)) {
      /* Filtro de solo tipo de sala, numero de sala y estado de las mesas */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $numSala, PDO::PARAM_STR);
      $stmt->bindParam(3, $estado, PDO::PARAM_STR);

    } else {
      /* Filtro de todo */
      $stmt->bindParam(1, $tipoSala, PDO::PARAM_STR);
      $stmt->bindParam(2, $numSala, PDO::PARAM_STR);
      $stmt->bindParam(3, $mesa, PDO::PARAM_STR);
      $stmt->bindParam(4, $estado, PDO::PARAM_STR);
    }

    $stmt->execute();
    $resultadoMesa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($resultadoMesa) > 0) {
      foreach ($resultadoMesa as $fila) {
        $tipo_mesa = $fila['sillas'];
        $nombre_mesa = $fila['nombre_mesa'];
        $estado = $fila['estado_nombre'];
        $id_mesa = $fila['id_mesa'];
        if ($estado == 'Ocupado') {
          $clase_circulo = '../images/table-ocuped.svg';
        } else {
          $clase_circulo = '../images/table-libre.svg';
        }
        $link = './mesa/ocupada.php?id_mesa=' . $id_mesa;
        ?>
        <div class="contenedor-mesas">
          <form action="./mesa/ocupada.php" method="get">
            <input type="hidden" name="sala" value="<?php echo $_GET['sala']; ?>">
            <input type="hidden" name="num_sala" value="<?php echo $_GET['num_sala']; ?>">
            <input type="hidden" name="mesa" value="<?php echo $_GET['mesa']; ?>">
            <input type="hidden" name="estado" value="<?php echo $_GET['estado']; ?>">
            <input type="hidden" name="id_mesa" value="<?php echo $id_mesa; ?>">
            <a class="numero-mesa">
              <?php echo $tipo_mesa; ?>
            </a>
            <button class="button-mesa" type="submit"><img src="<?php echo $clase_circulo; ?>" style="height: 190px;"></button>
            <a class="nombre-mesa">
              <?php echo $nombre_mesa; ?>
            </a>
          </form>
        </div>
    <?php
        // Cierra el bucle foreach
      }
    } else {
      echo "<div class='container-error'>
            <div class='text-error'>No hay ninguna mesa en ese estado.</div></div>";
    }
    ?>
    </div>
    </div>
    </div>
    <?php
    }
    ?>
    <script src="../js/ajax.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
    </html>