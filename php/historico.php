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
                        <th>Tipo de Sala</th>
                        <th>Mesa</th>
                        <th>Ocupaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selesala = "SELECT DISTINCT me.nombre_mesa, tisa.nombre_tipos ,sa.nombre_sala FROM tbl_historial hi
                    INNER JOIN tbl_mesas me ON me.id_mesa = hi.id_mesa
                    INNER JOIN tbl_salas sa ON sa.id_sala = hi.id_sala
                    INNER JOIN tbl_tipos_salas tisa ON tisa.id_tipos = sa.id_tipos_sala";
                    $stmt1 = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt1, $selesala);
                    mysqli_stmt_execute($stmt1);
                    $resultsala = mysqli_stmt_get_result($stmt1);
                    if (mysqli_num_rows($resultsala) > 0) {
                        foreach ($resultsala as $row) {
                            $selecount = "SELECT me.nombre_mesa FROM tbl_historial hi
                            INNER JOIN tbl_mesas me ON me.id_mesa = hi.id_mesa
                            INNER JOIN tbl_salas sa ON sa.id_sala = hi.id_sala
                            INNER JOIN tbl_tipos_salas tisa ON tisa.id_tipos = sa.id_tipos_sala WHERE hi.estado = 'Ocupado' AND me.nombre_mesa = ?";
                            $stmt2 = mysqli_stmt_init($conn);
                            mysqli_stmt_prepare($stmt2, $selecount);
                            mysqli_stmt_bind_param($stmt2, "s", $row['nombre_mesa']);
                            mysqli_stmt_execute($stmt2);
                            $resultcount = mysqli_stmt_get_result($stmt2);
                            $count = mysqli_num_rows($resultcount);
                            echo "<tr>";
                            echo "<td>" . $row['nombre_tipos'] . "  " . $row['nombre_sala'] . "</td>";
                            echo "<td>" . $row['nombre_mesa'] . "</td>";
                            echo "<td>" . $count . "</td>";
                            echo "</tr>";
                            mysqli_stmt_close($stmt2);
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay historico</td></tr>";
                    }


                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<?php
mysqli_stmt_close($stmt1);
mysqli_close($conn);
?>