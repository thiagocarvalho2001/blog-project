<?php

session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    echo "You must be logged to change your password.";
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $newPasswordConfirm = $_POST['new_password_confirm'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $newPasswordConfirm) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $hashedPassword, 'id' => $_SESSION['user_id']]);
            echo "Password changed.";
        } else {
            echo "Passwords do not match.";
        }
    } else {
        echo "Current password incorrect";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="change_pass.php" method="post">
        <input type="password" name="current_password" required> <br>
        <input type="password" name="new_password" required> <br>
        <input type="password" name="new_password_confirm" required> <br>
        <button type="submit">Change password.</button>
    </form>
</body>
</html>