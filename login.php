<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Veritabanı bağlantısı
    $conn = new mysqli('localhost', 'root', '', 'AtChat');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Kullanıcıyı sorgula
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['password']) { // Şifreyi doğrudan karşılaştır
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // Kullanıcı adını da oturuma ekledik
        header("Location: home.php");
        exit();
    } else {
        echo "<p>Giriş başarısız! <a href='login.php'>Tekrar deneyin</a></p>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include("navbar.php")?>
    <form method="POST" action="">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Parola:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Giriş Yap</button>
        <p>Hesabın yok mu?<a href="register.php">Kayıt Ol</a></p>
    </form>
</body>
</html>
