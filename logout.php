<?php
session_start();
session_destroy(); // Oturum bilgilerini yok eder
header("Location: login.php"); // Kullanıcıyı giriş sayfasına yönlendirir
exit();
?>
