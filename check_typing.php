<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['friend_id'])) {
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
$friend_id = intval($_GET['friend_id']);

$result = $conn->query("SELECT is_typing FROM users WHERE id = $friend_id");
$row = $result->fetch_assoc();

echo $row['is_typing']; // 1 veya 0 döndür
?>