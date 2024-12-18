<?php

session_start();

include '../models/db.php';
echo "<link rel='stylesheet' href='../../public/css/style.css'>";

if(!isset($_SESSION['user_id'])) {
    echo "You must be logged in to edit a comment";
    exit;
}

if(!isset($_GET['id'])) {
    echo "No comment here.";
    exit;
}

$commentId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM blogproject.comments 
WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $commentId, 'user_id' => $_SESSION['user_id']]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$comment){
    echo "Comment not found or you don't have permission to edit it.";
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedComment = $_POST['comment'];

    $stmt = $pdo->prepare("UPDATE blogproject.comments 
    SET comment = :comment WHERE id = :id");
    $stmt->execute(['comment' => $updatedComment, 'id' => $commentId]);

    echo "Comment edited.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT COMMENT</title>
</head>
<body>
    <form action="edit_comment.php?id=<?php echo $commentId; ?>" method="post">
        <textarea name="comment" required>
            <?php echo htmlspecialchars($comment['comment']) ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>