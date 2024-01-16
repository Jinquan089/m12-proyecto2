<?php
  session_start();
  include '../connection.php';
  if (!isset($_SESSION["user"])) {
      header('Location: ./cerrar.php');
      exit();
  }
  if (isset($_POST['tipo_sala']) && ((isset($_POST['num_sala']) || (isset($_POST['mesa']) || (isset($_POST['estado'])))))) {
    // Obtener el valor del parámetro 'Sala'
    $tipoSala = $_POST['tipo_sala'];

    // Construir la consulta SQL basada en los filtros proporcionados
    $sql = "SELECT id_mesa, nombre_mesa, sillas, estado_nombre FROM tbl_tipos_salas tsa 
          INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
          INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
          INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
          WHERE nombre_tipos = :tipo_sala";

    if (isset($_POST['num_sala'])) {
        $numSala = $_POST['num_sala'];
        $sql .= " AND nombre_sala = :num_sala";
    }

    if (isset($_POST['mesa'])) {
        $mesa = $_POST['mesa'];
        $sql .= " AND sillas = :mesa";
    }

    if (isset($_POST['estado'])) {
        $estado = $_POST['estado'];
        $sql .= " AND estado_nombre = :estado";
    }

    $sql .= " ORDER BY id_mesa ASC;";
    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Hacer las vinculaciones de parámetros usando el array
    if (isset($tipoSala) && isset($numSala) && !isset($mesa) && !isset($estado)) {
      /* Filtro de solo tipo de sala y numero sala */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":num_sala", $numSala);

    } else if (isset($tipoSala) && isset($mesa) && !isset($numSala) && !isset($estado)) {
      /* Filtro de solo tipo de sala y mesas */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":mesa", $mesa);

    } elseif (isset($tipoSala) && isset($estado) && !isset($numSala) && !isset($mesa)) {
      /* Filtro de solo tipo de sala y estado */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":estado", $estado);

    } elseif (isset($tipoSala) && isset($numSala) && isset($mesa) && !isset($estado)) {
      /* Filtro de solo tipo de sala, numero de sala y mesa */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":num_sala", $numSala);
      $stmt->bindParam(":mesa", $mesa);

    } elseif (isset($tipoSala) && isset($mesa) && isset($estado) && !isset($numSala)) {
      /* Filtro de solo tipo de sala, mesa y estado de las mesas */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":mesa", $mesa);
      $stmt->bindParam(":estado", $estado);

    } elseif (isset($tipoSala) && isset($numSala) && isset($estado) && !isset($mesa)) {
      /* Filtro de solo tipo de sala, numero de sala y estado de las mesas */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":num_sala", $numSala);
      $stmt->bindParam(":estado", $estado);

    } else {
      /* Filtro de todo */
      $stmt->bindParam(":tipo_sala", $tipoSala);
      $stmt->bindParam(":num_sala", $numSala);
      $stmt->bindParam(":mesa", $mesa);
      $stmt->bindParam(":estado", $estado);
    }

    $stmt->execute();
    $resultadoMesa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($resultadoMesa) > 0) {
      $options = "";
      foreach ($resultadoMesa as $fila) {
        $tipo_mesa = $fila['sillas'];
        $nombre_mesa = $fila['nombre_mesa'];
        $estado1 = $fila['estado_nombre'];
        $id_mesa = $fila['id_mesa'];
        if ($estado1 == 'Ocupado') {
          $clase_circulo = '../images/table-ocuped.svg';
        } else {
          $clase_circulo = '../images/table-libre.svg';
        }
        $options .= "<div class='contenedor-mesas'>";
        $options .= "<p class='numero-mesa'>$tipo_mesa</p>";
        $options .= "<button class='button-mesa' name='btnenviarid' id='$id_mesa'><img src='$clase_circulo' style='height: 190px;'></button>";
        $options .= "<p class='nombre-mesa'>$nombre_mesa</p></div>";
      }
      echo json_encode($options);
    } else {
      $options = "<div class='container-error'><div class='text-error'>No hay ninguna mesa en ese estado.</div></div>";
      echo json_encode($options);
    }
}
