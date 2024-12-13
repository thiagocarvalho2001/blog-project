<?php

session_start();
include 'db.php';
echo "<link rel='stylesheet' href='style.css'>";

define('DB_NAME', 'blogproject');

$stmt = $pdo->prepare("SELECT * FROM " . DB_NAME . ".category ORDER BY name ASC");
$stmt->execute();
$category = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!isset($_SESSION['user_id'])) {
    echo "Login before to create a post.";
    exit;
}

try{
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = $_POST['category_id'];
        $userId = $_SESSION['user_id'];

        $stmt = $pdo->prepare("INSERT INTO " . DB_NAME . ".posts 
        (user_id, title, content, category_id, created_at)
        VALUES (:user_id, :title, :content, :category_id, NOW())");
        $stmt->execute(['user_id' => $userId, 'title' => $title, 'content' => $content,
        'category_id' => $category_id ]);

        echo "Post created successfully";
    }
} catch (PDOException $e){
    echo "Error: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="create_post.php" method="POST">
        <input type="text" name="title" required> <br>
        <input type="text" name="content" required> <br>
        <label for="category_id">Category:
        <select name="category_id" id="category_id" required>
            <?php foreach ($category as $cat ): ?>
                <option value=" <?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Create</button>
    </form>
</body>
</html>