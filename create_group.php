<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Giriş yapmalısınız.");
}

$conn = new mysqli('localhost', 'root', '', 'AtChat');
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = $_POST['group_name'];
    $members = $_POST['members']; // Seçilen üyeler (array)
    $creator_id = $_SESSION['user_id'];

    // Grubu oluştur
    $stmt = $conn->prepare("INSERT INTO groups (name, creator_id) VALUES (?, ?)");
    $stmt->bind_param("si", $group_name, $creator_id);
    $stmt->execute();
    $group_id = $stmt->insert_id; // Yeni oluşturulan grup ID'si

    // Gruba kurucuyu ekle
    $conn->query("INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $creator_id)");

    // Seçilen üyeleri gruba ekle
    foreach ($members as $member_id) {
        $conn->query("INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $member_id)");
    }

    echo "Grup başarıyla oluşturuldu!";
    header("Location: home.php"); // Ana sayfaya yönlendir
}
?>