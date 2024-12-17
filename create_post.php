<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = $_POST['category_id'];
        $userId = $_SESSION['user_id'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '/opt/lampp/htdocs/blog/uploads/'; 
            $imageName = basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $userId . '_' . time() . '_' . $imageName;
            

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $stmt = $pdo->prepare("INSERT INTO " . DB_NAME . ".posts 
                (user_id, title, image, content, category_id, created_at)
                VALUES (:user_id, :title, :image, :content, :category_id, NOW())");
                $stmt->execute([
                    'user_id' => $userId,
                    'title' => $title,
                    'image' => $imageName, 
                    'content' => $content,
                    'category_id' => $category_id
                ]);

                echo "Post created successfully";
            } else {
                echo "Error uploading the image.";
            }
        } else {
            echo "No image uploaded or there was an upload error.";
        }
    }
} catch (PDOException $e) {
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
    <form action="create_post.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" required> <br>
        <input type="text" name="content" required> <br>
        <label for="imageUpload">Choose an image to upload:</label>
        <input type="file" name="image" accept="image/*" required> <br>
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <?php foreach ($category as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Create</button>
    </form>
</body>
</html>