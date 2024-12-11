<?php 

session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    echo "You must be logged.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user) {
    echo "<h1>Welcome, " . htmlspecialchars($user['username']) . "</h1>";
    echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
} else {
    echo "Not found";
}
?>