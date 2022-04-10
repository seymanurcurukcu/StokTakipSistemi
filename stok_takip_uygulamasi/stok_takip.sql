-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 Nis 2022, 12:14:53
-- Sunucu sürümü: 10.4.22-MariaDB
-- PHP Sürümü: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `stok_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ayarlar`
--

CREATE TABLE `ayarlar` (
  `id` int(11) NOT NULL,
  `ad` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `durum` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `ayarlar`
--

INSERT INTO `ayarlar` (`id`, `ad`, `durum`) VALUES
(1, 'otomatik', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `ad` varchar(20) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `kategori`
--

INSERT INTO `kategori` (`id`, `ad`) VALUES
(1, 'Kuru gıdalar'),
(2, 'İçecekler'),
(3, 'Şekerler'),
(4, 'Giyim');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanici`
--

CREATE TABLE `kullanici` (
  `id` int(11) NOT NULL,
  `kulad` varchar(40) COLLATE utf8_turkish_ci NOT NULL,
  `kulsifre` varchar(40) COLLATE utf8_turkish_ci NOT NULL,
  `yetki` int(11) NOT NULL,
  `tercih` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `kullanici`
--

INSERT INTO `kullanici` (`id`, `kulad`, `kulsifre`, `yetki`, `tercih`) VALUES
(1, '22cd924b0f3040c8280acc864836bf1c', '96de4eceb9a0c2b9b52c0b618819821b', 1, 2),
(2, 'cabef7e77dbbb08ca81e213e61be8854', '96de4eceb9a0c2b9b52c0b618819821b', 1, 2),
(3, '05efced19741c416e85fe45b1de00ca0', '96de4eceb9a0c2b9b52c0b618819821b', 3, 1),
(4, 'c956647bf4e991188fae6c98e711ce9e', '96de4eceb9a0c2b9b52c0b618819821b', 2, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `talepler`
--

CREATE TABLE `talepler` (
  `id` int(11) NOT NULL,
  `urunid` int(11) NOT NULL,
  `urunad` varchar(25) COLLATE utf8_turkish_ci NOT NULL,
  `talepstok` int(11) NOT NULL,
  `tarih` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `talepler`
--

INSERT INTO `talepler` (`id`, `urunid`, `urunad`, `talepstok`, `tarih`) VALUES
(6, 4, 'Soda', 500, NULL),
(9, 2, 'Bezelye', 6000, NULL),
(15, 5, 'Lolipop', 540, '2018-10-28 14:27:54'),
(17, 6, 'Pantolon', 500, '2022-03-22 13:21:12');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `id` int(11) NOT NULL,
  `katid` int(11) NOT NULL,
  `ad` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `katid`, `ad`, `stok`) VALUES
(5, 3, 'Lolipop', 650),
(2, 1, 'Bezelye', 750),
(3, 2, 'KolaO', 5000),
(4, 2, 'Soda', 500),
(6, 4, 'Pantolon', 3589),
(7, 3, 'Lolipop', 650),
(8, 1, 'Bezelye', 750),
(9, 2, 'KolaO', 5000),
(10, 2, 'Soda', 500),
(11, 4, 'Pantolon', 3589),
(12, 3, 'Lolipop', 650),
(13, 1, 'Bezelye', 750),
(14, 2, 'KolaO', 5000),
(15, 2, 'Soda', 500),
(16, 4, 'Pantolon', 3589),
(17, 3, 'Lolipop', 650),
(18, 1, 'Bezelye', 750),
(19, 2, 'KolaO', 5000),
(20, 2, 'Soda', 500),
(21, 4, 'Pantolon', 3589),
(22, 3, 'çikolata', 500);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `ayarlar`
--
ALTER TABLE `ayarlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanici`
--
ALTER TABLE `kullanici`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `talepler`
--
ALTER TABLE `talepler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `ayarlar`
--
ALTER TABLE `ayarlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `kullanici`
--
ALTER TABLE `kullanici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `talepler`
--
ALTER TABLE `talepler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
