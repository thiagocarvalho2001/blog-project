<?php 

if(!isset($_GET['id'])) {
    echo "User not found.";
    exit;
}

$userId = $_GET['id'];

if($userId == $_GET['id']);

if($userId == $_SESSION['user_id']){
    echo "You are trying to delete your own account. Please contact the admin.";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);

echo "User deleted successfully.";
?>