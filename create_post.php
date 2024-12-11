<?php

session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    echo "Login before to create a post.";
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO blogproject.posts 
    (user_id, title, content, category, created_at)
    VALUES (:user_id, :title, :content, :category, NOW())");
    $stmt->execute(['user_id' => $userId, 'title' => $title, 'content' => $content,
    'category' => $category]);

    echo "Post created successfully";
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
        <input type="text" name="category" required> <br>
        <input type="text" name="content" required> <br>
        <button type="submit">Create</button>
    </form>
</body>
</html>