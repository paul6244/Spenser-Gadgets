<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Check if email exists
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "Email already exists!";
        exit;
    }

    mysqli_stmt_close($check);

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.html");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
