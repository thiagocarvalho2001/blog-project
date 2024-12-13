<?php 

include 'db.php';
session_start();

define('DB_NAME', 'blogproject');

if(!isset($_SESSION['reset_user_id'])){
    header('location: forgot_password.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE " . DB_NAME . ".users SET password = :password WHERE id = :id");
    $stmt->execute(['password' => $newPassword, 'id' => $_SESSION['reset_user_id']]);

    unset($_SESSION['reset_user_id']);
    echo "You changed your password. Log in again.";
    header('Location:login.php');
    exit;
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
    <form method="post">
        <label for="password">Enter new password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Send</button>
    </form>
</body>
</html>