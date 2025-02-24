<!DOCTYPE html>
<?php include("navbar.php")?>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtChat - Sohbetin Yeni Adresi</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>
    
    <div class="container">
        <header class="hero">
            <h1>AtChat ile Sohbeti Yeniden Keşfet</h1>
            <p>Hızlı, güvenli ve tamamen ücretsiz sohbet deneyimi.</p>
            <a href="home.php" class="btn">Hemen Başla</a>
        </header>
    </div>
    
    <section id="features" class="features">
        <h2>Öne Çıkan Özellikler</h2>
        <div class="feature-box">
            <div class="feature">Gerçek Zamanlı Mesajlaşma</div>
            <div class="feature">Grup Sohbetleri</div>
            <div class="feature">Gizlilik ve Güvenlik</div>
        </div>
    </section>
    
    <section id="about" class="about">
        <h2>AtChat Nedir?</h2>
        <p>AtChat, kullanıcılarına modern bir sohbet deneyimi sunan yenilikçi bir platformdur.</p>
    </section>
    
    <footer>
        <p>&copy; 2025 AtChat. Tüm Hakları Saklıdır.</p>
    </footer>
    
    <script>
        gsap.fromTo(".hero h1", {opacity: 0, y: -50}, {opacity: 1, y: 0, duration: 1});
        gsap.fromTo(".hero p", {opacity: 0, y: -20}, {opacity: 1, y: 0, duration: 1, delay: 0.5});
        gsap.fromTo(".btn", {opacity: 0, scale: 0.8}, {opacity: 1, scale: 1, duration: 1, delay: 1});
    </script>
</body>
</html>
