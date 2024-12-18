<?php 

$host = "localhost";
$dbname = "blogproject";
$username = "root";
$password = "";

// Connection to my DB or Error
try {
    $pdo = new PDO("mysql:$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>