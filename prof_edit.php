<?php

session_start();
include 'db.php';
echo "<link rel='stylesheet' href='style.css'>";

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged to edit your profile.";
    exit;
}

if($_SERVER['REQUEST_METHOD']== 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users
     WHERE (email = :email or username = :username) and id != :id");
    $stmt->execute(['username' => $username, 
    'email' => $email, 'id' => $_SESSION['user_id']]);
    if($stmt->rowCount() > 0) {
        echo "Username or email is already taken.";
    } else {
        $stmt = $pdo->prepare('UPTADE user SET username = :username, email = :email
        WHERE id = :id');
        $stmt->execute(['username' => $username, 
        'email' => $email, 'id' => $_SESSION['user_id']]);
        echo 'Profile updated!';
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="prof_edit.php" method="post">
        <input type="text" name="username" 
        value="<?php echo htmlspecialchars($user['username']); ?>" required> <br>
        <input type="text" name="email" 
        value="<?php echo htmlspecialchars($user['email']); ?>" required> <br>
        <button type="submit">Update</button>
    </form>
</body>
</html>