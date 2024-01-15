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
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $sql = "SELECT * FROM tbl_users WHERE user = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $login = mysqli_fetch_assoc($result);
        $enc_pwd = $login['contra'];
        if (password_verify($pwd, $enc_pwd)) {
            $_SESSION['id_user'] = $login['id_user'];
            $_SESSION['user'] = $user;
            header('Location: ./home.php');
        } else {
            header('Location: ../index.php?exist=0');
        }
    } else {
        header('Location: ../index.php?exist=0');
    }
}
} catch (Exception $e) {
    echo "Error: ". $e->getMessage()."<br>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);