<?php
  if (isset($_POST['sala']) && ((isset($_POST['num_sala']) || (isset($_POST['mesa']) || (isset($_POST['estado'])))))) {
    // Obtener el valor del parámetro 'Sala'
    $tipoSala = $_POST['sala'];
    // Construir la consulta SQL basada en los filtros proporcionados
    $sql = "SELECT id_mesa, nombre_mesa, sillas, estado_nombre FROM tbl_tipos_salas tsa 
          INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
          INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
          INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
          WHERE nombre_tipos = :tipo_sala";
    // Definir un array para manejar las vinculaciones de parámetros
    $params = [":tipo_sala" => $tipoSala];

    // Definir los filtros y agregar a la consulta y al array de parámetros
    $filtros = ["num_sala", "mesa", "estado"];
    foreach ($filtros as $filtro) {
        if (isset($_POST[$filtro]) && $_POST[$filtro] != "nu") {
            $sql .= " AND $filtro = :$filtro";
            $params[":$filtro"] = $_POST[$filtro];
        }
    }

    $sql .= " ORDER BY id_mesa ASC;";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Hacer las vinculaciones de parámetros usando el array
    foreach ($params as $param => &$value) {
        $stmt->bindParam($param, $value);
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
        echo "<input type='hidden' name='sala' value='$tipoSala'>"
        ?>

            
            <input type="hidden" name="num_sala" value="<?php echo $_POST['num_sala']; ?>">
            <input type="hidden" name="mesa" value="<?php echo $_POST['mesa']; ?>">
            <input type="hidden" name="estado" value="<?php echo $_POST['estado']; ?>">
            <input type="hidden" name="id_mesa" value="<?php echo $id_mesa; ?>">
            <a class="numero-mesa">
              <?php echo $tipo_mesa; ?>
            </a>
            <button class="button-mesa" type="submit"><img src="<?php echo $clase_circulo; ?>" style="height: 190px;"></button>
            <a class="nombre-mesa">
              <?php echo $nombre_mesa; ?>
            </a>
    <?php
        // Cierra el bucle foreach
      }
    } else {
      echo "<div class='container-error'>
            <div class='text-error'>No hay ninguna mesa en ese estado.</div></div>";
    }
}
