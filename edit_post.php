<?php

session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    echo "Login in first.";
    exit;
}

if(!isset($_GET['id'])) {
    echo "Post not found.";
    exit;
}

$postId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM blogproject.posts 
WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $postId, 'user_id' => $_SESSION['user_id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$post){
    echo "Post not found or you don't have permission to edit this post.";
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $stmt = $pdo->prepare("UPDATE blogproject.posts SET title = :title, content = :content
    category = :category, update_at = NOW() WHERE id = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'category' => $category,
    'id' => $postId]);

    echo "Post edited with sucessfull";
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
    <form action="edit_post.php?id=<?php echo $postId; ?>" method="post">
        <input type="text" name="title" 
        value="<?php echo htmlspecialchars($post['title']);?>" required> <br>
        <textarea name="content" required>
            <?php echo htmlspecialchars($post['content']); ?></textarea><br>
        <input type="text" name="category" 
            value="<?php echo htmlspecialchars($post['category']); ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>