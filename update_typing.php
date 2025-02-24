<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['friend_id'])) {
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
$user_id = $_SESSION['user_id'];
$friend_id = intval($_POST['friend_id']);
$is_typing = intval($_POST['is_typing']); // 1 veya 0

$conn->query("UPDATE users SET is_typing = $is_typing WHERE id = $user_id");
?>