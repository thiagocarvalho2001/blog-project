<?php 

include '../app/models/db.php';
echo "<link rel='stylesheet' href='../public/css/style.css'>";

session_start();

if (!isset($_GET['id'])) {
    echo 'No posts here. ';
    exit;
}

$postId = $_GET['id'];

define('DB_NAME', 'blogproject');

try {
    $stmt = $pdo->prepare("SELECT p.*, u.username 
        FROM " . DB_NAME . ".posts AS p
        JOIN " . DB_NAME . ".users AS u
        ON p.user_id = u.id
        WHERE p.id = :id
    ");

    $stmt->execute(['id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($post) {
    echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
    echo "<p>By " . htmlspecialchars($post['username']) . 
    " | " . htmlspecialchars($post['category']) . " | " . $post['created_at'] . "</p>";
    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
} else {
    echo "Not found.";
}

if (isset($_SESSION['user_id'])) {
    echo '<form method="POST" action="">
            <textarea name="comment" placeholder="Write your comment." required></textarea>
            <button type="submit">Submit Comment</button>
          </form>';
} else {
    echo "<p>You must be logged in to comment.</p>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO " . DB_NAME . ".comments (post_id, user_id, comment, created_at) 
    VALUES (:post_id, :user_id, :comment, NOW())");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId, 'comment' => $comment]);

    header("Location: view_post.php?id=$postId");
    exit;
}

$stmt = $pdo->prepare("SELECT c.*, u.username 
    FROM " . DB_NAME . ".comments AS c
    JOIN " . DB_NAME . ".users AS u ON c.user_id = u.id
    WHERE c.post_id = :post_id
    ORDER BY c.created_at DESC");
$stmt->execute(['post_id' => $postId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Comments:</h3>";

foreach ($comments as $comment) {
    echo "<p><strong>" . htmlspecialchars($comment['username']) . ":</strong> "
        . nl2br(htmlspecialchars($comment['comment'])) . " <em>(" . $comment['created_at']
        . ")</em></p>";
}

?>

<!DOCTYPE html>
<html lang="en">
    <title>POST VIEW</title>
    <link rel='stylesheet' href='public/css/style.css'>