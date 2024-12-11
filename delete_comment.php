<?php 

session_start();
include 'db.php'; // Ensure this file contains the correct database connection

define('DB_NAME', 'blogproject');

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a comment.");
}

if (!isset($_GET['id'])) {
    die("Comment ID is missing.");
}

$commentId = $_GET['id'];

// Debugging: Output the comment ID
echo "Comment ID to delete: " . htmlspecialchars($commentId) . "<br>";

// Check if the comment exists and belongs to the user
$stmt = $pdo->prepare("SELECT * FROM comments WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $commentId, 'user_id' => $_SESSION['user_id']]);

if ($stmt->rowCount() === 0) {
    die("Comment does not exist or you do not have permission to delete it.");
}

// Proceed to delete the comment
$deleteStmt = $pdo->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
$deleteStmt->execute(['id' => $commentId, 'user_id' => $_SESSION['user_id']]);

if ($deleteStmt->rowCount() > 0) {
    echo "Comment was successfully deleted.";
} else {
    echo "Failed to delete the comment. Please try again.";
}

?>