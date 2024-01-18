<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $tipo_sala = $_POST['tipo_sala'];
    $silla = $_POST['silla'];
    $stmt = $conn->prepare("SELECT me.nombre_mesa AS nombre_mesa
    FROM tbl_mesas me
    INNER JOIN tbl_salas sa ON me.id_sala_mesa = sa.id_sala
    INNER JOIN tbl_tipos_salas tisa ON sa.id_tipos_sala = tisa.id_tipos
    LEFT JOIN tbl_reserva res ON me.id_mesa = res.id_mesa_reservada
    LEFT JOIN tbl_estado es ON es.id_estado = me.id_estado_mesa
    WHERE tisa.nombre_tipos = 'Terraza'
      AND me.sillas = 4
      AND (
        res.id_reserva IS NULL
        OR (res.hora != '13:00:00' AND res.fecha_hora_reserva = '2024-01-19')
      );");
    $stmt->bindParam(":tipo_sala", $tipo_sala);
    $stmt->bindParam(":silla", $silla);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $mesa = $row['nombre_mesa'];
        $options .= "<option value='$mesa'>$mesa</option>";
    }
    echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}