<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

define('DB_NAME', 'blogproject');

function getCount($pdo, $table) {
  $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM " . DB_NAME . ".$table");
  $stmt->execute();
  $result = $stmt->fetch();
  return $result['total'];
}

$totalPosts = getCount($pdo, 'posts');
$totalUsers = getCount($pdo, 'users');
$totalComments = getCount($pdo, 'comments');

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";

echo "<link rel='stylesheet' href='style.css'>";

echo "<h1>Admin Painel</h1>";
echo "<div class='stats-container'>";
echo "<div class='stats-card'>
        <h2 class='total-posts'> Total Posts: $totalPosts </h2>
      </div>";

echo "<div class='stats-card'>
        <h2>Total Users: $totalUsers </h2>
      </div>";

echo "<div class='stats-card'>
      <h2>Total Comments: $totalComments</h2>
    </div>";
echo "</div>";

echo "<ul>
        <li><a href='manage_users.php'> See Users</a></li>
        <li><a href='manage_posts.php'> See Posts</a></li>
        <li><a href='manage_comments.php'> See Comments</a></li>
      </ul>  
     ";
    
?>

