<?php
session_start();
$username = $_SESSION['username'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
?>

<nav class="navbar">
    <!-- Sol Kısım -->
    <div class="nav-left">
        <a href="index.php">AtChat</a>
        <?php if ($username): ?>
            <span class="username"> | <?php echo htmlspecialchars($username); ?></span>
        <?php endif; ?>
    </div>

    <!-- Sağ Kısım -->
    <div class="nav-right">
        <?php if ($username): ?>
            <a href="contact.php">Uygulamayı İndir</a>
            <a href="home.php">Sohbet</a>
            <a href="logout.php">Çıkış Yap</a>
            <!-- Bildirimler -->
            <span class="notifications-toggle" onclick="toggleNotifications()">Bildirimler</span>
            <div id="notifications-container" class="hidden">
                <?php
                $conn = new mysqli('localhost', 'root', '', 'AtChat');
                $result = $conn->query("SELECT fr.id, fr.sender_id, u.username 
                                        FROM friend_requests fr
                                        INNER JOIN users u ON u.id = fr.sender_id
                                        WHERE fr.receiver_id = $user_id AND fr.status = 'pending'");
                
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $request_id = $row['id'];
                        $sender_name = htmlspecialchars($row['username']);
                        echo "
                        <div class='notification-item'>
                            <strong>$sender_name</strong> arkadaşlık isteği gönderdi.<br>
                            <button onclick=\"handleRequest($request_id, 'accept')\" class='btn-accept'>Kabul Et</button>
                            <button onclick=\"handleRequest($request_id, 'reject')\" class='btn-reject'>Reddet</button>
                        </div>";
                    }
                } else {
                    echo "<div class='notification-item'>Bildirim yok.</div>";
                }
                ?>
            </div>
        <?php else: ?>
            <a href="contact.php">Uygulamayı İndir</a>
            <a href="login.php">Giriş Yap</a>
        <?php endif; ?>
    </div>
</nav>

<script>
function toggleNotifications() {
    const container = document.getElementById('notifications-container');
    container.classList.toggle('visible');
    container.classList.toggle('hidden');
}

// İstekleri kabul veya reddet
function handleRequest(requestId, action) {
    fetch('handle_request.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({request_id: requestId, action: action})
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Hata:', error));
}
</script>