<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
$user_id = $_SESSION['user_id'];

// Kullanıcının arkadaşlarını çek
$friends = [];
$result = $conn->query("SELECT u.id, u.username FROM users u INNER JOIN friends f ON u.id = f.friend_id WHERE f.user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $friends[] = $row;
}

// Kullanıcının içinde olduğu grupları çek
$groups = [];
$result = $conn->query("SELECT id, name FROM groups g 
                        INNER JOIN group_members gm ON g.id = gm.group_id 
                        WHERE gm.user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="navbar.css">
    <script>
        // Arkadaşlar için yönlendirme fonksiyonu
        function goToChat(friend_id) {
            window.location.href = 'chat.php?friend_id=' + friend_id;
        }

        // Gruplar için yönlendirme fonksiyonu
        function goToGroupChat(group_id) {
            window.location.href = 'group_chat.php?group_id=' + group_id;
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Üst Menü -->
    <?php include("navbar.php")?>

    <!-- Arkadaşlar ve Gruplar Listesi -->
    <div class="friend-list">
        <h2>Sohbetler</h2>
        <?php if (count($friends) == 0 && count($groups) == 0): ?>
            <p>Henüz sohbetiniz yok. <a href="add_friend.php">Arkadaş ekleyin</a> veya <a href="add_friend.php">Grup oluşturun</a>.</p>
        <?php else: ?>
            <ul>
                <!-- Arkadaşları Listele -->
                <?php foreach ($friends as $friend): ?>
                    <li>
                        <div onclick="goToChat(<?php echo $friend['id']; ?>)" class="friend-info" style="cursor: pointer;">
                            <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Profil Resmi">
                            <span><?php echo $friend['username']; ?></span>
                        </div>
                        <div class="last-message">Son mesaj...</div>
                    </li>
                <?php endforeach; ?>

                <!-- Grupları Listele -->
                <?php foreach ($groups as $group): ?>
                    <li>
                        <div onclick="goToGroupChat(<?php echo $group['id']; ?>)" class="friend-info" style="cursor: pointer;">
                            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Grup Resmi">
                            <span><?php echo $group['name']; ?></span>
                        </div>
                        <div class="last-message">Son mesaj...</div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Yeni Sohbet Butonu -->
    <div class="new-chat-btn">
        <a href="add_friend.php">
            <span>+</span>
        </a>
    </div>

</div>

</body>
</html>