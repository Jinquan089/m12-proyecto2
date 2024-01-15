<?php
// Sesiónes de Php
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
            <option value="nu">Seleccione una sala</option>
            <?php
            $sql_tipos_salas = "SELECT id_tipos, nombre_tipos, aforo FROM tbl_tipos_salas";
            $stmt_tipos_salas = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt_tipos_salas, $sql_tipos_salas);
            mysqli_stmt_execute($stmt_tipos_salas);
            $result_tipos_sala = mysqli_stmt_get_result($stmt_tipos_salas);

            if (mysqli_num_rows($result_tipos_sala) == 0) {
              mysqli_close($conn);
              echo "No";
            }

            foreach ($result_tipos_sala as $row) {
              $nombre_tipos = $row['nombre_tipos'];
              $id_tipos = $row['id_tipos'];
              ?>
              <option value="<?php echo $nombre_tipos ?>">
                <?php echo $nombre_tipos ?>
              </option>
              <?php
              /* Cierre del foreach de tipos de sala */
            }
            ?>
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
            <?php
            // Crear una sentencia SQL para obtener los estados de las mesas
            $sqlEstados = "SELECT DISTINCT estado_nombre FROM tbl_estado";
            $stmtEstados = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtEstados, $sqlEstados);
            mysqli_stmt_execute($stmtEstados);
            $resultEstados = mysqli_stmt_get_result($stmtEstados);
            // Iterar sobre los resultados y generar las opciones del dropdown  
            while ($rowEstado = mysqli_fetch_assoc($resultEstados)) {
              $nombreEstado = $rowEstado['estado_nombre'];
              echo '<option value="' . $nombreEstado . '">' . $nombreEstado . '</option>';
            }
            ?>
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
                  $selecciones[] = ["Sala :", mysqli_real_escape_string($conn, $_GET['sala'])];
                }
              }
              if (isset($_GET['num_sala'])) {
                if ($_GET['num_sala'] != "nu") {
                  $selecciones[] = ["Num.Sala:", mysqli_real_escape_string($conn, $_GET['num_sala'])];
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
                $tipoSala = mysqli_real_escape_string($conn, $_GET['sala']);
                // Construir la consulta SQL para obtener el número de mesas ocupadas y libres
                $sqlOcupacion = "SELECT
                                COUNT(CASE WHEN estado_nombre = 'Ocupado' THEN 1 END) as mesas_ocupadas,
                                COUNT(*) as total_mesas
                                FROM tbl_tipos_salas tsa 
                                INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
                                INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
                                INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
                                WHERE nombre_tipos = ?";

                if ($_GET['num_sala'] != "nu") {
                  $numSala = mysqli_real_escape_string($conn, $_GET['num_sala']);
                  $sqlOcupacion .= " AND nombre_sala = ?";
                }
                if ($_GET['mesa'] != "nu") {
                  $mesa = mysqli_real_escape_string($conn, $_GET['mesa']);
                  $sqlOcupacion .= " AND sillas = ?";
                }
                if ($_GET['estado'] != "nu") {
                  $estado = mysqli_real_escape_string($conn, $_GET['estado']);
                  $sqlOcupacion .= " AND estado_nombre = ?";
                }
                // Preparar la consulta de ocupación
                $stmtOcupacion = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmtOcupacion, $sqlOcupacion);

                if (isset($tipoSala) && isset($numSala) && !isset($mesa) && !isset($estado)) {
                  /* Filtro de solo tipo de sala y numero sala */
                  mysqli_stmt_bind_param($stmtOcupacion, "ss", $tipoSala, $numSala);
    
                } else if (isset($tipoSala) && isset($mesa) && !isset($numSala) && !isset($estado)) {
                  /* Filtro de solo tipo de sala y mesas */
                  mysqli_stmt_bind_param($stmtOcupacion, "ss", $tipoSala, $mesa);
    
                } elseif (isset($tipoSala) && isset($estado) && !isset($numSala) && !isset($mesa)) {
                  /* Filtro de solo tipo de sala y estado */
                  mysqli_stmt_bind_param($stmtOcupacion, "ss", $tipoSala, $estado);
    
                } elseif (isset($tipoSala) && isset($numSala) && isset($mesa) && !isset($estado)) {
                  /* Filtro de solo tipo de sala, numero de sala y mesa */
                  mysqli_stmt_bind_param($stmtOcupacion, "sss", $tipoSala, $numSala, $mesa);
    
                } elseif (isset($tipoSala) && isset($mesa) && isset($estado) && !isset($numSala)) {
                  /* Filtro de solo tipo de sala, mesa y estado de las mesas */
                  mysqli_stmt_bind_param($stmtOcupacion, "sss", $tipoSala, $mesa, $estado);
    
                } elseif (isset($tipoSala) && isset($numSala) && isset($estado) && !isset($mesa)) {
                  /* Filtro de solo tipo de sala, numero de sala y estado de las mesas */
                  mysqli_stmt_bind_param($stmtOcupacion, "sss", $tipoSala, $numSala, $estado);
    
                } else {
                  /* Filtro de todo */
                  mysqli_stmt_bind_param($stmtOcupacion, "ssss", $tipoSala, $numSala, $mesa, $estado);
    
                }
                
                mysqli_stmt_execute($stmtOcupacion);

                // Obtener el resultado de ocupación
                $resultOcupacion = mysqli_stmt_get_result($stmtOcupacion);

                // Obtener el número de mesas ocupadas y libres
                $rowOcupacion = mysqli_fetch_assoc($resultOcupacion);
                $mesasOcupadas = $rowOcupacion['mesas_ocupadas'];
                $mesasTotal = $rowOcupacion['total_mesas'];

                // Guardar información de ocupación en el array de selecciones
                $selecciones[] = ["Ocupación :", $mesasOcupadas . "/" . $mesasTotal];
                mysqli_stmt_close($stmtOcupacion);
              }
              // Procesar la selección de Mesa
              if (isset($_GET['mesa']) && $_GET['mesa'] != "nu") {
                $selecciones[] = ["Mesa :", mysqli_real_escape_string($conn, $_GET['mesa'])];
              }
              foreach ($selecciones as $seleccion) {
                echo "<tr><td id='izq'>{$seleccion[0]}</td><td id='derch'>{$seleccion[1]}</td></tr>";
              }
            }
            ?>
          </table>
        </div>
      </div>
      <?php
      if (isset($_GET['sala']) && $_GET['sala'] == "Terraza") {
        echo '<div class="container-mapa-mesas" style="background-image: url(../images/Terraza.jpg); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-size: cover; background-position: center; background-image: no-repeat; width: 1130px;">';
      } elseif (isset($_GET['sala']) && $_GET['sala'] == "Comedor") {
        echo '<div class="container-mapa-mesas" style="background-image: url(../images/comedor.jpg); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-size: cover; background-position: center; background-image: no-repeat; width: 1130px;">';
      } elseif (isset($_GET['sala']) && $_GET['sala'] == "Sala_privada") {
        echo '<div class="container-mapa-mesas" style="background-image: url(../images/Sala_privada.jpg); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-size: cover; background-position: center; background-image: no-repeat; width: 1130px;">';
      }
      ?>
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
          $tipoSala = mysqli_real_escape_string($conn, $_GET['sala']);
          // Construir la consulta SQL basada en los filtros proporcionados
          $sql = "SELECT id_mesa, nombre_mesa, sillas, estado_nombre FROM tbl_tipos_salas tsa 
          INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
          INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
          INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
          WHERE nombre_tipos = ?";
          // Agregar condiciones adicionales según los filtros proporcionados
            if ($_GET['num_sala'] != "nu") {
              $numSala = mysqli_real_escape_string($conn, $_GET['num_sala']);
              $sql .= " AND nombre_sala = ?";
            }
            if ($_GET['mesa'] != "nu") {
              $mesa = mysqli_real_escape_string($conn, $_GET['mesa']);
              $sql .= " AND sillas = ?";
            }

            if ($_GET['estado'] != "nu") {
              $estado = mysqli_real_escape_string($conn, $_GET['estado']);
              $sql .= " AND estado_nombre = ?";
            }
            $sql .= " ORDER BY id_mesa ASC;";
            // Preparar la consulta
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            // Bind de parámetros según los filtros proporcionados

            if (isset($tipoSala) && isset($numSala) && !isset($mesa) && !isset($estado)) {
              /* Filtro de solo tipo de sala y numero sala */
              mysqli_stmt_bind_param($stmt, "ss", $tipoSala, $numSala);

            } else if (isset($tipoSala) && isset($mesa) && !isset($numSala) && !isset($estado)) {
              /* Filtro de solo tipo de sala y mesas */
              mysqli_stmt_bind_param($stmt, "ss", $tipoSala, $mesa);

            } elseif (isset($tipoSala) && isset($estado) && !isset($numSala) && !isset($mesa)) {
              /* Filtro de solo tipo de sala y estado */
              mysqli_stmt_bind_param($stmt, "ss", $tipoSala, $estado);

            } elseif (isset($tipoSala) && isset($numSala) && isset($mesa) && !isset($estado)) {
              /* Filtro de solo tipo de sala, numero de sala y mesa */
              mysqli_stmt_bind_param($stmt, "sss", $tipoSala, $numSala, $mesa);

            } elseif (isset($tipoSala) && isset($mesa) && isset($estado) && !isset($numSala)) {
              /* Filtro de solo tipo de sala, mesa y estado de las mesas */
              mysqli_stmt_bind_param($stmt, "sss", $tipoSala, $mesa, $estado);

            } elseif (isset($tipoSala) && isset($numSala) && isset($estado) && !isset($mesa)) {
              /* Filtro de solo tipo de sala, numero de sala y estado de las mesas */
              mysqli_stmt_bind_param($stmt, "sss", $tipoSala, $numSala, $estado);

            } else {
              /* Filtro de todo */
              mysqli_stmt_bind_param($stmt, "ssss", $tipoSala, $numSala, $mesa, $estado);

            }
            // Ejecutar la consulta
            mysqli_stmt_execute($stmt);
            $resultadoMesa = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($resultadoMesa) > 0) {
              while ($fila = mysqli_fetch_assoc($resultadoMesa)) {
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
                    <button class="button-mesa" type="submit"><img src="<?php echo $clase_circulo; ?>"
                        style="height: 190px;"></button>
                    <a class="nombre-mesa">
                      <?php echo $nombre_mesa; ?>
                    </a>
                  </form>
                </div>
                <?php
                // Cierra el bucle while
              }
              mysqli_stmt_close($stmt);
            } else {
              echo "<div class='container-error'>
                  <div class='text-error'>No hay ninguna mesa en ese estado.</div></div>";
            }
        } else {
          echo "<div class='container-error'>
                <div class='text-error'>Porfavor, Seleccione una opcion mas.</div></div>";
        }
        ?>
      </div>
    </div>
  </div>
  <!-- Agrega el script de JavaScript -->
  <script>
    // Cuando se cambia la selección en el primer dropdown
    $('#dropdown1').on('change', function () {
      // Obtén el valor seleccionado del primer dropdown
      var Tiposala = $(this).val();
      // Realiza una solicitud AJAX para obtener los números de sala correspondientes al tipo de sala seleccionado
      $.ajax({
        url: './ajax/numero_sala.php',
        type: 'GET',
        data: { tipo_sala: Tiposala },
        success: function (numsala) {
          // Limpia y agrega las nuevas opciones al 2 dropdown
          $('#dropdown2').empty();
          $('#dropdown2').html(numsala);
        },
        error: function (error) {
          console.log(error);
        }
      });

      $.ajax({
        url: './ajax/numero_silla.php',
        type: 'GET',
        data: { tipo_sala: Tiposala },
        success: function (optionSillas) {
          // Limpia y agrega las nuevas opciones al 3 dropdown
          $('#dropdown3').empty();
          $('#dropdown3').html(optionSillas);
        },
        error: function (error) {
          console.log(error);
        }
      });
    });
  </script>
  </div>

</body>

</html>

<?php
mysqli_stmt_close($stmt_tipos_salas);
mysqli_stmt_close($stmtEstados);
mysqli_close($conn);
?>
