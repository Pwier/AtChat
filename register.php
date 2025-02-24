<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Şifre eşleşme kontrolü
    if ($password !== $password_confirm) {
        echo "<script>alert('Hata: Şifreler eşleşmiyor!');</script>";
    } else {
        // Veritabanı bağlantısı
        $conn = new mysqli('localhost', 'root', '', 'AtChat');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Kullanıcı adının var olup olmadığını kontrol et
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Kullanıcı adı zaten mevcut
            echo "<script>alert('Hata: Bu kullanıcı adı zaten kullanılıyor!');</script>";
        } else {
            // Şifreyi hashleyin ve kullanıcıyı kaydedin
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert_stmt->bind_param("ss", $username, $password);
            
            if ($insert_stmt->execute()) {
                echo "<script>alert('Kayıt başarılı! Giriş yapabilirsiniz.');</script>";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('Kayıt başarısız!');</script>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
    <script>
        // Şifreleri anlık doğrulamak için
        function checkPasswords() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const message = document.getElementById('password-error');

            if (password !== confirm) {
                message.textContent = '❌ Şifreler eşleşmiyor!';
                message.style.color = 'red';
            } else {
                message.textContent = '✅ Şifreler eşleşiyor.';
                message.style.color = 'green';
            }
        }
    </script>
</head>
<body>
    <?php include("navbar.php")?>

    <form method="POST" action="" onsubmit="return validatePasswords()">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Parola:</label>
        <input type="password" id="password" name="password" required onkeyup="checkPasswords()"><br><br>

        <label for="password_confirm">Parola (Tekrar):</label>
        <input type="password" id="password_confirm" name="password_confirm" required onkeyup="checkPasswords()">
        <p id="password-error" style="margin-top:5px;"></p><br>

        <button type="submit">Kayıt Ol</button>
        <p>Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
    </form>

    <script>
        // Form gönderilmeden önce son kontrol
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;

            if (password !== confirm) {
                alert('Hata: Şifreler eşleşmiyor!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>