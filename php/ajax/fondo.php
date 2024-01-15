<?php
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    if ($_POST['tipo_sala'] == "Terraza") {
    $fondo = "../images/Terraza.jpg";
    } elseif ($_POST['tipo_sala'] == "Comedor") {
    $fondo = "../images/comedor.jpg";
    } elseif ($_POST['tipo_sala'] == "Sala_privada") {
    $fondo = "../images/Sala_privada.jpg";
    }
    $resultfondo = "background-image: 
    url($fondo); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
    background-size: cover; background-position: center; background-image: no-repeat; width: 1130px;";
    
    echo json_encode($resultfondo);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
