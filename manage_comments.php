<?php 

include 'admin_check.php';
echo "<link rel='stylesheet' href='style.css'>";


$stmt = $pdo->prepare("SELECT comments.*, users.username AS 
                       commenter, post.title AS post_title
                       FROM comments
                       JOIN users ON comments.user_id = user_id
                       JOIN posts ON commetnts.post_id = posts.id");

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