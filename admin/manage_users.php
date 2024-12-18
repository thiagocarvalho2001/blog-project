<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin_check.php';

echo "<link rel='stylesheet' href='../public/css/style.css'>";

$stmt = $pdo->prepare("SELECT id, username, email, is_admin FROM " . DB_NAME . ".users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<!DOCTYPE html>";
echo "<h1>Manage Users:</h1>";

foreach ($users as $user) {
    echo "<p>
            Username: " . htmlspecialchars($user['username']) . " |
            Email: " . htmlspecialchars($user['email']) . " |
            Role: " . ($user['is_admin'] ? 'Admin' : 'User') . " 
            <a href='delete_user.php?id=" . $user['id'] . "'>Delete</a>
         </p>";
}
?>