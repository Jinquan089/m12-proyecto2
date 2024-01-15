<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}
          $selecciones = [];
          // Obtener y almacenar las selecciones
          if (isset($_POST['tipo_sala'])) {
              $selecciones[] = ["Sala :", htmlspecialchars($_POST['tipo_sala'])];
          }
          if (isset($_POST['num_sala'])) {
              $selecciones[] = ["Num.Sala:", htmlspecialchars($_POST['num_sala'])];
          }
          // Verificar si se proporcionaron filtros para la ocupación de mesas
          if (isset($_POST['tipo_sala']) && ((isset($_POST['num_sala'])) || (isset($_POST['mesa'])) || (isset($_POST['estado'])))) {          
            $tipoSala = htmlspecialchars($_POST['tipo_sala']);
            // Construir la consulta SQL para obtener el número de mesas ocupadas y libres
            $sqlOcupacion = "SELECT
                              COUNT(CASE WHEN estado_nombre = 'Ocupado' THEN 1 END) as mesas_ocupadas,
                              COUNT(*) as total_mesas
                              FROM tbl_tipos_salas tsa 
                              INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
                              INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
                              INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
                              WHERE nombre_tipos = :tipoSala";

            if (isset($_POST['num_sala'])) {
              $numSala = htmlspecialchars($_POST['num_sala']);
              $sqlOcupacion .= " AND nombre_sala = :numSala";
            }
            if (isset($_POST['mesa'])) {
              $mesa = htmlspecialchars($_POST['mesa']);
              $sqlOcupacion .= " AND sillas = :mesa";
            }
            if (isset($_POST['estado'])) {
              $estado = htmlspecialchars($_POST['estado']);
              $sqlOcupacion .= " AND estado_nombre = :estado";
            }

            // Preparar la consulta de ocupación
            $stmtOcupacion = $conn->prepare($sqlOcupacion);

            // Vincular parámetros para la consulta de ocupación
            $stmtOcupacion->bindParam(':tipoSala', $tipoSala);

            if (isset($numSala)) {
              $stmtOcupacion->bindParam(':numSala', $numSala);
            }
            if (isset($mesa)) {
              $stmtOcupacion->bindParam(':mesa', $mesa);
            }
            if (isset($estado)) {
              $stmtOcupacion->bindParam(':estado', $estado);
            }

            $stmtOcupacion->execute();

            // Obtener el resultado de ocupación
            $rowOcupacion = $stmtOcupacion->fetch();

            // Obtener el número de mesas ocupadas y libres
            $mesasOcupadas = $rowOcupacion['mesas_ocupadas'];
            $mesasTotal = $rowOcupacion['total_mesas'];

            // Guardar información de ocupación en el array de selecciones
            $selecciones[] = ["Ocupación :", $mesasOcupadas . "/" . $mesasTotal];
          }
          // Procesar la selección de Mesa
          if (isset($_POST['mesa'])) {
            $selecciones[] = ["Mesa :", htmlspecialchars($_POST['mesa'])];
          }
          $options = "";
          foreach ($selecciones as $seleccion) {
            $options .= "<tr><td id='izq'>{$seleccion[0]}</td><td id='derch'>{$seleccion[1]}</td></tr>";
          }
          echo json_encode($options);