<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, 
        "SELECT id, username, password FROM users WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];

            header("Location: dashboard.php");
            exit;

        } else {
            echo "Incorrect password!";
        }

    } else {
        echo "Email not found!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
