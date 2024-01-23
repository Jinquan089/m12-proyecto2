<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt = $conn->prepare("SELECT re.id_reserva AS id_re, re.nombre_persona AS nombre, re.num_personas AS sillas, re.hora AS hora, re.fecha_reserva AS fecha, me.nombre_mesa AS mesa 
    FROM tbl_reserva re INNER JOIN tbl_mesas me ON re.id_mesa_reservada = me.id_mesa;");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $idre = $row['id_re'];
        $options .= "<tr>";
        $options .= "<td>" . $row['nombre'] . "</td>";
        $options .= "<td>" . $row['sillas'] . "</td>";
        $options .= "<td>" . $row['hora'] . "</td>";
        $options .= "<td>" . $row['fecha'] . "</td>";
        $options .= "<td>" . $row['mesa'] . "</td>";
        $options .= "<td class='actions-cell'><button type='button' class='btn btn-warning' id='$idre'>Cancelar</button></td>";
        $options .= "</tr>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}