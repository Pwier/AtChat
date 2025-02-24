
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friend_name = $_POST['friend_name'];
    $conn = new mysqli('localhost', 'root', '', 'AtChat');
    $user_id = $_SESSION['user_id'];

    // Kullanıcı ID'sini al
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $friend_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $friend = $result->fetch_assoc();

    if ($friend) {
        $friend_id = $friend['id'];
        
        // Kullanıcı kendine istek atamaz
        if ($friend_id == $user_id) {
            echo "Kendinize arkadaşlık isteği gönderemezsiniz.";
            exit();
        }

        // Zaten arkadaşlar mı?
        $check_friends = $conn->query("SELECT * FROM friends WHERE user_id = $user_id AND friend_id = $friend_id");
        if ($check_friends->num_rows > 0) {
            echo "Zaten arkadaşsınız.";
            exit();
        }

        // Daha önce istek gönderilmiş mi?
        $check_request = $conn->query("SELECT * FROM friend_requests WHERE sender_id = $user_id AND receiver_id = $friend_id AND status = 'pending'");
        if ($check_request->num_rows > 0) {
            echo "Zaten bekleyen bir isteğiniz var.";
            exit();
        }

        // İstek gönder
        $insert = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $friend_id);
        if ($insert->execute()) {
            echo "Arkadaşlık isteği gönderildi.";
        } else {
            echo "Bir hata oluştu.";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>
<?php include("navbar.php")?>
<link rel="stylesheet" href="add_friend.css">
<link rel="stylesheet" href="navbar.css">
<form method="POST" action="">
    <label for="friend_name">Arkadaş Adı:</label>
    <input type="text" id="friend_name" name="friend_name" required><br><br>
    <button type="submit">Arkadaş Ekle</button>
</form>

<!-- Grup Oluşturma Formu -->
<form method="POST" action="create_group.php">
    <h3>Grup Oluştur</h3>
    <label for="group_name">Grup Adı:</label>
    <input type="text" id="group_name" name="group_name" required><br><br>

    <label for="members">Grup Üyeleri:</label><br>
    <select name="members[]" multiple required>
        <?php
        // Kullanıcının arkadaşlarını çekelim
        $conn = new mysqli('localhost', 'root', '', 'AtChat');
        $user_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT u.id, u.username FROM users u 
                                JOIN friends f ON u.id = f.friend_id 
                                WHERE f.user_id = $user_id");
        
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['username']}</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Grubu Oluştur</button>
</form>