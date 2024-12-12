<?php 

session_start();

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user || $user['is_admin'] != 1){
    echo "Admins-only page.";
    exit;
}

?>