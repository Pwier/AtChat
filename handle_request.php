<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum açık değil.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$request_id = $data['request_id'];
$action = $data['action'];

$conn = new mysqli('localhost', 'root', '', 'AtChat');

if ($action === 'accept') {
    // İsteği kabul et
    $stmt = $conn->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Arkadaşlığı ekle
    $stmt = $conn->prepare("SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    if ($request) {
        $sender_id = $request['sender_id'];
        $receiver_id = $request['receiver_id'];

        // Her iki kullanıcıyı da arkadaş olarak kaydet
        $conn->query("INSERT INTO friends (user_id, friend_id) VALUES ($sender_id, $receiver_id), ($receiver_id, $sender_id)");
    }

    echo json_encode(['status' => 'success', 'message' => 'Arkadaşlık isteği kabul edildi.']);
} elseif ($action === 'reject') {
    // İsteği reddet
    $stmt = $conn->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Arkadaşlık isteği reddedildi.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz işlem.']);
}
?>