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
    $hora = $_POST['hora'].':00:00';
    $fecha = $_POST['fecha'];
    $stmt = $conn->prepare("SELECT 
    me.id_mesa AS id_mesa, 
    me.nombre_mesa AS nombre_mesa,
    res.id_reserva,
    res.hora AS hora_reserva,
    res.fecha_reserva
    FROM tbl_mesas me
    INNER JOIN tbl_salas sa ON me.id_sala_mesa = sa.id_sala
    INNER JOIN tbl_tipos_salas tisa ON sa.id_tipos_sala = tisa.id_tipos
    LEFT JOIN tbl_reserva res ON me.id_mesa = res.id_mesa_reservada AND (res.hora = :hora AND res.fecha_reserva = :fecha)
    LEFT JOIN tbl_estado es ON es.id_estado = me.id_estado_mesa
    WHERE tisa.nombre_tipos = :tipo_sala AND me.sillas = :silla
    AND res.id_reserva IS NULL;");
    $stmt->bindParam(":tipo_sala", $tipo_sala);
    $stmt->bindParam(":silla", $silla);
    $stmt->bindParam(":hora", $hora);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $mesa = $row['nombre_mesa'];
        $idmesa = $row['id_mesa'];
        $options .= "<option value='$idmesa'>$mesa</option>";
    }
    echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}