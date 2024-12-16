<?php 
include 'db.php';
echo "<link rel='stylesheet' href='style.css'>";

define('DB_NAME', 'blogproject');

echo "
    <form action='index.php' method='GET'>
        <input type='text' name='search' placeholder='Search posts...' required>
        <button type='submit'>Search</button>
    </form>
";

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = "%$searchQuery%";

$postsPerPage = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $postsPerPage;

try {
    $stmt = $pdo->prepare("SELECT * FROM " . DB_NAME . ".category ORDER BY name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT " . DB_NAME . ".posts.*, category.name AS category_name, users.username
              FROM " . DB_NAME . ".posts
              JOIN " . DB_NAME . ".category ON posts.category_id = category.id
              JOIN " . DB_NAME . ".users ON posts.user_id = users.id";

    $conditions = [];
    if ($category_id) {
        $conditions[] = "posts.category_id = :category_id";
    }
    if ($searchQuery) {
        $conditions[] = "(posts.title LIKE :searchQuery OR posts.content LIKE :searchQuery)";
    }
    if ($conditions) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $query .= " ORDER BY posts.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
    if ($category_id) {
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    }
    if ($searchQuery) {
        $stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countQuery = "SELECT COUNT(*) AS total_posts FROM " . DB_NAME . ".posts";
    if ($conditions) {
        $countQuery .= " WHERE " . implode(' AND ', $conditions);
    }
    $countStmt = $pdo->prepare($countQuery);
    if ($category_id) {
        $countStmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    }
    if ($searchQuery) {
        $countStmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalPosts = $countStmt->fetch(PDO::FETCH_ASSOC)['total_posts'];
    $totalPages = ceil($totalPosts / $postsPerPage);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
</head>
<body>
    <h1>Posts</h1>
    <div>
        <a href="index.php">All categories</a>
        <?php foreach ($categories as $category): ?>
            <a href="index.php?category_id=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($posts)): ?>
        <p>No posts to show.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p>Category: <?= htmlspecialchars($post['category_name']) ?> 
            | By: <?= htmlspecialchars($post['username']) ?> | 
            <?= htmlspecialchars($post['created_at']) ?></p>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <a href="view_post?id=<?= $post['id'] ?>">Read More</a>
        <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>