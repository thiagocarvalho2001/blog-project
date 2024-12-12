<?php 

echo "<link rel='stylesheet' href='style.css'>";

$stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts 
JOIN users ON posts.user_id = users.id");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Manage Posts</h1>";
foreach($posts as $post){
    echo "<p>
            Title: " . htmlspecialchars($post['title']) . " |
            Author: " . htmlspecialchars($post['username']) . "
            <a href='delete_post.php?id=" . $post['id'] . "'>Delete</a>
          </p>";
}

?>