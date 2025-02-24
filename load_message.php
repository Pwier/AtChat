<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit();
}

if (!isset($_GET['friend_id'])) {
    exit();
}

$friend_id = intval($_GET['friend_id']);
$conn = new mysqli('localhost', 'root', '', 'whatsapp_clone');
$user_id = $_SESSION['user_id'];

// Mesajları al
$messages = [];
$result = $conn->query("SELECT m.message, m.timestamp, u.username, m.sender_id
                        FROM messages m
                        INNER JOIN users u ON u.id = m.sender_id
                        WHERE (m.sender_id = $user_id AND m.receiver_id = $friend_id)
                        OR (m.sender_id = $friend_id AND m.receiver_id = $user_id)
                        ORDER BY m.timestamp ASC");

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Mesajları HTML olarak döndür
foreach ($messages as $message) {
    $is_sender = ($message['sender_id'] == $user_id) ? "sender" : "receiver";
    echo "<p class='$is_sender'><strong>" . htmlspecialchars($message['username']) . ":</strong> " . htmlspecialchars($message['message']) . "</p>";
}
?>