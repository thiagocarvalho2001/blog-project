<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'admin_check.php';

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";

echo "<link rel='stylesheet' href='style.css'>";

echo "<h1>Admin Painel</h1>";

echo "<ul>
        <li><a href='manage_users.php'> See Users</a></li>
        <li><a href='manage_posts.php'> See Posts</a></li>
        <li><a href='manage_comments.php'> See Comments</a></li>
      </ul>  
     ";
    
?>

