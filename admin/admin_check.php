<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../app/models/db.php';

session_start();

define('DB_NAME', 'blogproject');

$userId = $_SESSION['user_id'];

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT is_admin FROM " . DB_NAME . ".users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user || $user['is_admin'] != 1){
    echo "Admins-only page.";
    exit;
}else{
    echo "Welcome, admin!";
}

?>

<!DOCTYPE html>
<html lang="en">
    <title>ADM CHECK</title>
