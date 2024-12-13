<?php 
session_start(); 

include 'db.php';
define('DB_NAME', 'blogproject');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];

    $stmt = $pdo->prepare("SELECT * FROM " . DB_NAME . ".users WHERE username = :input OR email = :input");
    
    if ($stmt->execute(['input' => $usernameOrEmail])) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['reset_user_id'] = $user['id'];
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "User  not found";
        }
    } else {
        $error = "Database query failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <form method="post">
        <label for="username_or_email">Enter username or email:</label>
        <input type="text" name="username_or_email" id="username_or_email" required>
        <button type="submit">Send</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>