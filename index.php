<?php 

include 'db.php';

// acessing my db using different structure of queries because of the white screen
define('DB_NAME', 'blogproject');

try {
    $stmt = $pdo->prepare("
        SELECT p.*, u.username 
        FROM " . DB_NAME . ".posts AS p
        JOIN " . DB_NAME . ".users AS u ON p.user_id = u.id 
        ORDER BY p.created_at DESC
    ");
    
    $stmt->execute();
    
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if(empty($posts)){
    echo "No posts to show.";
}else{
    foreach($posts as $post){
        echo "<h2>" . htmlspecialchars($post['title']) . "</h2>";
        echo "<p>By " . htmlspecialchars($post['username']) . 
        " | " . htmlspecialchars($post['category']) . " | " . $post['created_at'] . "</p>";
        echo "<a href='view_post.php?id=" . $post['id'] . "'>Read More</a><hr>";
    }
}
?>