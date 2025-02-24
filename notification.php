<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Yetkisiz erişim!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'AtChat');
    $id = $_POST['id'];
    $action = $_POST['action'];

    // İsteği güncelle
    $stmt = $conn->prepare("UPDATE friend_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);
    $stmt->execute();

    // Eğer kabul edildiyse arkadaşlık ekle
    if ($action === 'accepted') {
        // İlgili kullanıcıları bul
        $request = $conn->query("SELECT sender_id, receiver_id FROM friend_requests WHERE id = $id")->fetch_assoc();
        $sender_id = $request['sender_id'];
        $receiver_id = $request['receiver_id'];

        // Arkadaşlıkları ekle
        $conn->query("INSERT INTO friends (user_id, friend_id) VALUES ($sender_id, $receiver_id), ($receiver_id, $sender_id)");
    }

    echo "İşlem tamamlandı.";
}
?>