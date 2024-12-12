<?php 

include 'db.php';
echo "<link rel='stylesheet' href='style.css'>";

echo "
    <form action='index.php' method='GET'>
        <input type='text' name='search' placeholder='Search posts...' required>
        <button type='submit'>Search</button>
    </form>
    ";

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = "%$searchQuery%";

$postsPerPage = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $postsPerPage;

define('DB_NAME', 'blogproject');

try {
    $stmt = $pdo->prepare("
        SELECT p.*, u.username 
        FROM " . DB_NAME . ".posts AS p
        JOIN " . DB_NAME . ".users AS u 
        ON p.user_id = u.id 
        WHERE p.title LIKE :searchQuery OR p.content LIKE :searchQuery
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

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
        " | " . htmlspecialchars($post['category']) . " | " . htmlspecialchars($post['created_at']) . "</p>";
        echo "<a href='view_post.php?id=" . $post['id'] . "'>Read More</a><hr>";
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) AS total_posts FROM " . DB_NAME . ".posts 
                       WHERE title LIKE :searchQuery OR content LIKE :searchQuery");
$stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
$stmt->execute();
$totalPosts = $stmt->fetch(PDO::FETCH_ASSOC)['total_posts'];
$totalPages = ceil($totalPosts / $postsPerPage);

echo "<div class='pagination'>";
for($i = 1; $i <= $totalPages; $i++){
    echo "<a href='index.php?page=$i'>" . ($i == $page ? "<strong>$i</strong>" : $i) . "</a> ";
}
echo "</div>";

?>