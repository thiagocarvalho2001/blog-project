<?php
include 'db.php';

try {
    $stmt = $pdo->query("SELECT DATABASE()");
    $currentDatabase = $stmt->fetchColumn();
    echo "Connected to database: " . $currentDatabase;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>