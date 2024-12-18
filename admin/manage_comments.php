<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin_check.php';
include '../app/models/db.php';

echo "<!DOCTYPE html>";
echo "<link rel='stylesheet' href='../public/css/style.css'>";


$stmt = $pdo->prepare("SELECT comments.*, users.username AS 
                       commenter, posts.title AS post_title
                       FROM " . DB_NAME . ".comments 
                       JOIN " . DB_NAME . ".users ON comments.user_id = user_id
                       JOIN " . DB_NAME . ".posts ON comments.post_id = posts.id");

$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Manage Comments</h1>";
foreach($comments as $comment) {
    echo "<p>
            Comment: " . htmlspecialchars($comment['comment']) . " |
            By: " . htmlspecialchars($comment['commenter']) . " |
            On: " . htmlspecialchars($comment['post_title']) . " 
            <a href='delete_comment.php?id=" . $comment['id'] . "'>Delete</a>
          </p>";
}

?>
<title>MANAGE COMMENTS</title>