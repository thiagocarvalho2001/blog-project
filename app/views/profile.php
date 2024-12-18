<?php 

session_start();
include '../models/db.php';
echo "<link rel='stylesheet' href='../../public/css/style.css'>";


if(!isset($_SESSION['user_id'])) {
    echo "You must be logged.";
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($user) {
    echo "<h1>Users</h1>";
    echo "<h1>Welcome, " . htmlspecialchars($user['username']) . "</h1>";
    echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
    echo "<p>Created at: " . htmlspecialchars($user['created_at']) . "</p>";
} else {
    echo "There are no registered users on this site.";
}

echo "<h2>Your posts:</h2>";
foreach($posts as $post){
    echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
    echo "<a href='view_post.php?id=" . $post['id'] . "'>Read More</a><hr>";
}


?>

<!DOCTYPE html>
<html lang="en">
    <title>PROFILE</title>