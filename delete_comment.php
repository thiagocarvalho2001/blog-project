<?php 

session_start();
include 'db.php';

define('DB_NAME', 'blogproject');

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a comment.");
}

if (!isset($_GET['id'])) {
    die("Comment ID is missing.");
}

$commentId = $_GET['id'];

echo "Comment ID to delete: " . htmlspecialchars($commentId) . "<br>";

$stmt = $pdo->prepare("SELECT * FROM " . DB_NAME . ".comments WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $commentId, 'user_id' => $_SESSION['user_id']]);

if ($stmt->rowCount() === 0) {
    die("Comment does not exist or you do not have permission to delete it.");
}

$deleteStmt = $pdo->prepare("DELETE FROM " . DB_NAME . ".comments WHERE id = :id AND user_id = :user_id");
$deleteStmt->execute(['id' => $commentId, 'user_id' => $_SESSION['user_id']]);

if ($deleteStmt->rowCount() > 0) {
    echo "Comment was successfully deleted.";
} else {
    echo "Failed to delete the comment. Please try again.";
}

?>