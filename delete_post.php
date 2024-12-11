<?php 

session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    echo "Login First to delete a post.";
    exit;
}

if(!isset($_GET['id'])) {
    echo "Post not found.";
    exit;
}

$postId = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM blogproject.posts 
WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $postId, 'user_id' => $_SESSION['user_id']]);

if($stmt->rowCount() > 0){
    echo "Post deleted.";
} else {
    echo "Post not found or you don't have permission to delete this post.";
}

?>