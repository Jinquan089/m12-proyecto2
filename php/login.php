<?php
session_start();

if (isset($_SESSION["user"])) {
    header('Location: ./home.php');
    exit();
}

include './connection.php';

try {
    if (!isset($_POST['login'])) {
        header('Location: ../index.php');
    } else {
        $user = $_POST['user'];
        $pwd = $_POST['pwd'];

        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE user = :user");
        $stmt->bindParam(":user", $user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $enc_pwd = $result['contra'];
            if (password_verify($pwd, $enc_pwd)) {
                $_SESSION['id_user'] = $result['id_user'];
                $_SESSION['user'] = $user;
                header('Location: ./home.php');
            } else {
                header('Location: ../index.php?exist=0');
            }
        } else {
            header('Location: ../index.php?exist=0');
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

