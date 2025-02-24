<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
$user_id = $_SESSION['user_id'];
$friend_id = isset($_GET['friend_id']) ? intval($_GET['friend_id']) : 0;

// Arkadaşın adını al
$friend_name = "";
if ($friend_id) {
    $friend_result = $conn->query("SELECT username FROM users WHERE id = $friend_id");
    $friend_name = $friend_result->fetch_assoc()['username'];
}

// AJAX isteği mi kontrol et
if (isset($_POST['action'])) {
    if ($_POST['action'] === "load_messages") {
        $result = $conn->query("SELECT m.message, u.username 
                                FROM messages m 
                                INNER JOIN users u ON u.id = m.sender_id 
                                WHERE (m.sender_id = $user_id AND m.receiver_id = $friend_id) 
                                OR (m.sender_id = $friend_id AND m.receiver_id = $user_id) 
                                ORDER BY m.timestamp ASC");

        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
        }
        exit();
    }

    if ($_POST['action'] === "send_message") {
        $message = $conn->real_escape_string($_POST['message']);
        $conn->query("INSERT INTO messages (sender_id, receiver_id, message) VALUES ($user_id, $friend_id, '$message')");
        echo "success";
        exit();
    }

    if ($_POST['action'] === "update_typing") {
        $is_typing = intval($_POST['is_typing']);
        $conn->query("UPDATE typing_status SET is_typing = $is_typing WHERE user_id = $user_id AND friend_id = $friend_id");
        exit();
    }

    if ($_POST['action'] === "check_typing") {
        $result = $conn->query("SELECT is_typing FROM typing_status WHERE user_id = $friend_id AND friend_id = $user_id");
        $row = $result->fetch_assoc();
        echo $row ? $row['is_typing'] : 0;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sohbet - <?php echo htmlspecialchars($friend_name); ?></title>
    <link rel="stylesheet" href="chat.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>

<!-- Navbar -->
<?php include("navbar.php")?>

<!-- Sohbet Kutusu -->
<div id="chat-box"></div>

<!-- Mesaj Gönderme Formu -->
<form id="message-form">
    <input type="text" id="message" name="message" placeholder="Mesajınızı yazın..." required>
    <button type="submit">Gönder</button>
</form>

<p id="typing-indicator" style="display: none; font-style: italic; color: gray;">Yazıyor...</p>

<script>
    let typingTimeout;
    let friendId = <?php echo $friend_id; ?>;
    let chatBox = document.getElementById("chat-box");

    function ajaxRequest(action, data, callback) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "chat.php?friend_id=" + friendId, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200 && callback) callback(xhr.responseText);
        };
        xhr.send("action=" + action + "&" + data);
    }

    function loadMessages() {
        ajaxRequest("load_messages", "", function(response) {
            chatBox.innerHTML = response;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    }

    document.getElementById("message-form").addEventListener("submit", function (e) {
        e.preventDefault();
        let message = document.getElementById("message").value;
        ajaxRequest("send_message", "message=" + encodeURIComponent(message), function(response) {
            if (response === "success") {
                document.getElementById("message").value = "";
                loadMessages();
                updateTypingStatus(0);
            }
        });
    });

    document.getElementById("message").addEventListener("keydown", function () {
        clearTimeout(typingTimeout);
        updateTypingStatus(1);
        typingTimeout = setTimeout(() => updateTypingStatus(0), 2000);
    });

    function updateTypingStatus(status) {
        ajaxRequest("update_typing", "is_typing=" + status, null);
    }

    function checkTypingStatus() {
        ajaxRequest("check_typing", "", function(response) {
            document.getElementById("typing-indicator").style.display = response == "1" ? "block" : "none";
        });
    }

    setInterval(loadMessages, 800);
    setInterval(checkTypingStatus, 900);
</script>

</body>
</html>