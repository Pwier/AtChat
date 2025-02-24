<?php
session_start();

// Admin şifresi
$admin_password = "61AHK021";

// Giriş kontrolü
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged'] = true;
        } else {
            echo "<script>alert('Yanlış şifre!');</script>";
            header("Location: home.php");
            
        }
    } else {
        echo '<form method="POST">
                <label>Admin Şifresi:</label>
                <input type="password" name="password" required>
                <button type="submit">Giriş Yap</button>
              </form>';
        exit();
    }
}

// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'AtChat');
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Kullanıcı silme
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    $conn->query("DELETE FROM users WHERE id = $user_id");
    echo "<script>alert('Kullanıcı silindi!'); window.location='admin.php';</script>";
}

// Grup silme
if (isset($_GET['delete_group'])) {
    $group_id = intval($_GET['delete_group']);
    $conn->query("DELETE FROM groups WHERE id = $group_id");
    echo "<script>alert('Grup silindi!'); window.location='admin.php';</script>";
}

// Kullanıcıları çek
$users = $conn->query("SELECT id, username, password FROM users");

// Grupları çek
$groups = $conn->query("SELECT id, name FROM groups");
?><!DOCTYPE html><html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <h2>Kullanıcı Listesi</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>İsim</th>
            <th>Şifre</th>
            <th>İşlem</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['password']) ?></td>
                <td><a href="admin.php?delete_user=<?= $user['id'] ?>" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a></td>
            </tr>
        <?php endwhile; ?>
    </table><h2>Grup Listesi</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Grup Adı</th>
        <th>İşlem</th>
    </tr>
    <?php while ($group = $groups->fetch_assoc()): ?>
        <tr>
            <td><?= $group['id'] ?></td>
            <td><?= htmlspecialchars($group['name']) ?></td>
            <td><a href="admin.php?delete_group=<?= $group['id'] ?>" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>