<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['group_id'])) {
    die("Hata: group_id parametresi eksik!");
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
$group_id = intval($_GET['group_id']);
$user_id = $_SESSION['user_id'];

// AJAX ile mesajları çekme
if (isset($_POST['fetch_messages'])) {
    $query = "SELECT gm.message, gm.timestamp, u.username 
              FROM group_messages gm 
              INNER JOIN users u ON u.id = gm.sender_id 
              WHERE gm.group_id = $group_id 
              ORDER BY gm.timestamp ASC";

    $messages = $conn->query($query);
    while ($row = $messages->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
    }
    exit(); // HTML kodunun yüklenmesini engelle
}

// AJAX ile mesaj gönderme
if (isset($_POST['send_message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO group_messages (group_id, sender_id, message) VALUES ($group_id, $user_id, '$message')");
    exit(); // HTML kodunun yüklenmesini engelle
}

// Grubun adını al
$group_result = $conn->query("SELECT name FROM groups WHERE id = $group_id");
$group_name = $group_result->fetch_assoc()['name'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grup Sohbet - <?php echo htmlspecialchars($group_name); ?></title>
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
    <input type="text" id="message-input" placeholder="Mesajınızı yazın..." required>
    <button type="submit">Gönder</button>
</form>

<script>
    let groupId = <?php echo $group_id; ?>;
    
    function fetchMessages() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "group_chat.php?group_id=" + groupId, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status == 200) {
                document.getElementById("chat-box").innerHTML = xhr.responseText;
            }
        };
        xhr.send("fetch_messages=1");
    }

    document.getElementById("message-form").addEventListener("submit", function(e) {
        e.preventDefault();
        let message = document.getElementById("message-input").value;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "group_chat.php?group_id=" + groupId, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status == 200) {
                document.getElementById("message-input").value = "";
                fetchMessages(); // Yeni mesajı hemen al
            }
        };
        xhr.send("send_message=1&message=" + encodeURIComponent(message));
    });

    setInterval(fetchMessages, 900); // 900ms'de bir mesajları yenile

    // İlk yüklemede mesajları getir
    fetchMessages();
</script>

</body>
</html>