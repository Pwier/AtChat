-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 24 Şub 2025, 11:22:40
-- Sunucu sürümü: 5.7.34
-- PHP Sürümü: 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `AtChat`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`) VALUES
(26, 10, 10),
(25, 10, 10),
(24, 10, 8),
(23, 8, 10),
(22, 8, 6),
(21, 6, 8),
(20, 8, 7),
(19, 7, 8),
(18, 7, 6),
(17, 6, 7),
(27, 11, 10),
(28, 10, 11),
(29, 13, 12),
(30, 12, 13);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(5, 7, 8, 'accepted', '2025-02-19 06:43:16'),
(4, 6, 7, 'accepted', '2025-02-18 17:56:03'),
(6, 6, 8, 'accepted', '2025-02-19 06:43:37'),
(7, 8, 10, 'accepted', '2025-02-20 20:03:55'),
(8, 10, 10, 'accepted', '2025-02-20 20:04:21'),
(9, 11, 10, 'accepted', '2025-02-20 20:09:02'),
(10, 13, 12, 'accepted', '2025-02-21 08:31:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `groups`
--

INSERT INTO `groups` (`id`, `name`, `creator_id`, `created_at`) VALUES
(2, 'Aaa', 13, '2025-02-24 10:03:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `group_members`
--

CREATE TABLE `group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `group_members`
--

INSERT INTO `group_members` (`group_id`, `user_id`) VALUES
(1, 6),
(1, 7),
(1, 8),
(2, 12),
(2, 13);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `group_messages`
--

CREATE TABLE `group_messages` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `group_messages`
--

INSERT INTO `group_messages` (`id`, `group_id`, `sender_id`, `message`, `sent_at`, `timestamp`) VALUES
(12, 2, 13, 'Saaa', '2025-02-24 10:03:52', '2025-02-24 10:03:52'),
(11, 1, 8, 'As', '2025-02-19 19:03:50', '2025-02-19 19:03:50'),
(10, 1, 6, 'As', '2025-02-19 19:03:38', '2025-02-19 19:03:38'),
(9, 1, 7, 'Sa', '2025-02-19 19:02:30', '2025-02-19 19:02:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(16, 6, 8, 'Açals', '2025-02-19 06:46:01'),
(15, 6, 8, 'Saaaşa', '2025-02-19 06:45:59'),
(14, 8, 6, 'Sa', '2025-02-19 06:44:03'),
(13, 7, 6, '...', '2025-02-18 17:57:29'),
(12, 7, 6, 'Aw', '2025-02-18 17:57:25'),
(11, 6, 7, 'Aferim ', '2025-02-18 17:57:17'),
(10, 6, 7, 'Şu ipi yeme', '2025-02-18 17:56:59'),
(17, 6, 8, 'Zçald', '2025-02-19 06:46:04'),
(18, 6, 8, 'Dmdköe', '2025-02-19 06:46:06'),
(19, 8, 6, 'Akskkddsşslslslekememmddddfd', '2025-02-19 06:47:52'),
(20, 8, 10, 'Abi', '2025-02-20 20:05:13'),
(21, 10, 8, 'selam', '2025-02-20 20:06:58'),
(22, 8, 10, 'As', '2025-02-20 20:07:05'),
(23, 10, 8, 'napıyorsun', '2025-02-20 20:07:14'),
(24, 8, 10, '900 MS niyede bir yenileniyo', '2025-02-20 20:07:23'),
(25, 8, 10, 'Abi kendini görmemen lazımdı normalde', '2025-02-20 20:07:45'),
(26, 8, 10, 'PHP ile yaptım', '2025-02-20 20:07:51'),
(27, 10, 10, 'merhaba ben', '2025-02-20 20:08:21'),
(28, 10, 10, 'merhaba sen', '2025-02-20 20:08:34'),
(29, 10, 10, 'yani ben', '2025-02-20 20:08:37'),
(30, 10, 10, 'yani biz', '2025-02-20 20:08:40'),
(31, 10, 10, 'yani onlar olmayan', '2025-02-20 20:08:47'),
(32, 10, 8, 'mvc mi hocam', '2025-02-20 20:09:39'),
(33, 8, 10, 'O ne ', '2025-02-20 20:09:57'),
(34, 8, 10, ':)', '2025-02-20 20:10:01'),
(35, 10, 8, 'Model View Controller', '2025-02-20 20:10:15'),
(36, 8, 10, 'Bura daha iyi', '2025-02-20 20:10:18'),
(37, 11, 10, 'SELAM ABI', '2025-02-20 20:10:41'),
(38, 12, 13, 'A', '2025-02-24 10:19:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_typing` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_typing`) VALUES
(12, 'Admin', '61ahk', 0),
(13, 'Nuh', 'nuh', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Tablo için indeksler `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Tablo için indeksler `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `group_messages`
--
ALTER TABLE `group_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `group_messages`
--
ALTER TABLE `group_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Tablo için AUTO_INCREMENT değeri `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
