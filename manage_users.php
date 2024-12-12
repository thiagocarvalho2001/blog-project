<?php 

include 'admin_check.php';
echo "<link rel='stylesheet' href='style.css'>";

$stmt = $pdo->prepare("SELECT id, username, email, is_admin FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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