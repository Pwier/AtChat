<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit();
}

if (!isset($_POST['friend_id']) || !isset($_POST['message'])) {
    exit();
}

$friend_id = intval($_POST['friend_id']);
$message = trim($_POST['message']);
$conn = new mysqli('localhost', 'root', '', 'AtChat');
$user_id = $_SESSION['user_id'];

// Mesajı veritabanına kaydet
if ($message !== "") {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $friend_id, $message);
    $stmt->execute();
}
?>